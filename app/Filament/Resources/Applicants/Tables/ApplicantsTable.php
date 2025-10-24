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
