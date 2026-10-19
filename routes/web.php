<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('home', ['locale' => 'th']));

Route::pattern('locale', 'th|en');
Route::prefix('{locale}')
    ->middleware('set.locale')
    ->group(function () {
        Route::get('/', [PageController::class, 'home'])->name('home');
        Route::get('check-premium', [PageController::class, 'checkPremium'])->name('check-premium');

        Route::post('otp-confirmation', [PageController::class, 'otpConfirmation'])->name('otp-confirmation');
        Route::get('otp-form', [PageController::class, 'showOtpForm'])->name('otp-form');

        Route::post('verify-otp', [PageController::class, 'verifyOtpCode'])->name('verify-otp');
        Route::get('health-questions', [PageController::class, 'showHealthQuestion'])->name('show-health-questions');

        Route::post('health-questions', [PageController::class, 'saveHealthQuestion'])->name('save-health-questions');
        Route::get('information-form', [PageController::class, 'showInformationForm'])->name('show-information-form');

        Route::post('save-information', [PageController::class, 'saveInformation'])->name('save-information');
        Route::get('review-information', [PageController::class, 'showReviewInformation'])->name('show-review-information');

        Route::post('proceed-payment', [PageController::class, 'proceedPayment'])->name('proceed-payment');
        Route::get('payment-method', [PageController::class, 'paymentMethod'])->name('payment-method');

        Route::post('request-payment', [PageController::class, 'requestPayment'])->name('request-payment');
        Route::get('checkout', [PageController::class, 'showCheckout'])->name('show-checkout');

        Route::get('districts', [PageController::class, 'districts'])->name('districts');
        Route::get('subdistricts', [PageController::class, 'subdistricts'])->name('subdistricts');

        Route::get('payment-transitions/{order}/kbank/inquiry', [PaymentController::class, 'inquiryKBankPaymentTransition'])->name('kbank-payment-inquiry');
        Route::get('payment-transitions/{order}/payment-issue', [PaymentController::class, 'paymentIssue'])->name('payment-issue');
        Route::get('payment-transitions/{order}/receipt', [PaymentController::class, 'receipt'])->name('receipt');

        Route::post('payment-transitions/{order}/kbank/checkout', [PaymentController::class, 'kbankCheckout'])->name('kbank-checkout');
        Route::post('payment-transitions/create', [PaymentController::class, 'createPaymentTransition'])->name('payment-transitions-create-web');

        Route::get('check-policy', [PageController::class, 'checkPolicy'])->name('check-policy');
        Route::get('check-policy-form', [PageController::class, 'checkPolicyForm'])->name('check-policy-form');
    });
