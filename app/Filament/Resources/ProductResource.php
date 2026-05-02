<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationGroup = 'Catalog';
    protected static ?int $navigationSort = 3;
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Group::make()->schema([
                Forms\Components\Section::make('Identity')->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($state, ?Product $record, Forms\Set $set) {
                            if (! $record) {
                                $set('slug', Str::slug($state ?? ''));
                            }
                        }),
                    Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
                    Forms\Components\TextInput::make('sku')->unique(ignoreRecord: true),
                    Forms\Components\Textarea::make('short_description')->rows(2)->maxLength(500),
                    Forms\Components\RichEditor::make('description')->columnSpanFull(),
                ])->columns(2),

                Forms\Components\Section::make('Pricing')->schema([
                    Forms\Components\TextInput::make('base_price')
                        ->numeric()->prefix('$')->minValue(0)
                        ->helperText('Optional — leave empty to hide pricing publicly'),
                    Forms\Components\TextInput::make('sale_price')
                        ->numeric()->prefix('$')->minValue(0)
                        ->helperText('Leave empty for no sale'),
                    Forms\Components\Select::make('currency')
                        ->options(['USD' => 'USD', 'EUR' => 'EUR', 'GBP' => 'GBP', 'AED' => 'AED', 'PKR' => 'PKR'])
                        ->default('USD'),
                    Forms\Components\Select::make('stock_status')
                        ->options([
                            'in_stock'     => 'In stock',
                            'out_of_stock' => 'Out of stock',
                            'pre_order'    => 'Pre-order',
                        ])->default('in_stock')->required(),
                ])->columns(2),

                Forms\Components\Section::make('SEO')->schema([
                    Forms\Components\TextInput::make('meta_title')->maxLength(255),
                    Forms\Components\Textarea::make('meta_description')->rows(2)->maxLength(500),
                ])->collapsed(),
            ])->columnSpan(['lg' => 2]),

            Forms\Components\Group::make()->schema([
                Forms\Components\Section::make('Status')->schema([
                    Forms\Components\Toggle::make('is_published')
                        ->onColor('success')
                        ->live()
                        ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                            if ($state && ! $get('published_at')) {
                                $set('published_at', now());
                            }
                        }),
                    Forms\Components\DateTimePicker::make('published_at'),
                    Forms\Components\Toggle::make('is_featured'),
                ]),

                Forms\Components\Section::make('Taxonomy')->schema([
                    Forms\Components\Select::make('brand_id')
                        ->relationship('brand', 'name')
                        ->searchable()->preload(),
                    Forms\Components\Select::make('category_id')
                        ->relationship('category', 'name')
                        ->searchable()->preload(),
                ]),
            ])->columnSpan(['lg' => 1]),
        ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('updated_at', 'desc')
            ->columns([
                Tables\Columns\ImageColumn::make('primaryImage.path')->label('')
                    ->circular()->size(40),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()->sortable()->weight('semibold')
                    ->description(fn (Product $r) => $r->sku),
                Tables\Columns\TextColumn::make('brand.name')->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('category.name')->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('current_price')
                    ->label('Price')
                    ->money(fn (Product $r) => $r->currency)
                    ->description(fn (Product $r) => $r->is_on_sale
                        ? 'Was '.number_format((float) $r->base_price, 2)
                        : null),
                Tables\Columns\TextColumn::make('variants_count')->counts('variants')->label('Variants'),
                Tables\Columns\TextColumn::make('stock_status')
                    ->badge()
                    ->colors([
                        'success' => 'in_stock',
                        'danger'  => 'out_of_stock',
                        'warning' => 'pre_order',
                    ]),
                Tables\Columns\IconColumn::make('is_published')->boolean()->label('Live'),
                Tables\Columns\IconColumn::make('is_featured')->boolean()->label('Featured'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('brand_id')->relationship('brand', 'name')->label('Brand'),
                Tables\Filters\SelectFilter::make('category_id')->relationship('category', 'name')->label('Category'),
                Tables\Filters\SelectFilter::make('stock_status')->options([
                    'in_stock'     => 'In stock',
                    'out_of_stock' => 'Out of stock',
                    'pre_order'    => 'Pre-order',
                ]),
                Tables\Filters\TernaryFilter::make('is_published')->label('Published'),
                Tables\Filters\TernaryFilter::make('is_featured')->label('Featured'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\VariantsRelationManager::class,
            RelationManagers\ImagesRelationManager::class,
            RelationManagers\FeaturesRelationManager::class,
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([
            \Illuminate\Database\Eloquent\SoftDeletingScope::class,
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit'   => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
