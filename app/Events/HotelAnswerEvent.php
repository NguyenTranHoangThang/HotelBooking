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

class HotelAnswerEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $hotel,$question,$answer,$message,$hotel_id;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($hotel, $question, $answer)
    {
        $this->hotel = $hotel;
        $this->question = $question;
        $this->answer = $answer;
        $this->message = $hotel.' đã trả lời.';

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('question');
    }

    public function broadcastAs(){
        return 'hotel-answer';
    }
}
