<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingDetail extends Model
{
  protected $table = 'booking_detail';

  protected $fillable =[
    'room_type',
    'price',
    'adult-amount',
    'child_amount',
    'bed_type',
    'check_in',
    'check_out',
    'booking_id',
    'room_id',
  ];
}
