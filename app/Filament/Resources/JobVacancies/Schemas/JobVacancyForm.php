<?php

namespace App\Filament\Resources\JobVacancies\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class JobVacancyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('job_vacancy_name')
                    ->label('Nama Posisi')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                
                TextInput::make('job_vacancy_level_name')
                    ->label('Level Posisi')
                    ->maxLength(255),
                
                TextInput::make('job_request_hris_id')
                    ->label('HRIS Request ID')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(),
                
                TextInput::make('job_title_hris_id')
                    ->label('HRIS Title ID')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(),
                
                TextInput::make('job_vacancy_hris_location_id')
                    ->label('HRIS Location ID')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(),
                
                Textarea::make('job_vacancy_job_desc')
                    ->label('Deskripsi Pekerjaan')
                    ->rows(5)
                    ->columnSpanFull(),
                
                Textarea::make('job_vacancy_job_spec')
                    ->label('Spesifikasi/Kualifikasi')
                    ->rows(5)
                    ->columnSpanFull(),
                
                Select::make('job_vacancy_status_id')
                    ->label('Status')
                    ->options([
                        1 => 'Active',
                        2 => 'On Hold',
                        3 => 'Closed',
                        4 => 'Draft',
                    ])
                    ->default(1)
                    ->required(),
                
                DatePicker::make('job_vacancy_start_date')
                    ->label('Tanggal Mulai')
                    ->required()
                    ->default(now())
                    ->displayFormat('d/m/Y')
                    ->native(false),
                
                DatePicker::make('job_vacancy_end_date')
                    ->label('Tanggal Berakhir')
                    ->required()
                    ->after('job_vacancy_start_date')
                    ->displayFormat('d/m/Y')
                    ->native(false),
                
                TextInput::make('job_vacancy_man_power')
                    ->label('Jumlah Kebutuhan')
                    ->numeric()
                    ->default(1)
                    ->required()
                    ->minValue(1),
            ]);
    }
}
