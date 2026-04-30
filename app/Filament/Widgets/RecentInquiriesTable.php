<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\InquiryResource;
use App\Models\Inquiry;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentInquiriesTable extends BaseWidget
{
    protected static ?int $sort = 2;
    protected static ?string $heading = 'Recent inquiries';
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Inquiry::query()
                    ->latest()
                    ->limit(10),
            )
            ->columns([
                Tables\Columns\TextColumn::make('tracking_id')
                    ->label('ID')->weight('semibold')->copyable(),
                Tables\Columns\TextColumn::make('type')->badge()
                    ->color(fn (string $state) => $state === 'repair' ? 'warning' : 'info'),
                Tables\Columns\TextColumn::make('status')->badge()
                    ->color(fn (string $state) => match ($state) {
                        'new'         => 'warning',
                        'contacted'   => 'info',
                        'booked'      => 'primary',
                        'in_progress' => 'primary',
                        'completed'   => 'success',
                        'cancelled'   => 'danger',
                        default       => 'gray',
                    }),
                Tables\Columns\TextColumn::make('customer_name'),
                Tables\Columns\TextColumn::make('product.name')->placeholder('—'),
                Tables\Columns\TextColumn::make('repairModel.name')->label('Device')->placeholder('—'),
                Tables\Columns\TextColumn::make('created_at')->since(),
            ])
            ->actions([
                Tables\Actions\Action::make('open')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->url(fn (Inquiry $record) => InquiryResource::getUrl('view', ['record' => $record])),
            ]);
    }
}
