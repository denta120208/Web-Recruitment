<?php

namespace App\Filament\Widgets;

use App\Models\Applicant;
use App\Models\ApplyJob;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class ApplicantStatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $user = Auth::user();
        $isAdminPusat = $user && $user->role === 'admin_pusat';
        
        // Cache key berdasarkan role dan lokasi
        $cacheKey = $isAdminPusat ? 'stats_admin_pusat' : 'stats_admin_location_' . ($user->location_id ?? 'unknown');
        
        $ttl = cache()->remember($cacheKey, 60, function () use ($user, $isAdminPusat) {
            $query = ApplyJob::query();
            
            // Filter berdasarkan role dan lokasi
            if ($user->role === 'admin_pusat') {
                // Admin pusat melihat semua lamaran - tidak perlu filter
            } else {
                // Semua role selain admin_pusat hanya melihat lokasi mereka
                if ($user->location_id) {
                    $location = $user->location;
                    if ($location && $location->hris_location_id) {
                        $query->whereHas('jobVacancy', function ($q) use ($location) {
                            $q->where('job_vacancy_hris_location_id', $location->hris_location_id);
                        });
                    } else {
                        return 0; // Tidak ada lokasi valid
                    }
                } else {
                    return 0; // Tidak punya location_id
                }
            }
            
            return $query->count();
        });

        $description = $isAdminPusat ? 'Semua lamaran yang masuk' : 'Lamaran di lokasi Anda';

        return [
            Stat::make('Total Lamaran', $ttl)
                ->description($description)
                ->descriptionIcon('heroicon-o-users')
                ->color('primary'),
        ];
    }
}
