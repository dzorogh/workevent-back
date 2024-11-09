<?php

namespace App\Http\Requests\Api\V1;

use App\Enums\EventFormat;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class SearchEventsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'query' => ['nullable', 'string', 'max:255'],
            'format' => ['nullable', new Enum(EventFormat::class)],
            'city_id' => ['nullable', 'exists:cities,id'],
            'industry_id' => ['nullable', 'exists:industries,id'],
            'date_from' => ['nullable', 'date', 'before_or_equal:date_to'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'date_from.before_or_equal' => 'Дата начала должна быть раньше или равна дате окончания',
            'date_to.after_or_equal' => 'Дата окончания должна быть позже или равна дате начала',
        ];
    }
}
