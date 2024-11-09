<?php

namespace App\Filament\Resources\PresetResource\Pages;

use App\Filament\Resources\PresetResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPreset extends EditRecord
{
    protected static string $resource = PresetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
