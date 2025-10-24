<?php

namespace App\Filament\Resources\ApplyJobs\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
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
                    ->default(1),
                TextInput::make('apply_jobs_interview_by'),
                Textarea::make('apply_jobs_interview_result')
                    ->columnSpanFull(),
                Textarea::make('apply_jobs_interview_ai_result')
                    ->columnSpanFull()
                    ->disabled(),
                Select::make('apply_jobs_interview_status')
                    ->label('Interview Status')
                    ->relationship('interviewStatus', 'interview_status_name')
                    ->searchable()
                    ->preload()
                    ->default(0),
                TextInput::make('apply_jobs_psikotest_iq_num')
                    ->required()
                    ->numeric()
                    ->default(0),
                FileUpload::make('apply_jobs_psikotest_file')
                    ->label('File Psikotest')
                    ->disk('mlnas')
                    ->directory('psikotest-files')
                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/*'])
                    ->maxSize(10240)
                    ->downloadable()
                    ->openable()
                    ->previewable(false)
                    ->visibility('public'),
                Select::make('apply_jobs_psikotest_status')
                    ->label('Status Psikotes')
                    ->options([
                        1 => 'Approve',
                        2 => 'Considered',
                        3 => 'Reject'
                    ])
                    ->default(0),
                TextInput::make('require_id')
                    ->numeric()
                    ->hidden(),
            ]);
    }
}
