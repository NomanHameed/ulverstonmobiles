<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'images';
    protected static ?string $title = 'Images';
    protected static ?string $icon = 'heroicon-o-photo';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\FileUpload::make('path')
                ->image()
                ->directory('products')
                ->required()
                ->columnSpanFull(),
            Forms\Components\TextInput::make('alt')->placeholder('Alt text for accessibility'),
            Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
            Forms\Components\Toggle::make('is_primary')->helperText('Show as the product hero image'),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('alt')
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                Tables\Columns\ImageColumn::make('path')->size(64)->square(),
                Tables\Columns\TextColumn::make('alt')->placeholder('—'),
                Tables\Columns\IconColumn::make('is_primary')->boolean()->label('Primary'),
                Tables\Columns\TextColumn::make('sort_order')->label('Order'),
            ])
            ->headerActions([Tables\Actions\CreateAction::make()])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
