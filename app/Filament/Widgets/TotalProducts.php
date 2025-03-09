<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use App\Models\Product;

class TotalProducts extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            BaseWidget\Stat::make(__('total_products'), Product::count())
                ->label(__('filament/widgets.total_products.label'))
        ];
    }
}
