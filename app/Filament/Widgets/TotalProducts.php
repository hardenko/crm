<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use App\Models\Product;

class TotalProducts extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            BaseWidget\Stat::make('Total quantity of products', Product::count())
        ];
    }
}
