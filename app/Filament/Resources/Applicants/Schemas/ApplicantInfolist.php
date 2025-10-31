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
                ->icon('heroicon-o-user')
                ->columns(3)
                ->schema([
                    TextEntry::make('firstname')
                        ->label('Nama Depan')
                        ->placeholder('-'),
                    TextEntry::make('middlename')
                        ->label('Nama Tengah')
                        ->placeholder('-'),
                    TextEntry::make('lastname')
                        ->label('Nama Belakang')
                        ->placeholder('-'),
                    TextEntry::make('gender')
                        ->label('Jenis Kelamin')
                        ->placeholder('-'),
                    TextEntry::make('dateofbirth')
                        ->label('Tanggal Lahir')
                        ->date('d M Y')
                        ->placeholder('-')
                        ->columnSpan(2),
                ]),
            
                // Section 1: Identitas - Layout lebih rapi dengan grid 3 kolom
                Section::make('Pengalaman Kerja (Work Experience)')
                ->icon('heroicon-o-briefcase')
                ->columns(1)
                ->schema([
                    RepeatableEntry::make('workExperiences')
                        ->label('')
                        ->schema([
                            TextEntry::make('companyname')
                                ->label('Nama Perusahaan'),
                            TextEntry::make('joblevel')
                                ->label('Posisi/Level')
                                ->placeholder('-'),
                            TextEntry::make('startdate')
                                ->label('Tanggal Mulai')
                                ->date('d M Y')
                                ->placeholder('-'),
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
                        ->columns(1)
                        ->columnSpanFull()
                        ->placeholder('Belum ada data pengalaman kerja'),
                ])
                ->collapsible()
                ->collapsed(false),
                // Section 2: Kontak - Layout lebih compact
               

                // Section 3: Dokumen - Grid 2 kolom
                Section::make('Kontak')
                ->icon('heroicon-o-phone')
                ->columns(1)
                ->schema([
                    TextEntry::make('gmail')
                        ->label('Email')
                        ->placeholder('-'),
                    TextEntry::make('phone')
                        ->label('No. HP')
                        ->placeholder('-'),
                    TextEntry::make('linkedin')
                        ->label('LinkedIn')
                        ->placeholder('-'),
                    TextEntry::make('instagram')
                        ->label('Instagram')
                        ->placeholder('-'),
                    TextEntry::make('address')
                        ->label('Alamat')
                        ->placeholder('-'),
                    TextEntry::make('city')
                        ->label('Kota')
                        ->placeholder('-'),
                ]),

                // Section 4: Pendidikan - Layout lebih compact
                Section::make('Pendidikan (Education)')
                    ->icon('heroicon-o-academic-cap')
                    ->columns(1)
                    ->schema([
                        RepeatableEntry::make('educations')
                            ->label('')
                            ->schema([
                                TextEntry::make('institutionname')
                                    ->label('Nama Institusi'),
                                TextEntry::make('major')
                                    ->label('Jurusan')
                                    ->placeholder('-'),
                                TextEntry::make('startdate')
                                    ->label('Tanggal Mulai')
                                    ->date('d M Y')
                                    ->placeholder('-'),
                                TextEntry::make('enddate')
                                    ->label('Tanggal Selesai')
                                    ->date('d M Y')
                                    ->placeholder('-'),
                            ])
                            ->columns(1)
                            ->columnSpanFull()
                            ->placeholder('Belum ada data pendidikan'),
                    ])
                    ->collapsible()
                    ->collapsed(false),

                // Section 5: Pengalaman Kerja - Layout lebih compact
                 Section::make('Dokumen')
                ->icon('heroicon-o-document')
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

                // Section 6: Pelatihan & Sertifikat - Layout lebih compact
                Section::make('Pelatihan & Sertifikat (Training & Certificate)')
                    ->icon('heroicon-o-trophy')
                    ->columns(1)
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
                            ->columns(1)
                            ->columnSpanFull()
                            ->placeholder('Belum ada data pelatihan/sertifikat'),
                    ])
                    ->collapsible()
                    ->collapsed(false),

                // Section 7: Metadata - Grid 2 kolom di footer
                Section::make('Informasi Sistem')
                    ->icon('heroicon-o-information-circle')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('createdat')
                            ->label('Dibuat')
                            ->dateTime('d M Y H:i:s')
                            ->placeholder('-'),
                        TextEntry::make('updatedat')
                            ->label('Diperbarui')
                            ->dateTime('d M Y H:i:s')
                            ->placeholder('-'),
                    ])
                    ->collapsible()
                    ->collapsed(true),
            ]);
    }
}
