<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelType extends Model
{
  protected $table = 'hotel_type';

  protected $fillable = [
    'name',
    'meta_name',
  ];

  public function Hotel()
  {
    return $this->hasMany('App\Models\Hotel', 'hotel_manager_id', 'id');
  }
}
