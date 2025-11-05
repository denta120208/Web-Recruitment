<?php

namespace App\Filament\Resources\ApplyJobs\Pages;

use App\Filament\Resources\ApplyJobs\ApplyJobResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use App\Services\HrisApiService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class EditApplyJob extends EditRecord
{
    protected static string $resource = ApplyJobResource::class;
    
    protected $originalStatus;

    protected function getHeaderActions(): array
    {
        // Hide delete button in view-only mode
        if ($this->isViewOnly()) {
            return [];
        }
        
        $actions = [
            DeleteAction::make(),
        ];

        // Add Sync to HRIS button for Hired status (deprecated - use Generate Employee instead)
        // Keep this for backward compatibility but use same logic as Generate Employee
        
        return $actions;
    }

    public function getTitle(): string
    {
        $applicantName = $this->record->user?->name ?? 'Unknown';
        $suffix = '';
        
        if ($this->isViewOnly()) {
            if ($this->record->is_generated_employee) {
                $suffix = ' (View Only - Employee Generated)';
            } elseif ($this->isInterviewRejected()) {
                $suffix = ' (View Only - Interview Rejected)';
            }
        }
        
        return "Edit - {$applicantName}{$suffix}";
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Prevent editing if in view-only mode
        if ($this->isViewOnly()) {
            if ($this->record->is_generated_employee) {
                $message = 'Apply jobs ini sudah di-generate menjadi employee dan tidak bisa diedit.';
            } else {
                $message = 'Apply jobs ini sudah ditolak (Interview Status: Reject) dan tidak bisa diedit.';
            }
            
            Notification::make()
                ->title('Tidak dapat mengubah data')
                ->body($message)
                ->danger()
                ->send();
            
            $this->halt();
        }
        
        // Store original status before save for comparison in afterSave
        $this->originalStatus = $this->record->apply_jobs_status;
        
        return $data;
    }

    protected function afterSave(): void
    {
        // Refresh record to get latest data after save
        $this->record->refresh();
        
        // Sync to HRIS when status changed (includes status 1-5)
        $newStatus = $this->record->apply_jobs_status;
        
        // Check if status changed (all statuses including Hired)
        if ($this->originalStatus != $newStatus && $this->record->requireid) {
            $hrisService = app(HrisApiService::class);
            
            // Get applicant data
            $applicant = $this->record->applicant;
            
            if ($applicant) {
                // Build candidate name
                $candidate_name = trim(
                    ($applicant->firstname ?? '') . ' ' . 
                    ($applicant->middlename ?? '') . ' ' . 
                    ($applicant->lastname ?? '')
                );
                
                // For status 1-4, use setCandidate (normal status update)
                // For status 5 (Hired), also use setCandidate to sync status first
                // (Generate Employee button will send full employee data later)
                $data = [
                    'recruitment_candidate_id' => $this->record->requireid,
                    'candidate_name' => $candidate_name ?: 'Unknown',
                    'candidate_email' => $applicant->gmail ?? $this->record->user?->email ?? 'no-email@example.com',
                    'candidate_contact_number' => $applicant->phone ?? '0',
                    'candidate_apply_date' => $this->record->apply_date ?? $this->record->created_at->format('Y-m-d'),
                    'apply_jobs_status_id' => $newStatus,
                    'set_candidate_by' => optional(Auth::user())->name ?? 'Admin',
                ];
                
                Log::info('EditApplyJob - Syncing status change to HRIS', [
                    'apply_job_id' => $this->record->apply_jobs_id,
                    'old_status' => $this->originalStatus,
                    'new_status' => $newStatus,
                    'data' => $data
                ]);
                
                $result = $hrisService->setCandidate($data);
                
                if ($result['success']) {
                    $message = ($newStatus == 5) 
                        ? 'Status Hired berhasil disinkronkan ke HRIS. Klik "Generate Employee" untuk mengirim data employee lengkap.'
                        : 'Status berhasil disinkronkan ke HRIS';
                    
                    Notification::make()
                        ->title('Status berhasil disinkronkan ke HRIS')
                        ->body($message)
                        ->success()
                        ->send();
                        
                    Log::info('EditApplyJob - Successfully synced to HRIS', [
                        'apply_job_id' => $this->record->apply_jobs_id,
                        'response' => $result['data'] ?? null
                    ]);
                    
                    // If status changed to Hired (5), redirect to show Generate Employee button
                    if ($newStatus == 5) {
                        $this->redirect(static::getUrl(['record' => $this->record->apply_jobs_id]));
                    }
                } else {
                    Notification::make()
                        ->title('Gagal sinkronisasi ke HRIS')
                        ->body($result['error'] ?? 'Unknown error')
                        ->warning()
                        ->send();
                        
                    Log::warning('EditApplyJob - Failed to sync to HRIS', [
                        'apply_job_id' => $this->record->apply_jobs_id,
                        'error' => $result['error'] ?? 'Unknown error'
                    ]);
                }
            }
        }
    }

    protected function isInterviewRejected(): bool
    {
        // Check if interview status is "Reject"
        // Also check variations like "Rejected" or case-insensitive match
        $statusName = $this->record->interviewStatus?->interview_status_name;
        
        if (!$statusName) {
            return false;
        }
        
        return strtolower($statusName) === 'reject' || strtolower($statusName) === 'rejected';
    }
    
    protected function isViewOnly(): bool
    {
        // Centralized method to check if record should be view-only
        return $this->record->is_generated_employee || $this->isInterviewRejected();
    }

    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return false;
    }

    protected function getFormActions(): array
    {
        // Hide all action buttons in view-only mode
        if ($this->isViewOnly()) {
            return [];
        }
        
        $actions = parent::getFormActions();
        
        // Refresh record to ensure we have latest status
        $this->record->refresh();
        
        // Add Generate Employee button if conditions are met (Status Hired)
        if ($this->record->apply_jobs_status == 5 && // Hired status
            !$this->record->is_generated_employee &&
            $this->record->requireid) {
            
            $actions[] = Action::make('generate_employee')
                ->label('Add Data Employee')
                ->icon('heroicon-o-user-plus')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Add Data Employee')
                ->modalDescription('Kandidat ini sudah lolos data akan masuk ke system HRIS. Apakah anda yakin melanjutkan proses ini?')
                ->modalSubmitActionLabel('Ya, Lanjutkan')
                ->action(function (HrisApiService $hrisService) {
                    $applicant = $this->record->applicant;
                    
                    if (!$applicant) {
                        Notification::make()
                            ->title('Gagal generate employee')
                            ->body('Data applicant tidak ditemukan')
                            ->danger()
                            ->send();
                        return;
                    }
                    
                    // Build candidate name
                    $candidate_name = trim(
                        ($applicant->firstname ?? '') . ' ' . 
                        ($applicant->middlename ?? '') . ' ' . 
                        ($applicant->lastname ?? '')
                    );
                    
                    // Map gender: 1 = Male, 2 = Female
                    $genderValue = strtolower($applicant->gender ?? 'male');
                    $gender = ($genderValue === 'female' || $genderValue === 'perempuan' || $genderValue === 'f' || $genderValue === 'p' || $genderValue === '2') ? 2 : 1;
                    
                    // Prepare data for HRIS - pastikan semua field wajib ada dan optional null
                    $data = [
                        // Required fields dari recruitment
                        'recruitment_candidate_id' => $this->record->requireid,
                        'candidate_name' => $candidate_name ?: 'Unknown',
                        'candidate_email' => $applicant->gmail ?? $this->record->user?->email ?? 'no-email@example.com',
                        'candidate_contact_number' => $applicant->phone ?? '0',
                        'candidate_apply_date' => $this->record->apply_date ?? $this->record->created_at->format('Y-m-d'),
                        'apply_jobs_status_id' => 5, // Hired
                        'set_candidate_by' => optional(Auth::user())->name ?? 'System', // Wajib Diisi
                        
                        // Data Employee - Required fields (Wajib Diisi)
                        'joined_date' => now()->format('Y-m-d'), // Wajib Diisi
                        'emp_firstname' => $applicant->firstname ?? 'Unknown', // Wajib Diisi
                        'emp_gender' => $gender, // Wajib Diisi - 1 = Male, 2 = Female
                        'emp_marital_status' => 'Lajang', // Wajib Diisi - Default Lajang
                        
                        // Optional fields - harus null (bukan empty string)
                        'emp_middle_name' => (!empty($applicant->middlename)) ? $applicant->middlename : null,
                        'emp_lastname' => (!empty($applicant->lastname)) ? $applicant->lastname : null,
                        'emp_ktp' => null,
                        'emp_dri_lice_num' => null,
                        'emp_dri_lice_exp_date' => null,
                        'emp_birthday' => ($applicant->dateofbirth) ? $applicant->dateofbirth->format('Y-m-d') : null,
                        'bpjs_ks' => null,
                        'bpjs_tk' => null,
                        'npwp' => null,
                        // work_station removed - not supported by HRIS API
                    ];
                    
                    Log::info('Generate Employee - Sending to HRIS', [
                        'apply_job_id' => $this->record->apply_jobs_id,
                        'data' => $data
                    ]);
                    
                    $result = $hrisService->setCandidateHired($data);
                    
                    if ($result['success']) {
                        // Update is_generated_employee setelah sukses kirim ke HRIS
                    $this->record->update([
                        'is_generated_employee' => true,
                    ]);
                    
                    Notification::make()
                            ->title('Employee berhasil di-generate dan disinkronkan ke HRIS')
                        ->success()
                        ->send();
                    
                        Log::info('Generate Employee - Success', [
                            'apply_job_id' => $this->record->apply_jobs_id,
                            'response' => $result['data'] ?? null
                        ]);
                    
                    // Redirect to refresh the page and apply view-only mode
                    return redirect()->route('filament.admin.resources.apply-jobs.edit', ['record' => $this->record]);
                    } else {
                        Notification::make()
                            ->title('Gagal sinkronisasi ke HRIS')
                            ->body($result['error'] ?? 'Unknown error')
                            ->danger()
                            ->send();
                        
                        Log::error('Generate Employee - Failed', [
                            'apply_job_id' => $this->record->apply_jobs_id,
                            'error' => $result['error'] ?? 'Unknown error'
                        ]);
                    }
                });
        }
        
        return $actions;
    }

    public function mount(int | string $record): void
    {
        parent::mount($record);

        // Disable all form fields if in view-only mode
        if ($this->isViewOnly()) {
            $this->form->disabled();
        }
    }

    public function getFileDownloadUrl(string $type): ?string
    {
        if (!$this->record || !$this->record->apply_jobs_id) {
            return null;
        }

        return route('admin.file.apply-job', [
            'applyJobId' => $this->record->apply_jobs_id,
            'type' => $type
        ]);
    }
}
