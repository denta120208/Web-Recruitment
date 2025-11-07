<?php

namespace App\Filament\Resources\Applicants\Pages;

use App\Filament\Resources\Applicants\ApplicantResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\ApplyJob;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateApplicant extends CreateRecord
{
    protected static string $resource = ApplicantResource::class;
    
    protected ?int $jobVacancyId = null;
    protected array $educationsData = [];
    protected array $workExperiencesData = [];
    protected array $trainingsData = [];
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Store related data before removing
        $this->educationsData = $data['educations'] ?? [];
        $this->workExperiencesData = $data['workExperiences'] ?? [];
        $this->trainingsData = $data['trainings'] ?? [];
        
        // Remove fields that don't belong to the applicant table
        unset($data['create_apply_job']);
        $jobVacancyId = $data['job_vacancy_id'] ?? null;
        unset($data['job_vacancy_id']);
        unset($data['educations']);
        unset($data['workExperiences']);
        unset($data['trainings']);
        
        // Store job vacancy id for later use in afterCreate
        $this->jobVacancyId = $jobVacancyId;
        
        return $data;
    }
    
    protected function afterCreate(): void
    {
        $applicant = $this->record;
        
        // Create education records
        if (!empty($this->educationsData)) {
            try {
                foreach ($this->educationsData as $education) {
                    $applicant->educations()->create($education);
                }
                
                Notification::make()
                    ->title('Education Records Created')
                    ->body(count($this->educationsData) . ' riwayat pendidikan berhasil ditambahkan')
                    ->success()
                    ->send();
            } catch (\Exception $e) {
                Notification::make()
                    ->title('Education Creation Failed')
                    ->body("Gagal menambahkan riwayat pendidikan: " . $e->getMessage())
                    ->warning()
                    ->send();
            }
        }
        
        // Create work experience records
        if (!empty($this->workExperiencesData)) {
            try {
                foreach ($this->workExperiencesData as $workExp) {
                    $applicant->workExperiences()->create($workExp);
                }
                
                Notification::make()
                    ->title('Work Experience Records Created')
                    ->body(count($this->workExperiencesData) . ' pengalaman kerja berhasil ditambahkan')
                    ->success()
                    ->send();
            } catch (\Exception $e) {
                Notification::make()
                    ->title('Work Experience Creation Failed')
                    ->body("Gagal menambahkan pengalaman kerja: " . $e->getMessage())
                    ->warning()
                    ->send();
            }
        }
        
        // Create training/certification records
        if (!empty($this->trainingsData)) {
            try {
                foreach ($this->trainingsData as $training) {
                    $applicant->trainings()->create($training);
                }
                
                Notification::make()
                    ->title('Training Records Created')
                    ->body(count($this->trainingsData) . ' sertifikat/pelatihan berhasil ditambahkan')
                    ->success()
                    ->send();
            } catch (\Exception $e) {
                Notification::make()
                    ->title('Training Creation Failed')
                    ->body("Gagal menambahkan sertifikat/pelatihan: " . $e->getMessage())
                    ->warning()
                    ->send();
            }
        }
        
        // Create user account if doesn't exist
        if (!$applicant->user_id) {
            try {
                // Generate a random password
                $password = Str::random(12);
                
                $user = User::create([
                    'name' => trim($applicant->firstname . ' ' . ($applicant->middlename ?? '') . ' ' . ($applicant->lastname ?? '')),
                    'email' => $applicant->gmail, // Use decrypted email from accessor
                    'password' => Hash::make($password),
                ]);
                
                // Update applicant with user_id
                $applicant->user_id = $user->id;
                $applicant->save();
                
                Notification::make()
                    ->title('User Account Created')
                    ->body("User account berhasil dibuat untuk {$user->name}")
                    ->success()
                    ->send();
                    
            } catch (\Exception $e) {
                Notification::make()
                    ->title('User Account Creation Failed')
                    ->body("Gagal membuat user account: " . $e->getMessage())
                    ->warning()
                    ->send();
            }
        }
        
        // Create apply job if job vacancy was selected
        if (!empty($this->jobVacancyId) && $applicant->user_id) {
            try {
                ApplyJob::create([
                    'job_vacancy_id' => $this->jobVacancyId,
                    'user_id' => $applicant->user_id,
                    'requireid' => $applicant->requireid,
                    'apply_jobs_status' => 1, // Review Applicant
                    'apply_date' => now(),
                ]);
                
                Notification::make()
                    ->title('Apply Job Created')
                    ->body('Applicant berhasil didaftarkan ke lowongan pekerjaan')
                    ->success()
                    ->send();
                    
            } catch (\Exception $e) {
                Notification::make()
                    ->title('Apply Job Creation Failed')
                    ->body("Gagal mendaftarkan ke lowongan: " . $e->getMessage())
                    ->danger()
                    ->send();
            }
        }
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
