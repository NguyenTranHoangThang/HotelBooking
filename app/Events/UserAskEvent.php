<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class UserAskEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $id,$data;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($hotel,$ask,$message,$question,$notificationId)
    {
        $arr = array();
        $arr['hotel'] = $hotel;
        $arr['ask'] = $ask;
        $arr['message'] = $message;
        $arr['question'] = $question;
        $this->data = $arr;
        $this->id = $notificationId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('ask');
    }

    public function broadcastAs(){
        return 'user-ask';
    }
}
