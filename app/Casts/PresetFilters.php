<?php
 
namespace App\Casts;
 
use App\DTOs\PresetFiltersDTO;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use App\Enums\EventFormat;
class PresetFilters implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): PresetFiltersDTO
    {
        $filters = json_decode($attributes['filters'], true);

        return new PresetFiltersDTO(
            format: $filters['format'] ? EventFormat::from($filters['format']) : null,
            city_id: $filters['city_id'] ?? null,
            industry_id: $filters['industry_id'] ?? null
        );
    }
 
    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     * @return array<string, string>
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): array
    {
        if (! $value instanceof PresetFiltersDTO) {
            throw new InvalidArgumentException('The given value is not an Preset Filters instance.');
        }
 
        return [
            'format' => $value->format,
            'city_id' => $value->city_id,
            'industry_id' => $value->industry_id,
        ];
    }
}