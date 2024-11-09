<?php

namespace App\Http\Controllers\Api\V1;

use App\Contracts\HasMetadataContract;
use App\DTOs\MetadataDTO;
use App\Http\Controllers\Controller;
use App\Http\Resources\Resources\MetadataResource;
use App\Http\Requests\MetadataRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MetadataController extends Controller
{
    public function show(MetadataRequest $request): MetadataResource
    {
        $modelClass = Relation::getMorphedModel($request->validated('type'));

        if (!$modelClass) {
            throw new NotFoundHttpException('Unknown type');
        }

        if (!in_array(HasMetadataContract::class, class_implements($modelClass))) {
            throw new NotFoundHttpException('Model does not support metadata');
        }

        /** @var Model&HasMetadataContract $entity */
        $entity = $modelClass::findOrFail($request->validated('id'))->load('metadata');

        $metadata = $entity->metadata ?? new \App\Models\Metadata();

        return MetadataResource::make($metadata);
    }
}
