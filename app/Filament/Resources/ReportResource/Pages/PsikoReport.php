<?php

namespace App\Filament\Resources\ReportResource\Pages;

use App\Filament\Resources\ReportResource;
use App\Models\JobVacancy;
use App\Models\ApplyJob;
use App\Models\Applicant;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class PsikoReport extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static string $resource = ReportResource::class;

    protected string $view = 'filament.resources.report-resource.pages.psiko-report';
    
    public $job_vacancy_id;
    
    public function mount($job_vacancy_id): void
    {
        $this->job_vacancy_id = $job_vacancy_id;
    }
    
    public function getTitle(): string
    {
        $jobVacancy = JobVacancy::find($this->job_vacancy_id);
        return 'Psiko Test Report - ' . ($jobVacancy->jobTitle ?? 'Job Vacancy');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ApplyJob::query()
                    ->join('require', 'apply_jobs.requireid', '=', 'require.requireid')
                    ->where('apply_jobs.job_vacancy_id', $this->job_vacancy_id)
                    ->where('apply_jobs.apply_jobs_status', 3) // Psikotest status
                    ->select(
                        'apply_jobs.*', 
                        'require.*',
                        'apply_jobs.apply_jobs_psikotest_iq_num',
                        'apply_jobs.apply_jobs_psikotest_status',
                        'apply_jobs.apply_jobs_psikotest_file'
                    )
            )
            ->columns([
                TextColumn::make('firstname')
                    ->label('Nama Lengkap')
                    ->formatStateUsing(fn ($record) => 
                        ($record->firstname ?? '') . ' ' . 
                        ($record->middlename ?? '') . ' ' . 
                        ($record->lastname ?? '')
                    )
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('gmail')
                    ->label('Email')
                    ->getStateUsing(function ($record) {
                        if (empty($record->gmail)) return '-';
                        try {
                            return \Illuminate\Support\Facades\Crypt::decryptString($record->gmail);
                        } catch (\Exception $e) {
                            return $record->gmail; // Return raw if decryption fails
                        }
                    })
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('interview_by')
                    ->label('Interview By')
                    ->getStateUsing(function ($record) {
                        return $record->apply_jobs_interview_by ?? 'HR Team';
                    })
                    ->sortable(),
                    
                TextColumn::make('apply_jobs_psikotest_iq_num')
                    ->label('Psiko Test Score')
                    ->getStateUsing(function ($record) {
                        // Get actual IQ number from the database
                        return $record->apply_jobs_psikotest_iq_num ?? '-';
                    })
                    ->sortable(),
                    
                TextColumn::make('psiko_date')
                    ->label('Test Date')
                    ->getStateUsing(function ($record) {
                        return $record->updated_at->format('d M Y H:i');
                    })
                    ->sortable(),
                    
                TextColumn::make('apply_jobs_psikotest_status')
                    ->label('Psiko Test Status')
                    ->getStateUsing(function ($record) {
                        // Map status numbers to readable text
                        return match($record->apply_jobs_psikotest_status) {
                            0 => 'Pending',
                            1 => 'Completed',
                            2 => 'Pass',
                            3 => 'Fail',
                            default => $record->apply_jobs_psikotest_status ?? 'Unknown'
                        };
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pass' => 'success',
                        'Completed' => 'info',
                        'Fail' => 'danger',
                        'Pending' => 'warning',
                        default => 'gray',
                    }),
                    
                TextColumn::make('apply_jobs_psikotest_file')
                    ->label('File Psikotest')
                    ->getStateUsing(function ($record) {
                        if ($record->apply_jobs_psikotest_file) {
                            return 'Download';
                        }
                        return 'No File';
                    })
                    ->url(function ($record) {
                        if ($record->apply_jobs_psikotest_file) {
                            return route('admin.file.apply-job', ['path' => $record->apply_jobs_psikotest_file]);
                        }
                        return null;
                    })
                    ->openUrlInNewTab()
                    ->badge()
                    ->color(fn (string $state): string => $state === 'Download' ? 'success' : 'gray'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('apply_jobs_psikotest_status')
                    ->label('Psiko Test Status')
                    ->options([
                        0 => 'Pending',
                        1 => 'Completed',
                        2 => 'Pass',
                        3 => 'Fail',
                    ]),
            ])
            ->actions([
                // Actions akan ditambahkan nanti setelah kompatibilitas Filament v4 diperbaiki
            ])
            ->bulkActions([
                //
            ]);
    }
}
