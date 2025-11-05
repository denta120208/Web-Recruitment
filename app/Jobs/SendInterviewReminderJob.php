<?php

namespace App\Jobs;

use App\Mail\InterviewReminder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendInterviewReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $candidateEmail;
    protected $candidateName;
    protected $jobTitle;
    protected $interviewDate;
    protected $interviewTime;
    protected $interviewLocation;
    protected $interviewBy;
    protected $interviewerEmail;

    /**
     * Create a new job instance.
     */
    public function __construct(
        $candidateEmail,
        $candidateName,
        $jobTitle,
        $interviewDate,
        $interviewTime,
        $interviewLocation,
        $interviewBy,
        $interviewerEmail = null
    ) {
        $this->candidateEmail = $candidateEmail;
        $this->candidateName = $candidateName;
        $this->jobTitle = $jobTitle;
        $this->interviewDate = $interviewDate;
        $this->interviewTime = $interviewTime;
        $this->interviewLocation = $interviewLocation;
        $this->interviewBy = $interviewBy;
        $this->interviewerEmail = $interviewerEmail;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Send reminder to candidate
            Mail::to($this->candidateEmail)->send(
                new InterviewReminder(
                    $this->candidateName,
                    $this->jobTitle,
                    $this->interviewDate,
                    $this->interviewTime,
                    $this->interviewLocation,
                    $this->interviewBy
                )
            );

            Log::info('Interview reminder sent to candidate', [
                'email' => $this->candidateEmail,
                'candidate' => $this->candidateName,
                'job' => $this->jobTitle
            ]);

            // Send reminder to interviewer if email provided
            if ($this->interviewerEmail) {
                Mail::to($this->interviewerEmail)->send(
                    new InterviewReminder(
                        $this->interviewBy,
                        $this->jobTitle,
                        $this->interviewDate,
                        $this->interviewTime,
                        $this->interviewLocation,
                        $this->candidateName
                    )
                );

                Log::info('Interview reminder sent to interviewer', [
                    'email' => $this->interviewerEmail,
                    'interviewer' => $this->interviewBy,
                    'candidate' => $this->candidateName
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send interview reminder', [
                'error' => $e->getMessage(),
                'candidate_email' => $this->candidateEmail,
                'interviewer_email' => $this->interviewerEmail
            ]);
        }
    }
}
