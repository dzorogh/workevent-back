<?php

namespace App\Filament\Resources\Cities\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Cities\CityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCities extends ListRecords
{
    protected static string $resource = CityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
