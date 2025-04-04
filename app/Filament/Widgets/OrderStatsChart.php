<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Order;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

final class OrderStatsChart extends ChartWidget
{
    protected static ?string $heading = null;
    protected static string $color = 'Primary';
    protected static ?int $sort = 1;

    public ?string $filter = 'week';

    protected function getData(): array
    {
        $activeFilter = $this->filter;
        list($start, $end, $intervalMethod) = $this->determineTimeFrame($activeFilter);

        $orders = Trend::model(Order::class)
            ->between(start: $start, end: $end)
            ->$intervalMethod()
            ->count();

        $revenue = Trend::model(Order::class)
            ->between(start: $start, end: $end)
            ->$intervalMethod()
            ->sum('total_price');

        return [
            'datasets' => [
                [
                    'label' => __('filament/widgets.order_stats_chart.datasets.orders.label'),
                    'data' => $orders->map(fn(TrendValue $value) => $value->aggregate),
                    'backgroundColor' => 'rgba(54, 162, 235, 0.5)',
                ],
                [
                    'label' => __('filament/widgets.order_stats_chart.datasets.revenue.label'),
                    'data' => $revenue->map(fn(TrendValue $value) => $value->aggregate),
                    'backgroundColor' => 'rgba(75, 192, 192, 0.5)',
                ],
            ],
            'labels' => $orders->map(fn(TrendValue $value) => $value->date),
        ];
    }

    public function getHeading(): ?string
    {
        return __('filament/widgets.order_stats_chart.label');
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getFilters(): ?array
    {
        return [
            'week' => __('filament/widgets.order_stats_chart.filters.week'),
            'month' => __('filament/widgets.order_stats_chart.filters.month'),
            'year' => __('filament/widgets.order_stats_chart.filters.year'),
        ];
    }

    protected function determineTimeFrame($filter): array
    {
        return match ($filter) {
            'month' => [now()->startOfMonth(), now()->endOfMonth(), 'perDay'],
            'year' => [now()->startOfYear(), now()->endOfYear(), 'perMonth'],
            default => [now()->startOfWeek(), now()->endOfWeek(), 'perDay'],
        };
    }
    public static function canView(): bool
    {
        return auth()->user()->can('widget_OrderStatsChart') ?? false;
    }
}
