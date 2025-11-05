<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RejectionNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $candidateName;
    public $jobTitle;

    /**
     * Create a new message instance.
     */
    public function __construct($candidateName, $jobTitle)
    {
        $this->candidateName = $candidateName;
        $this->jobTitle = $jobTitle;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Pemberitahuan Status Lamaran - ' . $this->jobTitle)
                    ->view('emails.rejection_notification');
    }
}
