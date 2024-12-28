<?php

namespace App\Filament\Resources\IndustryResource\Pages;

use App\Filament\Resources\IndustryResource;
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
            Actions\CreateAction::make(),
        ];
    }

    public function reorderTable(array $order): void
    {
        parent::reorderTable($order);

        Cache::forget(CacheKeys::ACTIVE_INDUSTRIES->value);
    }
}
