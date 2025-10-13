<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class AdminDashboard extends BaseDashboard
{
    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?string $title = 'Dashboard Admin';

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\ApplicantStatsOverviewWidget::class,
        ];
    }
}
