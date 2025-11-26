<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class McuInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public $candidateName;
    public $jobTitle;
    public $mcuDate;
    public $mcuTime;
    public $mcuLocation;

    /**
     * Create a new message instance.
     */
    public function __construct($candidateName, $jobTitle, $mcuDate, $mcuTime, $mcuLocation)
    {
        $this->candidateName = $candidateName;
        $this->jobTitle = $jobTitle;
        $this->mcuDate = $mcuDate;
        $this->mcuTime = $mcuTime;
        $this->mcuLocation = $mcuLocation;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Undangan Medical Check Up — PT Metropolitan Land, Tbk')
                    ->replyTo('recruitment@metland.co.id', 'Metland Recruitment')
                    ->cc('receive.recruitment@metland.co.id')
                    ->view('emails.mcu_invitation');
    }
}
