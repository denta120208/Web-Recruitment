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
    
    protected static ?string $navigationLabel = 'Lamaran Pekerjaan';
    
    protected static ?string $modelLabel = 'Lamaran Pekerjaan';
    
    protected static ?string $pluralModelLabel = 'Lamaran Pekerjaan';

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
}
