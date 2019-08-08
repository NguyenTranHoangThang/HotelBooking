<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
  protected $table = 'district';

  protected $fillable = [
    'id',
    'name',
    'province_id',
  ];

  public function Ward()
  {
    return $this->hasMany("App\Models\Ward", "district_id", "id");
  }

  public function Province()
  {
    return $this->belongsTo('App\Models\Province', 'province_id', 'id');
  }
}
