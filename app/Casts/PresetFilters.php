<?php
 
namespace App\Casts;
 
use App\DTOs\PresetFiltersDTO;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use App\ValueObjects\PresetFilters as PresetFiltersValueObject;

class PresetFilters implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): PresetFiltersValueObject
    {
        $filters = json_decode($attributes['filters'], true);
        
        return PresetFiltersValueObject::fromArray($filters);
    }
 
    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     * @return array<string, string>
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): array
    {
        if (! $value instanceof PresetFiltersValueObject) {
            $value = PresetFiltersValueObject::fromArray($value);
        }

        return [
            'filters' => json_encode($value->jsonSerialize()),
        ];
    }
}
