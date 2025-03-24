<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Navigation\MenuItem;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;

class FilamentServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Filament::serving(function () {
            Filament::registerUserMenuItems([
                MenuItem::make()
                    ->label(__('filament/navigation.change_language'))
                    ->icon('heroicon-o-globe-alt')
                    ->url(route('switch-locale', ['lang' => App::getLocale() === 'en' ? 'uk' : 'en']))
                    ->sort(100),
            ]);
        });
    }
}
