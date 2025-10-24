<?php

namespace App\Filament\Resources\Applicants\Pages;

use App\Filament\Resources\Applicants\ApplicantResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditApplicant extends EditRecord
{
    protected static string $resource = ApplicantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make()
                ->requiresConfirmation()
                ->modalHeading('Hapus Data Pelamar')
                ->modalDescription('Apakah Anda yakin ingin menghapus data pelamar ini? Data yang sudah melamar pekerjaan tidak dapat dihapus.')
                ->before(function (DeleteAction $action, $record) {
                    // Check if applicant has applied to any job
                    if ($record->applyJobs()->exists()) {
                        \Filament\Notifications\Notification::make()
                            ->danger()
                            ->title('Tidak dapat menghapus')
                            ->body('Pelamar ini sudah melamar pekerjaan. Data tidak dapat dihapus untuk menjaga integritas data.')
                            ->persistent()
                            ->send();
                        
                        $action->cancel();
                    }
                }),
        ];
    }
}
