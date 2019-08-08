<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CouponCode extends Model
{
  use SoftDeletes;
  protected $table = 'coupon_code';

  protected $fillable = [
    'code',
    'discount_value',
    'title',
    'content',
    'start_at',
    'end_at',
    'apply_amount',
    'applied_amount',
    'hotel_id',
  ];

  public function Hotel()
  {
    return $this->belongsTo('App\Models\Hotel', 'hotel_id', 'id');
  }
  public function Apply()
  {
    return $this->hasMany('App\Models\ApplyCouponCodeRoomType', 'coupon_code_id', 'id');
  }
}
