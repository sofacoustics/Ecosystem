<?php

namespace App\Filament\Resources\DatafiletypeResource\Pages;

use App\Filament\Resources\DatafiletypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDatafiletypes extends ListRecords
{
    protected static string $resource = DatafiletypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
