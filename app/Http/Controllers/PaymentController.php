<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\PaymentTransitionCreateRequest;
use App\Models\Order;
use App\Models\PaymentTransition;
use App\Services\KbankPaymentService;
use App\Services\HttpService;

class PaymentController extends Controller
{
    public function paymentIssue(Request $request, Order $order)
    {
        $paymentTransition = PaymentTransition::where('order_id', $order->id)->first();

        $issuePolicyResult = is_array($order->issue_policy_result) ? $order->issue_policy_result : [];

        $message = trim((string)$request->message);
        if ($message === '') {
            $message = trim((string)($issuePolicyResult['errorMessage'] ?? ''));
        }

        if ($message === '') {
            $message = "We received your payment, but the policy could not be issued automatically.";
        }
        
        return view('payment_issue', [
            'title' => "We received your payment, but the policy could not be issued automatically.",
            'order' => $order,
            'payment' => $paymentTransition,
            'issuePolicyResult' => $issuePolicyResult,
            'message' => $message
        ]);
    }

    public function receipt(Order $order)
    {
        $sessionOrder = session()->get('order');
        $orderId = data_get($sessionOrder, 'id');

        if (empty($orderId) or (int) $orderId !== $order->id) {
            return $this->redirectRoute('check-premium', errors: [
                'order' => 'Order session timeout. Please start again.',
            ]);
        }

        $order->loadMissing('customer');

        return view('receipt', [
            'order' => $order,
            'policyNumber' => $order->policy_no ?: 'MISC-PAI26-0417-09031',
            'email' => $order->customer->email ?? '',
        ]);
    }

    public function kbankCheckout(Request $request, Order $order)
    {
        $data = $request->all();
        $token = $data['token'];
        $productName = $data['product_name'];
        $orderId = $data['order_id'];
        $amount = (float)$data['product_price'];
        
        if (empty($token)) {
            return $this->redirectRoute('home', errors: [
                'payment' => 'Please select a plan first!',
            ]);
        }
    
        $endpoint = config('services.kbank.master_inquiry_url');
        $apiKey = config('services.kbank.private_key');

        $payload = [
            'amount' => round($amount, 2),
            'currency' => 'THB',
            'description' => $productName,
            'source_type' => 'card',
            'mode' => 'token',
            'reference_order' => $orderId,
            'token' => $token,
            'ref_1' => 'ref1',
            'ref_2' => 'ref2'
        ];

        $headers = [
            'Content-Type: application/json',
            'x-api-key: ' . $apiKey,
            'Accept: application/json',
        ];

        $fields = json_encode($payload, JSON_UNESCAPED_UNICODE);

        // Send the request to the payment gateway
        $httpService = new HttpService($endpoint, $headers, $fields, 'post');
        $response = $httpService->send();
            
        $authorizeStatus = $response['transaction_state'];
        $status = $response['status'];

        // Handle fail response
        if (empty($status) or $status != 'success') {
            return $this->redirectRoute('home', errors: [
                'payment' => 'Failed to checkout with KBank',
            ]);
        }

        // Handle authorize response
        if ($authorizeStatus == 'Pre-Authorized') {
            $redirectUrl = ($response['redirect_url'] ?? '');
            if ($redirectUrl === '') {
                return $this->redirectRoute('home', errors: [
                    'payment' => 'KBank redirect URL is missing',
                ]);
            }

            return redirect()->away($redirectUrl);
        }

        // All others go to payment inquire page (checkout.blade.php)
        return redirect()->route('show-checkout');
    }

