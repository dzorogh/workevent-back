<?php

namespace App\Filament\Resources\Events\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Events\EventResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEvents extends ListRecords
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public ?string $tableSortColumn = 'start_date';

    public ?string $tableSortDirection = 'asc';
}
