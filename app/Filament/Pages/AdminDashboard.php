<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class AdminDashboard extends BaseDashboard
{
    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?string $title = 'Dashboard Admin';

    protected static ?int $navigationSort = 0;

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\ApplicantStatsOverviewWidget::class,
            \App\Filament\Widgets\JobVacancyApplicationsWidget::class,
        ];
    }
}
