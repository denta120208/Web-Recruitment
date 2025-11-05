<?php

namespace App\Filament\Resources\ApplyJobs\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
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
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->disabledOn('edit'),
                Select::make('apply_jobs_status')
                    ->label('Status Lamaran')
                    ->options([
                        1 => 'Review Aplicant',
                        2 => 'Interview User',
                        3 => 'Psikotest',
                        4 => 'Offering Letter',
                        5 => 'Hired'
                    ])
                    ->required()
                    ->default(1)
                    ->live(),
                TextInput::make('apply_jobs_interview_by')
                    ->label('Apply Jobs Interview by'),
                Textarea::make('apply_jobs_interview_result')
                    ->label('Apply Jobs Interview Result')
                    ->columnSpanFull(),
                Textarea::make('apply_jobs_interview_ai_result')
                    ->label('Apply Jobs Interview AI Result')
                    ->columnSpanFull()
                    ->disabled(),
                Select::make('apply_jobs_interview_status')
                    ->label('Interview Status')
                    ->relationship('interviewStatus', 'interview_status_name')
                    ->searchable()
                    ->preload()
                    ->default(0),
                Select::make('apply_jobs_psikotest_status')
                    ->label('Status Psikotes')
                    ->options([
                        1 => 'Approve',
                        2 => 'Considered',
                        3 => 'Reject'
                    ])
                    ->default(0),
                TextInput::make('apply_jobs_psikotest_iq_num')
                    ->label('Apply Jobs Psikotest IQ Num')
                    ->required()
                    ->numeric()
                    ->default(0),
                FileUpload::make('apply_jobs_psikotest_file')
                    ->label('File Psikotes')
                    ->disk('mlnas')
                    ->directory('psikotest')
                    ->acceptedFileTypes(['application/pdf', 'image/*'])
                    ->maxSize(10240)
                    ->preserveFilenames()
                    ->helperText(fn ($record) => $record && $record->apply_jobs_psikotest_file 
                        ? new \Illuminate\Support\HtmlString('Upload file hasil psikotes (PDF atau gambar, max 10MB) | <a href="' . route('admin.file.apply-job', ['path' => $record->apply_jobs_psikotest_file]) . '" class="text-primary-600 hover:underline font-semibold">📥 Download File Saat Ini</a>')
                        : 'Upload file hasil psikotes (PDF atau gambar, max 10MB)'
                    ),
                FileUpload::make('apply_jobs_mcu_file')
                    ->label('File MCU')
                    ->disk('mlnas')
                    ->directory('mcu')
                    ->acceptedFileTypes(['application/pdf', 'image/*'])
                    ->maxSize(10240)
                    ->preserveFilenames()
                    ->visible(fn ($get) => in_array($get('apply_jobs_status'), [4, 5]))
                    ->helperText(fn ($record) => $record && $record->apply_jobs_mcu_file 
                        ? new \Illuminate\Support\HtmlString('Upload file hasil MCU (PDF atau gambar, max 10MB) | <a href="' . route('admin.file.apply-job', ['path' => $record->apply_jobs_mcu_file]) . '" class="text-primary-600 hover:underline font-semibold">📥 Download File Saat Ini</a>')
                        : 'Upload file hasil MCU (PDF atau gambar, max 10MB)'
                    ),
                FileUpload::make('apply_jobs_offering_letter_file')
                    ->label('File Offering Letter')
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
