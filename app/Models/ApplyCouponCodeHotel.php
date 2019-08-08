<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplyCouponCodeHotel extends Model
{
  use SoftDeletes;
  protected $table = 'apply_coupon_code_hotel';

  protected $fillable =[
    'coupon_code_id',
    'hotel_id',
  ];
}
