<?php

namespace PaymentPackage;

use Illuminate\Support\ServiceProvider;
use PaymentPackage\Http\Controllers\PaymentController;

class PaymentPackageServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/payment-package.php',
            'payment-package'
        );
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'payment'); // Correct path here

        $this->publishes([
            __DIR__ . '/../config/payment-package.php' => config_path('payment-package.php'),
            __DIR__ . '/../resources/views' => resource_path('views'),
        ], 'payment-package');

        $this->app->make(PaymentController::class);
    }
}