<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\TransaksiService;
use App\Services\AsetTetapService;
use App\Services\StockService;
use App\Services\LaporanService;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(StockService::class);
        $this->app->singleton(AsetTetapService::class);
        $this->app->singleton(TransaksiService::class, function ($app) {
            return new TransaksiService($app->make(StockService::class));
        });
        $this->app->singleton(LaporanService::class);
    }

    public function boot(): void
    {
        //
    }
}
