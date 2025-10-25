<?php

namespace App\Filament\Resources\Tags\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\Tags\TagResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTag extends EditRecord
{
    protected static string $resource = TagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
