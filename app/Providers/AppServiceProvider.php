<?php

namespace App\Providers;

use App\Interfaces\ClientListServiceInterface;
use App\Interfaces\ComponentListServiceInterface;
use App\Interfaces\ProductListServiceInterface;
use App\Interfaces\UserListServiceInterface;
use App\Services\ClientListService;
use App\Services\ComponentListService;
use App\Services\ProductListService;
use App\Services\UserListService;
use Illuminate\Support\Facades\Gate;
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
        $this->app->bind(ProductListServiceInterface::class, ProductListService::class);
        $this->app->bind(UserListServiceInterface::class, UserListService::class);
        $this->app->bind(ClientListServiceInterface::class, ClientListService::class);

        Gate::before(function ($user, $ability) {
            if ($user->hasRole('admin')) {
                return true;
            }
            return null;
        });
    }
}
