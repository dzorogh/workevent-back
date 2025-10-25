<?php

namespace App\Filament\Resources\Speakers\Pages;

use App\Filament\Resources\Speakers\SpeakerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSpeaker extends CreateRecord
{
    protected static string $resource = SpeakerResource::class;
}
