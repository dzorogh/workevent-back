<?php

namespace App\Filament\Resources\Cities\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\Cities\CityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCity extends EditRecord
{
    protected static string $resource = CityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
