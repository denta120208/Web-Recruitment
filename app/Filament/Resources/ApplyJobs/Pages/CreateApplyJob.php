<?php

namespace App\Filament\Resources\ApplyJobs\Pages;

use App\Filament\Resources\ApplyJobs\ApplyJobResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms;

class CreateApplyJob extends CreateRecord
{
    protected static string $resource = ApplyJobResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Auto-fill requireid from selected user's applicant
        if (isset($data['user_id'])) {
            $user = \App\Models\User::with('applicant')->find($data['user_id']);
            if ($user && $user->applicant) {
                $data['requireid'] = $user->applicant->requireid;
            }
        }
        
        return $data;
    }
}
