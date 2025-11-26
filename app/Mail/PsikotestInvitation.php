<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PsikotestInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public $candidateName;
    public $jobTitle;
    public $psikotestDate;
    public $psikotestTime;
    public $psikotestLocation;
    public $picName;

    /**
     * Create a new message instance.
     */
    public function __construct($candidateName, $jobTitle, $psikotestDate, $psikotestTime, $psikotestLocation, $picName)
    {
        $this->candidateName = $candidateName;
        $this->jobTitle = $jobTitle;
        $this->psikotestDate = $psikotestDate;
        $this->psikotestTime = $psikotestTime;
        $this->psikotestLocation = $psikotestLocation;
        $this->picName = $picName;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Undangan Psikotest — PT Metropolitan Land, Tbk')
                    ->replyTo('recruitment@metland.co.id', 'Metland Recruitment')
                    ->cc('receive.recruitment@metland.co.id')
                    ->view('emails.psikotest_invitation');
    }
}
