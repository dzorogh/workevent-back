<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\DashboardStatsOverview;
use App\Filament\Widgets\UpcomingEventsWidget;
use App\Filament\Widgets\RecentCompaniesWidget;
use Filament\Pages\Dashboard as BaseDashboard;

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
