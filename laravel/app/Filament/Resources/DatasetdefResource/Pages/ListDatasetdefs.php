<?php

namespace App\Filament\Resources\DatasetdefResource\Pages;

use App\Filament\Resources\DatasetdefResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDatasetdefs extends ListRecords
{
    protected static string $resource = DatasetdefResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
