<?php

namespace App\Filament\Resources\Presets\Pages;

use App\Filament\Resources\Presets\PresetResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePreset extends CreateRecord
{
    protected static string $resource = PresetResource::class;
}
