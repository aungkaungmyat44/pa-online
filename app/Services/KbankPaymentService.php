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
    public const QRCODE = 'thai_qr';
    public const LINK = 'link';

    public string $publicKey;
    public string $privateKey;
    public string $merchantName;

    public string $masterMerchantId;
    public string $createQrUrl;
    public string $createLinkUrl;
    public string $linkMerchantId1;
    public string $linkMerchantId2;
    public string $inquirePaymentLinkUrl;

    public string $masterInquiryUrl;
    public string $qrInquiryUrl;
    public string $linkInquiryUrl;

    public function __construct(string $paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
        $this->publicKey = config('services.kbank.public_key');
        $this->privateKey = config('services.kbank.private_key');
        $this->merchantName = config('services.kbank.merchant_name');
        $this->masterMerchantId = config('services.kbank.master_merchant_id');
        $this->createQrUrl = config('services.kbank.create_qr_url');
        $this->createLinkUrl = config('services.kbank.create_link_url');
        $this->linkMerchantId1 = config('services.kbank.link_merchant_id_1');
        $this->linkMerchantId2 = config('services.kbank.link_merchant_id_2');
        $this->inquirePaymentLinkUrl = config('services.kbank.inquiry_payment_link_url');

        $this->masterInquiryUrl = config('services.kbank.master_inquiry_url');
        $this->qrInquiryUrl = config('services.kbank.qr_inquiry_url');
        $this->linkInquiryUrl = config('services.kbank.link_inquiry_url');
    }

    public function availablePaymentMethods() : array
    {
        return [
            self::CARD,
            self::QRCODE,
            self::LINK
        ];
    }

    public function checkout(Order $order) : array
    {
        $totalAmount = $order->total_amount;
        $plan = $order->plan;
        $planId = $plan->id;
        $planName = $plan->name_th;
        $referenceOrderId = (string) $order['order_unique_code'];
        
        if (in_array($this->paymentMethod, $this->availablePaymentMethods())) {
            if ($this->paymentMethod == self::CARD) {
                $result = $this->handleMasterCheckout($referenceOrderId, $totalAmount, $planId, $planName, $order);
            } else if ($this->paymentMethod == self::QRCODE) {
                $result = $this->handleQrCheckout($referenceOrderId, $totalAmount, $planId, $planName, $order);
            } else if ($this->paymentMethod == self::LINK) {
                $result = $this->handleLinkCheckout($referenceOrderId, $totalAmount, $planId, $planName, $order);
            }
            
            return [
                'success' => $result['success'] ?? false,
                'message' => $result['message'],
                'data' => $result['data'] ?? [],
                'code' => $result['code'] ?? (($result['success'] ?? false) ? 200 : 400),
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Payment method is not valid',
                'data' => [],
                'code' => 400,
            ];
        }
    }

    public function handleMasterCheckout(string $referenceOrderId, float $totalAmount, int $productId, string $productName, Order $order) : array
    {
        // Master checkout only need order information
        return [
            'success' => true,
            'message' => 'Success to generate master checkout data',
            'data' => [
                'referenceOrderId' => $referenceOrderId,
                'totalAmount' => $totalAmount,
                'productId' => $productId,
                'productName' => $productName
            ],
            'code' => 200,
        ];
    }

    public function handleQrCheckout(string $referenceOrderId, float $totalAmount, int $productId, string $productName, Order $order) : array 
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
            $paymentTransition = PaymentTransition::where('order_id', $order->id)
                                                ->where('method', self::QRCODE)
                                                ->first();
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
                    'amount' => $order->total_amount,
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
                ],
                'code' => 200,
            ];
        } catch (\Throwable $error) {
            Log::info("Error in QR code generation: " . $error->getMessage());
            return [
                'success' => false,
                'message' => 'Error in QR code generation',
                'data' => [],
                'code' => 500,
            ];
        }
    }

    public function handleLinkCheckout(string $referenceOrderId, float $totalAmount, int $productId, string $productName, Order $order) : array
    {
        try {
            // Create payment link
            $endpoint = $this->createLinkUrl;

            $headers = [
                'Content-Type: application/json',
                'x-api-key: ' . $this->privateKey,
                'Accept: application/json',
            ];

            $now = Carbon::now('Asia/Bangkok');
            $payload = [
                'service_name' => $productName,
                'currency' => 'THB',
                'description' => $productName,
                'amount' => $totalAmount,
                'active_time' => $now->format('YmdHis'),
                'expire_time' => $now->copy()->addMinutes(30)->format('YmdHis'),
                'type' => 'ONE_TIME',
                'reference_number' => $referenceOrderId,

                # Smart pay
                'merchant_id' => $this->linkMerchantId1,
                "merchant_name" => $this->merchantName,
                "merchant_location" => "online",
                'source_of_fund' => ['card_full', 'thai_qr'],//'card_smartpay',
                // 'card_smartpay' => [
                //     'merchant_id' => $this->linkMerchantId2,
                //     'smartpay_id' => '0001',
                //     'payment_term' => '3'
                // ]
            ];
            
            $paymentModel = new PaymentTransition();

            // To create payment transition payload
            $buildPaymentPayload = function (string $linkRef, array $paymentCreateInfo) use ($order): array {
                return [
                    'order_id' => $order->id,
                    'customer_id' => $order->customer_id,
                    'reference_no' => generateRandomNo(),
                    'charge_id' => null,
                    'qr_id' => null,
                    'link_ref' => $linkRef,
                    'provider' => 'kbank',
                    'method' => 'link',
                    'status' => 'pending',
                    'provider_status' => null,
                    'amount' => $order->total_amount,
                    'currency' => 'THB',
                    'payment_create_info' => $paymentCreateInfo
                ];
            };

            // To request payment link to update in payment transition record
            $requestPaymentLink = function (array $requestPayload) use ($endpoint, $headers): array {
                Log::info("Request params to create payment link : " . json_encode($requestPayload));

                $fields = json_encode($requestPayload, JSON_UNESCAPED_UNICODE);
                $httpService = new HttpService($endpoint, $headers, $fields, 'post');
                $decoded = $httpService->send();

                Log::info("Link order create response : " . json_encode($decoded));

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

            // To check the payment link's status
            $inquiryPaymentLink = function (string $linkRef, $inquirePaymentLinkUrl) use ($headers): array {
                $endpoint = $inquirePaymentLinkUrl . '/'. $linkRef;
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
            $paymentTransition = PaymentTransition::where('order_id', $order['id'])
                                                ->where('status', 'pending')
                                                ->where('method', self::LINK)
                                                ->first();

            $storedPaymentInfo = is_array($paymentTransition['payment_create_info'] ?? null) ? $paymentTransition['payment_create_info'] : [];
            
            if ($linkUrl === '' and (!empty($paymentTransition) and !empty($paymentTransition->link_ref))) {
                $linkData = $inquiryPaymentLink((string)$paymentTransition['link_ref'], $this->inquirePaymentLinkUrl);
                $paymentLink = $linkData['payment_link'];
                $linkUrl = $linkData['link_url'];
                $linkRef = $linkData['link_ref'];
                $qrCodeSrc = $linkData['qr_code'];
            }
            
            if ($linkUrl === '' and empty($paymentTransition) and empty($paymentTransition->link_ref)) {
                throw new \Exception('Unable to generate payment link');
            }

            $paymentCreateInfo = [
                'link_url' => $linkUrl,
                'link_ref' => $linkRef,
                'qr_code' => $qrCodeSrc,
                'payment_link' => $paymentLink,
            ];
            
            if (empty($paymentTransition)) {
                $newPaymentPayload = $buildPaymentPayload($linkRef, $paymentCreateInfo);
                $paymentTransition = PaymentTransition::create($newPaymentPayload);
            } else {
                $paymentTransition->update([
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
            
            return [
                'success' => true,
                'message' => 'Success to generate payment link',
                'data' => [
                    'paymentLink' => $paymentLink,
                    'linkUrl' => $linkUrl,
                    'linkRef' => $linkRef,
                    'qrCodeSrc' => $qrCodeSrc
                ],
                'code' => 200,
            ];
        } catch (\Throwable $error) {
            Log::error('Error in link URL generation', [
                'order_id' => $order->id,
                'exception' => $error,
            ]);
            return [
                'success' => false,
                'message' => 'Error in link URL generation',
                'data' => [],
                'code' => 500,
            ];
        }
    }

    public function handleInquiry(Order $order) : array
    {
        // Fetch order details
        $method = $order->payment_method ?? 'card';
        $chargeResponse = [];
        $paymentPayload = [];
        $headers = [
            'Content-Type: application/json',
            'x-api-key: ' . $this->privateKey,
            'Accept: application/json',
        ];

        $referenceOrder = (string)$order->order_unique_code;
        if (trim($referenceOrder) === '') {
            return [
                'success' => false,
                'message' => "Order has no reference unique code!",
                'data' => $result['data'] ?? [],
                'code' => 400,
            ];
        }

        try {
            if ($method == self::CARD) {
                $pendingPayment = PaymentTransition::where('order_id', $order->id)
                                                    ->where('status', 'pending')
                                                    ->first();

                if (empty($pendingPayment) or empty($pendingPayment['charge_id'])) {
                    return [
                        'success' => true,
                        'message' => 'Card Payment is waiting to start',
                        'data' => ['status' => 'pending'],
                        'code' => 200,
                    ];
                }
                
                $chargeId = $pendingPayment['charge_id'];
                $date = date('Ymd', time());
                $endpoint = $this->masterInquiryUrl . "$chargeId";
                $httpService = new HttpService($endpoint, $headers, '', 'get');
                $chargeResponse = $httpService->sendGet();
    
            } else if ($method == self::QRCODE) {
                $pendingPayment = PaymentTransition::where('order_id', $order->id)
                    ->where('status', 'pending')
                    ->first();
                if (empty($pendingPayment) or empty($pendingPayment['qr_id'])) {
                    return [
                        'success' => true,
                        'message' => 'QR Payment is waiting to start',
                        'data' => ['status' => 'pending'],
                        'code' => 200,
                    ];
                }
                $qrId = $pendingPayment['qr_id'];
                $endpoint = $this->qrInquiryUrl . "$qrId";
                $httpService = new HttpService($endpoint, $headers, '', 'get');
                $chargeResponse = $httpService->sendGet();
                
            } else if ($method == self::LINK) {
                $pendingPayment = PaymentTransition::where('order_id', $order->id)
                    ->where('status', 'pending')
                    ->first();
                if (empty($pendingPayment) or empty($pendingPayment['link_ref'])) {
                    return [
                        'success' => true,
                        'message' => 'Link Payment is waiting to start',
                        'data' => ['status' => 'pending'],
                        'code' => 200,
                    ];
                }
                $linkRef = $pendingPayment['link_ref'];
                $endpoint = $this->linkInquiryUrl . "$linkRef";
                $httpService = new HttpService($endpoint, $headers, '', 'get');
                $chargeResponse = $httpService->sendGet();
    
            } else {
                return [
                    'success' => false,
                    'message' => 'Unsupported payment method for inquiry',
                    'data' => [],
                    'code' => 400,
                ];
            }

            return [
                'success' => true,
                'message' => "Successfully fetch kbank payment information",
                'data' => $chargeResponse,
                'code' => 200,
            ];
        } catch (\Exception $error) {
            Log::error('Error in K-Bank payment inquiry', [
                'order_id' => $order->id,
                'payment_method' => $method,
                'reference_order' => $referenceOrder,
                'exception' => $error,
            ]);

            return [
                'success' => false,
                'message' => $error->getMessage(),
                'data' => $result['data'] ?? [],
                'code' => 500,
            ];
        }
    }

}
