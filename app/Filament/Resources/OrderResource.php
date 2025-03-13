<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatusType;
use App\Enums\PaymentStatusType;
use App\Filament\Resources\OrderResource\Pages;
use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use App\Models\WarehouseItem;
use Closure;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('product_id')
                    ->label('Product')
                    ->options(Product::pluck('name', 'id'))
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn($state, callable $set) => $set('total_price', Product::find($state)?->price ?? 0)
                    ),
                TextInput::make('quantity')
                    ->numeric()
                    ->default(1)
                    ->minValue(1)
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn($state, callable $set, callable $get) => $set('total_price', $state * (Product::find($get('product_id'))?->price ?? 0))
                    )
                    ->rule(function (callable $get) {
                        return function (string $attribute, $value, Closure $fail) use ($get) {
                            $product = Product::with('belongsToManyComponents')->find($get('product_id'));
                            if (!$product) return;

                            foreach ($product->belongsToManyComponents as $component) {
                                $neededQuantity = $component->pivot->quantity * $value;
                                $warehouseItem = WarehouseItem::where('component_id', $component->id)->first();

                                if (!$warehouseItem || $warehouseItem->quantity < $neededQuantity) {
                                    $fail("Not enough stock for component: {$component->name}. Required: {$neededQuantity}, Available: " . ($warehouseItem->quantity ?? 0));
                                }
                            }
                        };
                    }),
                TextInput::make('total_price')
                    ->disabled()
                    ->required(),
                Select::make('payer_id')
                    ->label('Payer')
                    ->options(Client::where('client_type', 'payer')->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                Select::make('receiver_id')
                    ->label('Receiver')
                    ->options(Client::where('client_type', 'receiver')->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                Select::make('payment_status')
                    ->label('Payment Status')
                    ->options(PaymentStatusType::options())
                    ->default(PaymentStatusType::Pending)
                    ->required(),
                Select::make('order_status')
                    ->label('Order Status')
                    ->options(OrderStatusType::options())
                    ->default(OrderStatusType::Pending)
                    ->required(),
                TextInput::make('comments')
                    ->nullable(),
            ]);
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        return self::calculateTotalPrice($data);
    }

    public static function mutateFormDataBeforeSave(array $data): array
    {
        return self::calculateTotalPrice($data);
    }

    private static function calculateTotalPrice(array $data): array
    {
        $product = Product::find($data['product_id'] ?? null);
        $data['total_price'] = ($data['quantity'] ?? 1) * ($product?->price ?? 0);
        return $data;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('product.name')
                    ->label('Product')
                    ->sortable(),
                TextColumn::make('order_status')
                    ->badge()
                    ->sortable(),
                TextColumn::make('quantity')
                    ->sortable(),
                TextColumn::make('total_price')
                    ->sortable(),
                TextColumn::make('payer.name')
                    ->label('Payer')
                    ->sortable(),
                TextColumn::make('payment_status')
                    ->badge()
                    ->formatStateUsing(fn($record) => $record->payment_status->label())
                    ->color(fn($record): string => match ($record->payment_status) {
                        PaymentStatusType::Pending => 'yellow',
                        PaymentStatusType::Completed => 'success',
                        PaymentStatusType::Failed => 'danger',
                    })
                    ->sortable(),
                TextColumn::make('receiver.name')
                    ->label('Receiver')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime('d.m.Y H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
