<?php

namespace App\Filament\Pages;

use App\Models\Event;
use App\Models\Company;
use App\Models\Speaker;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [
            DashboardStatsOverview::class,
            UpcomingEventsWidget::class,
            RecentCompaniesWidget::class,
        ];
    }
} 