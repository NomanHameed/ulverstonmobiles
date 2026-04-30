<?php

namespace App\Filament\Resources\RepairIssueResource\Pages;

use App\Filament\Resources\RepairIssueResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRepairIssues extends ListRecords
{
    protected static string $resource = RepairIssueResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
