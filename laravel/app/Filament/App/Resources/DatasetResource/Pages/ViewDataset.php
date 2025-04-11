<?php

namespace App\Filament\App\Resources\DatasetResource\Pages;

use App\Filament\App\Resources\DatasetResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDataset extends ViewRecord
{
    protected static string $resource = DatasetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
