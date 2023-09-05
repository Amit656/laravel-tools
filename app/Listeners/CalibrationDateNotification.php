<?php

namespace App\Listeners;

use App\Events\CalibrationDateProcessed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

use App\Mail\CalibrationDate;

class CalibrationDateNotification implements ShouldQueue
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
     * @param  CalibrationDateProcessed  $event
     * @return void
     */
    public function handle(CalibrationDateProcessed $event)
    {
        //

    }

    public function shouldQueue(CalibrationDateProcessed $event)
    {   
        foreach ($event->tools as $key => $details) {
            $event->tools = $details;
            Mail::to($event->admin['email'])->send(new CalibrationDate($event));
        }
    }   
}
