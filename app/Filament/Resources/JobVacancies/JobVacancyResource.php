<?php

namespace App\Filament\Resources\JobVacancies;

use App\Filament\Resources\JobVacancies\Pages\CreateJobVacancy;
use App\Filament\Resources\JobVacancies\Pages\EditJobVacancy;
use App\Filament\Resources\JobVacancies\Pages\ListJobVacancies;
use App\Filament\Resources\JobVacancies\Schemas\JobVacancyForm;
use App\Filament\Resources\JobVacancies\Tables\JobVacanciesTable;
use App\Models\JobVacancy;
use App\Traits\LocationFilterTrait;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class JobVacancyResource extends Resource
{
    use LocationFilterTrait;
    
    protected static ?string $model = JobVacancy::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $recordTitleAttribute = 'job_vacancy_name';
    
    protected static ?string $navigationLabel = 'Job Vacancies';
    
    protected static ?string $modelLabel = 'Job Vacancy';
    
    protected static ?string $pluralModelLabel = 'Job Vacancies';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return JobVacancyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JobVacanciesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListJobVacancies::route('/'),
            'create' => CreateJobVacancy::route('/create'),
            'edit' => EditJobVacancy::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();
        
        // Apply location filter
        $user = Auth::user();
        if ($user && in_array($user->role, ['admin_location', 'admin_pusat'])) {
            if ($user->role === 'admin_location' && $user->location_id) {
                $location = $user->location;
                if ($location && $location->hris_location_id) {
                    $query->where('job_vacancy_hris_location_id', $location->hris_location_id);
                } else {
                    // Jika admin lokasi tidak punya location yang valid, tampilkan data kosong
                    $query->whereRaw('1 = 0');
                }
            }
            // Admin pusat bisa lihat semua data (tidak perlu filter)
        } else {
            // Non-admin tidak bisa akses
            $query->whereRaw('1 = 0');
        }
        
        return $query;
    }
}
