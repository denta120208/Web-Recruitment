<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportResource\Pages;
use App\Models\JobVacancy;
use App\Models\ApplyJobs;
use Illuminate\Support\Facades\DB;
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
    protected static ?string $model = JobVacancy::class;
    
    protected static ?string $navigationLabel = 'Reports';
    
    protected static ?string $modelLabel = 'Report';
    
    protected static ?string $pluralModelLabel = 'Reports';

    protected static ?int $navigationSort = 5;
    
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-chart-bar';
    
    // Sementara disembunyikan dari navigasi
    protected static bool $shouldRegisterNavigation = false;

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
                        return DB::table('apply_jobs')
                            ->where('job_vacancy_id', $record->job_vacancy_id)
                            ->count();
                    })
                    ->badge()
                    ->color('primary'),
                    
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
                    ->url(fn ($record) => static::getUrl('interview-user', ['job_vacancy_id' => $record->job_vacancy_id]))
                    ->openUrlInNewTab(),
                    
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
                    ->url(fn ($record) => static::getUrl('psiko', ['job_vacancy_id' => $record->job_vacancy_id]))
                    ->openUrlInNewTab(),
                    
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
                    ->url(fn ($record) => static::getUrl('offering', ['job_vacancy_id' => $record->job_vacancy_id]))
                    ->openUrlInNewTab(),
                    
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
                    ->url(fn ($record) => static::getUrl('mcu', ['job_vacancy_id' => $record->job_vacancy_id]))
                    ->openUrlInNewTab(),
                    
                BadgeColumn::make('status')
                    ->label('Job Status')
                    ->getStateUsing(function ($record) {
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
                            fn (Builder $query): Builder => $query->where('job_vacancy_end_date', '>=', now()),
                        )->when(
                            $data['value'] === 'closed',
                            fn (Builder $query): Builder => $query->where('job_vacancy_end_date', '<', now()),
                        );
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
            'interview-user' => Pages\InterviewUserReport::route('/{job_vacancy_id}/interview-user'),
            'psiko' => Pages\PsikoReport::route('/{job_vacancy_id}/psiko'),
            'offering' => Pages\OfferingReport::route('/{job_vacancy_id}/offering'),
            'mcu' => Pages\McuReport::route('/{job_vacancy_id}/mcu'),
        ];
    }
}
