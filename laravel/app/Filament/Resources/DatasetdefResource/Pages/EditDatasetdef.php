<?php

namespace App\Filament\Resources\DatasetdefResource\Pages;

use App\Filament\Resources\DatasetdefResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDatasetdef extends EditRecord
{
    protected static string $resource = DatasetdefResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