    public function createPaymentTransition(PaymentTransitionCreateRequest $request)
    {
        $orderReferenceNumber = $request->input('reference_order');
        $order = Order::where('order_unique_code', $orderReferenceNumber)->first();

        if (empty($order)) {
            return $this->jsonError(
                'Order not found.',
                ['reference_order' => 'The specified order could not be found.'],
                null,
                404
            );
        }

        // Fetch request get
        $requestType = $request->request_type ?? 'inquire';
        $data = $request->all();
        Log::info('Params are : ' . json_encode($data));

        $createInfo = $data['payment_create_info'] ?? [];
        if (!empty($createInfo)) {
            $requestType = 'callback';
        }

        // If type 'callback' then need to make checksum validation
        if ($requestType == 'callback' and !$this->verifyChecksum($data, $createInfo)) {
            Log::error('KBank checksum verification failed. Payload: ' . json_encode($data, JSON_UNESCAPED_UNICODE));

            return $this->jsonError(
                'KBank checksum verification failed.',
                ['checksum' => 'Invalid payment checksum.'],
                $data,
                422
            );
        }

        // Fetch old payment transition
        $payment = PaymentTransition::where('charge_id', $data['charge_id'])->first();
        if (empty($payment)) {
            $payment = PaymentTransition::where('order_id', $order->id)->first();
        }

        // Fetch customer
        $customer = $order->customer;

        // Fetch order -> issue_policy_result to check already issued ? or not
        $existingIssueResult = $order->issue_policy_result ?? [];
        $alreadyIssued = is_array($existingIssueResult) and (($existingIssueResult['result'] ?? '') === 'Success' or trim((string)($existingIssueResult['PolicyNo'] ?? '')) !== '');
        
        /* # Soap API call
        if ($alreadyIssued) {
            $issueApiResult = $existingIssueResult;
            Log::info('Issue API result already exists for order ' . $order['order_id'] . ': ' . json_encode($issueApiResult));
        } else {
            # Call issue policy api method
            $issueApiResult = $this->callIssuePolicyAPI($order);
            Log::info('Issue API result is : ' . json_encode($issueApiResult));
        }
        
        // Save error message by Soap API
        if (empty($issueApiResult) or ($issueApiResult['result'] ?? '') == 'Fail') {
            $issueErrorMessage = $this->normalizeString($issueApiResult['errorMessage'] ?? '');
            $order->update([
                'issue_policy_result' => [
                    'vat' => '',
                    'duty' => '',
                    'total' => '',
                    'p_code' => '',
                    'result' => 'Fail',
                    'Barcode' => '',
                    'premium' => '',
                    'PolicyNo' => '',
                    'PolicyURL' => '',
                    'errorCode' => '',
                    'errorMessage' => $issueErrorMessage,
                ],
            ]);

            return $this->jsonError(
                'fail to create payment transition',
                $issueErrorMessage !== '' ? $issueErrorMessage : null,
                null,
                500
            );
        }
        */

        if (!empty($payment)) {
            // When payment is okay and order is only remaining
            if ($payment->status === 'success' and $order->payment_status !== 'paid' and $alreadyIssued) {
                $order->update([
                    'policy_no' => $existingIssueResult['PolicyNo'] ?? ($order->policy_no ?? ''),
                    'policy_url' => $existingIssueResult['PolicyURL'] ?? ($order->policy_url ?? ''),
                    'status' => 'delivered',
                    'is_email_sent' => 1,
                    'is_policy_generated' => 1,
                    'payment_status' => 'paid',
                    'paid_at' => date('Y-m-d H:i:s', time())
                ]);
    
                return $this->jsonResponse('Successfully created payment transition', $payment);
            }

            // When payment is still pending, then need to update the latest result
            if ($payment->status == 'pending') {
                $payment->update([
                    'charge_id' => $data['charge_id'] ?? null,
                    'provider_status' => $data['transaction_state'],
                    'status' => $data['status'],
                    'payment_create_info' => $data['payment_create_info'] ?? [],
                    'inquiry_data_result' => $data['inquiry_data_result'] ?? []
                ]);
                $payment = PaymentTransition::find($payment->id);
            }
        } else {
            // Create fresh payment transition with latest result
            $paymentPayload = [
                'order_id' => $order->id,
                'customer_id' => $customer->id,
                'reference_no' => generateRandomNo(),
                'charge_id' => $data['charge_id'],
                'provider' => 'kbank',
                'method' => $order->payment_method,
                'status' => $data['status'],
                'provider_status' => $data['transaction_state'],
                'amount' => $data['amount'],
                'currency' => 'THB',
                'payment_create_info' => $createInfo,
                'inquiry_data_result' => $data['inquiry_data_result'] ?? []
            ];
            $payment = PaymentTransition::create($paymentPayload);
        }

        if ($payment and $order->payment_status == 'unpaid') {
            $order->update([
                'policy_no' => $issueApiResult['PolicyNo'] ?? "",
                'policy_url' => $issueApiResult['PolicyURL'] ?? "",
                'status' => 'delivered',
                'is_email_sent' => 1,
                'is_policy_generated' => 1,
                'issue_policy_result' => $issueApiResult ?? [],
                'payment_status' => 'paid',
                'paid_at' => date('Y-m-d H:i:s', time())
            ]);
        }

        return $this->jsonResponse('Successfully created payment transition', $payment);
    }

