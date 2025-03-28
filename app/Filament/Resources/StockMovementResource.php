<?php

namespace App\Filament\Resources;

use App\Enums\StockMovementType;
use App\Filament\Resources\StockMovementResource\Pages;
use App\Models\Client;
use App\Models\Component;
use App\Models\StockMovement;
use App\Models\Warehouse;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class StockMovementResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = StockMovement::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('')
                    ->schema([
                        Select::make('type')
                            ->label(__('filament/resources/stock_movement.fields.type.label'))
                            ->options(StockMovementType::options())
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($set, $get, $state) {
                                $type = StockMovementType::tryFrom((string)$state);
                                $set('max_quantity', $type === StockMovementType::Outgoing ? Warehouse::where('component_id', $get('component_id'))->value('quantity') ?? 0 : null);
                                $set('comments_required', $type === StockMovementType::Outgoing);
                                $set('supplier_visible', $type === StockMovementType::Incoming);
                            }),
                        Select::make('component_id')
                            ->label(__('filament/resources/stock_movement.fields.component_id.label'))
                            ->options(Component::pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($set, $state) {
                                $component = Component::find($state);
                                $supplierId = $component ? $component->supplier_id : null;
                                $set('supplier_id', $supplierId);
                                $availableQuantity = Warehouse::where('component_id', $state)->value('quantity') ?? 0;
                                $set('available_quantity', $availableQuantity);
                                $set('max_quantity', $availableQuantity);
                            }),
                        Select::make('supplier_id')
                            ->label(__('filament/resources/stock_movement.fields.supplier_id.label'))
                            ->options(Client::where('client_type', 'supplier')->pluck('name', 'id'))
                            ->searchable()
                            ->hidden(fn($get) => !$get('supplier_visible'))
                            ->nullable()
                            ->default(null)
                            ->required(fn($get) => $get('supplier_visible')),
                    ])->columns(3),
                Section::make('')
                    ->schema([
                        TextInput::make('quantity')
                            ->label(__('filament/resources/stock_movement.fields.quantity.label'))
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->maxValue(fn($get) => StockMovementType::tryFrom($get('type')) === StockMovementType::Outgoing ? $get('max_quantity') : null),
                        TextInput::make('available_quantity')
                            ->label(__('filament/resources/stock_movement.fields.available_quantity.label'))
                            ->disabled()
                            ->default(0),
                        TextInput::make('price')
                            ->label(__('filament/resources/stock_movement.fields.price.label'))
                            ->numeric()
                            ->nullable()
                            ->default(null)
                            ->required(fn($get) => StockMovementType::tryFrom($get('type')) === StockMovementType::Incoming),
                    ])->columns(3),
                Section::make('')
                    ->schema([
                        Textarea::make('comments')
                            ->label(__('filament/resources/stock_movement.fields.comments.label'))
                            ->nullable()
                            ->required(fn($get) => StockMovementType::tryFrom($get('type')) === StockMovementType::Outgoing),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(__('filament/resources/stock_movement.columns.id.label'))
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('component.name')
                    ->label(__('filament/resources/stock_movement.fields.component_id.label'))
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('supplier.name')
                    ->label(__('filament/resources/stock_movement.fields.supplier_id.label'))
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('quantity')
                    ->label(__('filament/resources/stock_movement.fields.quantity.label'))
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('price')
                    ->label(__('filament/resources/stock_movement.fields.price.label'))
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('type')
                    ->label(__('filament/resources/stock_movement.fields.type.label'))
                    ->badge()
                    ->formatStateUsing(fn($record) => $record->type->label())
                    ->color(fn($record): string => match ($record->type) {
                        StockMovementType::Incoming => 'success',
                        StockMovementType::Outgoing => 'yellow',
                    })
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('comments')
                    ->label(__('filament/resources/stock_movement.fields.comments.label'))
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label(__('filament/resources/stock_movement.columns.created_at.label'))
                    ->dateTime('d.m.Y H:i:s')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label(__('filament/resources/stock_movement.fields.type.label'))
                    ->options(fn() => StockMovementType::options())
                    ->default(null),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStockMovements::route('/'),
            'create' => Pages\CreateStockMovement::route('/create'),
        ];
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament/resources/stock_movement.plural_label');
    }

    public static function getModelLabel(): string
    {
        return __('filament/resources/stock_movement.label');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament/resources/stock_movement.navigation_label');
    }
    public static function canDelete(Model $record): bool
    {
        return false;
    }
    public static function canEdit(Model $record): bool
    {
        return false;
    }
    public static function canViewAny(): bool
    {
        return auth()->user()->can('view_any_stock::movement');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('create_stock::movement');
    }

    public static function getPermissionPrefixes(): array
    {
        return [
            'view_any',
            'create'
        ];
    }
}
