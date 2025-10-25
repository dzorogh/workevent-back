<?php

namespace App\Filament\Resources\Venues\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Venues\VenueResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVenues extends ListRecords
{
    protected static string $resource = VenueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
