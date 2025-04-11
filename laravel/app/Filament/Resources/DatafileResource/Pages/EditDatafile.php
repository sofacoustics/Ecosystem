<?php

namespace App\Filament\Resources\DatafileResource\Pages;

use App\Filament\Resources\DatafileResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDatafile extends EditRecord
{
    protected static string $resource = DatafileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
