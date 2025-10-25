<?php

namespace App\Filament\Resources\Events\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\Events\EventResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEvent extends EditRecord
{
    protected static string $resource = EventResource::class;
    
    public function getTitle(): string 
    {
        return __('filament-resources.events.actions.edit.title', [
            'label' => $this->record->title,
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
    
}
