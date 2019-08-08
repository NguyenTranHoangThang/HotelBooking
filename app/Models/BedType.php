<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BedType extends Model
{
  protected $table = 'bed_type';

  protected $fillable = [
    'name',
    'width',
    'length',
    'status',
  ];

  public function RoomBedType()
  {
    return $this->hasMany('App\Models\RoomBedType', 'bed_type_id', 'id');
  }
}
