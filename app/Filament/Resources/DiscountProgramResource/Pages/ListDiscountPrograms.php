<?php

namespace App\Filament\Resources\DiscountProgramResource\Pages;

use App\Filament\Resources\DiscountProgramResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDiscountPrograms extends ListRecords
{
    protected static string $resource = DiscountProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
