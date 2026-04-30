<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DiscountProgramResource\Pages;
use App\Models\DiscountProgram;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class DiscountProgramResource extends Resource
{
    protected static ?string $model = DiscountProgram::class;
    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationGroup = 'Site';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationLabel = 'Discount programs';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->live(onBlur: true)
                ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', Str::slug($state ?? ''))),
            Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
            Forms\Components\TextInput::make('eligibility')->placeholder('e.g. NHS workers, students'),
            Forms\Components\TextInput::make('icon')->placeholder('e.g. heart, shield'),
            Forms\Components\Textarea::make('description')->rows(3)->columnSpanFull(),
            Forms\Components\FileUpload::make('image_path')->image()->directory('discounts')->columnSpanFull(),
            Forms\Components\TextInput::make('cta_label')->placeholder('e.g. Verify eligibility'),
            Forms\Components\TextInput::make('cta_url'),
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
                Tables\Columns\TextColumn::make('name')->weight('semibold')->searchable(),
                Tables\Columns\TextColumn::make('eligibility')->placeholder('—'),
                Tables\Columns\TextColumn::make('icon')->badge()->placeholder('—'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
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
            'index'  => Pages\ListDiscountPrograms::route('/'),
            'create' => Pages\CreateDiscountProgram::route('/create'),
            'edit'   => Pages\EditDiscountProgram::route('/{record}/edit'),
        ];
    }
}
