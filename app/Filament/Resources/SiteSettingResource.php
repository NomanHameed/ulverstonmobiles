<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteSettingResource\Pages;
use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SiteSettingResource extends Resource
{
    protected static ?string $model = SiteSetting::class;
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'Site';
    protected static ?int $navigationSort = 99;
    protected static ?string $navigationLabel = 'Settings';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('key')
                ->required()
                ->unique(ignoreRecord: true)
                ->placeholder('e.g. company.phone'),
            Forms\Components\TextInput::make('group')->required()->default('general'),
            Forms\Components\Select::make('type')
                ->options([
                    'string' => 'String',
                    'int'    => 'Integer',
                    'float'  => 'Float',
                    'bool'   => 'Boolean',
                    'json'   => 'JSON',
                ])
                ->default('string')
                ->required(),
            Forms\Components\TextInput::make('label'),
            Forms\Components\Textarea::make('value')->rows(3)->columnSpanFull(),
            Forms\Components\Textarea::make('description')->rows(2)->columnSpanFull(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('group')
            ->columns([
                Tables\Columns\TextColumn::make('key')->searchable()->copyable()->weight('semibold'),
                Tables\Columns\TextColumn::make('group')->badge(),
                Tables\Columns\TextColumn::make('type')->badge()->color('gray'),
                Tables\Columns\TextColumn::make('value')->limit(60)->placeholder('—'),
                Tables\Columns\TextColumn::make('label')->placeholder('—')->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('group')->options(
                    fn () => SiteSetting::query()->distinct()->pluck('group', 'group')->all(),
                ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->groups([
                Tables\Grouping\Group::make('group'),
            ])
            ->defaultGroup('group');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSiteSettings::route('/'),
            'create' => Pages\CreateSiteSetting::route('/create'),
            'edit'   => Pages\EditSiteSetting::route('/{record}/edit'),
        ];
    }
}
