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
                
            ])
            ->persistFiltersInSession()
            ->persistSearchInSession()
            ->defaultSort('createdat', 'desc');
    }
}
