<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HeroSlideResource\Pages;
use App\Models\HeroSlide;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HeroSlideResource extends Resource
{
    protected static ?string $model = HeroSlide::class;
    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationGroup = 'Site';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Hero slides';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Copy')->schema([
                Forms\Components\TextInput::make('eyebrow')->placeholder('e.g. New · iPhone 16 Pro')->maxLength(120),
                Forms\Components\TextInput::make('headline')->required()->maxLength(180),
                Forms\Components\Textarea::make('subheadline')->rows(2)->maxLength(500),
            ])->columns(1),

            Forms\Components\Section::make('Media')->schema([
                Forms\Components\FileUpload::make('image_path')->image()->directory('hero')->columnSpanFull(),
                Forms\Components\FileUpload::make('mobile_image_path')->image()->directory('hero')->columnSpanFull(),
                Forms\Components\TextInput::make('video_path')->placeholder('Optional video URL or path'),
            ]),

            Forms\Components\Section::make('Calls to action')->schema([
                Forms\Components\TextInput::make('primary_cta_label'),
                Forms\Components\TextInput::make('primary_cta_url'),
                Forms\Components\TextInput::make('secondary_cta_label'),
                Forms\Components\TextInput::make('secondary_cta_url'),
            ])->columns(2),

            Forms\Components\Section::make('Presentation')->schema([
                Forms\Components\Select::make('theme')->options(['light' => 'Light', 'dark' => 'Dark'])->default('light'),
                Forms\Components\Select::make('text_align')->options(['left' => 'Left', 'center' => 'Center', 'right' => 'Right'])->default('center'),
                Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
                Forms\Components\Toggle::make('is_active')->default(true),
                Forms\Components\DateTimePicker::make('starts_at'),
                Forms\Components\DateTimePicker::make('ends_at'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')->square()->size(56),
                Tables\Columns\TextColumn::make('headline')->searchable()->weight('semibold')->limit(50)
                    ->description(fn (HeroSlide $r) => $r->eyebrow),
                Tables\Columns\TextColumn::make('theme')->badge(),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('starts_at')->date()->placeholder('—'),
                Tables\Columns\TextColumn::make('ends_at')->date()->placeholder('—'),
                Tables\Columns\TextColumn::make('sort_order')->label('Order'),
            ])
            ->filters([
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
            'index'  => Pages\ListHeroSlides::route('/'),
            'create' => Pages\CreateHeroSlide::route('/create'),
            'edit'   => Pages\EditHeroSlide::route('/{record}/edit'),
        ];
    }
}
