<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ToolsRequest extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $tools;
    public $user;
    public $admin;
    public $modality;
    public $requestDate;
    public $expectedReturnDate;
    public $pickupType;
    public $dropLocation;
    public function __construct($details)
    {      
        $this->tools = $details->tools;
        $this->user = $details->user;
        $this->admin = $details->admin;
        $this->modality = $details->modality;
        $this->requestDate = $details->requestDate;
        $this->expectedReturnDate = $details->expectedReturnDate;
        $this->pickupType = $details->pickupType;
        $this->dropLocation = $details->dropLocation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   
        return $this->subject('Engineer request for tool')
            ->markdown('emails.tools_request');
    }
}
