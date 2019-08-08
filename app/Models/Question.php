<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
  use SoftDeletes;
  protected $table = 'question';

  protected $fillable = [
    'id',
    'title',
    'content',
    'customer_id',
    'hotel_id',
  ];

  public function Hotel()
  {
    return $this->belongsTo('App\Models\Hotel', 'hotel_id', 'id');
  }

  public function User()
  {
    return $this->belongsTo('App\Models\User', 'customer_id', 'user_id');
  }

  public function Customer()
  {
    $customer = User::find($this->customer_id);
    $customer->avatar = UserImage::where('user_id', $this->customer_id)->where('is_primary', 1)->first();
    return $customer;
  }
  
  public function Reply()
  {
    return $this->belongsTo('App\Models\Reply', 'id', 'question_id');
  }
}
