<?php

namespace App\Enums;

enum EventFormat: string
{
    case FORUM = 'forum';
    case CONFERENCE = 'conference';
    case EXHIBITION = 'exhibition';
    case SEMINAR = 'seminar';
    case WEBINAR = 'webinar';

    public function getLabel(): string
    {
        return match($this) {
            self::FORUM => __('filament-resources.events.formats.forum'),
            self::CONFERENCE => __('filament-resources.events.formats.conference'),
            self::EXHIBITION => __('filament-resources.events.formats.exhibition'),
            self::SEMINAR => __('filament-resources.events.formats.seminar'),
            self::WEBINAR => __('filament-resources.events.formats.webinar'),
        };
    }

    public static function getLabels(): array
    {
        return [
            self::FORUM->value => self::FORUM->getLabel(),
            self::CONFERENCE->value => self::CONFERENCE->getLabel(),
            self::EXHIBITION->value => self::EXHIBITION->getLabel(),
            self::SEMINAR->value => self::SEMINAR->getLabel(),
            self::WEBINAR->value => self::WEBINAR->getLabel(),
        ];
    }

    public function getColor(): string
    {
        return match($this) {
            self::FORUM => 'warning',
            self::CONFERENCE => 'success',
            self::EXHIBITION => 'info',
            self::SEMINAR => 'primary',
            self::WEBINAR => 'danger',
        };
    }
} 