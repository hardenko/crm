<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WarehouseResource\Pages;
use App\Models\Warehouse;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class WarehouseResource extends Resource
{
    protected static ?string $model = Warehouse::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('component.name')
                    ->label(__('filament/resources/warehouse.columns.component_name.label'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('quantity')
                    ->label(__('filament/resources/warehouse.columns.quantity.label'))
                    ->sortable(),
            ])
            ->filters([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWarehouses::route('/'),
        ];
    }
    public static function canCreate(): bool
    {
        return false;
    }
    public static function canEdit(Model $record): bool
    {
        return false;
    }
    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament/resources/warehouse.plural_label');
    }
    public static function getNavigationLabel(): string
    {
        return __('filament/resources/warehouse.navigation_label');
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->can('view warehouse');
    }
}
