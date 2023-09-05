<?php

namespace App\Listeners;

use App\Events\RejectToolRequestProcessed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

use App\Mail\RejectToolRequest;

class SendRejectToolRequestNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  RejectToolRequestProcessed  $event
     * @return void
     */
    public function handle(RejectToolRequestProcessed $event)
    {
        //

    }

    public function shouldQueue(RejectToolRequestProcessed $event)
    {   
        Mail::to($event->user['email'])->send(new RejectToolRequest($event));
    }   
}
