<?php

namespace App\Filament\Widgets;

use App\Models\ApplyJob;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class StatusBreakdownWidget extends ChartWidget
{
    protected static ?int $sort = 3;
    
    protected int | string | array $columnSpan = [
        'md' => 2,
        'xl' => 1,
    ];

    public ?string $heading = 'Status Lamaran';

    protected function getData(): array
    {
        $user = Auth::user();
        $isAdminPusat = $user && $user->role === 'admin_pusat';
        $locationId = $user ? $user->location_id : null;

        // Query berdasarkan role
        $query = ApplyJob::selectRaw('apply_jobs_status, COUNT(*) as count')
            ->groupBy('apply_jobs_status');

        if (!$isAdminPusat && $locationId) {
            $query->whereHas('jobVacancy', function ($q) use ($locationId) {
                $q->where('job_vacancy_hris_location_id', $locationId);
            });
        }

        $data = $query->get();

        // Status mapping berdasarkan nilai numerik
        $statusLabels = [
            '1' => 'Menunggu Review',
            '2' => 'Sedang Direview', 
            '3' => 'Interview',
            '4' => 'Diterima',
            '5' => 'Hired',
            '6' => 'Ditolak',
        ];

        $statusColors = [
            '1' => '#f59e0b',
            '2' => '#3b82f6',
            '3' => '#8b5cf6',
            '4' => '#10b981',
            '5' => '#059669',
            '6' => '#ef4444',
        ];

        $labels = [];
        $chartData = [];
        $colors = [];

        foreach ($data as $item) {
            $labels[] = $statusLabels[$item->apply_jobs_status] ?? ucfirst($item->apply_jobs_status);
            $chartData[] = $item->count;
            $colors[] = $statusColors[$item->apply_jobs_status] ?? '#6b7280';
        }

        return [
            'datasets' => [
                [
                    'data' => $chartData,
                    'backgroundColor' => $colors,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
        ];
    }
}
