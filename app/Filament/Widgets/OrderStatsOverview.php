<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatusTypeEnum;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

final class OrderStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Revenue', Order::query()->sum('total_price'))
                ->label(__('filament/widgets.order_stats_overview.total_revenue.label'))
                ->description(__('filament/widgets.order_stats_overview.total_revenue.description'))
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('cyan')
                ->url(route('filament.admin.resources.orders.index')),
            Stat::make('Total Orders', Order::query()->count())
                ->label(__('filament/widgets.order_stats_overview.total_orders.label'))
                ->description(__('filament/widgets.order_stats_overview.total_orders.description'))
                ->descriptionIcon('heroicon-o-shopping-cart')
                ->color('info')
                ->url(route('filament.admin.resources.orders.index')),
            Stat::make('Pending Orders', Order::query()->where('order_status', OrderStatusTypeEnum::PENDING)->count())
                ->label(__('filament/widgets.order_stats_overview.pending_orders.label'))
                ->description(__('filament/widgets.order_stats_overview.pending_orders.description'))
                ->descriptionIcon('heroicon-o-clock')
                ->color('yellow')
                ->url(route('filament.admin.resources.orders.index', ['tableFilters' => ['order_status' => ['value' => OrderStatusTypeEnum::PENDING->value]]])),
            Stat::make('Processing Orders', Order::query()->where('order_status', OrderStatusTypeEnum::PROCESSING)->count())
                ->label(__('filament/widgets.order_stats_overview.processing_orders.label'))
                ->description(__('filament/widgets.order_stats_overview.processing_orders.description'))
                ->descriptionIcon('heroicon-o-clock')
                ->color('violet')
                ->url(route('filament.admin.resources.orders.index', ['tableFilters' => ['order_status' => ['value' => OrderStatusTypeEnum::PROCESSING->value]]])),
            Stat::make('Shipped Orders', Order::query()->where('order_status', OrderStatusTypeEnum::SHIPPED)->count())
                ->label(__('filament/widgets.order_stats_overview.shipped_orders.label'))
                ->description(__('filament/widgets.order_stats_overview.shipped_orders.description'))
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success')
                ->url(route('filament.admin.resources.orders.index', ['tableFilters' => ['order_status' => ['value' => OrderStatusTypeEnum::SHIPPED->value]]])),
            Stat::make('Delivered Orders', Order::query()->where('order_status', OrderStatusTypeEnum::DELIVERED)->count())
                ->label(__('filament/widgets.order_stats_overview.delivered_orders.label'))
                ->description(__('filament/widgets.order_stats_overview.delivered_orders.description'))
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success')
                ->url(route('filament.admin.resources.orders.index', ['tableFilters' => ['order_status' => ['value' => OrderStatusTypeEnum::DELIVERED->value]]])),
            Stat::make('Cancelled Orders', Order::query()->where('order_status', OrderStatusTypeEnum::CANCELLED)->count())
                ->label(__('filament/widgets.order_stats_overview.cancelled_orders.label'))
                ->description(__('filament/widgets.order_stats_overview.cancelled_orders.description'))
                ->descriptionIcon('heroicon-m-x-mark')
                ->color('danger')
                ->url(route('filament.admin.resources.orders.index', ['tableFilters' => ['order_status' => ['value' => OrderStatusTypeEnum::CANCELLED->value]]])),

        ];
    }
    public static function canView(): bool
    {
        return auth()->user()->can('widget_OrderStatsOverview') ?? false;
    }
}
