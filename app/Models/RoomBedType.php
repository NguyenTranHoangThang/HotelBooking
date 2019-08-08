<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\BedTypeResource;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomBedType extends Model
{
  use SoftDeletes;
  protected $table = 'room_bed_type';

  protected $fillable = [
    'bed_type_id',
    'room_id',
    'amount',
  ];

  public function BedType()
  {
    return $this->belongsTo('App\Models\BedType', 'bed_type_id', 'id');
  }

  public function BedTypeResource()
  {
    return new BedTypeResource(BedType::find($this->bed_type_id));
  }

  public function Room()
  {
    return $this->belongsTo('App\Models\Room', 'room_id', 'id');
  }
}
