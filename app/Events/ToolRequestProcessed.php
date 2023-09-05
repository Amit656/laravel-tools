<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ToolRequestProcessed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
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
    public function __construct($details = array())
    {           
        $this->tools = $details['tools'];
        $this->user = $details['user'];
        $this->admin = $details['admin'];
        $this->modality = $details['tools'][0]->modality->name;
        $this->requestDate = $details['created_at'];
        $this->expectedReturnDate = $details['expected_return_date'];
        $this->pickupType = $details['pickup_type'];
        $this->dropLocation = $details['site_address'];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
