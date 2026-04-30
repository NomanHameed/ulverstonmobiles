<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class VariantsRelationManager extends RelationManager
{
    protected static string $relationship = 'variants';
    protected static ?string $title = 'Variants';
    protected static ?string $icon = 'heroicon-o-swatch';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('color')->placeholder('e.g. Natural Titanium'),
            Forms\Components\ColorPicker::make('color_hex'),
            Forms\Components\TextInput::make('storage')->placeholder('e.g. 256GB'),
            Forms\Components\TextInput::make('sku')->unique(ignoreRecord: true),
            Forms\Components\TextInput::make('price_modifier')->numeric()->prefix('$')->default(0)
                ->helperText('Added to product base/sale price'),
            Forms\Components\TextInput::make('price_override')->numeric()->prefix('$')
                ->helperText('Overrides base/sale entirely if set'),
            Forms\Components\TextInput::make('stock')->numeric()->default(0)->required(),
            Forms\Components\Toggle::make('is_default'),
            Forms\Components\Toggle::make('is_active')->default(true),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('color')
            ->columns([
                Tables\Columns\ColorColumn::make('color_hex')->label(''),
                Tables\Columns\TextColumn::make('color')->placeholder('—'),
                Tables\Columns\TextColumn::make('storage')->placeholder('—'),
                Tables\Columns\TextColumn::make('sku')->placeholder('—')->toggleable(),
                Tables\Columns\TextColumn::make('effective_price')->money('USD')->label('Price'),
                Tables\Columns\TextColumn::make('stock')->badge()
                    ->color(fn (int $state) => $state > 0 ? 'success' : 'danger'),
                Tables\Columns\IconColumn::make('is_default')->boolean()->label('Default'),
                Tables\Columns\IconColumn::make('is_active')->boolean()->label('Active'),
            ])
            ->headerActions([Tables\Actions\CreateAction::make()])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
