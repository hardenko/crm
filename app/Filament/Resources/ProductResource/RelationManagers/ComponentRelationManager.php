<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use App\Models\Component;
use Filament\Tables\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;

class ComponentRelationManager extends RelationManager
{
    protected static string $relationship = 'components';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Select::make('component_id')
                    ->label('Component')
                    ->options(Component::query()->pluck('name', 'id')->filter()->toArray())
                    ->searchable()
                    ->required(),

                TextInput::make('quantity')
                ->numeric()
                    ->required()
                    ->default(1),
            ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Component'),
                TextColumn::make('quantity')->label('Quantity'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
