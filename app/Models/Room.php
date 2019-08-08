<?php

namespace App\Models;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\RoomModeResource;
use App\Http\Resources\RoomTypeResource;
use App\Models\RoomType;
use App\Http\Resources\RoomBedTypeResource;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
  use SoftDeletes;
  protected $table = 'room';

  protected $fillable = [
    'status',
    'room_name',
    'description',
    'price',
    'max_adult_amount',
    'max_child_amount',
    'free_child_amount',
    'room_size',
    'amount',
    'room_mode_id',
    'room_type_id',
    'hotel_id',
  ];

  public function Hotel()
  {
    return $this->belongsTo('App\Models\Hotel', 'hotel_id', 'id');
  }

  public function RoomTypeResource()
  {
    return new RoomTypeResource(RoomType::find($this->room_type_id));
  }

  public function RoomType()
  {
    return $this->belongsTo('App\Models\RoomType', 'room_type_id', 'id');
  }

  public function RoomModeResource()
  {
    return new RoomModeResource(RoomMode::find($this->room_mode_id));
  }

  public function RoomMode()
  {
    return $this->belongsTo('App\Models\RoomMode', 'room_mode_id', 'id');
  }
  public function Booking()
  {
    return $this->hasMany('App\Models\Booking', 'room_id', 'id');
  }

  public function couponCode()
  {
    $roomType = RoomType::find($this->room_type_id);
    $hotel  = Hotel::find($this->hotel_id);
    $couponCodes = $roomType->Apply();
    $temp = array();
    foreach($couponCodes as $c){
      if(CouponCode::find($c->coupon_code_id)!=null && CouponCode::find($c->coupon_code_id)->hotel_id == $hotel->id){
        $couponCode = CouponCode::find($c->coupon_code_id);
        if (!Carbon::now()->lessThan(Carbon::parse($couponCode->start_at))) {
          if ($couponCode->end_at == null || Carbon::now()->lessThan(Carbon::parse($couponCode->end_at))) {
            if (($couponCode->apply_amount - $couponCode->applied_amount) > 0)
              $temp[] =  $couponCode;              
          }
        }
      }
    }
    return $temp;
  }

  public function RoomBedType()
  {
    return $this->hasMany('App\Models\RoomBedType', 'room_id', 'id');
  }

  public function RoomBedTypeResource()
  {
    $bedtype = array();
    foreach ($this->RoomBedType as $rBT) {
      $rBT->bed_type = $rBT->BedTypeResource();
      $bedtype[] = $rBT;
    }
    return $bedtype;
  }

  public function Feature()
  {
    return $this->hasMany('App\Models\RoomFeature','room_id','id');
  }

  public function roomFeature()
  {
    return RoomFeature::where('room_id',$this->id)->get();
  }

  public function availableRoomAmount($check_in, $check_out)
  {
    $number = $this->amount;
    // return $number;
    $bookings = Booking::where("room_id", $this->id)->get();
    if (sizeOf($bookings) <= 0) {
      return $number;
    }
    foreach ($bookings as $booking) {
      $bookingCheckIn = Carbon::createMidnightDate($booking->check_in);
      $bookingCheckOut = Carbon::createMidnightDate($booking->check_out);
      if ($check_in->lessThan($bookingCheckIn)) {
        if ($check_out <= $bookingCheckIn) {
          // duoc dat phong
          //kiem tra trang thai booking, neu booking cancel thi khong giam so luong phong
        } else {
          $number -= $booking->room_amount;
        }
      } else {
        if ($check_in->greaterThanOrEqualTo($bookingCheckOut)) {
          // duoc dat phong
        } else {
          $number -= $booking->room_amount;
        }
      }
    }
    return $number;
  }
  public function Image()
  {
    return $this->hasMany('App\Models\RoomImage', 'room_id', 'id');
  }
}
