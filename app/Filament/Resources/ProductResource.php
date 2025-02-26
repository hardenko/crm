<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use App\Models\Component;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;


class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                TextInput::make('type')->required(),
                TextInput::make('price')->numeric()->required(),
                Select::make('payment_status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                    ])
                    ->required(),
                TextInput::make('payer')->required(),
                TextInput::make('receiver')->required(),

                Repeater::make('components')
                    ->relationship('components')
                    ->schema([
                        Select::make('component_id')
                            ->label('Component')
                            ->options(Component::all()->pluck('name', 'id'))
                            ->required(),
                        TextInput::make('quantity')
                            ->numeric()
                            ->required(),
                    ])
                    ->mutateRelationshipDataBeforeCreateUsing(function (array $data) {
                        return ['quantity' => $data['quantity']];
                    })
                    ->mutateRelationshipDataBeforeFillUsing(function (array $data) {
                        return ['quantity' => $data['quantity']];
                    }),
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
                //
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

    public static function getRelations(): array
    {
        return [
            //
        ];
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
