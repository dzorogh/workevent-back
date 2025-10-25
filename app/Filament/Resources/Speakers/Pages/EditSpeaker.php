<?php

namespace App\Filament\Resources\Speakers\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\Speakers\SpeakerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSpeaker extends EditRecord
{
    protected static string $resource = SpeakerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
