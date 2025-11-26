<?php

namespace App\Filament\Widgets;

use App\Models\JobVacancy;
use App\Models\Location;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class JobVacancyApplicationsWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    
    protected int | string | array $columnSpan = 'full';
    
    public function getTableHeading(): ?string
    {
        return 'Total Lamaran per Lowongan';
    }
    
    public static function canView(): bool
    {
        return true;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('job_vacancy_name')
                    ->label('Lowongan Pekerjaan')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->weight('bold'),
                    
                TextColumn::make('location_name')
                    ->label('Lokasi')
                    ->getStateUsing(function ($record) {
                        if ($record->job_vacancy_hris_location_id) {
                            $location = Location::where('hris_location_id', $record->job_vacancy_hris_location_id)->first();
                            return $location ? $location->name : 'Unknown';
                        }
                        return '-';
                    })
                    ->searchable(),
                    
                TextColumn::make('applications_count')
                    ->label('Total Lamaran')
                    ->badge()
                    ->color('success')
                    ->sortable()
                    ->alignCenter()
                    ->extraAttributes(function ($record) {
                        return [
                            'style' => 'cursor: pointer;',
                            'onclick' => "window.location.href='" . route('filament.admin.resources.applicants.index') . "?tableFilters%5Bjob_vacancy_id%5D=" . $record->job_vacancy_id . "'",
                        ];
                    }),
                    
                TextColumn::make('job_vacancy_start_date')
                    ->label('Mulai')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(),
                    
                TextColumn::make('job_vacancy_end_date')
                    ->label('Berakhir')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('applications_count', 'desc')
            ->paginated([10, 25, 50]);
    }

    protected function getTableQuery(): Builder
    {
        $user = Auth::user();
        $isAdminPusat = $user && $user->role === 'admin_pusat';

        $query = JobVacancy::query()
            ->withCount('applyJobs as applications_count')
            ->has('applyJobs'); // Filter hanya job vacancy yang punya apply jobs

        // Filter berdasarkan role dan lokasi
        if ($user) {
            // Cek role user - hanya admin_pusat yang bisa melihat semua
            if ($user->role === 'admin_pusat') {
                // Admin pusat melihat semua job vacancy - tidak perlu filter
            } else {
                // Semua role selain admin_pusat (termasuk admin_location) hanya melihat lokasi mereka
                if ($user->location_id) {
                    $location = $user->location;
                    if ($location && $location->hris_location_id) {
                        $query->where('job_vacancy_hris_location_id', $location->hris_location_id);
                    } else {
                        // Jika tidak punya lokasi valid, tidak tampilkan apa-apa
                        $query->whereRaw('1 = 0');
                    }
                } else {
                    // Jika tidak punya location_id, tidak tampilkan apa-apa
                    $query->whereRaw('1 = 0');
                }
            }
        } else {
            // User tidak login, tidak tampilkan apa-apa
            $query->whereRaw('1 = 0');
        }

        return $query;
    }
}
