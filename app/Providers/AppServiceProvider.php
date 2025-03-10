<?php

namespace App\Providers;

use App\Interfaces\ComponentListServiceInterface;
use App\Services\ComponentListService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(ComponentListServiceInterface::class, ComponentListService::class);
    }
}
