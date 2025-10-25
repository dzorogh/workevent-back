<?php

namespace App\Filament\Resources\Presets\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\Presets\PresetResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPreset extends EditRecord
{
    protected static string $resource = PresetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
