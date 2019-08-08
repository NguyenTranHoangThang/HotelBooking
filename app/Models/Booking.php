<?php

namespace App\Models;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\HotelResource;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
  use SoftDeletes;
  protected $table = 'booking';

  protected $fillable = [
    'id',
    'hotel_name',
    'contact_name',
    'room_price',
    'room_amount',
    'check_in',
    'check_out',
    'contact_email',
    'contact_phone',
    'contact_address',
    'special_request',
    'bank',
    'coupon_code',
    'discount_value',
    'customer_id',
    'payment_method_id',
    'status_id',
    'room_id',
  ];

  public function checkInRequired()
  {
    $marks = [1, 2];
    if (in_array($this->status_id, $marks)) {
      $bookingCheckinExplode = explode("-", $this->check_in);
      $todayExplode = explode("-", date("Y-m-d"));
      $bookingCheckIn = Carbon::createMidnightDate($bookingCheckinExplode[0], $bookingCheckinExplode[1], $bookingCheckinExplode[2]);
      $today = Carbon::createMidnightDate($todayExplode[0], $todayExplode[1], $todayExplode[2]);
      if (!$today->lessThan($bookingCheckIn)) {
        $this->update([
          'status_id' => 7
        ]);
      }
    }
  }

  public function Status()
  {
    return $this->belongsTo('App\Models\BookingStatus', 'status_id', 'id');
  }

  public function PaymentMethod()
  {
    return $this->belongsTo('App\Models\PaymentMethod', 'payment_method_id', 'id');
  }

  public function Customer()
  {
    return $this->belongsTo('App\Models\Customer', 'customer_id', 'user_id');
  }

  public function Review()
  {
    return $this->belongsTo('App\Models\Review', 'id', 'booking_id');
  }

  public function Room()
  {
    return $this->belongsTo('App\Models\Room', 'room_id', 'id');
  }

  public function Hotel()
  {
    $room = $this->Room;
    $hotel = new HotelResource(Hotel::find($room->hotel_id));
    return $hotel;
  }

  public function reviewAble()
  {
    $status = false;
    if ($this->status_id == 4 && $this->Review == null) {
      $bookingCheckOutExplode = explode("-", $this->check_out);
      $todayExplode = explode("-", date("Y-m-d"));
      $bookingCheckOut = Carbon::createMidnightDate($bookingCheckOutExplode[0], $bookingCheckOutExplode[1], $bookingCheckOutExplode[2]);
      $today = Carbon::createMidnightDate($todayExplode[0], $todayExplode[1], $todayExplode[2]);
      if (!$today->lessThan($bookingCheckOut)) {
        $status = true;
      }
    }
    return $status;
  }

  public function cancelAble()
  {
    $this->checkInRequired();
    $status = false;
    $policy = $this->Hotel()->Policy;
    switch ($this->Status->id) {
      case 1:
        $status = true;
        break;
      case 2:
      case 4:
        if($policy->cancelable ==0){
          $status = false;
        }else{
          $now = Carbon::now();
          $bookingCheckIn = Carbon::parse($this->check_in);
          if($now->lessThan($bookingCheckIn)){
            if($bookingCheckIn->diffInDays($now)>=$policy->cancelable){
              $status = true;
            }else{
              $status = false;
            }
          }else{
            $status = false;
          }
        }
        break;
      default:
        $status = false;
        break;
    }
    return $status;
  }
}
