<?php

if (! function_exists('generateRandomNo')) {
    function generateRandomNo(int $length = 13): string
    {
        $random = '';

        for ($i = 0; $i < $length; $i++) {
            $random .= mt_rand(0, 9);
        }

        return $random;
    }
}
