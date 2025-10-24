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
                        TextEntry::make('firstname')->label('Nama Depan'),
                        TextEntry::make('middlename')->label('Nama Tengah')->placeholder('-'),
                        TextEntry::make('lastname')->label('Nama Belakang')->placeholder('-'),
                        TextEntry::make('gender')->label('Jenis Kelamin')->placeholder('-'),
                        TextEntry::make('dateofbirth')->label('Tanggal Lahir')->placeholder('-')->columnSpanFull(),
                    ]),

                Section::make('Kontak')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('gmail')->label('Email')->placeholder('-')->columnSpanFull(),
                        TextEntry::make('phone')->label('No. HP')->placeholder('-')->columnSpanFull(),
                        TextEntry::make('linkedin')->placeholder('-'),
                        TextEntry::make('instagram')->placeholder('-'),
                        TextEntry::make('address')->label('Alamat')->placeholder('-')->columnSpanFull(),
                        TextEntry::make('city')->label('Kota')->placeholder('-'),
                    ]),

                Section::make('Dokumen')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('cvpath')
                            ->label('CV')
                            ->html()
                            ->state(function ($record) {
                                if (empty($record->cvpath)) {
                                    return '<span class="text-gray-500">-</span>';
                                }
                                $url = route('admin.file.applicant', ['requireId' => $record->getKey(), 'type' => 'cv']);
                                return '<a href="' . e($url) . '" class="fi-btn fi-btn-size-sm fi-color-primary" download>Download CV</a>';
                            }),
                        TextEntry::make('photopath')
                            ->label('Foto diri')
                            ->html()
                            ->state(function ($record) {
                                if (empty($record->photopath)) {
                                    return '<span class="text-gray-500">-</span>';
                                }
                                $url = route('admin.file.applicant', ['requireId' => $record->getKey(), 'type' => 'photo']);
                                return '<a href="' . e($url) . '" target="_blank" class="fi-btn fi-btn-size-sm fi-color-primary">Lihat Foto</a>';
                            }),
                    ]),

                Section::make('')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('createdat')->label('Dibuat')->dateTime()->placeholder('-'),
                        TextEntry::make('updatedat')->label('Diperbarui')->dateTime()->placeholder('-'),
                    ]),
            ]);
    }
}
