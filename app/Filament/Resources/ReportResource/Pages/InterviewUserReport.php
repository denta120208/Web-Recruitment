<?php

namespace App\Filament\Resources\ReportResource\Pages;

use App\Filament\Resources\ReportResource;
use App\Models\JobVacancy;
use App\Models\ApplyJob;
use App\Models\Applicant;
use Illuminate\Support\Facades\DB;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;

class InterviewUserReport extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static string $resource = ReportResource::class;

    protected string $view = 'filament.resources.report-resource.pages.interview-user-report';
    
    public $job_vacancy_id;
    
    public function mount($job_vacancy_id): void
    {
        $this->job_vacancy_id = $job_vacancy_id;
    }
    
    public function getTitle(): string
    {
        $jobVacancy = JobVacancy::find($this->job_vacancy_id);
        return 'Interview User Report - ' . ($jobVacancy->jobTitle ?? 'Job Vacancy');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ApplyJob::query()
                    ->join('require', 'apply_jobs.requireid', '=', 'require.requireid')
                    ->where('apply_jobs.job_vacancy_id', $this->job_vacancy_id)
                    ->where('apply_jobs.apply_jobs_status', 2) // Interview User status dari tabel apply_jobs_status
                    ->select('apply_jobs.*', 'require.*')
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
                    
                TextColumn::make('phone')
                    ->label('Phone')
                    ->getStateUsing(function ($record) {
                        if (empty($record->phone)) return '-';
                        try {
                            return \Illuminate\Support\Facades\Crypt::decryptString($record->phone);
                        } catch (\Exception $e) {
                            return $record->phone; // Return raw if decryption fails
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
                    
                TextColumn::make('interview_date')
                    ->label('Interview Date')
                    ->getStateUsing(function ($record) {
                        // Menggunakan updated_at dari apply_jobs sebagai tanggal interview
                        return \Carbon\Carbon::parse($record->updated_at)->format('d M Y H:i');
                    })
                    ->sortable(),
                    
                TextColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(function ($record) {
                        return 'Interview User'; // Status dari apply_jobs_status_id = 2
                    })
                    ->badge()
                    ->color('success'),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Actions akan ditambahkan nanti setelah kompatibilitas Filament v4 diperbaiki
            ])
            ->bulkActions([
                //
            ]);
    }
}
