<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatusTypeEnum;
use App\Enums\PaymentStatusTypeEnum;
use App\Filament\Resources\OrderResource\Pages;
use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use App\Rules\ValidateStock;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Infolists\Components\Section as InfolistSection;

class OrderResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('filament/resources/order.section_name.order_details'))
                    ->schema([
                        Select::make('product_id')
                            ->label(__('filament/resources/order.fields.product_id.label'))
                            ->options(Product::pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn($state, callable $set) => $set('total_price', Product::find($state)?->price ?? 0)),
                        TextInput::make('quantity')
                            ->label(__('filament/resources/order.fields.quantity.label'))
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn($state, callable $set, callable $get) => $set('total_price', $state * (Product::find($get('product_id'))?->price ?? 0)))
                            ->rule(function (callable $get) {
                                return $get('product_id')
                                    ? new ValidateStock($get('product_id'))
                                    : null;
                            }),
                        Select::make('order_status')
                            ->label(__('filament/resources/order.fields.order_status.label'))
                            ->options(OrderStatusTypeEnum::options())
                            ->default(OrderStatusTypeEnum::PENDING)
                            ->required(),
                    ])->columns(3),
                Section::make(__('filament/resources/order.section_name.payment_details'))
                    ->schema([
                        TextInput::make('total_price')
                            ->label(__('filament/resources/order.fields.total_price.label'))
                            ->disabled()
                            ->dehydrated(false)
                            ->required(),
                        Select::make('payment_status')
                            ->label(__('filament/resources/order.fields.payment_status.label'))
                            ->options(PaymentStatusTypeEnum::options())
                            ->default(PaymentStatusTypeEnum::PENDING)
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
                    ])->columns(),
                Section::make(__('filament/resources/order.section_name.additional_details'))
                    ->schema([
                        Textarea::make('comments')
                            ->label(__('filament/resources/order.fields.comments.label'))
                            ->nullable(),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(__('filament/resources/order.columns.id.label'))
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('product.name')
                    ->label(__('filament/resources/order.fields.product_id.label'))
                    ->color('info')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->action(
                        Tables\Actions\ViewAction::make()
                            ->modalHidden(fn($record) => $record['product.name']),
                    ),
                TextColumn::make('order_status')
                    ->label(__('filament/resources/order.fields.order_status.label'))
                    ->badge()
                    ->formatStateUsing(fn($record) => $record->order_status->label())
                    ->color(fn($record): string => match ($record->order_status) {
                        OrderStatusTypeEnum::PENDING => 'yellow',
                        OrderStatusTypeEnum::PROCESSING => 'violet',
                        OrderStatusTypeEnum::SHIPPED => 'success',
                        OrderStatusTypeEnum::DELIVERED => 'success',
                        OrderStatusTypeEnum::CANCELLED => 'danger',
                    })
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('quantity')
                    ->label(__('filament/resources/order.fields.quantity.label'))
                    ->badge()
                    ->color('yellow')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('total_price')
                    ->label(__('filament/resources/order.fields.total_price.label'))
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('payer.name')
                    ->label(__('filament/resources/order.fields.payer_id.label'))
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('payment_status')
                    ->label(__('filament/resources/order.fields.payment_status.label'))
                    ->badge()
                    ->formatStateUsing(fn($record) => $record->payment_status->label())
                    ->color(fn($record): string => match ($record->payment_status) {
                        PaymentStatusTypeEnum::PENDING => 'yellow',
                        PaymentStatusTypeEnum::COMPLETED => 'success',
                        PaymentStatusTypeEnum::FAILED => 'danger',
                    })
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('receiver.name')
                    ->label(__('filament/resources/order.fields.receiver_id.label'))
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label(__('filament/resources/order.columns.created_at.label'))
                    ->dateTime('d.m.Y H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('order_status')
                    ->label(__('filament/resources/order.fields.order_status.label'))
                    ->options(fn() => OrderStatusTypeEnum::options())
                    ->default(null),
            ])
            ->recordAction('view')
            ->actions([
                Tables\Actions\ViewAction::make()->icon('')->label(''),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                InfolistSection::make(__('filament/resources/order.section_name.order_details'))
                    ->schema([
                        TextEntry::make('id')
                            ->label(__('filament/resources/order.columns.id.label')),
                        TextEntry::make('order_status')
                            ->label(__('filament/resources/order.fields.order_status.label'))
                            ->badge()
                            ->formatStateUsing(fn($record) => $record->order_status->label())
                            ->color(fn($record): string => match ($record->order_status) {
                                OrderStatusTypeEnum::PENDING => 'yellow',
                                OrderStatusTypeEnum::PROCESSING => 'success',
                                OrderStatusTypeEnum::SHIPPED => 'zinc',
                                OrderStatusTypeEnum::DELIVERED => 'sky',
                                OrderStatusTypeEnum::CANCELLED => 'danger',
                            }),
                        TextEntry::make('product.name')
                            ->label(__('filament/resources/order.fields.product_id.label')),
                        TextEntry::make('quantity')
                            ->label(__('filament/resources/order.fields.quantity.label'))
                            ->badge()
                            ->color('yellow'),
                        TextEntry::make('product.price')
                            ->label(__('filament/resources/order.infolist.fields.product_price.label'))
                            ->badge()
                            ->color('violet'),
                        TextEntry::make('total_price')
                            ->label(__('filament/resources/order.fields.total_price.label'))
                            ->badge()
                            ->color('success'),
                        TextEntry::make('created_at')
                            ->label(__('filament/resources/order.columns.created_at.label'))
                            ->dateTime('d.m.Y H:i:s'),
                    ])->columns(3),
                InfolistSection::make(__('filament/resources/order.section_name.order_details'))
                    ->schema([
                        TextEntry::make('payer.name')
                            ->label(__('filament/resources/order.fields.payer_id.label'))
                            ->url(fn($record) => $record->payer->id ? route('filament.admin.resources.clients.view', $record->payer->id) : null)
                            ->color('info')
                            ->openUrlInNewTab(),
                        TextEntry::make('payment_status')
                            ->label(__('filament/resources/order.fields.payment_status.label'))
                            ->badge()
                            ->formatStateUsing(fn($record) => $record->payment_status->label())
                            ->color(fn($record): string => match ($record->payment_status) {
                                PaymentStatusTypeEnum::PENDING => 'yellow',
                                PaymentStatusTypeEnum::COMPLETED => 'success',
                                PaymentStatusTypeEnum::FAILED => 'danger',
                            }),
                        TextEntry::make('receiver.name')
                            ->label(__('filament/resources/order.fields.receiver_id.label')),
                    ])->columns(),
                InfolistSection::make(__('filament/resources/order.infolist.section_name.component_list'))
                    ->schema([
                        RepeatableEntry::make('product.productComponentsRelation')
                            ->label('')
                            ->schema([
                                TextEntry::make('components.name')
                                    ->label(__('filament/resources/order.infolist.fields.component_name.label')),
                                TextEntry::make('components.description')
                                    ->label(__('filament/resources/order.infolist.fields.component_description.label')),
                                TextEntry::make('quantity')
                                    ->label(__('filament/resources/order.infolist.fields.quantity.label'))
                                    ->badge()
                                    ->color('primary')
                                    ->columns(3),
                            ])
                            ->columns(3),
                    ])
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
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

    public static function canViewAny(): bool
    {
        return auth()->user()->can('view_any_order');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('create_order');
    }

    public static function canEdit($record): bool
    {
        return auth()->user()->can('update_order');
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->can('delete_order');
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()->can('delete_any_order');
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
