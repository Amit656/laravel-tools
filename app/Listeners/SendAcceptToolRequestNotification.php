<?php

namespace App\Listeners;

use App\Events\AcceptToolRequestProcessed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

use App\Mail\AcceptToolRequest;

class SendAcceptToolRequestNotification implements ShouldQueue
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
     * @param  AcceptToolRequestProcessed  $event
     * @return void
     */
    public function handle(AcceptToolRequestProcessed $event)
    {
        //

    }

    public function shouldQueue(AcceptToolRequestProcessed $event)
    {   
        Mail::to($event->user['email'])->send(new AcceptToolRequest($event));
    }   
}
