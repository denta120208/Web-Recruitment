<?php

namespace App\Filament\Resources\Applicants;

use App\Filament\Resources\Applicants\Pages\CreateApplicant;
use App\Filament\Resources\Applicants\Pages\EditApplicant;
use App\Filament\Resources\Applicants\Pages\ListApplicants;
use App\Filament\Resources\Applicants\Pages\ViewApplicant;
use App\Filament\Resources\Applicants\Schemas\ApplicantForm;
use App\Filament\Resources\Applicants\Schemas\ApplicantInfolist;
use App\Filament\Resources\Applicants\Tables\ApplicantsTable;
use App\Models\Applicant;
use App\Traits\LocationFilterTrait;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class ApplicantResource extends Resource
{
    use LocationFilterTrait;
    
    protected static ?string $model = Applicant::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'admin';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return ApplicantForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ApplicantInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ApplicantsTable::configure($table);
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
            'index' => ListApplicants::route('/'),
            'create' => CreateApplicant::route('/create'),
            'view' => ViewApplicant::route('/{record}'),
            'edit' => EditApplicant::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery()
            ->select([
                'requireid',
                'firstname',
                'middlename',
                'lastname',
                'gender',
                'dateofbirth',
                'cvpath',
                'photopath',
                'idcardpath',
                'address',
                'city',
                'gmail',
                'linkedin',
                'instagram',
                'phone',
                'createdat',
                'updatedat',
                'admin_notes',
                'status_updated_at',
                'reviewed_by',
                'user_id',
            ])
            ->with(['user', 'educations', 'workExperiences', 'trainings']);

        // Apply location filter - filter applicants berdasarkan apply jobs mereka
        $user = Auth::user();
        
        if ($user && in_array($user->role, ['admin_location', 'admin_pusat'])) {
            if ($user->role === 'admin_location' && $user->location_id) {
                $location = $user->location;
                if ($location && $location->hris_location_id) {
                    // Filter applicants yang punya apply jobs di lokasi ini
                    $query->whereHas('user.applyJobs.jobVacancy', function ($q) use ($location) {
                        $q->where('job_vacancy_hris_location_id', $location->hris_location_id);
                    });
                } else {
                    // Admin location has no valid location, show empty
                    $query->whereRaw('1 = 0');
                }
            }
            // Admin pusat can see all applicants (no filter needed)
        } else {
            // Non-admin cannot access
            $query->whereRaw('1 = 0');
        }

        return $query;
    }
}
