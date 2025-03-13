<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatusType;
use App\Enums\PaymentStatusType;
use App\Filament\Resources\OrderResource\Pages;
use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use App\Models\Warehouse;
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
                    ->label(__('filament/resources/order.fields.product_id.label'))
                    ->options(Product::pluck('name', 'id'))
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn($state, callable $set) => $set('total_price', Product::find($state)?->price ?? 0)
                    ),
                TextInput::make('quantity')
                    ->label(__('filament/resources/order.fields.quantity.label'))
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
                                $warehouseItem = Warehouse::where('component_id', $component->id)->first();

                                if (!$warehouseItem || $warehouseItem->quantity < $neededQuantity) {
                                    $fail("Not enough stock for component: {$component->name}. Required: {$neededQuantity}, Available: " . ($warehouseItem->quantity ?? 0));
                                }
                            }
                        };
                    }),
                TextInput::make('total_price')
                    ->label(__('filament/resources/order.fields.total_price.label'))
                    ->disabled()
                    ->required(),
                Select::make('payer_id')
                    ->label(__('filament/resources/order.fields.payer_id.label'))
                    ->options(Client::where('client_type', 'payer')->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                Select::make('receiver_id')
                    ->label(__('filament/resources/order.fields.receiver_id.label'))
                    ->options(Client::where('client_type', 'receiver')->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                Select::make('payment_status')
                    ->label(__('filament/resources/order.fields.payment_status.label'))
                    ->options(PaymentStatusType::options())
                    ->default(PaymentStatusType::Pending)
                    ->required(),
                Select::make('order_status')
                    ->label(__('filament/resources/order.fields.order_status.label'))
                    ->options(OrderStatusType::options())
                    ->default(OrderStatusType::Pending)
                    ->required(),
                TextInput::make('comments')
                    ->label(__('filament/resources/order.fields.comments.label'))
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
                    ->label(__('filament/resources/order.columns.id.label'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('product.name')
                    ->label(__('filament/resources/order.fields.product_id.label'))
                    ->sortable(),
                TextColumn::make('order_status')
                    ->label(__('filament/resources/order.fields.order_status.label'))
                    ->badge()
                    ->formatStateUsing(fn($record) => $record->order_status->label())
                    ->color(fn($record): string => match ($record->order_status)
                    {
                        OrderStatusType::Pending => 'yellow',
                        OrderStatusType::Processing => 'success',
                        OrderStatusType::Shipped => 'zinc',
                        OrderStatusType::Delivered => 'sky',
                        OrderStatusType::Cancelled => 'danger',
                    })
                    ->sortable(),
                TextColumn::make('quantity')
                    ->label(__('filament/resources/order.fields.quantity.label'))
                    ->sortable(),
                TextColumn::make('total_price')
                    ->label(__('filament/resources/order.fields.total_price.label'))
                    ->sortable(),
                TextColumn::make('payer.name')
                    ->label(__('filament/resources/order.fields.payer_id.label'))
                    ->sortable(),
                TextColumn::make('payment_status')
                    ->label(__('filament/resources/order.fields.payment_status.label'))
                    ->badge()
                    ->formatStateUsing(fn($record) => $record->payment_status->label())
                    ->color(fn($record): string => match ($record->payment_status) {
                        PaymentStatusType::Pending => 'yellow',
                        PaymentStatusType::Completed => 'success',
                        PaymentStatusType::Failed => 'danger',
                    })
                    ->sortable(),
                TextColumn::make('receiver.name')
                    ->label(__('filament/resources/order.fields.receiver_id.label'))
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('filament/resources/order.columns.created_at.label'))
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

    public static function getPluralModelLabel(): string
    {
        return __('filament/resources/order.plural_label');
    }
    public static function getModelLabel(): string
    {
        return __('filament/resources/order.label');
    }
    public static function getNavigationLabel(): string
    {
        return __('filament/resources/order.navigation_label');
    }
}
