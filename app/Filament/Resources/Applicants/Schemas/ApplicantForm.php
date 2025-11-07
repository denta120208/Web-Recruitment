<?php

namespace App\Filament\Resources\Applicants\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Crypt;

class ApplicantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                // Header for Personal Info
                Placeholder::make('section_personal')
                    ->label('INFORMASI PRIBADI')
                    ->content('')
                    ->columnSpanFull(),
                
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
                    ->required()
                    ->columnSpanFull(),
                
                // Header for Contact
                Placeholder::make('section_contact')
                    ->label('KONTAK')
                    ->content('')
                    ->columnSpanFull(),
                
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
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('city')
                    ->label('Kota')
                    ->required(),
                TextInput::make('linkedin')
                    ->label('LinkedIn')
                    ->prefix('https://linkedin.com/in/')
                    ->placeholder('username'),
                TextInput::make('instagram')
                    ->label('Instagram')
                    ->prefix('@')
                    ->placeholder('username'),
                
                // Header for Documents
                Placeholder::make('section_documents')
                    ->label('UPLOAD DOKUMEN')
                    ->content('Upload CV dan Foto diri applicant')
                    ->columnSpanFull(),
                
                FileUpload::make('cvpath')
                    ->label('CV')
                    ->disk('mlnas')
                    ->directory('cv')
                    ->acceptedFileTypes(['application/pdf'])
                    ->maxSize(10240)
                    ->preserveFilenames()
                    ->helperText('Upload file CV dalam format PDF (max 10MB)')
                    ->downloadable()
                    ->openable()
                    ->columnSpanFull(),
                FileUpload::make('photopath')
                    ->label('Foto Diri')
                    ->disk('mlnas')
                    ->directory('photos')
                    ->image()
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        '1:1',
                        '4:3',
                    ])
                    ->maxSize(5120)
                    ->preserveFilenames()
                    ->helperText('Upload foto diri dalam format gambar (max 5MB)')
                    ->downloadable()
                    ->openable()
                    ->columnSpanFull(),
                
                // Header for Education
                Placeholder::make('section_education')
                    ->label('RIWAYAT PENDIDIKAN')
                    ->content('Tambahkan riwayat pendidikan applicant')
                    ->columnSpanFull(),
                
                Repeater::make('educations')
                    ->relationship('educations')
                    ->schema([
                        Select::make('education_id')
                            ->label('Tingkat Pendidikan')
                            ->options([
                                1 => 'SD',
                                2 => 'SMP',
                                3 => 'SMA/SMK',
                                4 => 'D3',
                                5 => 'S1',
                                6 => 'S2',
                                7 => 'S3',
                            ])
                            ->required(),
                        TextInput::make('institutionname')
                            ->label('Nama Institusi')
                            ->placeholder('Contoh: Universitas Indonesia')
                            ->required(),
                        TextInput::make('major')
                            ->label('Jurusan/Bidang Studi')
                            ->placeholder('Contoh: Teknik Informatika'),
                        TextInput::make('year')
                            ->label('Tahun Lulus')
                            ->numeric()
                            ->placeholder('Contoh: 2020'),
                        TextInput::make('score')
                            ->label('IPK/Nilai')
                            ->numeric()
                            ->step(0.01)
                            ->placeholder('Contoh: 3.75'),
                        DatePicker::make('startdate')
                            ->label('Tanggal Mulai')
                            ->native(false)
                            ->displayFormat('d/m/Y'),
                        DatePicker::make('enddate')
                            ->label('Tanggal Selesai')
                            ->native(false)
                            ->displayFormat('d/m/Y'),
                    ])
                    ->columns(2)
                    ->defaultItems(0)
                    ->addActionLabel('Tambah Pendidikan')
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['institutionname'] ?? 'Pendidikan Baru')
                    ->columnSpanFull()
                    ->hiddenOn('edit'),
                
                // Header for Work Experience
                Placeholder::make('section_work_experience')
                    ->label('PENGALAMAN KERJA')
                    ->content('Tambahkan pengalaman kerja applicant')
                    ->columnSpanFull(),
                
                Repeater::make('workExperiences')
                    ->relationship('workExperiences')
                    ->schema([
                        TextInput::make('companyname')
                            ->label('Nama Perusahaan')
                            ->placeholder('Contoh: PT. ABC Indonesia')
                            ->required(),
                        TextInput::make('joblevel')
                            ->label('Posisi/Jabatan')
                            ->placeholder('Contoh: Software Engineer')
                            ->required(),
                        DatePicker::make('startdate')
                            ->label('Tanggal Mulai')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->required(),
                        DatePicker::make('enddate')
                            ->label('Tanggal Selesai')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->disabled(fn ($get) => $get('iscurrent') === true),
                        Toggle::make('iscurrent')
                            ->label('Masih Bekerja')
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $set('enddate', null);
                                }
                            }),
                        TextInput::make('salary')
                            ->label('Gaji')
                            ->numeric()
                            ->prefix('Rp')
                            ->placeholder('Contoh: 5000000'),
                        Textarea::make('eexp_comments')
                            ->label('Deskripsi Pekerjaan')
                            ->placeholder('Jelaskan tugas dan tanggung jawab Anda...')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->defaultItems(0)
                    ->addActionLabel('Tambah Pengalaman Kerja')
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['companyname'] ?? 'Pengalaman Baru')
                    ->columnSpanFull()
                    ->hiddenOn('edit'),
                
                // Header for Training/Certification
                Placeholder::make('section_training')
                    ->label('SERTIFIKAT & PELATIHAN')
                    ->content('Tambahkan sertifikat dan pelatihan yang dimiliki')
                    ->columnSpanFull(),
                
                Repeater::make('trainings')
                    ->relationship('trainings')
                    ->schema([
                        TextInput::make('trainingname')
                            ->label('Nama Pelatihan/Sertifikat')
                            ->placeholder('Contoh: AWS Certified Solutions Architect')
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('certificateno')
                            ->label('Nomor Sertifikat')
                            ->placeholder('Contoh: AWS-123456'),
                        DatePicker::make('starttrainingdate')
                            ->label('Tanggal Mulai')
                            ->native(false)
                            ->displayFormat('d/m/Y'),
                        DatePicker::make('endtrainingdate')
                            ->label('Tanggal Selesai')
                            ->native(false)
                            ->displayFormat('d/m/Y'),
                    ])
                    ->columns(2)
                    ->defaultItems(0)
                    ->addActionLabel('Tambah Sertifikat/Pelatihan')
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['trainingname'] ?? 'Sertifikat Baru')
                    ->columnSpanFull()
                    ->hiddenOn('edit'),
                
                // Header for Job Application
                Placeholder::make('section_job_application')
                    ->label('LAMARAN PEKERJAAN')
                    ->content('Tambahkan applicant ini ke lowongan pekerjaan')
                    ->columnSpanFull()
                    ->hiddenOn('edit'),
                
                Toggle::make('create_apply_job')
                    ->label('Daftarkan ke Lowongan Pekerjaan')
                    ->helperText('Aktifkan untuk langsung mendaftarkan applicant ini ke lowongan pekerjaan')
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if (!$state) {
                            $set('job_vacancy_id', null);
                        }
                    })
                    ->columnSpanFull()
                    ->hiddenOn('edit'),
                Select::make('job_vacancy_id')
                    ->label('Pilih Lowongan Pekerjaan')
                    ->options(function () {
                        // Get active job vacancies using the active scope
                        return \App\Models\JobVacancy::active()
                            ->orderBy('job_vacancy_name')
                            ->pluck('job_vacancy_name', 'job_vacancy_id');
                    })
                    ->searchable()
                    ->placeholder('Pilih lowongan yang sesuai')
                    ->visible(fn ($get) => $get('create_apply_job') === true)
                    ->required(fn ($get) => $get('create_apply_job') === true)
                    ->helperText('Pilih lowongan pekerjaan yang akan dilamar oleh applicant ini')
                    ->columnSpanFull()
                    ->hiddenOn('edit'),
                
                // Header for Admin Notes
                Placeholder::make('section_notes')
                    ->label('CATATAN ADMIN')
                    ->content('')
                    ->columnSpanFull(),
                
                Textarea::make('admin_notes')
                    ->label('Catatan Admin')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}
