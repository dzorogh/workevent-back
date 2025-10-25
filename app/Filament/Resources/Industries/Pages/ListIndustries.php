<?php

namespace App\Filament\Resources\Industries\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Industries\IndustryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Cache;
use App\Enums\CacheKeys;

class ListIndustries extends ListRecords
{
    protected static string $resource = IndustryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function reorderTable(array $order, string|int|null $draggedRecordKey = null): void
    {
        parent::reorderTable($order, $draggedRecordKey);

        Cache::forget(CacheKeys::ACTIVE_INDUSTRIES->value);
    }
}
