<?php

namespace App\Filament\Resources\Presets\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Presets\PresetResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPresets extends ListRecords
{
    protected static string $resource = PresetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
