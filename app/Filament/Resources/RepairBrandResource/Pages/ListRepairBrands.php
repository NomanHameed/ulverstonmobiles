<?php

namespace App\Filament\Resources\RepairBrandResource\Pages;

use App\Filament\Resources\RepairBrandResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRepairBrands extends ListRecords
{
    protected static string $resource = RepairBrandResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
