<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InterviewInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public $candidateName;
    public $jobTitle;
    public $interviewDate;
    public $interviewTime;
    public $interviewLocation;
    public $interviewBy;

    /**
     * Create a new message instance.
     */
    public function __construct($candidateName, $jobTitle, $interviewDate, $interviewTime, $interviewLocation, $interviewBy)
    {
        $this->candidateName = $candidateName;
        $this->jobTitle = $jobTitle;
        $this->interviewDate = $interviewDate;
        $this->interviewTime = $interviewTime;
        $this->interviewLocation = $interviewLocation;
        $this->interviewBy = $interviewBy;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Undangan Interview - ' . $this->jobTitle)
                    ->view('emails.interview_invitation');
    }
}
