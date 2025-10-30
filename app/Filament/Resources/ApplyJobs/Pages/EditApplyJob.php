<?php

namespace App\Filament\Resources\ApplyJobs\Pages;

use App\Filament\Resources\ApplyJobs\ApplyJobResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditApplyJob extends EditRecord
{
    protected static string $resource = ApplyJobResource::class;

    protected function getHeaderActions(): array
    {
        // Hide delete button in view-only mode
        if ($this->isViewOnly()) {
            return [];
        }
        
        return [
            DeleteAction::make(),
        ];
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
        
        return $data;
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
        
        // Add Generate Employee button if conditions are met
        if ($this->record->apply_jobs_status == 4 && // Offering Letter status
            !$this->record->is_generated_employee &&
            $this->record->apply_jobs_offering_letter_file &&
            $this->record->apply_jobs_mcu_file) {
            
            $actions[] = Action::make('generate_employee')
                ->label('Generate Employee')
                ->icon('heroicon-o-user-plus')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Generate Employee')
                ->modalDescription('Apakah anda yakin untuk generate employee? Setelah di-generate, apply jobs ini tidak bisa diedit lagi.')
                ->modalSubmitActionLabel('Ya, Generate')
                ->action(function () {
                    $this->record->update([
                        'is_generated_employee' => true,
                    ]);
                    
                    Notification::make()
                        ->title('Employee berhasil di-generate')
                        ->success()
                        ->send();
                    
                    // Redirect to refresh the page and apply view-only mode
                    return redirect()->route('filament.admin.resources.apply-jobs.edit', ['record' => $this->record]);
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
