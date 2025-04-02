<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

class ProductResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-c-rocket-launch';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Section::make('')
                    ->schema([
                        TextInput::make('name')
                            ->label(__('filament/resources/product.fields.name.label'))
                            ->maxLength(255)
                            ->required(),
                        TextInput::make('type')
                            ->label(__('filament/resources/product.fields.type.label'))
                            ->maxLength(255)
                            ->required(),
                        TextInput::make('price')
                            ->label(__('filament/resources/product.fields.price.label'))
                            ->numeric()
                            ->required(),
                    ])->columns(3),
                Section::make(__('filament/resources/product.section_label'))
                    ->schema([
                        Repeater::make('components')
                            ->label(__('filament/resources/product.section_button'))
                            ->relationship('productComponentsRelation')
                            ->schema([
                                Select::make('component_id')
                                    ->label(__('filament/resources/product.section_fields.component_id.label'))
                                    ->relationship('components', 'name')
                                    ->searchable()
                                    ->required(),
                                TextInput::make('quantity')
                                    ->label(__('filament/resources/product.section_fields.quantity.label'))
                                    ->integer()
                                    ->required(),
                            ])
                            ->hiddenLabel()
                            ->columnSpanFull(),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(__('filament/resources/product.columns.id.label'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('name')
                    ->label(__('filament/resources/product.fields.name.label'))
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('type')
                    ->label(__('filament/resources/product.fields.type.label'))
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('price')
                    ->label(__('filament/resources/product.fields.price.label'))
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label(__('filament/resources/product.columns.created_at.label'))
                    ->dateTime('d.m.Y H:i:s')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
            ])
            ->filters([

            ])
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament/resources/product.plural_label');
    }

    public static function getModelLabel(): string
    {
        return __('filament/resources/product.label');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament/resources/product.navigation_label');
    }

    public static function getNavigationGroup(): string
    {
        return __('filament/navigation.admin_panel_label');
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->can('view_any_product');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('create_product');
    }

    public static function canEdit($record): bool
    {
        return auth()->user()->can('update_product');
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->can('delete_product');
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()->can('delete_any_product');
    }

    public static function getPermissionPrefixes(): array
    {
        return [
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any'
        ];
    }
}
