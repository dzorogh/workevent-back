<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\DashboardStatsOverview::class,
            \App\Filament\Widgets\UpcomingEventsWidget::class,
            \App\Filament\Widgets\RecentCompaniesWidget::class,
        ];
    }
}
