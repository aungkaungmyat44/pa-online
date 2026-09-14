<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('check-premium', [PageController::class, 'checkPremium'])->name('check-premium');
Route::post('otp-confirmation', [PageController::class, 'otpConfirmation'])->name('otp-confirmation');
Route::post('health-questions', [PageController::class, 'healthQuestion'])->name('health-questions');
Route::post('information-form', [PageController::class, 'informationForm'])->name('information-form');
Route::post('review-information', [PageController::class, 'reviewInformation'])->name('review-information');
Route::post('payment-methods', [PageController::class, 'paymentMethods'])->name('payment-methods');
Route::post('payment-process', [PageController::class, 'paymentProcess'])->name('payment-process');
Route::post('checkout', [PageController::class, 'checkout'])->name('checkout');
Route::get('check-policy', [PageController::class, 'checkPolicy'])->name('check-policy');

Route::get('check-policy-form', [PageController::class, 'checkPolicyForm'])->name('check-policy-form');
Route::get('districts', [PageController::class, 'districts'])->name('districts');
Route::get('subdistricts', [PageController::class, 'subdistricts'])->name('subdistricts');
