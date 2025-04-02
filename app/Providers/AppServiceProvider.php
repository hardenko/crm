<?php

namespace App\Providers;

use App\Interfaces\ClientListServiceInterface;
use App\Interfaces\ComponentListServiceInterface;
use App\Interfaces\ProductListServiceInterface;
use App\Interfaces\UserListServiceInterface;
use App\Models\Component;
use App\Models\Order;
use App\Models\StockMovement;
use App\Observers\ComponentObserver;
use App\Observers\OrderObserver;
use App\Observers\StockMovementObserver;
use App\Services\ClientListService;
use App\Services\ComponentListService;
use App\Services\ProductListService;
use App\Services\UserListService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

class AppServiceProvider extends ServiceProvider
{
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

        Route::aliasMiddleware('permission', PermissionMiddleware::class);
        Route::aliasMiddleware('role', RoleMiddleware::class);
        Route::aliasMiddleware('role_or_permission', RoleOrPermissionMiddleware::class);

        Order::observe(OrderObserver::class);
        Component::observe(ComponentObserver::class);
        StockMovement::observe(StockMovementObserver::class);
    }
}
