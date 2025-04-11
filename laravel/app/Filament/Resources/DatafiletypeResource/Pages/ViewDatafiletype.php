<?php

namespace App\Filament\Resources\DatafiletypeResource\Pages;

use App\Filament\Resources\DatafiletypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDatafiletype extends ViewRecord
{
    protected static string $resource = DatafiletypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
