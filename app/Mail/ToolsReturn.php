<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ToolsReturn extends Mailable
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
    public $requestDate;
    public $pickupType;
    public function __construct($details)
    {   
        $this->tools = $details->tools;
        $this->user = $details->user;
        $this->user = $details->user;
        $this->admin = $details->admin;
        $this->requestDate = $details->requestDate;
        $this->pickupType = $details->pickupType;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   

        return $this->subject('Engineer request to return for tool')
            ->markdown('emails.tools_return');
    }
}
