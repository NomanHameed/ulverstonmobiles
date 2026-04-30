<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class FeaturesRelationManager extends RelationManager
{
    protected static string $relationship = 'features';
    protected static ?string $title = 'Feature blocks';
    protected static ?string $icon = 'heroicon-o-sparkles';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')->required(),
            Forms\Components\TextInput::make('icon')->placeholder('e.g. shield, cpu, aperture'),
            Forms\Components\Textarea::make('description')->rows(3)->columnSpanFull(),
            Forms\Components\FileUpload::make('image_path')->image()->directory('product-features')->columnSpanFull(),
            Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                Tables\Columns\TextColumn::make('title')->weight('semibold'),
                Tables\Columns\TextColumn::make('icon')->badge()->placeholder('—'),
                Tables\Columns\TextColumn::make('description')->limit(60)->placeholder('—'),
                Tables\Columns\TextColumn::make('sort_order')->label('Order'),
            ])
            ->headerActions([Tables\Actions\CreateAction::make()])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
