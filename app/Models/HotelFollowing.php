<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HotelFollowing extends Model
{
  use SoftDeletes;
  protected $table = 'hotel_following';

  protected $fillable =[
    'customer_id',
    'hotel_id',
  ];

  public function Customer()
  {
    return $this->belongsTo('App\Models\User', 'customer_id', 'id');
  }

  public function Hotel()
  {
    return $this->belongsTo('App\Models\Hotel', 'hotel_id', 'id');
  }

}
