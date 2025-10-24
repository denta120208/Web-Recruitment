<?php

namespace App\Filament\Resources\JobVacancies\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;

class JobVacanciesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('job_vacancy_id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                
                TextColumn::make('job_vacancy_name')
                    ->label('Nama Posisi')
                    ->sortable()
                    ->searchable()
                    ->wrap(),
                
                TextColumn::make('job_vacancy_level_name')
                    ->label('Level')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                
                BadgeColumn::make('job_vacancy_status_id')
                    ->label('Status')
                    ->formatStateUsing(fn ($state) => match($state) {
                        1 => 'Active',
                        2 => 'On Hold',
                        3 => 'Closed',
                        4 => 'Draft',
                        default => 'Unknown'
                    })
                    ->colors([
                        'success' => 1,
                        'warning' => 2,
                        'danger' => 3,
                        'secondary' => 4,
                    ])
                    ->sortable(),
                
                TextColumn::make('job_vacancy_man_power')
                    ->label('Kebutuhan')
                    ->sortable()
                    ->suffix(' orang'),
                
                TextColumn::make('job_vacancy_start_date')
                    ->label('Tanggal Mulai')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('job_vacancy_end_date')
                    ->label('Tanggal Berakhir')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('applyJobs_count')
                    ->label('Total Pelamar')
                    ->counts('applyJobs')
                    ->sortable()
                    ->badge()
                    ->color('info'),
                
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('updated_at')
                    ->label('Diupdate')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('job_vacancy_status_id')
                    ->label('Status')
                    ->options([
                        1 => 'Active',
                        2 => 'On Hold',
                        3 => 'Closed',
                        4 => 'Draft',
                    ]),
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
