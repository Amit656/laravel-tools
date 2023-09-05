<?php

namespace App\Listeners;

use App\Events\AcceptToolReturnProcessed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

use App\Mail\AcceptToolReturn;
use App\Mail\ToolAvailability;

class SendAcceptToolReturnNotification implements ShouldQueue
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
     * @param  AcceptToolReturnProcessed  $event
     * @return void
     */
    public function handle(AcceptToolReturnProcessed $event)
    {
        //

    }

    public function shouldQueue(AcceptToolReturnProcessed $event)
    {   
        Mail::to($event->user['email'])->send(new AcceptToolReturn($event));
        
        if ($event->toolStatus == 'available' && count($event->toolAvailabiltyNotityUsers) > 0) {
            foreach ($event->toolAvailabiltyNotityUsers as $userDetails) {
                $event->user = ['name' => $userDetails['user']['name'], 'email' => $userDetails['user']['email']];

                Mail::to($event->user['email'])->send(new ToolAvailability($event));
            }
        }
    }   
}
