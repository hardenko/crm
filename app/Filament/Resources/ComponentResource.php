<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComponentResource\Pages;
use App\Models\Component;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;

class ComponentResource extends Resource
{
    protected static ?string $model = Component::class;

    protected static ?string $navigationIcon = 'heroicon-m-rectangle-stack';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label(__('filament/resources/component.fields.name.label'))
                    ->required(),
                Textarea::make('description')
                    ->label(__('filament/resources/component.fields.description.label')),
                TextInput::make('quantity_in_stock')
                    ->label(__('filament/resources/component.fields.quantity_in_stock.label'))
                    ->numeric()
                    ->required(),
                TextInput::make('supplier')
                    ->label(__('filament/resources/component.fields.supplier.label')),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament/resources/component.fields.name.label'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('description')
                    ->label(__('filament/resources/component.fields.description.label'))
                    ->searchable(),
                TextColumn::make('quantity_in_stock')
                    ->label(__('filament/resources/component.fields.quantity_in_stock.label'))
                    ->sortable(),
                TextColumn::make('supplier')
                    ->label(__('filament/resources/component.fields.supplier.label'))
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComponents::route('/'),
            'create' => Pages\CreateComponent::route('/create'),
            'edit' => Pages\EditComponent::route('/{record}/edit'),
        ];
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament/resources/component.plural_label');
    }

    public static function getModelLabel(): string
    {
        return __('filament/resources/component.label'); // Для однини
    }

    public static function getNavigationLabel(): string
    {
        return __('filament/resources/component.navigation_label');
    }
}
