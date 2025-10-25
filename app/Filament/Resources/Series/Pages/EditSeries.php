<?php

namespace App\Filament\Resources\Series\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\Series\SeriesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSeries extends EditRecord
{
    protected static string $resource = SeriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
