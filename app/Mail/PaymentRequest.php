<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Booking;
use App\Models\Room;
use App\Models\RoomImage;

class PaymentRequest extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $credit_card;
    public $booking;
    public $policy;
    public $image;
    public $stay_days;
    public $bank;
    public function __construct(Booking $booking, $policy,RoomImage $image,$credit_card,$stay_days,$bank)
    {
        $this->booking = $booking;
        $this->policy = $policy;
        $this->image = $image;
        $this->stay_days = $stay_days;
        $this->credit_card = $credit_card;
        $this->bank = $bank;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('Mail.paymentRequest');
    }
}
