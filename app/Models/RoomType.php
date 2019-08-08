<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ServiceRoomType;
use App\Models\ApplyCouponCodeRoomType;

class RoomType extends Model
{
  protected $table = 'room_type';

  protected $fillable = [
    'name',
    'status',
    'description',
    'amount',
  ];

  public function ServiceRoomType()
  {
    return $this->hasMany('App\Models\ServiceRoomType', 'room_type_id', 'id');
  }
  public function Room()
  {
    return $this->hasMany('App\Models\Room', 'room_type_id', 'id');
  }
  public function Apply()
  {
    return ApplyCouponCodeRoomType::where('room_type_id', $this->id)->get();
  }

  public function Service()
  {
    return $this->belongsToMany('App\Models\Service', 'service_room_type', 'room_type_id', 'service_id');
  }
  
  public function ServiceRoomTypeByHotel($id)
  {
    return ServiceRoomType::where('room_type_id', $this->id)->where('hotel_id', $id)->get();
  }
}
