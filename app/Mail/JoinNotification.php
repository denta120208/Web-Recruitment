<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JoinNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $candidateName;
    public $jobTitle;
    public $joinDate;
    public $workLocation;

    public function __construct($candidateName, $jobTitle, $joinDate, $workLocation)
    {
        $this->candidateName = $candidateName;
        $this->jobTitle = $jobTitle;
        $this->joinDate = $joinDate;
        $this->workLocation = $workLocation;
    }

    public function build()
    {
        return $this->subject('Selamat Bergabung di PT Metropolitan Land, Tbk')
            ->replyTo('recruitment@metland.co.id', 'Metland Recruitment')
            ->cc('receive.recruitment@metland.co.id')
            ->view('emails.join_notification');
    }
}
