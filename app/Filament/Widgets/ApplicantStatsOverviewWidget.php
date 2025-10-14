<?php

namespace App\Filament\Widgets;

use App\Models\Applicant;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ApplicantStatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $ttl = cache()->remember('stats_total_applicants', 60, fn () => Applicant::count());

        return [
            Stat::make('Total Lamaran', $ttl)
                ->description('Semua lamaran yang masuk')
                ->descriptionIcon('heroicon-o-users')
                ->color('primary'),
        ];
    }
}
