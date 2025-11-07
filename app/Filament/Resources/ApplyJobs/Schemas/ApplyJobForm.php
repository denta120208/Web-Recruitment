<?php

namespace App\Filament\Resources\ApplyJobs\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class ApplyJobForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('job_vacancy_id')
                    ->relationship('jobVacancy', 'job_vacancy_name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('user_id')
                    ->label('User')
                    ->options(function () {
                        // Load ALL users with their applicant firstname
                        return \App\Models\Applicant::with('user')
                            ->get()
                            ->filter(fn($applicant) => $applicant->user_id !== null)
                            ->mapWithKeys(function ($applicant) {
                                return [$applicant->user_id => $applicant->firstname];
                            })
                            ->sort();
                    })
                    ->required()
                    ->searchable()
                    ->disabledOn('edit'),
                Select::make('apply_jobs_status')
                    ->label('Status Lamaran')
                    ->options([
                        1 => 'Review Aplicant',
                        2 => 'Interview User',
                        3 => 'Psikotest',
                        4 => 'Offering Letter',
                        6 => 'MCU',
                        5 => 'Hired'
                    ])
                    ->required()
                    ->default(1)
                    ->live(),
                TextInput::make('apply_jobs_interview_by')
                    ->label('Apply Jobs Interview by')
                    ->visible(fn ($get) => in_array($get('apply_jobs_status'), [2, 5])),
                Textarea::make('apply_jobs_interview_result')
                    ->label('Apply Jobs Interview Result')
                    ->columnSpanFull()
                    ->visible(fn ($get) => in_array($get('apply_jobs_status'), [2, 5])),
                Select::make('apply_jobs_interview_status')
                    ->label('Interview Status')
                    ->relationship('interviewStatus', 'interview_status_name')
                    ->searchable()
                    ->preload()
                    ->placeholder('Choose Interview Status')
                    ->native(false)
                    ->visible(fn ($get) => in_array($get('apply_jobs_status'), [2, 5])),
                Textarea::make('apply_jobs_interview_ai_result')
                    ->label('Apply Jobs Interview AI Result')
                    ->columnSpanFull()
                    ->disabled()
                    ->visible(fn ($get) => in_array($get('apply_jobs_status'), [2, 5])),
                Textarea::make('apply_jobs_interview_location')
                    ->label('Interview Location')
                    ->placeholder('Masukkan lokasi atau URL meeting (contoh: https://zoom.us/j/123456)')
                    ->rows(2)
                    ->helperText('Anda bisa memasukkan alamat fisik atau URL meeting online (Zoom, Google Meet, dll)')
                    ->visible(fn ($get) => in_array($get('apply_jobs_status'), [2, 5])),
                DatePicker::make('apply_jobs_interview_date')
                    ->label('Interview Date')
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->visible(fn ($get) => in_array($get('apply_jobs_status'), [2, 5])),
                TimePicker::make('apply_jobs_interview_time')
                    ->label('Interview Time')
                    ->native(false)
                    ->seconds(false)
                    ->visible(fn ($get) => in_array($get('apply_jobs_status'), [2, 5])),
                Select::make('apply_jobs_psikotest_status')
                    ->label('Status Psikotes')
                    ->options([
                        1 => 'Approve',
                        2 => 'Considered',
                        3 => 'Reject'
                    ])
                    ->default(0)
                    ->visible(fn ($get) => in_array($get('apply_jobs_status'), [3, 5])),
                TextInput::make('apply_jobs_psikotest_iq_num')
                    ->label('Apply Jobs Psikotest IQ Num')
                    ->numeric()
                    ->default(0)
                    ->visible(fn ($get) => in_array($get('apply_jobs_status'), [3, 5])),
                FileUpload::make('apply_jobs_psikotest_file')
                    ->label('File Psikotes')
                    ->disk('mlnas')
                    ->directory('psikotest')
                    ->acceptedFileTypes(['application/pdf', 'image/*'])
                    ->maxSize(10240)
                    ->preserveFilenames()
                    ->visible(fn ($get) => in_array($get('apply_jobs_status'), [3, 5]))
                    ->helperText(fn ($record) => $record && $record->apply_jobs_psikotest_file 
                        ? new \Illuminate\Support\HtmlString('Upload file hasil psikotes (PDF atau gambar, max 10MB) | <a href="' . route('admin.file.apply-job', ['path' => $record->apply_jobs_psikotest_file]) . '" class="text-primary-600 hover:underline font-semibold">📥 Download File Saat Ini</a>')
                        : 'Upload file hasil psikotes (PDF atau gambar, max 10MB)'
                    ),
                FileUpload::make('apply_jobs_mcu_file')
                    ->label('Upload MCU')
                    ->disk('mlnas')
                    ->directory('mcu')
                    ->acceptedFileTypes(['application/pdf', 'image/*'])
                    ->maxSize(10240)
                    ->preserveFilenames()
                    ->visible(fn ($get) => in_array($get('apply_jobs_status'), [6, 5]))
                    ->helperText(fn ($record) => $record && $record->apply_jobs_mcu_file 
                        ? new \Illuminate\Support\HtmlString('Upload file hasil MCU (PDF atau gambar, max 10MB) | <a href="' . route('admin.file.apply-job', ['path' => $record->apply_jobs_mcu_file]) . '" class="text-primary-600 hover:underline font-semibold">📥 Download File Saat Ini</a>')
                        : 'Upload file hasil MCU (PDF atau gambar, max 10MB)'
                    ),
                FileUpload::make('apply_jobs_offering_letter_file')
                    ->label('Upload Offering')
                    ->disk('mlnas')
                    ->directory('offering_letter')
                    ->acceptedFileTypes(['application/pdf', 'image/*'])
                    ->maxSize(10240)
                    ->preserveFilenames()
                    ->visible(fn ($get) => in_array($get('apply_jobs_status'), [4, 5]))
                    ->helperText(fn ($record) => $record && $record->apply_jobs_offering_letter_file 
                        ? new \Illuminate\Support\HtmlString('Upload file offering letter (PDF atau gambar, max 10MB) | <a href="' . route('admin.file.apply-job', ['path' => $record->apply_jobs_offering_letter_file]) . '" class="text-primary-600 hover:underline font-semibold">📥 Download File Saat Ini</a>')
                        : 'Upload file offering letter (PDF atau gambar, max 10MB)'
                    ),
                TextInput::make('require_id')
                    ->numeric()
                    ->hidden(),
            ]);
    }
}
