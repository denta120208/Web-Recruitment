<?php

namespace App\Filament\Resources\ApplyJobs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Filament\Notifications\Notification;

class ApplyJobsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nama Pelamar')
                    ->searchable()
                    ->sortable()
                    ->limit(25),
                TextColumn::make('jobVacancy.job_vacancy_name')
                    ->label('Posisi Dilamar')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                TextColumn::make('created_at')
                    ->label('Tanggal Lamar')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->width(120),
                TextColumn::make('interviewStatus.interview_status_name')
                    ->label('Interview Status')
                    ->sortable()
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('apply_jobs_interview_by')
                    ->label('Interviewer')
                    ->searchable()
                    ->placeholder('-')
                    ->toggleable(),
                TextColumn::make('apply_jobs_psikotest_file')
                    ->label('File Psikotes')
                    ->formatStateUsing(function ($state, $record) {
                        if ($state) {
                            $url = route('admin.file.apply-job', ['path' => $state]);
                            return new HtmlString('<a href="' . e($url) . '" class="fi-btn fi-btn-size-sm fi-color-primary">Download</a>');
                        }
                        return new HtmlString('<span class="text-gray-400">-</span>');
                    })
                    ->html()
                    ->searchable(false)
                    ->sortable(false),
                TextColumn::make('apply_jobs_psikotest_status')
                    ->label('Psikotes')
                    ->formatStateUsing(function ($state) {
                        $statuses = [
                            1 => 'Approve',
                            2 => 'Considered',
                            3 => 'Reject'
                        ];
                        return $statuses[$state] ?? 'Belum Test';
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '1' => 'success',
                        '2' => 'warning',
                        '3' => 'danger',
                        default => 'gray',
                 }),
                TextColumn::make('apply_jobs_mcu_file')
                    ->label('File MCU')
                    ->formatStateUsing(function ($state, $record) {
                        if ($state && $record && $record->apply_jobs_status == 4) {
                            $url = route('admin.file.apply-job', ['path' => $state]);
                            return new HtmlString('<a href="' . e($url) . '" class="fi-btn fi-btn-size-sm fi-color-primary">Download</a>');
                        }
                        return new HtmlString('<span class="text-gray-400">-</span>');
                    })
                    ->html()
                    ->visible(fn ($record) => $record && $record->apply_jobs_status == 4)
                    ->searchable(false)
                    ->sortable(false),
                TextColumn::make('apply_jobs_offering_letter_file')
                    ->label('File Offering Letter')
                    ->formatStateUsing(function ($state, $record) {
                        if ($state && $record && $record->apply_jobs_status == 4) {
                            $url = route('admin.file.apply-job', ['path' => $state]);
                            return new HtmlString('<a href="' . e($url) . '" class="fi-btn fi-btn-size-sm fi-color-primary">Download</a>');
                        }
                        return new HtmlString('<span class="text-gray-400">-</span>');
                    })
                    ->html()
                    ->visible(fn ($record) => $record && $record->apply_jobs_status == 4)
                    ->searchable(false)
                    ->sortable(false),
                TextColumn::make('apply_jobs_status')
                    ->label('Status')
                    ->formatStateUsing(function ($state) {
                        $statuses = [
                            1 => 'Review Aplicant',
                            2 => 'Interview User',
                            3 => 'Psikotest',
                            4 => 'Offering Letter',
                            5 => 'Hired'
                        ];
                        return $statuses[$state] ?? 'Unknown';
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '1' => 'info',
                        '2' => 'warning',
                        '3' => 'primary',
                        '4' => 'success',
                        '5' => 'success',
                        default => 'gray',
                    }),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('apply_jobs_status')
                    ->label('Status Lamaran')
                    ->options([
                        1 => 'Review Aplicant',
                        2 => 'Interview User',
                        3 => 'Psikotest',
                        4 => 'Offering Letter',
                        5 => 'Hired'
                    ]),
                \Filament\Tables\Filters\SelectFilter::make('job_vacancy_id')
                    ->label('Posisi yang Dilamar')
                    ->relationship('jobVacancy', 'job_vacancy_name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('View Applicant')
                    ->url(fn ($record) => route('filament.admin.resources.applicants.view', $record->requireid))
                    ->openUrlInNewTab(),
                Action::make('generate_employee')
                    ->label('Generate Employee')
                    ->icon('heroicon-o-user-plus')
                    ->color('success')
                    ->visible(fn ($record) => $record->apply_jobs_status == 5 && !$record->is_generated_employee)
                    ->requiresConfirmation()
                    ->modalHeading('Generate Employee')
                    ->modalDescription('Apakah Anda yakin ingin generate employee untuk pelamar ini? Setelah di-generate, data tidak dapat diedit lagi.')
                    ->modalSubmitActionLabel('Ya, Generate')
                    ->modalCancelActionLabel('Batal')
                    ->action(function ($record) {
                        $record->update(['is_generated_employee' => true]);
                        
                        Notification::make()
                            ->title('Employee berhasil di-generate')
                            ->success()
                            ->send();
                    }),
                EditAction::make()
                    ->hidden(fn ($record) => $record->is_generated_employee),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
