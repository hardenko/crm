<?php

namespace App\Filament\Resources;

use App\Enums\ClientLegalForm;
use App\Enums\ClientType;
use App\Models\Client;
use App\Filament\Resources\ClientResource\Pages;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ClientResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Client::class;
    protected static ?string $navigationIcon = 'heroicon-c-banknotes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('')
                    ->schema([
                        TextInput::make('name')
                            ->label(__('filament/resources/client.fields.name.label'))
                            ->maxLength(255)
                            ->required(),
                        TextInput::make('phone')
                            ->label(__('filament/resources/client.fields.phone.label'))
                            ->default('+380')
                            ->length(13)
                            ->required()
                            ->rules(['regex:/^\+38\d{3}\d{7}$/']),
                    ])->columns(),
                Section::make('')
                    ->schema([
                        Select::make('client_type')
                            ->label(__('filament/resources/client.fields.type.label'))
                            ->options(ClientType::options())
                            ->required(),
                        Select::make('legal_form')
                            ->label(__('filament/resources/client.fields.legal_form.label'))
                            ->options(ClientLegalForm::options())
                            ->required(),
                    ])->columns(),
                Section::make('')
                    ->schema([
                        Textarea::make('bank_account')
                            ->label(__('filament/resources/client.fields.bank_account.label'))
                            ->maxLength(255)
                            ->nullable(),
                    ])->columnSpanFull(),
                Section::make('')
                    ->schema([
                        Textarea::make('comments')
                            ->label(__('filament/resources/client.fields.comments.label'))
                            ->maxLength(255)
                            ->nullable(),
                    ])->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(__('filament/resources/client.columns.id.label'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('name')
                    ->label(__('filament/resources/client.fields.name.label'))
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('phone')
                    ->label(__('filament/resources/client.fields.phone.label'))
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('legal_form')
                    ->label(__('filament/resources/client.fields.legal_form.label'))
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('client_type')
                    ->label(__('filament/resources/client.fields.type.label'))
                    ->sortable()
                    ->badge()
                    ->formatStateUsing(fn($record) => $record->client_type->label())
                    ->color(fn($record): string => match ($record->client_type) {
                        ClientType::Payer => 'yellow',
                        ClientType::Receiver => 'success',
                        ClientType::Supplier => 'info',
                    })
                    ->toggleable(),
                TextColumn::make('bank_account')
                    ->label(__('filament/resources/client.fields.bank_account.label'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('comments')
                    ->label(__('filament/resources/client.fields.comments.label'))
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label(__('filament/resources/client.columns.created_at.label'))
                    ->dateTime('d.m.Y H:i:s')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('client_type')
                    ->label(__('filament/resources/client.fields.type.label'))
                    ->options(ClientType::options()),
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
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
            'view' => Pages\ViewClient::route('/{record}'),
        ];
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament/resources/client.plural_label');
    }

    public static function getModelLabel(): string
    {
        return __('filament/resources/client.label');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament/resources/client.navigation_label');
    }

    public static function getNavigationGroup(): string
    {
        return __('filament/navigation.admin_panel_label');
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->can('view_any_client');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('create_client');
    }

    public static function canEdit($record): bool
    {
        return auth()->user()->can('update_client');
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->can('delete_client');
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()->can('delete_any_client');
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
