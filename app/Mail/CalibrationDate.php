<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CalibrationDate extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $tools;
    public $admin;
    public $calibrationDate;
    public function __construct($details)
    {   
        $this->tools = $details->tools;
        $this->admin = $details->admin;
        $this->calibrationDate = $details->calibrationDate;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   

        return $this->subject('Tools Calibration Date')
        ->markdown('emails.tools_calibration_date');
    }
}
