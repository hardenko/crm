<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Order;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

final class OrderStatsChart extends ChartWidget
{
    protected static ?string $heading = 'Order Statistics';
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
                    'label' => 'Orders',
                    'data' => $orders->map(fn(TrendValue $value) => $value->aggregate),
                    'backgroundColor' => 'rgba(54, 162, 235, 0.5)',
                ],
                [
                    'label' => 'Revenue',
                    'data' => $revenue->map(fn(TrendValue $value) => $value->aggregate),
                    'backgroundColor' => 'rgba(75, 192, 192, 0.5)',
                ],
            ],
            'labels' => $orders->map(fn(TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getFilters(): ?array
    {
        return [
            'week' => 'This week',
            'month' => 'This month',
            'year' => 'This year',
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
}
