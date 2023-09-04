<?php

use Illuminate\Support\Facades\Route;
use PaymentPackage\Http\Controllers\PaymentController;

Route::middleware(['web'])->group(function () {
    Route::get('/payment/process', [PaymentController::class, 'process']);
    Route::get('/payment/failure', [PaymentController::class, 'failure']);
    Route::post('/payment/callback', [PaymentController::class, 'callback']);
});