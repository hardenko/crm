<?php

namespace App\Filament\Resources;

use App\Enums\UserRoleType;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UserResource extends Resource
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
                Select::make('user_role')
                    ->label(__('filament/resources/user.fields.user_role.label'))
                    ->options(UserRoleType::options())
                    ->required(),
                TextInput::make('password')
                    ->label(__('filament/resources/user.fields.password.label'))
                    ->password()
                    ->required(fn ($context) => $context === 'create') //перевіряє контекст форми (create або edit)
                    ->dehydrated(fn ($state) => !empty($state)) //керує тим, чи передавати значення поля у форму обробки (dehydration – видалення з форми при сабміті).
                    ->hiddenOn('edit')
                    ->formatStateUsing(fn ($state) => bcrypt($state)),
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
                TextColumn::make('user_role')
                    ->label(__('filament/resources/user.fields.user_role.label'))
                    ->sortable()
                    ->badge()
                    ->formatStateUsing(fn($record) => $record->user_role->label())
                    ->color(fn($record): string => match ($record->user_role) {
                        UserRoleType::Admin => 'yellow',
                        UserRoleType::Manager => 'success',
                        UserRoleType::Wh_manager => 'danger',
                    })
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label(__('filament/resources/user.columns.created_at.label'))
                    ->dateTime('d.m.Y H:i:s')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('user_role')
                    ->label(__('filament/resources/user.fields.user_role.label'))
                    ->options(UserRoleType::options()),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
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
}
