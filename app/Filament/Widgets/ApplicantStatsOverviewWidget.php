<?php

namespace App\Filament\Widgets;

use App\Models\Applicant;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ApplicantStatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Lamaran', Applicant::count())
                ->description('Semua lamaran yang masuk')
                ->descriptionIcon('heroicon-o-users')
                ->color('primary'),

            Stat::make('Menunggu Review', Applicant::where('status', 'pending')->count())
                ->description('Lamaran yang belum direview')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Sedang Direview', Applicant::where('status', 'under_review')->count())
                ->description('Lamaran dalam proses review')
                ->descriptionIcon('heroicon-o-eye')
                ->color('info'),

            Stat::make('Diterima', Applicant::where('status', 'accepted')->count())
                ->description('Lamaran yang diterima')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Ditolak', Applicant::where('status', 'rejected')->count())
                ->description('Lamaran yang ditolak')
                ->descriptionIcon('heroicon-o-x-circle')
                ->color('danger'),

            Stat::make('Sudah Dipekerjakan', Applicant::where('status', 'hired')->count())
                ->description('Kandidat yang sudah bekerja')
                ->descriptionIcon('heroicon-o-briefcase')
                ->color('success'),
        ];
    }
}
