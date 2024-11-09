<?php

namespace App\Filament\Resources\FilterPresetResource\Pages;

use App\Filament\Resources\FilterPresetResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFilterPreset extends EditRecord
{
    protected static string $resource = FilterPresetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
