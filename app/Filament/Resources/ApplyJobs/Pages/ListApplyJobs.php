<?php

namespace App\Filament\Resources\ApplyJobs\Pages;

use App\Filament\Resources\ApplyJobs\ApplyJobResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListApplyJobs extends ListRecords
{
    protected static string $resource = ApplyJobResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
