<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use App\Models\Company;
use App\Models\Speaker;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make(
                __('filament-widgets.stats.events.total'), 
                Event::count()
            )
                ->description(__('filament-widgets.stats.events.total_description'))
                ->descriptionIcon('heroicon-m-calendar')
                ->color('primary'),

            Stat::make(
                __('filament-widgets.stats.events.upcoming'), 
                Event::where('start_date', '>=', now())->count()
            )
                ->description(__('filament-widgets.stats.events.upcoming_description'))
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make(
                __('filament-widgets.stats.companies.total'), 
                Company::count()
            )
                ->description(__('filament-widgets.stats.companies.description'))
                ->descriptionIcon('heroicon-m-building-office')
                ->color('warning'),

            Stat::make(
                __('filament-widgets.stats.speakers.total'), 
                Speaker::count()
            )
                ->description(__('filament-widgets.stats.speakers.description'))
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),
        ];
    }
} 