    public function inquiryKBankPaymentTransition(Request $request, Order $order)
    {
        $existingPayment = PaymentTransition::where('order_id', $order->id)->first();
        $payment = [];

        $issuePolicyResult = $order->issue_policy_result ?? [];
        if (
            !empty($existingPayment) and
            $existingPayment->status == 'success' and
            $order->payment_status != 'paid' and
            !empty($issuePolicyResult) and
            $issuePolicyResult['result'] == 'Fail'
        ) {
            $issueErrorMessage = trim((string)($issuePolicyResult['errorMessage'] ?? ''));

            return $this->jsonError(
                $issueErrorMessage !== '' ? $issueErrorMessage : 'Fail to Issue policy',
                [],
                ['payment_issue' => true],
                500
            );
        }

        if (!empty($existingPayment) and $existingPayment->status == 'success' and $order->payment_status == 'paid') {
            $payment = $existingPayment;
            return $this->jsonResponse('Success to fetch K-Bank payment information', $payment, null, 200);
        }

        $apiKey = config('services.kbank.private_key');
        $headers = [
            'Content-Type: application/json',
            'x-api-key: ' . $apiKey,
            'Accept: application/json',
        ];

        $kbankPaymentService = new KbankPaymentService($order->payment_method);
        $chargeResult = $kbankPaymentService->handleInquiry($order);
        $chargeResponse = [];

        if (empty($chargeResult) or !$chargeResult['success']) {
            return $this->jsonError(
                $chargeResult['message'] ?? 'Fail to fetch K-Bank payment information',
                [],
                $chargeResult['data'] ?? null,
                $chargeResult['code'] ?? 400
            );
        }

        $chargeResponse = $chargeResult['data'];
        Log::info('Charge result is : ' . json_encode($chargeResult));
        $isPaidLink = !empty($chargeResponse)
            and !empty($chargeResponse['payment_link']['status'])
            and $chargeResponse['payment_link']['status'] == 'PAID';

        $isAuthorizedCharge = !empty($chargeResponse)
            and ($chargeResponse['status'] ?? '') == 'success'
            and ($chargeResponse['transaction_state'] ?? '') == 'Authorized';

        if (!empty($chargeResponse)) {
            $paymentPayload = [];
            if ($isAuthorizedCharge and in_array($order->payment_method, ['card', 'thai_qr']) and !empty($chargeResponse['id'])) {
                $paymentPayload = [
                    'charge_id' => $chargeResponse['id'],
                    'reference_order' => $order->order_unique_code,
                    'status' => $chargeResponse['status'],
                    "transaction_state" => $chargeResponse['transaction_state'],
                    "amount" => $chargeResponse['amount'] ?? 0,
                    "payment_create_info" => [],
                    'inquiry_data_result' => $chargeResponse ?? []
                ];
            }

            if ($isPaidLink and in_array($order->payment_method, ['link'])) {
                if (!empty($chargeResponse) and !empty($chargeResponse['payment_link']['status']) and $chargeResponse['payment_link']['status'] == 'PAID') {
                    $paymentPayload = [
                        'charge_id' => $chargeResponse['payment_detail'][0]['id'],
                        'reference_order' => $order->order_unique_code,
                        'status' => $chargeResponse['payment_link']['status'] == 'PAID' ? 'success' : 'pending',
                        'transaction_state' => $chargeResponse['payment_detail'][0]['transaction_state'],
                        'amount' => $chargeResponse['payment_link']['amount'] ?? 0,
                        'payment_create_info' => [],
                        'inquiry_data_result' => $chargeResponse ?? []
                    ];
                }
            }
    
            if (!empty($paymentPayload)) {
                /* # Create payment transition after inquirying success from KBank
                
                $paymentTransitionRequest = PaymentTransitionCreateRequest::create(
                    route('payment-transitions-create-web'),
                    'POST',
                    $paymentPayload
                );
                Log::info(json_encode($paymentTransitionRequest));
                $result = $this->createPaymentTransition($paymentTransitionRequest);
                if ($result->getStatusCode() >= 400) {
                    return $result;
                }
                */
    
                $payment = PaymentTransition::where('order_id', $order->id)->first();

                return $this->jsonResponse('Success to fetch K-Bank payment information', $payment, null, 200);
            }
        }

        return $this->jsonResponse('Success to fetch K-Bank payment information', $payment, null, 200);
    }

    private function verifyChecksum(array $payload, array $createInfo): bool
    {
        $receivedChecksum = strtolower(preg_replace('/\s+/', '', (string)($createInfo['checksum'] ?? $payload['checksum'] ?? '')));
        if ($receivedChecksum === '') {
            Log::info('KBank checksum is missing from callback payload');
            return false;
        }

        $amount = $createInfo['amount'] ?? ($payload['amount'] ?? 0);
        $checksumBase = $this->normalizeString($createInfo['id'] ?? ($payload['charge_id'] ?? ''))
            . number_format((float)$amount, 4, '.', '')
            . $this->normalizeString($createInfo['currency'] ?? ($payload['currency'] ?? 'THB'))
            . $this->normalizeString($createInfo['status'] ?? ($payload['status'] ?? ''))
            . $this->normalizeString($createInfo['transaction_state'] ?? ($payload['transaction_state'] ?? ''))
            . config('services.kbank.private_key');

        $expectedChecksum = hash('sha256', $checksumBase);
        if (!hash_equals($expectedChecksum, $receivedChecksum)) {
            Log::error(
                'KBank checksum mismatch. Expected: ' . $expectedChecksum . ' Received: ' . $receivedChecksum,
                'ERROR'
            );
            return false;
        }

        return true;
    }

    private function normalizeString(mixed $value): string
    {
        return trim((string) $value);
    }
}
