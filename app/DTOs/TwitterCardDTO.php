<?php

namespace App\DTOs;

use App\Models\Metadata;

class TwitterCardDTO
{
    public function __construct(
        public readonly ?string $card,
        public readonly ?string $title,
        public readonly ?string $description,
        public readonly ?string $image,
        public readonly ?string $site,
        public readonly ?string $creator,
    ) {}

    public static function fromModel(Metadata $metadata): ?self
    {
        if (!$metadata->tw_title && !$metadata->tw_description) {
            return null;
        }

        return new self(
            card: $metadata->tw_card,
            title: $metadata->tw_title,
            description: $metadata->tw_description,
            image: $metadata->tw_image,
            site: $metadata->tw_site,
            creator: $metadata->tw_creator,
        );
    }
}
