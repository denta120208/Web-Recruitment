<?php

namespace App\Filament\Resources\ApplyJobs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ApplyJobsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('apply_jobs_id')
                    ->label('ID')
                    ->numeric()
                    ->sortable()
                    ->width(80),
                TextColumn::make('jobVacancy.job_vacancy_name')
                    ->label('Posisi Dilamar')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                TextColumn::make('user.name')
                    ->label('Nama Pelamar')
                    ->searchable()
                    ->sortable()
                    ->limit(25),
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
                TextColumn::make('apply_jobs_psikotest_status')
                    ->label('Status Psikotes')
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
                TextColumn::make('apply_jobs_interview_by')
                    ->label('Interviewer')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Tanggal Lamar')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->width(120),
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
                    ->url(fn ($record) => route('filament.admin.resources.applicants.view', $record->require_id))
                    ->openUrlInNewTab(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
