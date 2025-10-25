<?php

namespace App\Filament\Resources\Companies\Pages;

use App\Filament\Resources\Companies\CompanyResource;
use Filament\Resources\Pages\EditRecord;

class EditCompany extends EditRecord
{
    protected static string $resource = CompanyResource::class;

    protected function mutateFormData(array $data): array
    {
        $data['event_participations'] = $this->record->events
            ->map(fn ($event) => [
                'event_id' => $event->id,
                'participation_type' => $event->pivot->participation_type,
            ])
            ->toArray();

        return $data;
    }
}
