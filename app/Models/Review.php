<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
  use SoftDeletes;
  protected $table = 'review';

  protected $fillable = [
    'title',
    'content',
    'useful_point',
    'point',
    'likes',
    'comments',
    'can_comment',
    'customer_id',
    'hotel_id',
    'booking_id',
  ];

  public function User()
  {
    return $this->belongsTo('App\Models\User', 'customer_id', 'id');
  }

  public function Customer()
  {
    $customer = User::find($this->customer_id);
    $customer->avatar = UserImage::where('user_id', $this->customer_id)->where('is_primary', 1)->first();
    return $customer;
  }
  public function Comment()
  {
    return $this->hasMany('App\Models\Comment', 'review_id', 'id')->orderBy('created_at', 'desc');
  }

  public function Hotel()
  {
    return $this->belongsTo('App\Models\Hotel', 'hotel_id', 'id');
  }

  public function CustomerReview($customerID, $reviewID)
  {
    $customer_review = CustomerReview::where('customer_id', $customerID)->where('review_id', $reviewID)->first();
    if ($customer_review == null) {
      $customer_review = new CustomerReview();
      $customer_review->customer_id = $customerID;
      $customer_review->status = 0;
      $customer_review->like = 0;
      $customer_review->useful = 0;
    }
    return $customer_review;
  }
  public function UserReview()
  {
    return $this->hasMany('App\Models\CustomerReview', 'review_id', 'id')->where('customer_id', '<>', 'customer_id');
  }
  public function Booking()
  {
    return $this->belongsTo('App\Models\Booking', 'booking_id', 'id');
  }
  public function bookingDetail()
  {
    $booking = Booking::find($this->booking_id);
    $data = [];
    $data['id'] = $booking->id;
    $data['check_in'] = $booking->check_in;
    $data['check_out'] = $booking->check_out;
    $data['days'] = Carbon::parse($booking->check_in)->diffInDays(Carbon::parse($booking->check_out));
    $data['discount_value'] = $booking->discount_value;
    $data['room_amount'] = $booking->room_amount;
    $room = $booking->Room;
    $temp = array();
    foreach ($room->roomFeature() as $f) {
      $temp[] = $f->FeatureResource();
    }
    $room->feature = $temp;
    $data['room'] = $room;
    return $data;
  }
}
