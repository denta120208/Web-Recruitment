<?php

namespace App\Filament\Resources\Applicants\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Crypt;

class ApplicantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('FirstName')
                    ->label('Nama Depan')
                    ->required(),
                TextInput::make('MiddleName')
                    ->label('Nama Tengah'),
                TextInput::make('LastName')
                    ->label('Nama Belakang'),
                Select::make('Gender')
                    ->label('Jenis Kelamin')
                    ->options([
                        'Male' => 'Laki-laki',
                        'Female' => 'Perempuan',
                    ])
                    ->required(),
                TextInput::make('DateOfBirth')
                    ->label('Tanggal Lahir')
                    ->type('date')
                    ->formatStateUsing(function ($state, $record) {
                        if (! $record) return $state;
                        try {
                            $raw = $record->getRawOriginal('DateOfBirth');
                            if (empty($raw)) return $state;
                            return Crypt::decryptString($raw);
                        } catch (\Throwable $e) {
                            return $state;
                        }
                    })
                    ->required(),
                TextInput::make('Phone')
                    ->label('Nomor Telepon')
                    ->formatStateUsing(function ($state, $record) {
                        if (! $record) return $state;
                        try {
                            $raw = $record->getRawOriginal('Phone');
                            if (empty($raw)) return $state;
                            return Crypt::decryptString($raw);
                        } catch (\Throwable $e) {
                            return $state;
                        }
                    })
                    ->required(),
                TextInput::make('Gmail')
                    ->label('Email')
                    ->email()
                    ->formatStateUsing(function ($state, $record) {
                        if (! $record) return $state;
                        try {
                            $raw = $record->getRawOriginal('Gmail');
                            if (empty($raw)) return $state;
                            return Crypt::decryptString($raw);
                        } catch (\Throwable $e) {
                            return $state;
                        }
                    })
                    ->required(),
                Textarea::make('Address')
                    ->label('Alamat Lengkap')
                    ->required(),
                TextInput::make('City')
                    ->label('Kota')
                    ->required(),
                TextInput::make('LinkedIn')
                    ->label('LinkedIn'),
                TextInput::make('Instagram')
                    ->label('Instagram'),
                TextInput::make('CVPath')
                    ->label('CV'),
                TextInput::make('PhotoPath')
                    ->label('Foto'),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Menunggu Review',
                        'under_review' => 'Sedang Direview',
                        'interview_scheduled' => 'Interview Dijadwalkan',
                        'accepted' => 'Diterima',
                        'rejected' => 'Ditolak',
                        'hired' => 'Sudah Dipekerjakan',
                    ])
                    ->default('pending')
                    ->required(),
                Textarea::make('admin_notes')
                    ->label('Catatan Admin'),
            ]);
    }
}
