<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UserResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-c-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label(__('filament/resources/user.fields.name.label'))
                    ->maxLength(255)
                    ->required(),
                TextInput::make('email')
                    ->label(__('filament/resources/user.fields.email.label'))
                    ->email()
                    ->unique(ignoreRecord: true)
                    ->required(),
                TextInput::make('password')
                    ->label(__('filament/resources/user.fields.password.label'))
                    ->password()
                    ->required(fn ($context) => $context === 'create') //перевіряє контекст форми (create або edit)
                    ->dehydrated(fn ($state) => !empty($state)) //керує тим, чи передавати значення поля у форму обробки (dehydration – видалення з форми при сабміті).
                    ->hiddenOn('edit')
                    ->formatStateUsing(fn ($state) => bcrypt($state)),
                Select::make('roles')
                    ->label(__('filament/resources/user.fields.roles.label'))
                    ->relationship('roles', 'name')
                    ->preload()
                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(__('filament/resources/user.columns.id.label'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('name')
                    ->label(__('filament/resources/user.fields.name.label'))
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('email')
                    ->label(__('filament/resources/user.fields.email.label'))
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('roles.name')
                    ->label(__('filament/resources/user.fields.roles.label'))
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label(__('filament/resources/user.columns.created_at.label'))
                    ->dateTime('d.m.Y H:i:s')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
            ])
            ->filters([

            ])
            ->actions([
                Tables\Actions\EditAction::make()->visible(fn ($record) => auth()->user()->can('edit users')),
                Tables\Actions\DeleteAction::make()->visible(fn ($record) => auth()->user()->can('delete users')),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->visible(fn () => auth()->user()->can('delete users')),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament/resources/user.plural_label');
    }

    public static function getModelLabel(): string
    {
        return __('filament/resources/user.label');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament/resources/user.navigation_label');
    }

    public static function getNavigationGroup(): string
    {
        return __('filament/navigation.admin_panel_label');
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->can('view_any_user');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('create_user');
    }

    public static function canEdit($record): bool
    {
        return auth()->user()->can('update_user');
    }
    public static function canDelete($record): bool
    {
        return auth()->user()->can('delete_user');
    }

    public static function getPermissionPrefixes(): array
    {
        return [
            'view_any',
            'create',
            'update',
            'delete',
        ];
    }
}
