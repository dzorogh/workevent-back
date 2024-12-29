<?php

namespace App\Enums;

enum CacheKeys: string
{
    case ACTIVE_INDUSTRIES = 'active_industries';
    case INDUSTRIES_SLUGS = 'industries_slugs';
    case ACTIVE_CITIES = 'active_cities';
    case ACTIVE_PRESETS = 'active_presets';
    case ACTIVE_PRESETS_SLUGS = 'presets_slugs';
    case ACTIVE_SERIES = 'active_series';
}
