<?php

namespace App\Filament\Resources\ApplyJobs;

use App\Filament\Resources\ApplyJobs\Pages\CreateApplyJob;
use App\Filament\Resources\ApplyJobs\Pages\EditApplyJob;
use App\Filament\Resources\ApplyJobs\Pages\ListApplyJobs;
use App\Filament\Resources\ApplyJobs\Schemas\ApplyJobForm;
use App\Filament\Resources\ApplyJobs\Tables\ApplyJobsTable;
use App\Models\ApplyJob;
use App\Traits\LocationFilterTrait;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class ApplyJobResource extends Resource
{
    use LocationFilterTrait;
    
    protected static ?string $model = ApplyJob::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'apply_jobs_id';
    
    protected static ?string $navigationLabel = 'Status Monitoring Applicant';
    
    protected static ?string $modelLabel = 'Status Monitoring Applicant';
    
    protected static ?string $pluralModelLabel = 'Status Monitoring Applicant';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return ApplyJobForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ApplyJobsTable::configure($table);
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
            'index' => ListApplyJobs::route('/'),
            'create' => CreateApplyJob::route('/create'),
            'edit' => EditApplyJob::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        // Optimized: eager load relations and select only needed columns
        $query = parent::getEloquentQuery()
            ->select([
                'apply_jobs_id',
                'job_vacancy_id',
                'user_id',
                'apply_jobs_status',
                'apply_jobs_psikotest_status',
                'apply_jobs_interview_by',
                'apply_jobs_interview_user_email',
                'apply_jobs_interview_location',
                'apply_jobs_interview_date',
                'apply_jobs_interview_time',
                'apply_jobs_interview_result',
                'apply_jobs_interview_ai_result',
                'apply_jobs_interview_status',
                'apply_jobs_psikotest_iq_num',
                'apply_jobs_psikotest_file',
                'apply_jobs_mcu_file',
                'apply_jobs_offering_letter_file',
                'is_generated_employee',
                'created_at',
                'updated_at',
                'requireid',
                'require_id',
            ])
            ->with([
                'jobVacancy', 
                'user', 
                'interviewStatus',
                'applicant.educations',
                'applicant.workExperiences',
                'applicant.trainings'
            ]);

        // Apply location filter through job vacancy
        $user = Auth::user();
        
        if ($user && in_array($user->role, ['admin_location', 'admin_pusat'])) {
            if ($user->role === 'admin_location' && $user->location_id) {
                $location = $user->location;
                if ($location && $location->hris_location_id) {
                    $query->whereHas('jobVacancy', function ($q) use ($location) {
                        $q->where('job_vacancy_hris_location_id', $location->hris_location_id);
                    });
                } else {
                    // Admin location has no valid location, show empty
                    $query->whereRaw('1 = 0');
                }
            }
            // Admin pusat can see all data (no filter needed)
        } else {
            // Non-admin cannot access
            $query->whereRaw('1 = 0');
        }

        return $query;
    }
}
