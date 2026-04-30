<?php

namespace App\Filament\Resources\InquiryResource\Pages;

use App\Filament\Resources\InquiryResource;
use App\Models\Inquiry;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListInquiries extends ListRecords
{
    protected static string $resource = InquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }

    public function getTabs(): array
    {
        $count = fn (?string $status = null) => Inquiry::query()
            ->when($status, fn ($q) => $q->where('status', $status))
            ->count();

        return [
            'all'         => Tab::make('All')->badge($count()),
            'new'         => Tab::make('New')->badge($count('new'))->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $q) => $q->where('status', 'new')),
            'contacted'   => Tab::make('Contacted')->badge($count('contacted'))->badgeColor('info')
                ->modifyQueryUsing(fn (Builder $q) => $q->where('status', 'contacted')),
            'booked'      => Tab::make('Booked')->badge($count('booked'))->badgeColor('primary')
                ->modifyQueryUsing(fn (Builder $q) => $q->where('status', 'booked')),
            'in_progress' => Tab::make('In progress')->badge($count('in_progress'))->badgeColor('primary')
                ->modifyQueryUsing(fn (Builder $q) => $q->where('status', 'in_progress')),
            'completed'   => Tab::make('Completed')->badge($count('completed'))->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $q) => $q->where('status', 'completed')),
            'cancelled'   => Tab::make('Cancelled')->badgeColor('danger')
                ->modifyQueryUsing(fn (Builder $q) => $q->where('status', 'cancelled')),
        ];
    }
}
