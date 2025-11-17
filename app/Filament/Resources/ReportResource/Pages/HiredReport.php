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
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Builder;

class HiredReport extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static string $resource = ReportResource::class;

    protected string $view = 'filament.resources.report-resource.pages.hired-report';
    
    public $job_vacancy_id;
    
    public function mount($job_vacancy_id): void
    {
        $this->job_vacancy_id = $job_vacancy_id;
    }
    
    public function getTitle(): string
    {
        $jobVacancy = JobVacancy::find($this->job_vacancy_id);
        return 'Hired Candidates Report - ' . ($jobVacancy->job_vacancy_name ?? 'Job Vacancy');
    }
    
    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Back to Reports')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(route('filament.admin.resources.reports.index'))
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ApplyJob::query()
                    ->join('require', 'apply_jobs.requireid', '=', 'require.requireid')
                    ->where('apply_jobs.job_vacancy_id', $this->job_vacancy_id)
                    ->where('apply_jobs.apply_jobs_status', 5) // Hired status
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
                    ->url(function ($record) {
                        // Link to applicant view page
                        return route('filament.admin.resources.applicants.view', $record->requireid);
                    })
                    ->color('primary')
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
                    
                TextColumn::make('hired_date')
                    ->label('Hired Date')
                    ->getStateUsing(function ($record) {
                        return \Carbon\Carbon::parse($record->updated_at)->format('d M Y H:i');
                    })
                    ->sortable(),
                    
                TextColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(function ($record) {
                        return 'Hired';
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
