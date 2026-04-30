<?php

namespace App\Filament\Resources\RepairBrandResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ModelsRelationManager extends RelationManager
{
    protected static string $relationship = 'models';
    protected static ?string $title = 'Device models';
    protected static ?string $icon = 'heroicon-o-device-phone-mobile';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->live(onBlur: true)
                ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', Str::slug($state ?? ''))),
            Forms\Components\TextInput::make('slug')->required(),
            Forms\Components\TextInput::make('release_year')->numeric()->minValue(2000)->maxValue(2099),
            Forms\Components\FileUpload::make('image_path')->image()->directory('repair-models'),
            Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
            Forms\Components\Toggle::make('is_active')->default(true),
            Forms\Components\Section::make('Supported issues')
                ->schema([
                    Forms\Components\Select::make('issues')
                        ->relationship('issues', 'name')
                        ->multiple()
                        ->preload()
                        ->searchable()
                        ->helperText('Optionally override pricing per issue from the repair issues editor.'),
                ])
                ->columnSpanFull(),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')->square()->size(40),
                Tables\Columns\TextColumn::make('name')->weight('semibold')->searchable(),
                Tables\Columns\TextColumn::make('release_year')->placeholder('—'),
                Tables\Columns\TextColumn::make('issues_count')->counts('issues')->label('Issues'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('sort_order')->label('Order'),
            ])
            ->headerActions([Tables\Actions\CreateAction::make()])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
