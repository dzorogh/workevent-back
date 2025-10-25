<?php

namespace App\Filament\Resources\Speakers\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Speakers\SpeakerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSpeakers extends ListRecords
{
    protected static string $resource = SpeakerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
