<?php

namespace App\DTOs;

use App\Models\Metadata;

class MetadataDTO
{
    public function __construct(
        public readonly string $title,
        public readonly ?string $h1 = null,
        public readonly ?string $description = null,
        public readonly ?string $keywords = null,
        public readonly ?string $canonicalUrl = null,
        public readonly ?string $robots = null,
        public readonly ?OpenGraphDTO $openGraph = null,
        public readonly ?TwitterCardDTO $twitter = null,
    ) {}

    public static function fromModel(Metadata $metadata): self
    {
        return new self(
            title: $metadata->title,
            h1: $metadata->h1,
            description: $metadata->description,
            keywords: $metadata->keywords,
            canonicalUrl: $metadata->canonical_url,
            robots: $metadata->robots,
            openGraph: OpenGraphDTO::fromModel($metadata),
            twitter: TwitterCardDTO::fromModel($metadata),
        );
    }
}
