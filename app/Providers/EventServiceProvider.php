<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

use App\Events\ToolRequestProcessed;
use App\Listeners\SendToolRequestNotification;

use App\Events\ToolReturnProcessed;
use App\Listeners\SendToolReturnNotification;

use App\Events\RejectToolRequestProcessed;
use App\Listeners\SendRejectToolRequestNotification;

use App\Events\AcceptToolRequestProcessed;
use App\Listeners\SendAcceptToolRequestNotification;

use App\Events\AcceptToolReturnProcessed;
use App\Listeners\SendAcceptToolReturnNotification;

use App\Events\CalibrationDateProcessed;
use App\Listeners\CalibrationDateNotification;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        ToolRequestProcessed::class => [
            SendToolRequestNotification::class,
        ], 
        ToolReturnProcessed::class => [
            SendToolReturnNotification::class,
        ]
        , 
        RejectToolRequestProcessed::class => [
            SendRejectToolRequestNotification::class,
        ],
        AcceptToolRequestProcessed::class => [
            SendAcceptToolRequestNotification::class,
        ],
        AcceptToolReturnProcessed::class => [
            SendAcceptToolReturnNotification::class,
        ],
        CalibrationDateProcessed::class => [
            CalibrationDateNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
