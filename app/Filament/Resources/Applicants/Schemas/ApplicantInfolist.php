<?php

namespace App\Filament\Resources\Applicants\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ApplicantInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('FirstName')->label('Nama Depan'),
                        TextEntry::make('MiddleName')->label('Nama Tengah')->placeholder('-'),
                        TextEntry::make('LastName')->label('Nama Belakang')->placeholder('-'),
                        TextEntry::make('Gender')->label('Jenis Kelamin')->placeholder('-'),
                        TextEntry::make('DateOfBirth')->label('Tanggal Lahir')->placeholder('-')->columnSpanFull(),
                    ]),

                Section::make('Kontak')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('Gmail')->label('Email')->placeholder('-')->columnSpanFull(),
                        TextEntry::make('Phone')->label('No. HP')->placeholder('-')->columnSpanFull(),
                        TextEntry::make('LinkedIn')->placeholder('-'),
                        TextEntry::make('Instagram')->placeholder('-'),
                        TextEntry::make('Address')->label('Alamat')->placeholder('-')->columnSpanFull(),
                        TextEntry::make('City')->label('Kota')->placeholder('-'),
                    ]),

                Section::make('Dokumen')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('CVPath')
                            ->label('CV')
                            ->html()
                            ->state(function ($record) {
                                if (empty($record->CVPath)) {
                                    return '<span class="text-gray-500">-</span>';
                                }
                                $url = route('admin.file.applicant', ['requireId' => $record->RequireID, 'type' => 'cv']);
                                return '<a href="' . e($url) . '" class="fi-btn fi-btn-size-sm fi-color-primary" download>Download CV</a>';
                            }),
                        TextEntry::make('PhotoPath')
                            ->label('Foto diri')
                            ->html()
                            ->state(function ($record) {
                                if (empty($record->PhotoPath)) {
                                    return '<span class="text-gray-500">-</span>';
                                }
                                $url = route('admin.file.applicant', ['requireId' => $record->RequireID, 'type' => 'photo']);
                                return '<a href="' . e($url) . '" target="_blank" class="fi-btn fi-btn-size-sm fi-color-primary">Lihat Foto</a>';
                            }),
                    ]),

                Section::make('')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('CreatedAt')->label('Dibuat')->dateTime()->placeholder('-'),
                        TextEntry::make('UpdatedAt')->label('Diperbarui')->dateTime()->placeholder('-'),
                    ]),
            ]);
    }
}
