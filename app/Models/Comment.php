<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
  use SoftDeletes;
  protected $table = 'comment';

  protected $fillable = [
    'customer_id',
    'review_id',
    'content',
  ];

  public function Customer()
  {
    $user = User::find($this->customer_id);
    $user->avatar = UserImage::where('user_id', $user->id)->where('is_primary', 1)->first();
    return $user;
  }

  public function Review()
  {
    return $this->belongsTo('App\Models\Review', 'review_id', 'id');
  }
}
