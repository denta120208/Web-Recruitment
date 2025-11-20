<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportResource\Pages;
use App\Models\JobVacancy;
use App\Models\ApplyJobs;
use App\Models\ApplyJob;
use App\Models\Location;
use App\Traits\LocationFilterTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use BackedEnum;

class ReportResource extends Resource
{
    use LocationFilterTrait;
    
    protected static ?string $model = JobVacancy::class;
    
    protected static ?string $navigationLabel = 'Reports';
    
    protected static ?string $modelLabel = 'Report';
    
    protected static ?string $pluralModelLabel = 'Reports';

    protected static ?int $navigationSort = 5;
    
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-chart-bar';
    
    // Sementara disembunyikan dari navigasi
    protected static bool $shouldRegisterNavigation = true;

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('job_vacancy_start_date', 'desc')
            ->striped()
            ->columns([
                TextColumn::make('job_vacancy_name')
                    ->label('Job Vacancy')
                    ->getStateUsing(function ($record) {
                        // Menampilkan nama job vacancy dan level
                        $jobName = $record->job_vacancy_name ?? 'Unknown Position';
                        $level = $record->job_vacancy_level_name ?? '';
                        
                        if ($level) {
                            return $jobName ;
                        }
                        return $jobName;
                    })
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('job_vacancy_level_name')
                    ->label('Level')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('location_name')
                    ->label('Lokasi')
                    ->getStateUsing(function ($record) {
                        if ($record->job_vacancy_hris_location_id) {
                            return Location::getNameByHrisId($record->job_vacancy_hris_location_id);
                        }
                        return 'Tidak ada lokasi';
                    })
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                    
                TextColumn::make('job_vacancy_man_power')
                    ->label('Man Power Needed')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('job_vacancy_start_date')
                    ->label('Start Date')
                    ->date('d M Y')
                    ->sortable(),
                    
                TextColumn::make('job_vacancy_end_date')
                    ->label('End Date')
                    ->date('d M Y')
                    ->sortable(),
                    
                TextColumn::make('total_applicants')
                    ->label('Total Applicants')
                    ->getStateUsing(function ($record) {
                        // Count all applicants for this job vacancy
                        $total = DB::table('apply_jobs')
                            ->where('job_vacancy_id', $record->job_vacancy_id)
                            ->count();
                        
                        // Get breakdown by status
                        $breakdown = DB::table('apply_jobs')
                            ->where('job_vacancy_id', $record->job_vacancy_id)
                            ->selectRaw('apply_jobs_status, COUNT(*) as count')
                            ->groupBy('apply_jobs_status')
                            ->pluck('count', 'apply_jobs_status')
                            ->toArray();
                        
                        return $total;
                    })
                    ->badge()
                    ->color('primary')
                    ->tooltip(function ($record) {
                        // Show breakdown in tooltip
                        $breakdown = DB::table('apply_jobs')
                            ->where('job_vacancy_id', $record->job_vacancy_id)
                            ->selectRaw('apply_jobs_status, COUNT(*) as count')
                            ->groupBy('apply_jobs_status')
                            ->pluck('count', 'apply_jobs_status')
                            ->toArray();
                        
                        $statusNames = [
                            0 => 'Applied',
                            1 => 'Review',
                            2 => 'Interview',
                            3 => 'Psiko Test',
                            4 => 'Offering',
                            5 => 'Hired',
                            6 => 'MCU'
                        ];
                        
                        $tooltipText = 'Breakdown by Status:';
                        foreach ($breakdown as $status => $count) {
                            $statusName = $statusNames[$status] ?? "Status $status";
                            $tooltipText .= "\n$statusName: $count";
                        }
                        
                        return $tooltipText;
                    })
                    ->url(fn ($record) => static::getUrl('all-applicants', ['job_vacancy_id' => $record->job_vacancy_id])),
                    
                TextColumn::make('review_applicant_count')
                    ->label('Review Applicant')
                    ->getStateUsing(function ($record) {
                        return DB::table('apply_jobs')
                            ->where('job_vacancy_id', $record->job_vacancy_id)
                            ->where('apply_jobs_status', 1) // Review Applicant dari tabel apply_jobs_status
                            ->count();
                    })
                    ->badge()
                    ->color('info')
                    ->url(fn ($record) => static::getUrl('review-applicant', ['job_vacancy_id' => $record->job_vacancy_id])),
                    
                TextColumn::make('interview_user_count')
                    ->label('Interview User')
                    ->getStateUsing(function ($record) {
                        return DB::table('apply_jobs')
                            ->where('job_vacancy_id', $record->job_vacancy_id)
                            ->where('apply_jobs_status', 2) // Interview User dari tabel apply_jobs_status
                            ->count();
                    })
                    ->badge()
                    ->color('success')
                    ->url(fn ($record) => static::getUrl('interview-user', ['job_vacancy_id' => $record->job_vacancy_id])),
                    
                TextColumn::make('psiko_count')
                    ->label('Psiko Test')
                    ->getStateUsing(function ($record) {
                        return DB::table('apply_jobs')
                            ->where('job_vacancy_id', $record->job_vacancy_id)
                            ->where('apply_jobs_status', 3) // Psikotest dari tabel apply_jobs_status
                            ->count();
                    })
                    ->badge()
                    ->color('info')
                    ->url(fn ($record) => static::getUrl('psiko', ['job_vacancy_id' => $record->job_vacancy_id])),
                    
                TextColumn::make('offering_letter_count')
                    ->label('Offering Letter')
                    ->getStateUsing(function ($record) {
                        return DB::table('apply_jobs')
                            ->where('job_vacancy_id', $record->job_vacancy_id)
                            ->where('apply_jobs_status', 4) // Offering Letter dari tabel apply_jobs_status
                            ->count();
                    })
                    ->badge()
                    ->color('warning')
                    ->url(fn ($record) => static::getUrl('offering', ['job_vacancy_id' => $record->job_vacancy_id])),
                    
                TextColumn::make('mcu_count')
                    ->label('MCU')
                    ->getStateUsing(function ($record) {
                        return DB::table('apply_jobs')
                            ->where('job_vacancy_id', $record->job_vacancy_id)
                            ->where('apply_jobs_status', 6) // MCU dari tabel apply_jobs_status
                            ->count();
                    })
                    ->badge()
                    ->color('danger')
                    ->url(fn ($record) => static::getUrl('mcu', ['job_vacancy_id' => $record->job_vacancy_id])),
                    
                TextColumn::make('hired_count')
                    ->label('Hired')
                    ->getStateUsing(function ($record) {
                        return DB::table('apply_jobs')
                            ->where('job_vacancy_id', $record->job_vacancy_id)
                            ->where('apply_jobs_status', 5) // Hired dari tabel apply_jobs_status
                            ->count();
                    })
                    ->badge()
                    ->color('success')
                    ->url(fn ($record) => static::getUrl('hired', ['job_vacancy_id' => $record->job_vacancy_id])),
                    
                BadgeColumn::make('status')
                    ->label('Job Status')
                    ->getStateUsing(function ($record) {
                        // Check if hired count has reached man power requirement
                        $hiredCount = DB::table('apply_jobs')
                            ->where('job_vacancy_id', $record->job_vacancy_id)
                            ->where('apply_jobs_status', 5) // Hired status
                            ->count();
                        
                        $manPowerNeeded = $record->job_vacancy_man_power ?? 0;
                        
                        // If hired count reaches man power needed, job is closed
                        if ($hiredCount >= $manPowerNeeded && $manPowerNeeded > 0) {
                            return 'Closed';
                        }
                        
                        // Check end date
                        $endDate = \Carbon\Carbon::parse($record->job_vacancy_end_date);
                        $now = \Carbon\Carbon::now();
                        
                        if ($now->gt($endDate)) {
                            return 'Closed';
                        } else {
                            return 'Active';
                        }
                    })
                    ->colors([
                        'success' => 'Active',
                        'danger' => 'Closed',
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Job Status')
                    ->options([
                        'active' => 'Active',
                        'closed' => 'Closed',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'] === 'active',
                            function (Builder $query): Builder {
                                return $query->where(function ($q) {
                                    // Active if end date hasn't passed AND hired count < man power needed
                                    $q->where('job_vacancy_end_date', '>=', now())
                                      ->whereRaw('(
                                          SELECT COUNT(*) 
                                          FROM apply_jobs 
                                          WHERE apply_jobs.job_vacancy_id = job_vacancy.job_vacancy_id 
                                          AND apply_jobs.apply_jobs_status = 5
                                      ) < job_vacancy.job_vacancy_man_power');
                                });
                            }
                        )->when(
                            $data['value'] === 'closed',
                            function (Builder $query): Builder {
                                return $query->where(function ($q) {
                                    // Closed if end date has passed OR hired count >= man power needed
                                    $q->where('job_vacancy_end_date', '<', now())
                                      ->orWhereRaw('(
                                          SELECT COUNT(*) 
                                          FROM apply_jobs 
                                          WHERE apply_jobs.job_vacancy_id = job_vacancy.job_vacancy_id 
                                          AND apply_jobs.apply_jobs_status = 5
                                      ) >= job_vacancy.job_vacancy_man_power AND job_vacancy.job_vacancy_man_power > 0');
                                });
                            }
                        );
                    }),
                
                Tables\Filters\SelectFilter::make('job_vacancy_hris_location_id')
                    ->label('Lokasi')
                    ->options(function () {
                        $user = Auth::user();
                        // Hanya tampilkan filter lokasi untuk admin pusat
                        if ($user && $user->role === 'admin_pusat') {
                            return Location::orderBy('name')
                                ->pluck('name', 'hris_location_id')
                                ->toArray();
                        }
                        return [];
                    })
                    ->visible(function () {
                        $user = Auth::user();
                        return $user && $user->role === 'admin_pusat';
                    }),
            ])
            ->actions([
                // Actions akan ditambahkan nanti setelah tabel utama berfungsi
            ])
            ->bulkActions([
                // Bulk actions akan ditambahkan nanti
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReports::route('/'),
            'all-applicants' => Pages\AllApplicantsReport::route('/{job_vacancy_id}/all-applicants'),
            'review-applicant' => Pages\ReviewApplicantReport::route('/{job_vacancy_id}/review-applicant'),
            'interview-user' => Pages\InterviewUserReport::route('/{job_vacancy_id}/interview-user'),
            'psiko' => Pages\PsikoReport::route('/{job_vacancy_id}/psiko'),
            'offering' => Pages\OfferingReport::route('/{job_vacancy_id}/offering'),
            'mcu' => Pages\McuReport::route('/{job_vacancy_id}/mcu'),
            'hired' => Pages\HiredReport::route('/{job_vacancy_id}/hired'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
        // Apply location filter
        $user = Auth::user();
        
        if ($user && in_array($user->role, ['admin_location', 'admin_pusat'])) {
            if ($user->role === 'admin_location' && $user->location_id) {
                $location = $user->location;
                if ($location && $location->hris_location_id) {
                    $query->where('job_vacancy_hris_location_id', $location->hris_location_id);
                } else {
                    // Admin location has no valid location, show empty
                    $query->whereRaw('1 = 0');
                }
            }
            // Admin pusat can see all data (no filter needed)
        } else {
            // Non-admin cannot access
            $query->whereRaw('1 = 0');
        }
        
        return $query;
    }
}
