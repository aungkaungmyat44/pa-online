<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ValidatePaymentCallbackSignature
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$this->callbackMiddleware($request)) {
            Log::error('Fail! Signature is not valid', [
                'app_id' => $request->headers->get('X-APP-ID', ''),
                'timestamp' => $request->headers->get('X-TIMESTAMP', ''),
                'path' => $request->path(),
                'request_params' => $request->all(),
            ]);

            return response()->json([
                'message' => 'Signature is not valid',
                'data' => null,
                'error' => [],
            ], 400);
        }

        return $next($request);
    }

    private function callbackMiddleware(Request $request): bool
    {
        $input = $request->all();

        $requiredFields = [
            'charge_id',
            'reference_order',
            'status',
            'transaction_state',
            'amount',
            'payment_create_info',
        ];

        foreach ($requiredFields as $field) {
            if (! array_key_exists($field, $input)) {
                return false;
            }
        }

        $appId = $request->headers->get('X-APP-ID', '');
        $timestamp = $request->headers->get('X-TIMESTAMP', '');
        $signature = $request->headers->get('X-SIGNATURE', '');

        $apps = config('services.payment_callback.apps', []);
        $secret = $apps[$appId] ?? null;

        if (! is_string($secret) or $secret === '') {
            return false;
        }

        if (! ctype_digit($timestamp) or $signature === '') {
            return false;
        }

        $maxRequestAge = (int) config(
            'services.payment_callback.max_request_age',
            3600
        );

        if (abs(time() - (int) $timestamp) > $maxRequestAge) {
            return false;
        }

        // Same field order as the callback server.
        $body = [
            'charge_id' => $input['charge_id'],
            'reference_order' => $input['reference_order'],
            'status' => $input['status'],
            'transaction_state' => $input['transaction_state'],
            'amount' => $input['amount'],
            'payment_create_info' => $input['payment_create_info'],
        ];

        try {
            $payload = json_encode(
                $body,
                JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR
            );
        } catch (\JsonException $e) {
            return false;
        }


        $expected = hash_hmac('sha256', $payload, $secret);
        $rawPayload = $request->getContent();
        Log::debug('Callback signature debug', [
            'rebuilt_sha256' => hash('sha256', $payload),
            'rebuilt_length' => strlen($payload),
            'raw_sha256' => hash('sha256', $rawPayload),
            'raw_length' => strlen($rawPayload),
            'secret_fingerprint' => hash('sha256', $secret),
            'expected_signature' => $expected,
            'received_signature' => $signature,
        ]);
        
        return hash_equals($expected, $signature);
    }
}
