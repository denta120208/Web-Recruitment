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
                    TextEntry::make('marital_status')
                        ->label('Status Perkawinan')
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
                            TextEntry::make('eexp_comments')
                                ->label('Alasan Keluar')
                                ->placeholder('-'),
                            TextEntry::make('jobdesk')
                                ->label('Jobdesk / Tugas & Tanggung Jawab')
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

                Section::make('Referensi')
                    ->icon('heroicon-o-users')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('ref1_name')->label('Ref 1 - Nama')->placeholder('-'),
                        TextEntry::make('ref1_address_phone')->label('Ref 1 - Alamat & Telepon')->placeholder('-'),
                        TextEntry::make('ref1_occupation')->label('Ref 1 - Pekerjaan')->placeholder('-'),
                        TextEntry::make('ref1_relationship')->label('Ref 1 - Hubungan')->placeholder('-'),

                        TextEntry::make('ref2_name')->label('Ref 2 - Nama')->placeholder('-'),
                        TextEntry::make('ref2_address_phone')->label('Ref 2 - Alamat & Telepon')->placeholder('-'),
                        TextEntry::make('ref2_occupation')->label('Ref 2 - Pekerjaan')->placeholder('-'),
                        TextEntry::make('ref2_relationship')->label('Ref 2 - Hubungan')->placeholder('-'),

                        TextEntry::make('ref3_name')->label('Ref 3 - Nama')->placeholder('-'),
                        TextEntry::make('ref3_address_phone')->label('Ref 3 - Alamat & Telepon')->placeholder('-'),
                        TextEntry::make('ref3_occupation')->label('Ref 3 - Pekerjaan')->placeholder('-'),
                        TextEntry::make('ref3_relationship')->label('Ref 3 - Hubungan')->placeholder('-'),
                    ]),

                Section::make('Kontak Darurat')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('emergency1_name')->label('Darurat 1 - Nama')->placeholder('-'),
                        TextEntry::make('emergency1_address')->label('Darurat 1 - Alamat')->placeholder('-'),
                        TextEntry::make('emergency1_phone')->label('Darurat 1 - Telepon')->placeholder('-'),
                        TextEntry::make('emergency1_relationship')->label('Darurat 1 - Hubungan')->placeholder('-'),

                        TextEntry::make('emergency2_name')->label('Darurat 2 - Nama')->placeholder('-'),
                        TextEntry::make('emergency2_address')->label('Darurat 2 - Alamat')->placeholder('-'),
                        TextEntry::make('emergency2_phone')->label('Darurat 2 - Telepon')->placeholder('-'),
                        TextEntry::make('emergency2_relationship')->label('Darurat 2 - Hubungan')->placeholder('-'),
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

                Section::make('Pertanyaan Tambahan')
                    ->icon('heroicon-o-question-mark-circle')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('is_fresh_graduate')
                            ->label('Fresh Graduate')
                            ->formatStateUsing(fn ($state) => $state ? 'Ya' : 'Tidak')
                            ->placeholder('-'),
                        TextEntry::make('q11_willing_outside_jakarta')
                            ->label('Bersedia ditempatkan di luar Jakarta (Q11)')
                            ->formatStateUsing(fn ($state) => is_null($state) ? '-' : ($state ? 'Ya' : 'Tidak')),
                        TextEntry::make('q14_current_income')
                            ->label('Q14 - Penghasilan & fasilitas saat ini')
                            ->placeholder('-')
                            ->columnSpan(2),
                        TextEntry::make('q15_expected_income')
                            ->label('Q15 - Penghasilan & fasilitas yang diharapkan')
                            ->placeholder('-')
                            ->columnSpan(2),
                        TextEntry::make('q16_available_from')
                            ->label('Q16 - Siap mulai bekerja dari')
                            ->placeholder('-')
                            ->columnSpan(2),
                    ]),

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
