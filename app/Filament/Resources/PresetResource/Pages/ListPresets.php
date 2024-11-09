<?php

namespace App\Filament\Resources\PresetResource\Pages;

use App\Filament\Resources\PresetResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPresets extends ListRecords
{
    protected static string $resource = PresetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
