<?php

namespace App\Filament\Resources\Applicants\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
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

                Section::make('Pendidikan (Education)')
                    ->schema([
                        RepeatableEntry::make('educations')
                            ->label('')
                            ->schema([
                                TextEntry::make('institutionname')
                                    ->label('Nama Institusi'),
                                TextEntry::make('major')
                                    ->label('Jurusan'),
                                TextEntry::make('startdate')
                                    ->label('Tanggal Mulai')
                                    ->date('d M Y'),
                                TextEntry::make('enddate')
                                    ->label('Tanggal Selesai')
                                    ->date('d M Y'),
                            ])
                            ->columns(2)
                            ->columnSpanFull()
                            ->placeholder('Belum ada data pendidikan'),
                    ])
                    ->collapsible()
                    ->collapsed(false),

                Section::make('Pengalaman Kerja (Work Experience)')
                    ->schema([
                        RepeatableEntry::make('workExperiences')
                            ->label('')
                            ->schema([
                                TextEntry::make('companyname')
                                    ->label('Nama Perusahaan'),
                                TextEntry::make('joblevel')
                                    ->label('Posisi/Level'),
                                TextEntry::make('startdate')
                                    ->label('Tanggal Mulai')
                                    ->date('d M Y'),
                                TextEntry::make('enddate')
                                    ->label('Tanggal Selesai')
                                    ->date('d M Y')
                                    ->placeholder('Masih bekerja')
                                    ->formatStateUsing(fn ($state, $record) => 
                                        $record->iscurrent ? 'Masih bekerja' : ($state ? date('d M Y', strtotime($state)) : '-')
                                    ),
                                TextEntry::make('salary')
                                    ->label('Gaji')
                                    ->money('IDR')
                                    ->placeholder('-'),
                            ])
                            ->columns(2)
                            ->columnSpanFull()
                            ->placeholder('Belum ada data pengalaman kerja'),
                    ])
                    ->collapsible()
                    ->collapsed(false),

                Section::make('Pelatihan & Sertifikat (Training & Certificate)')
                    ->schema([
                        RepeatableEntry::make('trainings')
                            ->label('')
                            ->schema([
                                TextEntry::make('trainingname')
                                    ->label('Nama Pelatihan/Sertifikat'),
                                TextEntry::make('certificateno')
                                    ->label('No. Sertifikat')
                                    ->placeholder('-'),
                                TextEntry::make('starttrainingdate')
                                    ->label('Tanggal Mulai')
                                    ->date('d M Y')
                                    ->placeholder('-'),
                                TextEntry::make('endtrainingdate')
                                    ->label('Tanggal Selesai')
                                    ->date('d M Y')
                                    ->placeholder('-'),
                            ])
                            ->columns(2)
                            ->columnSpanFull()
                            ->placeholder('Belum ada data pelatihan/sertifikat'),
                    ])
                    ->collapsible()
                    ->collapsed(false),

                Section::make('')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('createdat')->label('Dibuat')->dateTime()->placeholder('-'),
                        TextEntry::make('updatedat')->label('Diperbarui')->dateTime()->placeholder('-'),
                    ]),
            ]);
    }
}
