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
                __('filament-widgets.dashboard-stats-overview.total_events'), 
                Event::count()
            )
                ->description(__('filament-widgets.dashboard-stats-overview.total_events_description'))
                ->descriptionIcon('heroicon-m-calendar')
                ->color('primary'),

            Stat::make(
                __('filament-widgets.dashboard-stats-overview.upcoming_events'), 
                Event::where('start_date', '>=', now())->count()
            )
                ->description(__('filament-widgets.dashboard-stats-overview.upcoming_events_description'))
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Companies', Company::count())
                ->description('Registered companies')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('warning'),

            Stat::make('Speakers', Speaker::count())
                ->description('Registered speakers')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),
        ];
    }
} 