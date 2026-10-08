<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PaymentController;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('check-premium', [PageController::class, 'checkPremium'])->name('check-premium');

Route::post('otp-confirmation', [PageController::class, 'otpConfirmation'])->name('otp-confirmation'); // prepare to request otp code
Route::get('otp-form', [PageController::class, 'showOtpForm'])->name('otp-form'); // show otp form

Route::post('verify-otp', [PageController::class, 'verifyOtpCode'])->name('verify-otp'); 
Route::get('health-questions', [PageController::class, 'showHealthQuestion'])->name('show-health-questions'); // show health question form

Route::post('health-questions', [PageController::class, 'saveHealthQuestion'])->name('save-health-questions');
Route::get('information-form', [PageController::class, 'showInformationForm'])->name('show-information-form'); // show information form

Route::post('save-information', [PageController::class, 'saveInformation'])->name('save-information');
Route::get('review-information', [PageController::class, 'showReviewInformation'])->name('show-review-information');

Route::post('proceed-payment', [PageController::class, 'proceedPayment'])->name('proceed-payment');
Route::get('payment-method', [PageController::class, 'paymentMethod'])->name('payment-method');

Route::post('request-payment', [PageController::class, 'requestPayment'])->name('request-payment');
Route::get('checkout', [PageController::class, 'showCheckout'])->name('show-checkout');

// API
Route::get('districts', [PageController::class, 'districts'])->name('districts');
Route::get('subdistricts', [PageController::class, 'subdistricts'])->name('subdistricts');

// Payment Transition Group
Route::get('payment-transitions/{order}/kbank/inquiry', [PaymentController::class, 'inquiryKBankPaymentTransition'])->name('kbank-payment-inquiry');
Route::get('payment-transitions/payment-issue/{order}', [PaymentController::class, 'paymentIssue'])->name('payment-issue');

Route::post('payment-transitions/{order}/kbank/checkout', [PaymentController::class, 'kbankCheckout'])->name('kbank-checkout');
Route::post('payment-transitions/{order}/create', [PaymentController::class, 'createPaymentTransition'])->name('payment-transitions-create');

// Route::post('review-information', [PageController::class, 'reviewInformation'])->name('review-information');
// Route::post('payment-methods', [PageController::class, 'paymentMethods'])->name('payment-methods');
// Route::post('payment-process', [PageController::class, 'paymentProcess'])->name('payment-process');
// Route::post('checkout', [PageController::class, 'checkout'])->name('checkout');
// Route::get('check-policy', [PageController::class, 'checkPolicy'])->name('check-policy');

// Route::get('check-policy-form', [PageController::class, 'checkPolicyForm'])->name('check-policy-form');