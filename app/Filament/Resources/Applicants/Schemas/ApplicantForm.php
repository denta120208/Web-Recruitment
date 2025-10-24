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
                TextInput::make('firstname')
                    ->label('Nama Depan')
                    ->required(),
                TextInput::make('middlename')
                    ->label('Nama Tengah'),
                TextInput::make('lastname')
                    ->label('Nama Belakang'),
                Select::make('gender')
                    ->label('Jenis Kelamin')
                    ->options([
                        'Male' => 'Laki-laki',
                        'Female' => 'Perempuan',
                    ])
                    ->required(),
                TextInput::make('dateofbirth')
                    ->label('Tanggal Lahir')
                    ->type('date')
                    ->formatStateUsing(function ($state, $record) {
                        if (! $record) return $state;
                        try {
                            $raw = $record->getRawOriginal('dateofbirth');
                            if (empty($raw)) return $state;
                            return Crypt::decryptString($raw);
                        } catch (\Throwable $e) {
                            return $state;
                        }
                    })
                    ->required(),
                TextInput::make('phone')
                    ->label('Nomor Telepon')
                    ->formatStateUsing(function ($state, $record) {
                        if (! $record) return $state;
                        try {
                            $raw = $record->getRawOriginal('phone');
                            if (empty($raw)) return $state;
                            return Crypt::decryptString($raw);
                        } catch (\Throwable $e) {
                            return $state;
                        }
                    })
                    ->required(),
                TextInput::make('gmail')
                    ->label('Email')
                    ->email()
                    ->formatStateUsing(function ($state, $record) {
                        if (! $record) return $state;
                        try {
                            $raw = $record->getRawOriginal('gmail');
                            if (empty($raw)) return $state;
                            return Crypt::decryptString($raw);
                        } catch (\Throwable $e) {
                            return $state;
                        }
                    })
                    ->required(),
                Textarea::make('address')
                    ->label('Alamat Lengkap')
                    ->required(),
                TextInput::make('city')
                    ->label('Kota')
                    ->required(),
                TextInput::make('linkedin')
                    ->label('LinkedIn'),
                TextInput::make('instagram')
                    ->label('Instagram'),
                TextInput::make('cvpath')
                    ->label('CV'),
                TextInput::make('photopath')
                    ->label('Foto'),
                Textarea::make('admin_notes')
                    ->label('Catatan Admin'),
            ]);
    }
}
