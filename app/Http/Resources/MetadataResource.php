<?php

namespace App\Http\Resources;

use App\Models\Metadata;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * @mixin Metadata
 */
class MetadataResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->resource->title,
            'h1' => $this->resource->h1,
            'description' => $this->resource->description,
            'keywords' => $this->resource->keywords,
            'canonicalUrl' => $this->resource->canonical_url,
            'robots' => $this->resource->robots,
            'openGraph' => $this->when($this->hasOpenGraph(), [
                'title' => $this->resource->og_title,
                'description' => $this->resource->og_description,
                'type' => $this->resource->og_type,
                'image' => $this->resource->og_image,
                'url' => $this->resource->og_url,
                'siteName' => $this->resource->og_site_name,
                'locale' => $this->resource->og_locale,
            ]),
            'twitter' => $this->when($this->hasTwitter(), [
                'card' => $this->resource->tw_card,
                'title' => $this->resource->tw_title,
                'description' => $this->resource->tw_description,
                'image' => $this->resource->tw_image,
                'site' => $this->resource->tw_site,
                'creator' => $this->resource->tw_creator,
            ]),
        ];
    }

    protected function hasOpenGraph(): bool
    {
        return !empty($this->resource->og_title) ||
            !empty($this->resource->og_description) ||
            !empty($this->resource->og_image);
    }

    protected function hasTwitter(): bool
    {
        return !empty($this->resource->tw_title) ||
            !empty($this->resource->tw_description) ||
            !empty($this->resource->tw_image);
    }
}
