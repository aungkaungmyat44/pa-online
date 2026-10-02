<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

abstract class Controller
{
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
