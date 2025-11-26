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
    public $placementLocation;
    public $picName;

    /**
     * Create a new message instance.
     */
    public function __construct($candidateName, $jobTitle, $interviewDate, $interviewTime, $interviewLocation, $interviewBy, $placementLocation, $picName)
    {
        $this->candidateName = $candidateName;
        $this->jobTitle = $jobTitle;
        $this->interviewDate = $interviewDate;
        $this->interviewTime = $interviewTime;
        $this->interviewLocation = $interviewLocation;
        $this->interviewBy = $interviewBy;
        $this->placementLocation = $placementLocation;
        $this->picName = $picName;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Undangan Interview ' . $this->jobTitle . ' — PT Metropolitan Land, Tbk')
                    ->replyTo('recruitment@metland.co.id', 'Metland Recruitment')
                    ->cc('receive.recruitment@metland.co.id')
                    ->view('emails.interview_invitation');
    }
}
