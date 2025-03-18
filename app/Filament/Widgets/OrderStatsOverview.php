<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatusType;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

final class OrderStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Revenue', Order::query()->sum('total_price'))
                ->description('Total revenue from all orders')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('cyan')
                ->url(route('filament.admin.resources.orders.index')),

            Stat::make('Total Orders', Order::query()->count())
                ->description('Total number of orders')
                ->descriptionIcon('heroicon-o-shopping-cart')
                ->color('info')
                ->url(route('filament.admin.resources.orders.index')),

            Stat::make('Pending Orders', Order::query()->where('order_status', OrderStatusType::Pending)->count())
                ->description('Orders that are currently pending')
                ->descriptionIcon('heroicon-o-clock')
                ->color('yellow')
                ->url(route('filament.admin.resources.orders.index', ['tableFilters' => ['order_status' => ['value' => OrderStatusType::Pending->value]]])),

            Stat::make('Processing Orders', Order::query()->where('order_status', OrderStatusType::Processing)->count())
                ->description('Orders that are currently processing')
                ->descriptionIcon('heroicon-o-clock')
                ->color('violet')
                ->url(route('filament.admin.resources.orders.index', ['tableFilters' => ['order_status' => ['value' => OrderStatusType::Processing->value]]])),

            Stat::make('Shipped Orders', Order::query()->where('order_status', OrderStatusType::Shipped)->count())
                ->description('Orders that have been shipped')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success')
                ->url(route('filament.admin.resources.orders.index', ['tableFilters' => ['order_status' => ['value' => OrderStatusType::Shipped->value]]])),

            Stat::make('Delivered Orders', Order::query()->where('order_status', OrderStatusType::Delivered)->count())
                ->description('Orders that have been delivered')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success')
                ->url(route('filament.admin.resources.orders.index', ['tableFilters' => ['order_status' => ['value' => OrderStatusType::Delivered->value]]])),

            Stat::make('Cancelled Orders', Order::query()->where('order_status', OrderStatusType::Cancelled)->count())
                ->description('Orders that have been cancelled')
                ->descriptionIcon('heroicon-m-x-mark')
                ->color('danger')
                ->url(route('filament.admin.resources.orders.index', ['tableFilters' => ['order_status' => ['value' => OrderStatusType::Cancelled->value]]])),

        ];
    }

    public static function canView(): bool
    {
        return auth()->user()->can('view dashboard') ?? false;
    }
}
