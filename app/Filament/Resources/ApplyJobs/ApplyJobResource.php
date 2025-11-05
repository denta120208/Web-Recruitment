<?php

namespace App\Filament\Resources\ApplyJobs;

use App\Filament\Resources\ApplyJobs\Pages\CreateApplyJob;
use App\Filament\Resources\ApplyJobs\Pages\EditApplyJob;
use App\Filament\Resources\ApplyJobs\Pages\ListApplyJobs;
use App\Filament\Resources\ApplyJobs\Schemas\ApplyJobForm;
use App\Filament\Resources\ApplyJobs\Tables\ApplyJobsTable;
use App\Models\ApplyJob;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ApplyJobResource extends Resource
{
    protected static ?string $model = ApplyJob::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'apply_jobs_id';
    
    protected static ?string $navigationLabel = 'Apply Jobs';
    
    protected static ?string $modelLabel = 'Apply Jobs';
    
    protected static ?string $pluralModelLabel = 'Apply Jobs';

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
        return parent::getEloquentQuery()
            ->select([
                'apply_jobs_id',
                'job_vacancy_id',
                'user_id',
                'apply_jobs_status',
                'apply_jobs_psikotest_status',
                'apply_jobs_interview_by',
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
            ->with(['jobVacancy', 'user', 'interviewStatus']);
    }
}
