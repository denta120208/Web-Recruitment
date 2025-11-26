<?php

namespace App\Filament\Resources\Applicants\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Crypt;
use App\Models\Location;

class ApplicantsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('requireid')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('full_name')
                    ->label('Nama Lengkap')
                    ->getStateUsing(function ($record): string {
                        return trim($record->firstname . ' ' . $record->middlename . ' ' . $record->lastname);
                    })
                    ->searchable(['firstname', 'middlename', 'lastname']),
                TextColumn::make('gender')
                    ->label('Jenis Kelamin')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Male' => 'info',
                        'Female' => 'success',
                    }),
                TextColumn::make('phone')
                    ->label('Telepon')
                    ->getStateUsing(function ($record) {
                        try {
                            $raw = $record->getRawOriginal('phone');
                            if (empty($raw)) return null;
                            return Crypt::decryptString($raw);
                        } catch (\Throwable $e) {
                            return null;
                        }
                    })
                    ->searchable(),
                TextColumn::make('gmail')
                    ->label('Email')
                    ->getStateUsing(function ($record) {
                        try {
                            $raw = $record->getRawOriginal('gmail');
                            if (empty($raw)) return null;
                            return Crypt::decryptString($raw);
                        } catch (\Throwable $e) {
                            return null;
                        }
                    })
                    ->searchable(),
                TextColumn::make('dateofbirth')
                    ->label('Tanggal Lahir')
                    ->getStateUsing(function ($record) {
                        try {
                            $raw = $record->getRawOriginal('dateofbirth');
                            if (empty($raw)) return null;
                            $decrypted = Crypt::decryptString($raw);
                            return \Illuminate\Support\Carbon::parse($decrypted)->format('d/m/Y');
                        } catch (\Throwable $e) {
                            return null;
                        }
                    })
                    ->sortable(),
                TextColumn::make('city')
                    ->label('Kota')
                    ->searchable(),
                
                TextColumn::make('job_vacancies')
                    ->label('Posisi yang Dilamar')
                    ->getStateUsing(function ($record) {
                        if (!$record->user) {
                            return 'User tidak ditemukan';
                        }
                        
                        // Ambil semua job vacancy yang dilamar
                        $jobVacancies = $record->user->applyJobs()
                            ->with('jobVacancy')
                            ->get()
                            ->map(function ($applyJob) {
                                if ($applyJob->jobVacancy) {
                                    return $applyJob->jobVacancy->job_vacancy_name;
                                }
                                return null;
                            })
                            ->filter()
                            ->unique()
                            ->values()
                            ->toArray();
                        
                        return empty($jobVacancies) ? 'Tidak ada posisi' : implode(', ', $jobVacancies);
                    })
                    ->searchable()
                    ->wrap()
                    ->toggleable(),
                
                TextColumn::make('job_locations')
                    ->label('Lokasi Lamaran')
                    ->getStateUsing(function ($record) {
                        // Periksa apakah user ada
                        if (!$record->user) {
                            return 'User tidak ditemukan';
                        }
                        
                        // Ambil semua lokasi dari job vacancy yang dilamar
                        $locations = $record->user->applyJobs()
                            ->with('jobVacancy')
                            ->get()
                            ->map(function ($applyJob) {
                                if ($applyJob->jobVacancy && $applyJob->jobVacancy->job_vacancy_hris_location_id) {
                                    return Location::getNameByHrisId($applyJob->jobVacancy->job_vacancy_hris_location_id);
                                }
                                return null;
                            })
                            ->filter()
                            ->unique()
                            ->values()
                            ->toArray();
                        
                        return empty($locations) ? 'Tidak ada lokasi' : implode(', ', $locations);
                    })
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('createdat')
                    ->label('Tanggal Lamaran')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('gender')
                    ->label('Jenis Kelamin')
                    ->options([
                        'Male' => 'Laki-laki',
                        'Female' => 'Perempuan',
                    ]),
                
                SelectFilter::make('job_vacancy_id')
                    ->label('Posisi yang Dilamar')
                    ->options(function () {
                        return \App\Models\JobVacancy::query()
                            ->orderBy('job_vacancy_name')
                            ->pluck('job_vacancy_name', 'job_vacancy_id')
                            ->toArray();
                    })
                    ->query(function ($query, $state) {
                        if (!empty($state)) {
                            // Handle single value atau array
                            if (is_array($state)) {
                                $values = array_filter($state);
                            } else {
                                $values = [$state];
                            }
                            
                            if (!empty($values)) {
                                return $query->whereHas('user.applyJobs', function ($q) use ($values) {
                                    $q->whereIn('job_vacancy_id', $values);
                                });
                            }
                        }
                        return $query;
                    })
                    ->indicateUsing(function ($state): ?string {
                        if (empty($state)) {
                            return null;
                        }
                        
                        // Handle single value atau array
                        if (is_array($state)) {
                            $values = array_filter($state);
                        } else {
                            $values = [$state];
                        }
                        
                        if (empty($values)) {
                            return null;
                        }
                        
                        $jobVacancies = \App\Models\JobVacancy::whereIn('job_vacancy_id', $values)
                            ->pluck('job_vacancy_name')
                            ->toArray();
                        
                        return 'Posisi: ' . implode(', ', $jobVacancies);
                    })
                    ->searchable()
                    ->preload()
                    ->multiple(),
                
            ])
            ->persistFiltersInSession()
            ->persistSearchInSession()
            ->defaultSort('createdat', 'desc');
    }
}
