<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Models\Page as PageModel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PageResource extends Resource
{
    protected static ?string $model = PageModel::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Site';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationLabel = 'Pages';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Group::make()->schema([
                Forms\Components\Section::make('Content')->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($state, ?PageModel $record, Forms\Set $set) {
                            if (! $record) $set('slug', Str::slug($state ?? ''));
                        }),
                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->helperText('Used in the URL: /pages/{slug}, or special slugs "about" / "contact" / "discounts" map to dedicated routes.'),
                    Forms\Components\TextInput::make('eyebrow')->placeholder('e.g. Our story')->maxLength(120),
                    Forms\Components\Textarea::make('intro')->rows(2)->maxLength(500)
                        ->helperText('Short lead paragraph shown under the hero.'),
                    Forms\Components\RichEditor::make('body')->columnSpanFull(),
                    Forms\Components\FileUpload::make('hero_image_path')->image()->directory('pages')->columnSpanFull(),
                ]),

                Forms\Components\Section::make('SEO')->schema([
                    Forms\Components\TextInput::make('meta_title'),
                    Forms\Components\Textarea::make('meta_description')->rows(2),
                ])->collapsed(),
            ])->columnSpan(['lg' => 2]),

            Forms\Components\Group::make()->schema([
                Forms\Components\Section::make('Visibility')->schema([
                    Forms\Components\Toggle::make('is_published')->default(true),
                    Forms\Components\Toggle::make('show_in_nav')->helperText('Show as a top-nav link.'),
                    Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
                ]),
            ])->columnSpan(['lg' => 1]),
        ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                Tables\Columns\TextColumn::make('title')->weight('semibold')->searchable(),
                Tables\Columns\TextColumn::make('slug')->color('gray')->copyable(),
                Tables\Columns\IconColumn::make('is_published')->boolean()->label('Live'),
                Tables\Columns\IconColumn::make('show_in_nav')->boolean()->label('In nav'),
                Tables\Columns\TextColumn::make('updated_at')->since(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_published'),
                Tables\Filters\TernaryFilter::make('show_in_nav'),
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
            'index'  => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit'   => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
