<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Room;
use App\Models\Booking;
use App\Models\RoomImage;

class AcceptOrderOffline extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $booking;
    public $room;
    public $image;
    public $stay_days;
    public function __construct(Booking $booking,Room $room,RoomImage $image,$stay_days)
    {
        $this->booking = $booking;
        $this->room = $room;
        $this->image = $image;
        $this->stay_days = $stay_days;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('Mail.confirmOffline');
    }
}
