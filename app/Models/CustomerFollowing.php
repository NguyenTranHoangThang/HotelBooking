<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerFollowing extends Model
{
  use SoftDeletes;
  protected $table = 'customer_following';

  protected $fillable = [
    'follower_id',
    'followed_id',
  ];

  public function Follower()
  {
    return $this->belongsTo('App\Models\User', 'follower_id', 'id');
  }

  public function Followed()
  {
    return $this->belongsTo('App\Models\User', 'followed_id', 'id');
  }
}
