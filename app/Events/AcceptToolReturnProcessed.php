<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AcceptToolReturnProcessed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $tools;
    public $user;
    public $modality;
    public $requestDate;
    public $pickupType;
    public $toolStatus;
    public $toolAvailabiltyNotityUsers;
    public function __construct($details = array())
    {   
        $this->tools = $details['tools'];
        $this->user = $details['user'];
        $this->admin = $details['admin'];
        $this->modality = $details['tools']->modality->name;
        $this->requestDate = $details['created_at'];
        $this->toolStatus = $details['tool_status'];
        $this->pickupType = $details['pickup_type'];

        $this->toolAvailabiltyNotityUsers = $details['toolAvailabiltyNotityUsers'];
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
