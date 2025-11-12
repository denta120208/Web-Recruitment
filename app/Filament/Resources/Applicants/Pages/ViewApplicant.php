<?php

namespace App\Filament\Resources\Applicants\Pages;

use App\Filament\Resources\Applicants\ApplicantResource;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewApplicant extends ViewRecord
{
    protected static string $resource = ApplicantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('print')
                ->label('Print Formulir')
                ->icon('heroicon-o-printer')
                ->color('success')
                ->url(fn () => route('admin.applicant.print', $this->record->requireid))
                ->openUrlInNewTab(),
            
            Action::make('pdf')
                ->label('Download PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('danger')
                ->url(fn () => route('admin.applicant.pdf', $this->record->requireid)),
            
            EditAction::make(),
        ];
    }
}
