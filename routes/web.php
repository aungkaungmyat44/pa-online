<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('check-premium', [PageController::class, 'checkPremium'])->name('check-premium');
Route::post('otp-confirmation', [PageController::class, 'otpConfirmation'])->name('otp-confirmation');
Route::post('health-questions', [PageController::class, 'healthQuestion'])->name('health-questions');
Route::post('information-form', [PageController::class, 'informationForm'])->name('information-form');