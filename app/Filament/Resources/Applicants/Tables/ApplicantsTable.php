<?php

namespace App\Filament\Resources\Applicants\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Crypt;

class ApplicantsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('RequireID')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('full_name')
                    ->label('Nama Lengkap')
                    ->getStateUsing(function ($record): string {
                        return trim($record->FirstName . ' ' . $record->MiddleName . ' ' . $record->LastName);
                    })
                    ->searchable(['FirstName', 'MiddleName', 'LastName']),
                TextColumn::make('Gender')
                    ->label('Jenis Kelamin')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Male' => 'info',
                        'Female' => 'success',
                    }),
                TextColumn::make('Phone')
                    ->label('Telepon')
                    ->getStateUsing(function ($record) {
                        try {
                            $raw = $record->getRawOriginal('Phone');
                            if (empty($raw)) return null;
                            return Crypt::decryptString($raw);
                        } catch (\Throwable $e) {
                            return null;
                        }
                    })
                    ->searchable(),
                TextColumn::make('Gmail')
                    ->label('Email')
                    ->getStateUsing(function ($record) {
                        try {
                            $raw = $record->getRawOriginal('Gmail');
                            if (empty($raw)) return null;
                            return Crypt::decryptString($raw);
                        } catch (\Throwable $e) {
                            return null;
                        }
                    })
                    ->searchable(),
                TextColumn::make('DateOfBirth')
                    ->label('Tanggal Lahir')
                    ->getStateUsing(function ($record) {
                        try {
                            $raw = $record->getRawOriginal('DateOfBirth');
                            if (empty($raw)) return null;
                            $decrypted = Crypt::decryptString($raw);
                            return \Illuminate\Support\Carbon::parse($decrypted)->format('d/m/Y');
                        } catch (\Throwable $e) {
                            return null;
                        }
                    })
                    ->sortable(),
                TextColumn::make('City')
                    ->label('Kota')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(fn ($record): string => $record->status_label)
                    ->badge()
                    ->color(fn ($record): string => $record->status_color),
                TextColumn::make('CreatedAt')
                    ->label('Tanggal Lamaran')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('status_updated_at')
                    ->label('Terakhir Diupdate')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Menunggu Review',
                        'under_review' => 'Sedang Direview',
                        'interview_scheduled' => 'Interview Dijadwalkan',
                        'accepted' => 'Diterima',
                        'rejected' => 'Ditolak',
                        'hired' => 'Sudah Dipekerjakan',
                    ]),
                SelectFilter::make('Gender')
                    ->label('Jenis Kelamin')
                    ->options([
                        'Male' => 'Laki-laki',
                        'Female' => 'Perempuan',
                    ]),
            ])
            ->defaultSort('CreatedAt', 'desc');
    }
}
