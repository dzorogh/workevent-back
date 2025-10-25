<?php

namespace App\Filament\Resources\Venues\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\Venues\VenueResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVenue extends EditRecord
{
    protected static string $resource = VenueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
