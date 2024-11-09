<?php

namespace App\Filament\Resources\EventTagResource\Pages;

use App\Filament\Resources\EventTagResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTag extends CreateRecord
{
    protected static string $resource = EventTagResource::class;
}