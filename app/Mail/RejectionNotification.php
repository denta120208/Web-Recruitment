<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RejectionNotification extends Mailable
{
    use SerializesModels; // Removed Queueable to send email immediately

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
        return $this->subject('Update Lamaran Anda - ' . $this->jobTitle . ' di Metland')
                    ->replyTo('recruitment@metland.co.id', 'Metland Recruitment')
                    ->cc('receive.recruitment@metland.co.id')
                    ->view('emails.rejection_notification')
                    ->with([
                        'candidateName' => $this->candidateName,
                        'jobTitle' => $this->jobTitle,
                    ]);
    }
}
