<?php

namespace App\Filament\Resources;

use App\Enums\ClientType;
use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers;
use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;
    protected static ?string $navigationIcon = 'heroicon-c-banknotes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label(__('filament/resources/client.fields.name.label'))
                    ->maxLength(255)
                    ->required(),
                TextInput::make('phone')
                    ->label(__('filament/resources/client.fields.phone.label'))
                    ->required(),
                TextInput::make('tax_id')
                    ->label(__('filament/resources/client.fields.tax_id.label'))
                    ->maxLength(255)
                    ->nullable(),
                TextInput::make('bank_account')
                    ->label(__('filament/resources/client.fields.bank_account.label'))
                    ->maxLength(255)
                    ->nullable(),
                TextInput::make('comments')
                    ->label(__('filament/resources/client.fields.comments.label'))
                    ->maxLength(255)
                    ->nullable(),
                Select::make('type')
                    ->label(__('filament/resources/client.fields.type.label'))
                    ->options(ClientType::options())
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament/resources/client.fields.name.label'))
                    ->searchable(),
                TextColumn::make('phone')
                    ->label(__('filament/resources/client.fields.phone.label'))
                    ->searchable(),
                TextColumn::make('tax_id')
                    ->label(__('filament/resources/client.fields.tax_id.label'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('type')
                    ->label(__('filament/resources/client.fields.type.label'))
                    ->sortable()
                    ->badge()
                    ->formatStateUsing(fn($state) => ClientType::tryFrom($state)?->label() ?? $state)
                    ->color(fn($state): string => match (ClientType::tryFrom($state)) {
                        ClientType::Payer => 'yellow',
                        ClientType::Receiver => 'success',
                    }),
                TextColumn::make('bank_account')
                    ->label(__('filament/resources/client.fields.bank_account.label'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('comments')
                    ->label(__('filament/resources/client.fields.comments.label'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('type')
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

    public static function getNavigationGroup(): string //TODO ?
    {
        return __('filament/navigation.admin_panel_label');
    }
}
