<?php

namespace App\Listeners;

use App\Events\ToolReturnProcessed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\ToolsReturn;
use Illuminate\Support\Facades\Mail;

class SendToolReturnNotification implements ShouldQueue
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
    public function handle(ToolReturnProcessed $event)
    {
        //

    }

    public function shouldQueue(ToolReturnProcessed $event)
    {
        foreach ($event->tools as $key => $toolDetails) {

            $event->tools = $toolDetails;
            Mail::to($event->admin['email'])->send(new ToolsReturn($event));
        } 
    }   
}
