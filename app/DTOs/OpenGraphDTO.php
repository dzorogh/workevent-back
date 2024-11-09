<?php

namespace App\DTOs;

use App\Models\Metadata;

class OpenGraphDTO
{
    public function __construct(
        public readonly ?string $title,
        public readonly ?string $description,
        public readonly ?string $type,
        public readonly ?string $image,
        public readonly ?string $url,
        public readonly ?string $siteName,
        public readonly ?string $locale,
    ) {}

    public static function fromModel(Metadata $metadata): ?self
    {
        if (!$metadata->og_title && !$metadata->og_description) {
            return null;
        }

        return new self(
            title: $metadata->og_title,
            description: $metadata->og_description,
            type: $metadata->og_type,
            image: $metadata->og_image,
            url: $metadata->og_url,
            siteName: $metadata->og_site_name,
            locale: $metadata->og_locale,
        );
    }
}
