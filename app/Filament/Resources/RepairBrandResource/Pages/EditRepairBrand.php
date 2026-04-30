<?php

namespace App\Filament\Resources\RepairBrandResource\Pages;

use App\Filament\Resources\RepairBrandResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRepairBrand extends EditRecord
{
    protected static string $resource = RepairBrandResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
