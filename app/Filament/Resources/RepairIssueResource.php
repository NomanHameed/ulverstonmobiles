<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RepairIssueResource\Pages;
use App\Models\RepairIssue;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class RepairIssueResource extends Resource
{
    protected static ?string $model = RepairIssue::class;
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?string $navigationGroup = 'Repair';
    protected static ?string $navigationLabel = 'Issue catalogue';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', Str::slug($state ?? ''))),
                Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('icon')->placeholder('e.g. screen, battery'),
                Forms\Components\TextInput::make('base_price')->numeric()->prefix('$'),
                Forms\Components\TextInput::make('estimated_minutes')->numeric()->suffix('min'),
                Forms\Components\Textarea::make('description')->rows(3)->columnSpanFull(),
            ])->columns(2),

            Forms\Components\Section::make('Status')->schema([
                Forms\Components\Toggle::make('is_active')->default(true),
                Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->weight('semibold'),
                Tables\Columns\TextColumn::make('icon')->badge()->placeholder('—'),
                Tables\Columns\TextColumn::make('base_price')->money('USD'),
                Tables\Columns\TextColumn::make('estimated_minutes')->suffix(' min')->placeholder('—'),
                Tables\Columns\TextColumn::make('models_count')->counts('models')->label('Models'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
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

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListRepairIssues::route('/'),
            'create' => Pages\CreateRepairIssue::route('/create'),
            'edit'   => Pages\EditRepairIssue::route('/{record}/edit'),
        ];
    }
}
