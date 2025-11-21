<?php

namespace App\Filament\Widgets;

use App\Models\ApplyJob;
use App\Models\Location;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class LocationBreakdownWidget extends ChartWidget
{
    protected static ?int $sort = 4;
    
    protected int | string | array $columnSpan = [
        'md' => 2,
        'xl' => 1,
    ];

    public ?string $heading = 'Lamaran per Lokasi';

    public static function canView(): bool
    {
        $user = Auth::user();
        return $user && $user->role === 'admin_pusat';
    }

    protected function getData(): array
    {
        // Hanya untuk admin pusat
        $data = ApplyJob::selectRaw('job_vacancy.job_vacancy_hris_location_id, COUNT(*) as count')
            ->join('job_vacancy', 'apply_jobs.job_vacancy_id', '=', 'job_vacancy.job_vacancy_id')
            ->groupBy('job_vacancy.job_vacancy_hris_location_id')
            ->orderByDesc('count')
            ->limit(10) // Top 10 lokasi
            ->get();

        $labels = [];
        $chartData = [];
        $colors = [
            '#3b82f6', '#ef4444', '#10b981', '#f59e0b', '#8b5cf6',
            '#06b6d4', '#84cc16', '#f97316', '#ec4899', '#6366f1'
        ];

        foreach ($data as $index => $item) {
            $location = Location::where('hris_location_id', $item->job_vacancy_hris_location_id)->first();
            $labels[] = $location ? $location->name : 'Unknown';
            $chartData[] = $item->count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Lamaran',
                    'data' => $chartData,
                    'backgroundColor' => array_slice($colors, 0, count($chartData)),
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
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
