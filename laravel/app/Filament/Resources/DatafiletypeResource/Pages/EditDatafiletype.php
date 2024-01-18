<?php

namespace App\Filament\Resources\DatafiletypeResource\Pages;

use App\Filament\Resources\DatafiletypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDatafiletype extends EditRecord
{
    protected static string $resource = DatafiletypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
