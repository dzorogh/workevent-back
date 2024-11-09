<?php

namespace App\Enums;

enum ParticipationType: string
{
    case ORGANIZER = 'organizer';
    case SPONSOR = 'sponsor';
    case PARTICIPANT = 'participant';
    case PARTNER = 'partner';

    public function getLabel(): string
    {
        return match($this) {
            self::ORGANIZER => __('filament-resources.companies.participation_types.organizer'),
            self::SPONSOR => __('filament-resources.companies.participation_types.sponsor'),
            self::PARTICIPANT => __('filament-resources.companies.participation_types.participant'),
            self::PARTNER => __('filament-resources.companies.participation_types.partner'),
        };
    }

    public static function getLabels(): array
    {
        return [
            self::ORGANIZER->value => self::ORGANIZER->getLabel(),
            self::SPONSOR->value => self::SPONSOR->getLabel(),
            self::PARTICIPANT->value => self::PARTICIPANT->getLabel(),
            self::PARTNER->value => self::PARTNER->getLabel(),
        ];
    }

    public function getColor(): string
    {
        return match($this) {
            self::ORGANIZER => 'success',
            self::SPONSOR => 'warning',
            self::PARTICIPANT => 'primary',
            self::PARTNER => 'info',
        };
    }
} 