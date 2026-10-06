<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\PaymentTransition;
use Carbon\Carbon;

final class KbankPaymentService
{
    public string $paymentMethod;

    // Payment methods
    public const CARD = 'card';
    public const QRCODE = 'qr_code';
    public const LINK = 'link';

    public string $publicKey;
    public string $privateKey;
    public string $merchantName;

    public string $masterMerchantId;
    public string $createQrUrl;
    public string $createLinkUrl;

    public function __construct(string $paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
        $this->publicKey = config('services.kbank.public_key');
        $this->privateKey = config('services.kbank.private_key');
        $this->merchantName = config('services.kbank.merchant_name');
        $this->masterMerchantId = config('services.kbank.master_merchant_id');
        $this->createQrUrl = config('services.kbank.create_qr_url');
        $this->createLinkUrl = config('services.kbank.create_link_url');
    }

    public function availablePaymentMethods()
    {
        return [
            self::CARD,
            self::QRCODE,
            self::LINK
        ];
    }

    public function checkout(Order $order)
    {
        $totalAmount = $order->total_amount;
        $plan = $order->plan;
        $planId = $plan->id;
        $planName = $plan->name_th;
        $referenceOrderId = (string) $order['order_unique_code'];
        
        if (in_array($this->paymentMethod, $this->availablePaymentMethods())) {
            if ($this->paymentMethod == self::CARD) {
                $result = $this->handleMasterCheckout($referenceOrderId, $totalAmount, $planId, $planName, $order->id);
            } else if ($this->paymentMethod == self::QRCODE) {
                $result = $this->handleQrCheckout($referenceOrderId, $totalAmount, $planId, $planName, $order->id);
            } else if ($this->paymentMethod == self::LINK) {
                $result = $this->handleLinkCheckout($referenceOrderId, $totalAmount, $planId, $planName, $order->id);
            }

            return [
                'success' => true,
                'message' => $result['message'],
                'data' => $result['data']
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Payment method is not valid',
                'data' => []
            ];
        }
    }

    public function handleMasterCheckout(string $referenceOrderId, float $totalAmount, int $productId, string $productName, int $orderId)
    {

        // Master checkout only need order information
        return [
            'message' => 'Success to generate master checkout data',
            'data' => [
                'referenceOrderId' => $referenceOrderId,
                'totalAmount' => $totalAmount,
                'productId' => $productId,
                'productName' => $productName
            ]
        ];
    }

    public function handleQrCheckout(string $referenceOrderId, float $totalAmount, int $productId, string $productName, int $orderId)
    {
        try {
            $endpoint = $this->createQrUrl;
            $payload = [
                'amount' => round($totalAmount, 2),
                'currency' => 'THB',
                'description' => $productName,
                'source_type' => "qr",
                'reference_order' => $referenceOrderId
            ];
            
            $headers = [
                'Content-Type: application/json',
                'x-api-key: ' . $this->privateKey,
                'Accept: application/json',
            ];
        
            $fields = json_encode($payload, JSON_UNESCAPED_UNICODE);
        
            $httpService = new HttpService($endpoint, $headers, $fields, 'post');
            $decoded = $httpService->send();
            Log::info("QR order create response : " . json_encode($decoded));
            $qrOrderId = $decoded['id'];
            
            // Need to save the qr order id and create pending payment
            $paymentTransition = PaymentTransition::where('order_id', $referenceOrderId)->first();
            if (empty($paymentTransition)) {
                $payload = [
                    'order_id' => $order['id'],
                    'customer_id' => $order['customer_id'],
                    'reference_no' => generateRandomNo(),
                    'charge_id' => null,
                    'qr_id' => $qrOrderId,
                    'provider' => 'kbank',
                    'method' => 'qr',
                    'status' => 'pending',
                    'provider_status' => null,
                    'amount' => $order['amount'],
                    'currency' => 'THB',
                    'payment_create_info' => []
                ];
                
                $paymentTransition = PaymentTransition::create($payload);
            } else {
                $paymentTransition->update([
                    'charge_id' => null,
                    'qr_id' => $qrOrderId,
                    'link_ref' => null,
                    'provider' => 'kbank',
                    'method' => 'qr',
                    'status' => 'pending',
                    'provider_status' => null,
                ]);
            }
            return [
                'success' => true,
                'message' => 'Success to generate QR code',
                'data' => [
                    'qrId' => $qrOrderId,
                    'paymentTransition' => $paymentTransition
                ]
            ];
        } catch (\Throwable $error) {
            Log::info("Error in QR code generation: " . $error->getMessage());
            return [
                'success' => false,
                'message' => 'Error in QR code generation'
            ];
        }
    }

