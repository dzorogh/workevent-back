<?php

namespace App\Enums;

enum EventFormat: string
{
    case FORUM = 'forum';
    case CONFERENCE = 'conference';
    case EXHIBITION = 'exhibition';
    case SEMINAR = 'seminar';
    case WEBINAR = 'webinar';
    case SUMMIT = 'summit';

    public function getLabel(): string
    {
        return match($this) {
            self::FORUM => __('filament-resources.events.formats.forum'),
            self::CONFERENCE => __('filament-resources.events.formats.conference'),
            self::EXHIBITION => __('filament-resources.events.formats.exhibition'),
            self::SEMINAR => __('filament-resources.events.formats.seminar'),
            self::WEBINAR => __('filament-resources.events.formats.webinar'),
            self::SUMMIT => __('filament-resources.events.formats.summit'),
        };
    }

    public static function getLabels(): array
    {
        $labels = [];
        foreach (self::cases() as $case) {
            $labels[$case->value] = $case->getLabel();
        }
        return $labels;
    }

    public function getColor(): string
    {
        return match($this) {
            self::FORUM => 'warning',
            self::CONFERENCE => 'success',
            self::EXHIBITION => 'info',
            self::SEMINAR => 'primary',
            self::WEBINAR => 'danger',
            self::SUMMIT => 'warning',
            default => 'primary',
        };
    }
}
