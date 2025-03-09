<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use App\Models\Component;

class TotalComponents extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            BaseWidget\Stat::make('total_components', Component::count())
                ->label(__('filament/widgets.total_components.label'))
        ];
    }
}
