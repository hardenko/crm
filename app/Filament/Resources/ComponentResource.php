<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComponentResource\Pages;
use App\Models\Client;
use App\Models\Component;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;

class ComponentResource extends Resource implements HasShieldPermissions
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
                Select::make('supplier_id')
                    ->label(__('filament/resources/component.fields.supplier.label'))
                    ->options(Client::where('client_type', 'supplier')->pluck('name', 'id'))
                    ->searchable()
                    ->nullable(),
                Textarea::make('description')
                    ->label(__('filament/resources/component.fields.description.label'))
                ->columnSpanFull(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(__('filament/resources/component.columns.id.label'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('name')
                    ->label(__('filament/resources/component.fields.name.label'))
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('description')
                    ->label(__('filament/resources/component.fields.description.label'))
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('supplier.name')
                    ->label(__('filament/resources/component.fields.supplier.label'))
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label(__('filament/resources/component.columns.created_at.label'))
                    ->dateTime('d.m.Y H:i:s')
                    ->toggleable(isToggledHiddenByDefault: true)
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
    public static function getNavigationGroup(): string
    {
        return __('filament/navigation.admin_panel_label');
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->can('view_any_component');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('create_component');
    }

    public static function canEdit($record): bool
    {
        return auth()->user()->can('update_component');
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->can('delete_component');
    }
    public static function canDeleteAny(): bool
    {
        return auth()->user()->can('delete_any_component');
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
