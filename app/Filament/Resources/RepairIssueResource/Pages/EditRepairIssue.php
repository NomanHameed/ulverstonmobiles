<?php

namespace App\Filament\Resources\RepairIssueResource\Pages;

use App\Filament\Resources\RepairIssueResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRepairIssue extends EditRecord
{
    protected static string $resource = RepairIssueResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
