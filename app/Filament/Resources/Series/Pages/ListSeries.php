<?php

namespace App\Filament\Resources\Series\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Series\SeriesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSeries extends ListRecords
{
    protected static string $resource = SeriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
