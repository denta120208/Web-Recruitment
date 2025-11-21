<?php

namespace App\Filament\Widgets;

use App\Models\ApplyJob;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApplicantStatsWidget extends ChartWidget
{
    protected static ?int $sort = 2;
    
    protected int | string | array $columnSpan = 'full';

    public ?string $heading = 'Trend Lamaran Bulanan';

    protected function getData(): array
    {
        $user = Auth::user();
        $isAdminPusat = $user && $user->role === 'admin_pusat';
        $locationId = $user ? $user->location_id : null;

        // Query berdasarkan role
        $query = ApplyJob::selectRaw('EXTRACT(MONTH FROM created_at) as month, EXTRACT(YEAR FROM created_at) as year, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month');

        if (!$isAdminPusat && $locationId) {
            $query->whereHas('jobVacancy', function ($q) use ($locationId) {
                $q->where('job_vacancy_hris_location_id', $locationId);
            });
        }

        $data = $query->get();

        // Prepare data untuk 12 bulan
        $months = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ];

        $chartData = [];
        $labels = [];

        for ($i = 1; $i <= 12; $i++) {
            $labels[] = $months[$i];
            $monthData = $data->where('month', $i)->first();
            $chartData[] = $monthData ? $monthData->count : 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Lamaran',
                    'data' => $chartData,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }
}