    public function handleLinkCheckout(string $referenceOrderId, float $totalAmount, int $productId, string $productName, int $orderId)
    {
        try {
            // Create payment link
            $endpoint = $this->createLinkUrl;

            $headers = [
                'Content-Type: application/json',
                'x-api-key: ' . $this->privateKey,
                'Accept: application/json',
            ];

            $payload = [
                'service_name' => $productName,
                'currency' => 'THB',
                'description' => $productName,
                'amount' => $totalAmount,
                'active_time' => Carbon::now()->format('YmdHis'),
                'expire_time' => Carbon::now()->addMinutes(6)->format('YmdHis'),
                'type' => 'ONE_TIME',
                'reference_number' => $referenceOrderId,

                # Smart pay
                'merchant_id' => KBANK_LINK_MERCHANT_ID_1,
                "merchant_name" => $this->merchantName,
                "merchant_location" => "online",
                'source_of_fund' => ['card_full', 'card_smartpay', 'thai_qr'],
                'card_smartpay' => [
                    'merchant_id' => KBANK_LINK_MERCHANT_ID_2,
                    'smartpay_id' => '0001',
                    'payment_term' => '3'
                ]
            ];

            $paymentModel = new PaymentTransition();

            $buildPaymentPayload = static function (string $linkRef, array $paymentCreateInfo) use ($order): array {
                return [
                    'order_id' => $order['id'],
                    'customer_id' => $order['customer_id'],
                    'reference_no' => generateRandomNo(),
                    'charge_id' => null,
                    'qr_id' => null,
                    'link_ref' => $linkRef,
                    'provider' => 'kbank',
                    'method' => 'link',
                    'status' => 'pending',
                    'provider_status' => null,
                    'amount' => $order['amount'],
                    'currency' => 'THB',
                    'payment_create_info' => $paymentCreateInfo
                ];
            };

            $requestPaymentLink = static function (array $requestPayload) use ($endpoint, $headers): array {
                write_log("Request params to create payment link : " . json_encode($requestPayload));

                $fields = json_encode($requestPayload, JSON_UNESCAPED_UNICODE);
                $httpService = new HttpService($endpoint, $headers, $fields, 'post');
                $decoded = $httpService->send();

                write_log("Link order create response : " . json_encode($decoded));

                $paymentLink = $decoded['payment_link'] ?? [];
                if (!is_array($paymentLink)) {
                    $paymentLink = [];
                }

                return [
                    'decoded' => $decoded,
                    'payment_link' => $paymentLink,
                    'link_url' => trim((string)($paymentLink['link_url'] ?? '')),
                    'link_ref' => trim((string)($paymentLink['link_ref'] ?? '')),
                    'qr_code' => trim((string)($paymentLink['qr_code'] ?? '')),
                ];
            };

            $inquiryPaymentLink = static function (string $linkRef) use ($headers): array {
                $endpoint = KBANK_LINK_INQUIRY_URL . $linkRef;
                $httpService = new HttpService($endpoint, $headers, '', 'get');
                $decoded = $httpService->sendGet();

                $paymentLink = $decoded['payment_link'] ?? [];
                if (!is_array($paymentLink)) {
                    $paymentLink = [];
                }

                return [
                    'decoded' => $decoded,
                    'payment_link' => $paymentLink,
                    'link_url' => trim((string)($paymentLink['link_url'] ?? '')),
                    'link_ref' => trim((string)($paymentLink['link_ref'] ?? '')),
                    'qr_code' => trim((string)($paymentLink['qr_code'] ?? '')),
                ];
            };

            $linkData = $requestPaymentLink($payload);
            $decoded = $linkData['decoded'];
            $paymentLink = $linkData['payment_link'];
            $linkUrl = $linkData['link_url'];
            $linkRef = $linkData['link_ref'];
            $qrCodeSrc = $linkData['qr_code'];
            $paymentTransition = $paymentModel->findByOrderId($order['id'], 'pending');
            $storedPaymentInfo = is_array($paymentTransition['payment_create_info'] ?? null)
                ? $paymentTransition['payment_create_info']
                : [];

            if (($linkUrl === '' or $linkRef === '') and !empty($paymentTransition['link_ref'])) {
                if (!empty($storedPaymentInfo['link_url']) and !empty($storedPaymentInfo['link_ref'])) {
                    $linkUrl = trim((string)($storedPaymentInfo['link_url'] ?? ''));
                    $linkRef = trim((string)($storedPaymentInfo['link_ref'] ?? ''));
                    $qrCodeSrc = trim((string)($storedPaymentInfo['qr_code'] ?? ''));
                } else {
                    $linkData = $inquiryPaymentLink((string)$paymentTransition['link_ref']);
                    $paymentLink = $linkData['payment_link'];
                    $linkUrl = $linkData['link_url'];
                    $linkRef = $linkData['link_ref'];
                    $qrCodeSrc = $linkData['qr_code'];
                }
            }

            if ($linkUrl === '' or $linkRef === '') {
                throw new RuntimeException('Payment link response is empty');
            }

            $paymentCreateInfo = [
                'link_url' => $linkUrl,
                'link_ref' => $linkRef,
                'qr_code' => $qrCodeSrc,
                'payment_link' => $paymentLink,
            ];

            if (empty($paymentTransition)) {
                $paymentTransition = $paymentModel->create($buildPaymentPayload($linkRef, $paymentCreateInfo));
            } else {
                $paymentModel->update($paymentTransition['id'], [
                    'charge_id' => null,
                    'qr_id' => null,
                    'link_ref' => $linkRef,
                    'provider' => 'kbank',
                    'method' => 'link',
                    'status' => 'pending',
                    'provider_status' => null,
                    'payment_create_info' => $paymentCreateInfo,
                ]);
            }
        } catch (\Throwable $error) {
            write_log("Error in link URL generation: " . $error->getMessage());
            return [
                'success' => false,
                'message' => 'Error in link URL generation'
            ];
        }
    }
}