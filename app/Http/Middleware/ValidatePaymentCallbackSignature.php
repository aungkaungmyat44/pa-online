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
        $body = $request->all();
        if (empty($body)) {
            return false;
        }

        $appId = $request->headers->get('X-APP-ID', '');
        $timestamp = $request->headers->get('X-TIMESTAMP', '');
        $signature = $request->headers->get('X-SIGNATURE', '');

        $apps = config('services.payment_callback.apps', []);
        if (empty($apps[$appId])) {
            return false;
        }

        if ($timestamp === '' || $signature === '') {
            return false;
        }

        $maxRequestAge = (int) config('services.payment_callback.max_request_age', 60 * 60);
        if (abs(time() - (int) $timestamp) > $maxRequestAge) {
            return false;
        }

        $payload = json_encode($body);
        if ($payload === false) {
            return false;
        }

        $expected = hash_hmac('sha256', $payload, $apps[$appId]);

        return hash_equals($expected, $signature);
    }
}
