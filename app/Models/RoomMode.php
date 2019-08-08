<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\RoomResource;

class RoomMode extends Model
{
  protected $table = 'room_mode';

  protected $fillable = [
    'price',
    'name',
    'status',
  ];
  public function roomByHotel($hotel_id)
  {
    $rooms = [];
    foreach(Hotel::find($hotel_id)->Room as $room){
      if($room->room_mode_id == $this->id){
        $rooms[] = new RoomResource($room);
      }
    }
    return $rooms;
  }
}
