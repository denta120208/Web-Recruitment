<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OfferingLetter extends Mailable
{
    use Queueable, SerializesModels;

    public $candidateName;
    public $jobTitle;
    public $placementLocation;
    public $offeringLetterDate;

    public function __construct($candidateName, $jobTitle, $placementLocation, $offeringLetterDate)
    {
        $this->candidateName = $candidateName;
        $this->jobTitle = $jobTitle;
        $this->placementLocation = $placementLocation;
        $this->offeringLetterDate = $offeringLetterDate;
    }

    public function build()
    {
        return $this->subject('Offering Letter — PT Metropolitan Land, Tbk')
            ->replyTo('recruitment@metland.co.id', 'Metland Recruitment')
            ->cc('receive.recruitment@metland.co.id')
            ->view('emails.offering_letter');
    }
}
