<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
  protected $table = 'plan';

  protected $fillable = [
    'name',
    'start_at',
    'end_at',
    'price',
    'customer_id',
    'province_id',
  ];

  public function Customer()
  {
    return $this->belongsTo('App\Models\Customer', 'customer_id', 'user_id');
  }

  public function Province()
  {
    return $this->belongsTo('App\Models\Province', 'province_id', 'id');
  }
}
