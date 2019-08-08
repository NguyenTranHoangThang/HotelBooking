<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserImage extends Model
{
  use SoftDeletes;
  protected $table = 'user_image';

  protected $fillable = [
    'image_link',
    'is_primary',
    'name',
    'user_id',
  ];

  public function User()
  {
    return $this->belongsTo('App\Models\User', 'user_id', 'id');
  }
}
