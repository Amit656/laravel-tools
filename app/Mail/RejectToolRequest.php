<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RejectToolRequest extends Mailable
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
    public $deliveryDate;
    public $pickupType;
    public $site;
    public function __construct($details)
    {   
        $this->tools = $details->tools;
        $this->user = $details->user;
        $this->admin = $details->admin;
        $this->modality = $details->modality;
        $this->requestDate = $details->requestDate;
        $this->deliveryDate = $details->deliveryDate;
        $this->pickupType = $details->pickupType;
        $this->site = $details->site;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   

        return $this->subject('Tool request rejected')
        ->markdown('emails.reject_tool_request');
    }
}
