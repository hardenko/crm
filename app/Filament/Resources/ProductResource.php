<?php

namespace App\Filament\Resources;

use App\Enums\PaymentStatusType;
use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->maxLength(255)
                    ->required(),
                TextInput::make('type')
                    ->maxLength(255)
                    ->required(),
                TextInput::make('price')
                    ->numeric()
                    ->required(),
                Select::make('payment_status')
                    ->options(PaymentStatusType::options())
                    ->default(PaymentStatusType::Pending)
                    ->required(),
                TextInput::make('payer')
                    ->maxLength(255)
                    ->required(),
                TextInput::make('receiver')
                    ->maxLength(255)
                    ->required(),
                Section::make('Components')
                    ->schema([
                        Repeater::make('components')
                            ->label('Component')
                            ->relationship('components')
                            ->schema([
                                Select::make('component_id')
                                    ->label('Component Name')
                                    ->relationship('components', 'name')
                                    ->searchable()
                                    ->required(),
                                TextInput::make('quantity')
                                    ->integer()
                                    ->required(),
                            ])
                            ->hiddenLabel()
                            ->columnSpanFull(),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('type')->sortable(),
                TextColumn::make('price')->sortable(),
                TextColumn::make('payment_status')->sortable(),
                TextColumn::make('payer')->sortable(),
                TextColumn::make('receiver')->sortable(),
            ])
            ->filters([

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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
