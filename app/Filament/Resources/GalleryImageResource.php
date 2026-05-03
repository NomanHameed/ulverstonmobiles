<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GalleryImageResource\Pages;
use App\Models\GalleryImage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GalleryImageResource extends Resource
{
    protected static ?string $model = GalleryImage::class;
    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationGroup = 'Site';
    protected static ?int $navigationSort = 5;
    protected static ?string $navigationLabel = 'Gallery';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('group')
                ->options(['store' => 'Store', 'repair' => 'Repair', 'workshop' => 'Workshop', 'team' => 'Team'])
                ->default('store')
                ->required(),
            Forms\Components\FileUpload::make('image_path')->image()->directory('gallery')->required()->columnSpanFull(),
            Forms\Components\TextInput::make('caption'),
            Forms\Components\TextInput::make('alt')->placeholder('Alt text for accessibility'),
            Forms\Components\Toggle::make('is_active')->default(true),
            Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')->square()->size(56),
                Tables\Columns\TextColumn::make('group')->badge(),
                Tables\Columns\TextColumn::make('caption')->placeholder('—')->limit(40),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('sort_order')->label('Order'),
            ])
            ->groups([
                Tables\Grouping\Group::make('group')->collapsible(),
            ])
            ->defaultGroup('group')
            ->filters([
                Tables\Filters\SelectFilter::make('group')->options([
                    'store' => 'Store', 'repair' => 'Repair', 'workshop' => 'Workshop', 'team' => 'Team',
                ]),
                Tables\Filters\TernaryFilter::make('is_active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListGalleryImages::route('/'),
            'create' => Pages\CreateGalleryImage::route('/create'),
            'edit'   => Pages\EditGalleryImage::route('/{record}/edit'),
        ];
    }
}
