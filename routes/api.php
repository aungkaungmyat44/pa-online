<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::post('payment-transitions/create', [PaymentController::class, 'createPaymentTransition'])
    ->middleware('payment.callback.signature')
    ->name('payment-transitions-create');
