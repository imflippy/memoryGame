<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LogoutUser implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

//id of user to be removed from db
    public $idUser;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($idUser)
    {
        $this->idUser = $idUser;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('logout');
    }

    public function broadcastAs() {
      return 'offline';
    }
}
