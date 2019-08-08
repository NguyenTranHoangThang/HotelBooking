<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\ServiceResource;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceRoomType extends Model
{
  use SoftDeletes;
  protected $table = 'service_room_type';

  protected $fillable = [
    'service_id',
    'room_type_id',
    'hotel_id',
  ];

  public function Service()
  {
    return $this->belongsTo('App\Models\Service', 'service_id', 'id');
  }

  public function ServiceResource()
  {
    return new ServiceResource(Service::find($this->service_id));
  }

  public function RoomType()
  {
    return $this->belongsTo('App\Models\RoomType', 'room_type_id', 'id');
  }

  public function Hotel()
  {
    return $this->belongsTo('App\Models\Hotel', 'hotel_id', 'id');
  }
}
