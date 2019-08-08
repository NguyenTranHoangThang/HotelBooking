<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Room;
use App\Http\Resources\RoomResource;
use Carbon\Carbon;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $room = Room::find($this->room_id)->first();
        $checkin = Carbon::parse($this->check_in);
        $checkout = Carbon::parse($this->check_out);
        $stay_days = $checkin->diffInDays($checkout);
        $total_price = $this->room_price *
         $this->room_amount *
          $stay_days *((100 - $this->discount_value)/100);
        $arrayData = [
            "id" => $this->id,
            "hotel_name" => $this->hotel_name,
            "room_name" => $room->room_name,
            "room_price" => $this->room_price,
            "room_amount" => $this->room_amount,
            "contact_name" => $this->contact_name,
            "contact_email" => $this->contact_email,
            "contact_phone" => $this->contact_phone,
            "contact_address" => $this->contact_address,
            "special_request" => $this->special_request,
            "bank" => $this->bank,
            "check_in" => $this->check_in,
            "check_out" => $this->check_out,
            "room_id" => $this->room_id,
            "customer_id" => $this->customer_id,
            "status" => $this->Status->name,
            "status_id" => $this->status_id,
            "payment_method" => $this->PaymentMethod->name,
            "room_images" => $this->Room->Image,
            "room" => new RoomResource($this->Room),
            "stay_days" => $stay_days,
            "total_price" => $total_price,
        ];
        
        
        return $arrayData;
    }
}
