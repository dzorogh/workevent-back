<?php

namespace App\Filament\Resources\EventSeriesResource\Pages;

use App\Filament\Resources\EventSeriesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEventSeries extends EditRecord
{
    protected static string $resource = EventSeriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
