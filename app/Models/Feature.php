<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
  protected $table = 'feature';

  protected $fillable = [
    'name',
    'meta_name',
    'status',
  ];

  public function Room()
  {
    return $this->belongsToMany("App\Models\Room", "room_feature", "feature_id", "room_id");
  }

  public function RoomByHotel($id)
  {
    $data = [];
    $rooms = $this->Room;
    foreach ($rooms as $room) {
      if ($room->hotel_id == $id) {
        $data[] = $room->id;
      }
    }
    return $data;
  }
}
