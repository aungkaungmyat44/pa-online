<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

abstract class Controller
{
    protected function jsonResponse(
        string $message = '',
        mixed $data = null,
        mixed $error = null,
        int $statusCode = 200,
    ): JsonResponse {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'error' => $error,
        ], $statusCode);
    }

    protected function jsonError(
        string $message,
        mixed $error = null,
        mixed $data = null,
        int $statusCode = 400,
    ): JsonResponse {
        return $this->jsonResponse($message, $data, $error, $statusCode);
    }

    protected function generateRandomNo(int $length = 13): string
    {
        $random = '';

        for ($i = 0; $i < $length; $i++) {
            $random .= mt_rand(0, 9);
        }

        return $random;
    }

    protected function redirectRoute(
        string $route,
        array $parameters = [],
        mixed $errors = null,
        ?array $input = null,
        array $flash = [],
    ): RedirectResponse {
        $redirect = redirect()->route($route, $parameters);

        if ($input !== null) {
            $redirect->withInput($input);
        }

        if ($errors !== null) {
            $redirect->withErrors($errors);
        }

        foreach ($flash as $key => $value) {
            $redirect->with($key, $value);
        }

        return $redirect;
    }
}
