<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InquiryResource\Pages;
use App\Models\Inquiry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class InquiryResource extends Resource
{
    protected static ?string $model = Inquiry::class;
    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';
    protected static ?string $navigationGroup = 'Inquiries';
    protected static ?int $navigationSort = 1;
    protected static ?string $recordTitleAttribute = 'tracking_id';

    public static function getNavigationBadge(): ?string
    {
        return (string) Inquiry::where('status', 'new')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Pipeline')->schema([
                Forms\Components\TextInput::make('tracking_id')
                    ->disabled()->dehydrated(false)
                    ->placeholder('auto-generated on create'),
                Forms\Components\Select::make('type')
                    ->options(['product' => 'Product inquiry', 'repair' => 'Repair booking'])
                    ->required()
                    ->live(),
                Forms\Components\Select::make('status')
                    ->options(array_combine(Inquiry::STATUSES, array_map('ucfirst', Inquiry::STATUSES)))
                    ->required()->default('new')
                    ->live(),
                Forms\Components\Select::make('assigned_to')
                    ->relationship('assignee', 'name')
                    ->searchable()->preload()->placeholder('Unassigned'),
            ])->columns(2),

            Forms\Components\Section::make('Customer')->schema([
                Forms\Components\TextInput::make('customer_name')->required(),
                Forms\Components\TextInput::make('customer_phone')->required()->tel(),
                Forms\Components\TextInput::make('customer_email')->email(),
                Forms\Components\TextInput::make('customer_whatsapp')->tel(),
                Forms\Components\Select::make('preferred_contact')
                    ->options(['phone' => 'Phone', 'whatsapp' => 'WhatsApp', 'email' => 'Email'])
                    ->default('whatsapp')->required(),
                Forms\Components\Select::make('source')
                    ->options(['website' => 'Website', 'whatsapp' => 'WhatsApp', 'phone' => 'Phone', 'manual' => 'Manual'])
                    ->default('website')->required(),
                Forms\Components\Textarea::make('message')->rows(3)->columnSpanFull(),
            ])->columns(2),

            Forms\Components\Section::make('Product details')
                ->visible(fn (Forms\Get $get) => $get('type') === 'product')
                ->schema([
                    Forms\Components\Select::make('product_id')
                        ->relationship('product', 'name')
                        ->searchable()->preload(),
                    Forms\Components\Select::make('product_variant_id')
                        ->relationship('variant', 'id')
                        ->getOptionLabelFromRecordUsing(fn ($record) => $record->label ?: ('#'.$record->id))
                        ->searchable()->preload(),
                ])->columns(2),

            Forms\Components\Section::make('Repair details')
                ->visible(fn (Forms\Get $get) => $get('type') === 'repair')
                ->schema([
                    Forms\Components\Select::make('repair_brand_id')
                        ->relationship('repairBrand', 'name')
                        ->live()
                        ->searchable()->preload(),
                    Forms\Components\Select::make('repair_model_id')
                        ->relationship(
                            'repairModel',
                            'name',
                            fn (Builder $q, Forms\Get $get) => $q->when(
                                $get('repair_brand_id'),
                                fn ($q, $brand) => $q->where('repair_brand_id', $brand),
                            ),
                        )
                        ->searchable()->preload(),
                    Forms\Components\Select::make('repair_issue_id')
                        ->relationship('repairIssue', 'name')
                        ->searchable()->preload(),
                ])->columns(3),

            Forms\Components\Section::make('Timeline')->schema([
                Forms\Components\DateTimePicker::make('contacted_at'),
                Forms\Components\DateTimePicker::make('booked_at'),
                Forms\Components\DateTimePicker::make('completed_at'),
            ])->columns(3)->collapsed(),
        ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Pipeline')->schema([
                Infolists\Components\TextEntry::make('tracking_id')->copyable()->weight('bold'),
                Infolists\Components\TextEntry::make('type')->badge(),
                Infolists\Components\TextEntry::make('status')->badge()
                    ->color(fn (string $state) => match ($state) {
                        'new'         => 'warning',
                        'contacted'   => 'info',
                        'booked'      => 'primary',
                        'in_progress' => 'primary',
                        'completed'   => 'success',
                        'cancelled'   => 'danger',
                        default       => 'gray',
                    }),
                Infolists\Components\TextEntry::make('assignee.name')->placeholder('Unassigned')->label('Assigned to'),
                Infolists\Components\TextEntry::make('created_at')->since(),
            ])->columns(5),

            Infolists\Components\Section::make('Customer')->schema([
                Infolists\Components\TextEntry::make('customer_name'),
                Infolists\Components\TextEntry::make('customer_phone')->copyable(),
                Infolists\Components\TextEntry::make('customer_email')->copyable()->placeholder('—'),
                Infolists\Components\TextEntry::make('customer_whatsapp')->copyable()->placeholder('—'),
                Infolists\Components\TextEntry::make('preferred_contact')->badge(),
                Infolists\Components\TextEntry::make('message')->columnSpanFull()->placeholder('No message provided'),
            ])->columns(2),

            Infolists\Components\Section::make('Product')->visible(fn ($record) => $record->type === 'product')
                ->schema([
                    Infolists\Components\TextEntry::make('product.name'),
                    Infolists\Components\TextEntry::make('variant.label')->label('Variant')->placeholder('—'),
                ])->columns(2),

            Infolists\Components\Section::make('Repair')->visible(fn ($record) => $record->type === 'repair')
                ->schema([
                    Infolists\Components\TextEntry::make('repairBrand.name')->label('Brand'),
                    Infolists\Components\TextEntry::make('repairModel.name')->label('Model'),
                    Infolists\Components\TextEntry::make('repairIssue.name')->label('Issue'),
                ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('tracking_id')
                    ->label('ID')->copyable()->searchable()->weight('semibold'),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state) => $state === 'repair' ? 'warning' : 'info'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'new'         => 'warning',
                        'contacted'   => 'info',
                        'booked'      => 'primary',
                        'in_progress' => 'primary',
                        'completed'   => 'success',
                        'cancelled'   => 'danger',
                        default       => 'gray',
                    }),
                Tables\Columns\TextColumn::make('customer_name')->searchable(),
                Tables\Columns\TextColumn::make('customer_phone')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('product.name')->placeholder('—')->toggleable(),
                Tables\Columns\TextColumn::make('repairModel.name')->label('Device')->placeholder('—')->toggleable(),
                Tables\Columns\TextColumn::make('repairIssue.name')->label('Issue')->placeholder('—')->toggleable(),
                Tables\Columns\TextColumn::make('assignee.name')->placeholder('Unassigned')->toggleable(),
                Tables\Columns\TextColumn::make('created_at')->since()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(array_combine(Inquiry::STATUSES, array_map('ucfirst', Inquiry::STATUSES)))
                    ->multiple(),
                SelectFilter::make('type')
                    ->options(['product' => 'Product', 'repair' => 'Repair']),
                SelectFilter::make('assigned_to')
                    ->relationship('assignee', 'name')->label('Assignee'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('advance')
                    ->label('Advance')
                    ->icon('heroicon-o-arrow-right')
                    ->color('primary')
                    ->visible(fn (Inquiry $r) => ! in_array($r->status, ['completed', 'cancelled']))
                    ->requiresConfirmation()
                    ->action(function (Inquiry $record) {
                        $next = match ($record->status) {
                            'new'         => 'contacted',
                            'contacted'   => 'booked',
                            'booked'      => 'in_progress',
                            'in_progress' => 'completed',
                            default       => $record->status,
                        };

                        $stamps = match ($next) {
                            'contacted'   => ['contacted_at' => now()],
                            'booked'      => ['booked_at' => now()],
                            'completed'   => ['completed_at' => now()],
                            default       => [],
                        };

                        $record->update(['status' => $next] + $stamps);
                    }),
                Tables\Actions\Action::make('cancel')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Inquiry $r) => ! in_array($r->status, ['completed', 'cancelled']))
                    ->requiresConfirmation()
                    ->action(fn (Inquiry $record) => $record->update(['status' => 'cancelled'])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->groups([
                Tables\Grouping\Group::make('status')->collapsible(),
                Tables\Grouping\Group::make('type')->collapsible(),
            ])
            ->defaultGroup('status');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListInquiries::route('/'),
            'create' => Pages\CreateInquiry::route('/create'),
            'view'   => Pages\ViewInquiry::route('/{record}'),
            'edit'   => Pages\EditInquiry::route('/{record}/edit'),
        ];
    }
}
