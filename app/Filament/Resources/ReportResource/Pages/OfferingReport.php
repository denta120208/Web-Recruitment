<?php

namespace App\Filament\Resources\ReportResource\Pages;

use App\Filament\Resources\ReportResource;
use App\Models\JobVacancy;
use App\Models\ApplyJob;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Actions\Action;
use Illuminate\Support\Facades\DB;

class OfferingReport extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static string $resource = ReportResource::class;

    protected string $view = 'filament.resources.report-resource.pages.offering-report';
    
    public $job_vacancy_id;
    
    public function mount($job_vacancy_id): void
    {
        $this->job_vacancy_id = $job_vacancy_id;
    }
    
    public function getTitle(): string
    {
        $jobVacancy = JobVacancy::find($this->job_vacancy_id);
        return 'Offering Letter Report - ' . ($jobVacancy->jobTitle ?? 'Job Vacancy');
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
                    ->where('apply_jobs.apply_jobs_status', 4) // Offering Letter status
                    ->select(
                        'apply_jobs.*', 
                        'require.*',
                        'apply_jobs.apply_jobs_offering_letter_file'
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
                    
                TextColumn::make('apply_jobs_offering_letter_file')
                    ->label('File Offering Letter')
                    ->getStateUsing(function ($record) {
                        if ($record->apply_jobs_offering_letter_file) {
                            return 'Download';
                        }
                        return 'No File';
                    })
                    ->url(function ($record) {
                        if ($record->apply_jobs_offering_letter_file) {
                            return route('admin.file.apply-job', ['path' => $record->apply_jobs_offering_letter_file]);
                        }
                        return null;
                    })
                    ->openUrlInNewTab()
                    ->badge()
                    ->color(fn (string $state): string => $state === 'Download' ? 'success' : 'gray'),
                    
                TextColumn::make('offering_date')
                    ->label('Offering Date')
                    ->getStateUsing(function ($record) {
                        return \Carbon\Carbon::parse($record->updated_at)->format('d M Y H:i');
                    })
                    ->sortable(),
                    
                TextColumn::make('offering_status')
                    ->label('Response Status')
                    ->getStateUsing(function ($record) {
                        if ($record->apply_jobs_offering_letter_file) {
                            return 'Sent';
                        }
                        return 'Pending';
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Accepted' => 'success',
                        'Rejected' => 'danger',
                        'Sent' => 'info',
                        'Pending' => 'warning',
                        default => 'gray',
                    }),
                    
                TextColumn::make('salary_offered')
                    ->label('Salary Offered')
                    ->getStateUsing(function ($record) {
                        return 'Confidential'; // Placeholder - bisa disesuaikan dengan field yang ada
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'accepted' => 'Accepted',
                        'rejected' => 'Rejected',
                        'sent' => 'Sent',
                        'pending' => 'Pending',
                    ]),
            ])
            ->actions([
                // Download functionality is now integrated into the File Offering Letter column
            ])
            ->bulkActions([
                //
            ]);
    }
}
