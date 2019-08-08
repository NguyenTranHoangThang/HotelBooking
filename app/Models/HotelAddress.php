<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelAddress extends Model
{
  protected $table = 'hotel_address';

  protected $fillable = [
    'ward_id',
    'hotel_id',
    'address',
  ];

  public function Ward()
  {
    return $this->belongsTo('App\Models\Ward', 'ward_id', 'id');
  }

  public function Hotel()
  {
    return $this->belongsTo('App\Models\Hotel', 'hotel_id', 'id');
  }
}
