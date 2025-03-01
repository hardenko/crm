<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use App\Models\Component;

class TotalComponents extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            BaseWidget\Stat::make('Total quantity of components', Component::count())
        ];
    }
}
