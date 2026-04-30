<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RepairBrandResource\Pages;
use App\Filament\Resources\RepairBrandResource\RelationManagers;
use App\Models\RepairBrand;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class RepairBrandResource extends Resource
{
    protected static ?string $model = RepairBrand::class;
    protected static ?string $navigationIcon = 'heroicon-o-device-phone-mobile';
    protected static ?string $navigationGroup = 'Repair';
    protected static ?string $navigationLabel = 'Brands & Models';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->live(onBlur: true)
                ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', Str::slug($state ?? ''))),
            Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
            Forms\Components\FileUpload::make('logo_path')->image()->directory('repair-brands')->columnSpanFull(),
            Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
            Forms\Components\Toggle::make('is_active')->default(true),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                Tables\Columns\ImageColumn::make('logo_path')->circular(),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable()->weight('semibold'),
                Tables\Columns\TextColumn::make('models_count')->counts('models')->label('Models'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('sort_order')->label('Order'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->reorderable('sort_order');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ModelsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListRepairBrands::route('/'),
            'create' => Pages\CreateRepairBrand::route('/create'),
            'edit'   => Pages\EditRepairBrand::route('/{record}/edit'),
        ];
    }
}
