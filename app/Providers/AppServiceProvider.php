<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Services
use App\Services\CurrencyQuotation;
use App\Services\CurrencyQuotationInterface;

// Repositories

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Register Services
        $this->app->bind(CurrencyQuotationInterface::class, CurrencyQuotation::class);

        // Register Repositories
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
