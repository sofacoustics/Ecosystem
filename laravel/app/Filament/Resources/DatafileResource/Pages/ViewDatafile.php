<?php

namespace App\Filament\Resources\DatafileResource\Pages;

use App\Filament\Resources\DatafileResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDatafile extends ViewRecord
{
    protected static string $resource = DatafileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
