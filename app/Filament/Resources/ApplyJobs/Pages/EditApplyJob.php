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
use Illuminate\Support\Facades\Mail;
use App\Mail\InterviewInvitation;
use App\Mail\RejectionNotification;
use App\Jobs\SendInterviewReminderJob;
use Carbon\Carbon;

class EditApplyJob extends EditRecord
{
    protected static string $resource = ApplyJobResource::class;
    
    protected $originalStatus;
    protected $originalInterviewStatus;

    protected function getHeaderActions(): array
    {
        $actions = [];
        
        // If in view-only mode and rejected, add resend rejection email button
        if ($this->isViewOnly() && $this->isInterviewRejected()) {
            $actions[] = Action::make('resend_rejection_email')
                ->label('Resend Rejection Email')
                ->icon('heroicon-o-envelope')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Resend Rejection Email')
                ->modalDescription('Apakah Anda yakin ingin mengirim ulang email penolakan ke kandidat?')
                ->action(function () {
                    $this->sendRejectionEmail();
                });
            
            return $actions;
        }
        
        // Normal mode - show delete button
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
        $this->originalInterviewStatus = $this->record->apply_jobs_interview_status;
        
        return $data;
    }

    protected function afterSave(): void
    {
        // Refresh record to get latest data after save
        $this->record->refresh();
        
        // Sync to HRIS when status changed (includes status 1-6)
        $newStatus = $this->record->apply_jobs_status;
        
        // Handle email notifications for Interview User status (2)
        if ($newStatus == 2 && $this->originalStatus != $newStatus) {
            $this->sendInterviewInvitation();
        }
        
        // Handle rejection email when interview status CHANGED to Reject
        $newInterviewStatus = $this->record->apply_jobs_interview_status;
        
        Log::info('Checking rejection email condition', [
            'apply_job_id' => $this->record->apply_jobs_id,
            'newStatus' => $newStatus,
            'newInterviewStatus' => $newInterviewStatus,
            'originalInterviewStatus' => $this->originalInterviewStatus,
            'isInterviewRejected' => $this->isInterviewRejected(),
            'statusChanged' => $this->originalInterviewStatus != $newInterviewStatus
        ]);
        
        if ($newStatus == 2 && $this->isInterviewRejected() && $this->originalInterviewStatus != $newInterviewStatus) {
            Log::info('Sending rejection email', [
                'apply_job_id' => $this->record->apply_jobs_id
            ]);
            $this->sendRejectionEmail();
        }
        
        // Check if status changed (all statuses including Hired and MCU)
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
    
    protected function sendInterviewInvitation(): void
    {
        try {
            $applicant = $this->record->applicant;
            $user = $this->record->user;
            $jobVacancy = $this->record->jobVacancy;
            
            if (!$applicant || !$user || !$jobVacancy) {
                Log::warning('Cannot send interview invitation - missing data', [
                    'apply_job_id' => $this->record->apply_jobs_id
                ]);
                return;
            }
            
            // Get candidate email from user table
            $candidateEmail = $user->email;
            $candidateName = trim(
                ($applicant->firstname ?? '') . ' ' . 
                ($applicant->middlename ?? '') . ' ' . 
                ($applicant->lastname ?? '')
            );
            
            $jobTitle = $jobVacancy->job_vacancy_name ?? 'Unknown Position';
            $interviewDate = $this->record->apply_jobs_interview_date 
                ? Carbon::parse($this->record->apply_jobs_interview_date)->format('d F Y')
                : 'TBA';
            $interviewTime = $this->record->apply_jobs_interview_time 
                ? Carbon::parse($this->record->apply_jobs_interview_time)->format('H:i')
                : 'TBA';
            $interviewLocation = $this->record->apply_jobs_interview_location ?? 'TBA';
            $interviewBy = $this->record->apply_jobs_interview_by ?? 'HRD Team';
            
            // Send interview invitation email
            Mail::to($candidateEmail)->send(
                new InterviewInvitation(
                    $candidateName,
                    $jobTitle,
                    $interviewDate,
                    $interviewTime,
                    $interviewLocation,
                    $interviewBy
                )
            );
            
            Log::info('Interview invitation sent', [
                'apply_job_id' => $this->record->apply_jobs_id,
                'email' => $candidateEmail,
                'candidate' => $candidateName
            ]);
            
            // Schedule H-1 reminder if interview date is set
            if ($this->record->apply_jobs_interview_date) {
                $interviewDateTime = Carbon::parse($this->record->apply_jobs_interview_date);
                $reminderDateTime = $interviewDateTime->copy()->subDay()->setTime(9, 0); // H-1 at 9 AM
                
                // Only schedule if reminder date is in the future
                if ($reminderDateTime->isFuture()) {
                    // Get interviewer email from users table if available
                    $interviewerEmail = $this->record->apply_jobs_interview_user_email;
                    
                    SendInterviewReminderJob::dispatch(
                        $candidateEmail,
                        $candidateName,
                        $jobTitle,
                        $interviewDate,
                        $interviewTime,
                        $interviewLocation,
                        $interviewBy,
                        $interviewerEmail
                    )->delay($reminderDateTime);
                    
                    Log::info('Interview reminder scheduled', [
                        'apply_job_id' => $this->record->apply_jobs_id,
                        'reminder_date' => $reminderDateTime->toDateTimeString(),
                        'candidate_email' => $candidateEmail,
                        'interviewer_email' => $interviewerEmail
                    ]);
                }
            }
            
            Notification::make()
                ->title('Email undangan interview berhasil dikirim')
                ->body('Email telah dikirim ke ' . $candidateEmail)
                ->success()
                ->send();
                
        } catch (\Exception $e) {
            Log::error('Failed to send interview invitation', [
                'apply_job_id' => $this->record->apply_jobs_id,
                'error' => $e->getMessage()
            ]);
            
            Notification::make()
                ->title('Gagal mengirim email')
                ->body('Terjadi kesalahan: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
    
    protected function sendRejectionEmail(): void
    {
        try {
            $applicant = $this->record->applicant;
            $user = $this->record->user;
            $jobVacancy = $this->record->jobVacancy;
            
            if (!$applicant || !$user || !$jobVacancy) {
                Log::warning('Cannot send rejection email - missing data', [
                    'apply_job_id' => $this->record->apply_jobs_id
                ]);
                return;
            }
            
            // Get candidate email from user table
            $candidateEmail = $user->email;
            $candidateName = trim(
                ($applicant->firstname ?? '') . ' ' . 
                ($applicant->middlename ?? '') . ' ' . 
                ($applicant->lastname ?? '')
            );
            
            $jobTitle = $jobVacancy->job_vacancy_name ?? 'Unknown Position';
            
            // Send rejection email
            Mail::to($candidateEmail)->send(
                new RejectionNotification(
                    $candidateName,
                    $jobTitle
                )
            );
            
            Log::info('Rejection email sent', [
                'apply_job_id' => $this->record->apply_jobs_id,
                'email' => $candidateEmail,
                'candidate' => $candidateName
            ]);
            
            Notification::make()
                ->title('Email penolakan berhasil dikirim')
                ->body('Email telah dikirim ke ' . $candidateEmail)
                ->success()
                ->send();
                
        } catch (\Exception $e) {
            Log::error('Failed to send rejection email', [
                'apply_job_id' => $this->record->apply_jobs_id,
                'error' => $e->getMessage()
            ]);
            
            Notification::make()
                ->title('Gagal mengirim email')
                ->body('Terjadi kesalahan: ' . $e->getMessage())
                ->danger()
                ->send();
        }
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
                    
                    // Get education list from HRIS untuk mapping
                    $hrisEducations = $hrisService->getAllEducations() ?? [];
                    
                    // Prepare data education
                    $dataEducation = [];
                    foreach ($applicant->educations as $edu) {
                        // Pastikan field wajib tidak null
                        // Field wajib: institute, year, score, start_date
                        if (!empty($edu->institutionname) && !empty($edu->year) && !empty($edu->score) && !empty($edu->startdate)) {
                            $dataEducation[] = [
                                'education_id' => $edu->education_id ?? null,
                                'institute' => $edu->institutionname, // Wajib
                                'major' => $edu->major ?? null, // Optional
                                'year' => $edu->year, // Wajib
                                'score' => $edu->score, // Wajib
                                'start_date' => $edu->startdate->format('Y-m-d'), // Wajib
                                'end_date' => $edu->enddate ? $edu->enddate->format('Y-m-d') : null, // Optional
                            ];
                        }
                    }
                    
                    // Prepare data work experience
                    $dataWorkExperience = [];
                    foreach ($applicant->workExperiences as $workExp) {
                        $dataWorkExperience[] = [
                            'eexp_employer' => $workExp->companyname ?? null,
                            'eexp_jobtit' => $workExp->joblevel ?? null,
                            'eexp_from_date' => $workExp->startdate ? $workExp->startdate->format('Y-m-d') : null,
                            'eexp_to_date' => $workExp->enddate ? $workExp->enddate->format('Y-m-d') : null,
                            'eexp_comments' => $workExp->eexp_comments ?? null,
                        ];
                    }
                    
                    // Prepare data training
                    $dataTraining = [];
                    foreach ($applicant->trainings as $training) {
                        $dataTraining[] = [
                            'train_name' => $training->trainingname ?? null,
                            'license_no' => $training->certificateno ?? null,
                            'license_issued_date' => $training->starttrainingdate ? $training->starttrainingdate->format('Y-m-d') : null,
                            'license_expiry_date' => $training->endtrainingdate ? $training->endtrainingdate->format('Y-m-d') : null,
                        ];
                    }
                    
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
                        // Photo path - kirim path lengkap dengan nama file dan extension
                        // Format: path beserta nama & ext filenya (contoh: applicants/photos/filename.jpg)
                        // Pastikan path sudah include nama file dan extension
                        'image_profile_path' => !empty($applicant->photopath) 
                            ? (strpos($applicant->photopath, '/') !== false ? $applicant->photopath : 'applicants/photos/' . $applicant->photopath)
                            : null,
                        
                        // Data arrays
                        'data_education' => !empty($dataEducation) ? $dataEducation : null,
                        'data_work_experience' => !empty($dataWorkExperience) ? $dataWorkExperience : null,
                        'data_training' => !empty($dataTraining) ? $dataTraining : null,
                    ];
                    
                    Log::info('Generate Employee - Sending to HRIS', [
                        'apply_job_id' => $this->record->apply_jobs_id,
                        'applicant_id' => $applicant->requireid,
                        'education_count' => count($dataEducation),
                        'work_experience_count' => count($dataWorkExperience),
                        'training_count' => count($dataTraining),
                        'photo_path' => $data['image_profile_path'],
                        'data_education' => $dataEducation,
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
                        // Ambil error message dari response
                        $errorMessage = 'Unknown error';
                        if (isset($result['error'])) {
                            $errorMessage = $result['error'];
                        } elseif (isset($result['data']['message'])) {
                            $errorMessage = $result['data']['message'];
                        } elseif (isset($result['data']['error'])) {
                            $errorMessage = $result['data']['error'];
                        } elseif (isset($result['data'])) {
                            $errorMessage = is_string($result['data']) ? $result['data'] : json_encode($result['data']);
                        }
                        
                        Notification::make()
                            ->title('Gagal sinkronisasi ke HRIS')
                            ->body('Status: ' . ($result['status'] ?? 'N/A') . '. Error: ' . $errorMessage)
                            ->danger()
                            ->send();
                        
                        Log::error('Generate Employee - Failed', [
                            'apply_job_id' => $this->record->apply_jobs_id,
                            'status' => $result['status'] ?? null,
                            'error' => $errorMessage,
                            'full_result' => $result
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