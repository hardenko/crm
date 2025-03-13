<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StockMovementResource\Pages;
use App\Models\Client;
use App\Models\Component;
use App\Models\StockMovement;
use App\Models\WarehouseItem;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class StockMovementResource extends Resource
{
    protected static ?string $model = StockMovement::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('type')
                    ->label('Type')
                    ->options([
                        'incoming' => 'Incoming',
                        'outgoing' => 'Outgoing'
                    ])
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($set, $get, $state) {
                        $set('max_quantity', $state === 'outgoing' ? WarehouseItem::where('component_id', $get('component_id'))->value('quantity') ?? 0 : null);
                        $set('comments_required', $state === 'outgoing');
                        $set('supplier_visible', $state === 'incoming');
                    }),
                Select::make('component_id')
                    ->label('Component')
                    ->options(Component::pluck('name', 'id'))
                    ->searchable()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($set, $state) {
                        $component = Component::find($state);
                        $supplierId = $component ? $component->supplier_id : null;
                        $set('supplier_id', $supplierId);
                        $availableQuantity = WarehouseItem::where('component_id', $state)->value('quantity') ?? 0;
                        $set('available_quantity', $availableQuantity);
                        $set('max_quantity', $availableQuantity);
                    }),
                TextInput::make('quantity')
                    ->label('Quantity')
                    ->numeric()
                    ->required()
                    ->minValue(1)
                    ->maxValue(fn($get) => $get('type') === 'outgoing' ? $get('max_quantity') : null),
                TextInput::make('available_quantity')
                    ->label('Available Quantity')
                    ->disabled()
                    ->default(0),
                Select::make('supplier_id')
                    ->label('Supplier')
                    ->options(Client::where('client_type', 'supplier')->pluck('name', 'id'))
                    ->searchable()
                    ->hidden(fn($get) => !$get('supplier_visible'))
                    ->nullable()
                    ->default(null)
                    ->required(fn($get) => $get('supplier_visible')),
                TextInput::make('price')
                    ->label('Price')
                    ->numeric()
                    ->nullable()
                    ->default(null)
                    ->required(fn($get) => $get('type') === 'incoming'),
                Textarea::make('comments')
                    ->label('Comments')
                    ->nullable()
                    ->required(fn($get) => $get('type') === 'outgoing'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('component.name')->label('Component')->sortable(),
                TextColumn::make('supplier.name')->label('Supplier')->sortable(),
                TextColumn::make('quantity')->label('Quantity')->sortable(),
                TextColumn::make('price')->label('Price')->sortable(),
                TextColumn::make('type')->label('Type')->sortable(),
                TextColumn::make('comments')->label('Comments'),
                TextColumn::make('created_at')->label('Date')->dateTime('d.m.Y H:i:s')->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Type')
                    ->options([
                        'incoming' => 'Incoming',
                        'outgoing' => 'Outgoing',
                    ])
                    ->default('')
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStockMovements::route('/'),
            'create' => Pages\CreateStockMovement::route('/create'),
        ];
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }
}
