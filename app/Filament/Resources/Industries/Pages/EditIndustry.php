<?php

namespace App\Filament\Resources\Industries\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\Industries\IndustryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIndustry extends EditRecord
{
    protected static string $resource = IndustryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
