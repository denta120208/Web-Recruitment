<?php

namespace App\Filament\Resources\ApplyJobs\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
// no additional imports needed

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
                Textarea::make('apply_jobs_interview_AI_result')
                    ->columnSpanFull()
                    ->disabled(),
                TextInput::make('apply_jobs_interview_status')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('apply_jobs_psikotest_iq_num')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('apply_jobs_psikotest_file'),
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
