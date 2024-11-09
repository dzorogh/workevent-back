<?php

namespace App\Filament\Resources\FilterPresetResource\Pages;

use App\Filament\Resources\FilterPresetResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFilterPresets extends ListRecords
{
    protected static string $resource = FilterPresetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
