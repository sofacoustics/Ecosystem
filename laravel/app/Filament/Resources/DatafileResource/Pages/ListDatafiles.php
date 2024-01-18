<?php

namespace App\Filament\Resources\DatafileResource\Pages;

use App\Filament\Resources\DatafileResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDatafiles extends ListRecords
{
    protected static string $resource = DatafileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
