<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomImage extends Model
{
  use SoftDeletes;
    protected $table = 'room_image';

  protected $fillable =[
    'image_link',
    'is_primary',
    'room_id',
  ];
}
