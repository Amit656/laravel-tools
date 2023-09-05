<?php

namespace App\Listeners;

use App\Events\ToolRequestProcessed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\ToolsRequest;
use Illuminate\Support\Facades\Mail;

class SendToolRequestNotification implements ShouldQueue
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
     * @param  ToolRequestProcessed  $event
     * @return void
     */
    public function handle(ToolRequestProcessed $event)
    {
        //

    }

    public function shouldQueue(ToolRequestProcessed $event)
    {   
        foreach ($event->tools as $key => $toolDetails) {

            $event->tools = $toolDetails;
            Mail::to($event->admin['email'])->send(new ToolsRequest($event));
        }       
    }   
}
