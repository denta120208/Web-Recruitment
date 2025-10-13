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
        $pending = cache()->remember('stats_pending_applicants', 60, fn () => Applicant::where('status', 'pending')->count());
        $review = cache()->remember('stats_review_applicants', 60, fn () => Applicant::where('status', 'under_review')->count());
        $interview = cache()->remember('stats_interview_applicants', 60, fn () => Applicant::where('status', 'interview_scheduled')->count());
        $accepted = cache()->remember('stats_accepted_applicants', 60, fn () => Applicant::where('status', 'accepted')->count());
        $rejected = cache()->remember('stats_rejected_applicants', 60, fn () => Applicant::where('status', 'rejected')->count());
        $hired = cache()->remember('stats_hired_applicants', 60, fn () => Applicant::where('status', 'hired')->count());

        return [
            Stat::make('Total Lamaran', $ttl)
                ->description('Semua lamaran yang masuk')
                ->descriptionIcon('heroicon-o-users')
                ->color('primary'),

            Stat::make('Menunggu Review', $pending)
                ->description('Lamaran yang belum direview')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Sedang Direview', $review)
                ->description('Lamaran dalam proses review')
                ->descriptionIcon('heroicon-o-eye')
                ->color('info'),

            Stat::make('Diterima', $accepted)
                ->description('Lamaran yang diterima')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Ditolak', $rejected)
                ->description('Lamaran yang ditolak')
                ->descriptionIcon('heroicon-o-x-circle')
                ->color('danger'),

            Stat::make('Sudah Dipekerjakan', $hired)
                ->description('Kandidat yang sudah bekerja')
                ->descriptionIcon('heroicon-o-briefcase')
                ->color('success'),
        ];
    }
}
