<?php

namespace App\Http\Requests;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MetadataRequest extends FormRequest
{
    public function rules(): array
    {
        $type = $this->input('type');
        $modelClass = Relation::getMorphedModel($type);

        return [
            'type' => ['required', 'string', Rule::in(array_keys(Relation::morphMap()))],
            'id' => ['required', 'numeric', Rule::exists($modelClass, 'id')],
        ];
    }
} 