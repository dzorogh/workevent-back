<?php

namespace App\Services;

use App\DTOs\PresetFiltersDTO;
use App\Models\Preset;

class PresetService
{
    public function getPresets(PresetFiltersDTO $presetFilters)
    {
        $presetsQuery = Preset::query();

        foreach (get_object_vars($presetFilters) as $key => $value) {
            $presetsQuery->where("filters->{$key}", $value);
        }

        $presets = $presetsQuery->orderBy('sort_order')->get();

        return $presets;
    }

    public function getPresetsWithOptionalFilters(PresetFiltersDTO $presetFilters)
    {
        $presetsQuery = Preset::query();

        foreach (get_object_vars($presetFilters) as $key => $value) {
            $presetsQuery->where(function ($query) use ($key, $value) {
                $query
                    ->where("filters->{$key}", $value)
                    ->orWhereNull("filters->{$key}");
            });
        }

        $presets = $presetsQuery->orderBy('sort_order')->get();

        return $presets;
    }
}
