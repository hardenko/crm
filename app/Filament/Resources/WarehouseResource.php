<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WarehouseResource\Pages;
use App\Models\WarehouseItem;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class WarehouseResource extends Resource
{
    protected static ?string $model = WarehouseItem::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('component_id')
            ->columns([
                TextColumn::make('component.name')
                    ->label('Component')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('quantity')
                    ->label('Quantity')
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
}
