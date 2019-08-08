<?php

namespace App\Models;

use Carbon\Carbon;

use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Http\Resources\BookingStatusResource;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements JWTSubject
{
  use SoftDeletes;
  use Notifiable;

  protected $table = 'users';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'username',
    'email',
    'password',
    'phone_number'
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password', 'remember_token',
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];

  public function HotelManager()
  {
    return $this->hasMany('App\Models\HotelManager', 'user_id', 'id');
  }

  public function getCustomer()
  {
    return $this->belongsTo('App\Models\Customer', 'id', 'user_id');
  }

  public function Customer()
  {
    return $this->belongsTo('App\Models\Customer', 'id', 'user_id');
  }

  public function Booking()
  {
    return $this->hasMany('App\Models\Booking', 'customer_id', 'id')->orderBy('created_at','desc');
  }

  public function bookingList()
  {
    $bookingList = $this->Booking;
    foreach ($bookingList as $b) {
      $b->cancel_status = $b->cancelAble();
      $b->status = new BookingStatusResource($b->Status);
      $hotel = $b->Room->Hotel;      
      $b->room->room_mode = $b->Room->RoomMode;
      $b->room->room_type = $b->Room->RoomType;   
      $b->days =  Carbon::parse($b->check_in)->diffInDays( Carbon::parse($b->check_out));   
      $b->total = ($b->days)*($b->room_amount*$b->room_price*((100-$b->discount_value)/100));
      $b->test = $hotel->paymentMethods();
      foreach($hotel->paymentMethods() as $p){
        if($p['method']->id == $b->payment_method_id) $b->payment_method = [
          'id' =>$p['method']->id,
          'name' =>$p['method']->name,
          'content' =>$p['content'],
          ];
      }
      $temp = array();
      $temp['id'] = $hotel->id; 
      $temp['name'] = $hotel->name; 
      $temp['type'] = $hotel->HotelType->name; 
      $b->room->hotel = $temp; 
      $b->room->image = RoomImage::where('room_id',$b->room->id)->where('is_primary',1)->first()->image_link;
      $b->Review;
      $b->can_review = $b->reviewAble();
      $b->slug = 'bookingcode'.$b->id;
    }
    return $bookingList;
  }

  public function Followers()
  {
    $followers = CustomerFollowing::where('followed_id', $this->id)->get();
    foreach ($followers as $f) {
      $f->Follower;
      $f->avatar = UserImage::where('user_id', $f->follower_id)->where('is_primary', 1)->first();
    }
    return $followers;
  }

  public function Followings()
  {
    $followings = CustomerFollowing::where('follower_id', $this->id)->get();
    foreach ($followings as $f) {
      $f->Followed;
      $f->avatar = UserImage::where('user_id', $f->followed_id)->where('is_primary', 1)->first();
    }
    return $followings;
  }

  public function HotelFollowings()
  {
    $followings = HotelFollowing::where('customer_id', $this->id)->get();
    foreach ($followings as $f) {
      $f->Customer;
      $f->avatar = HotelImage::where('hotel_id', $f->hotel_id)->where('is_primary', 1)->first();
    }
    return $followings;
  }

  public function isFollowing($id)
  {
    $customerFollowing = $this->customerFollowings();
    foreach ($customerFollowing as $c) {
      if ($c->followed->id == $id) return true;
    }
    return false;
  }
  public function isFollowed($id)
  {
    $follower = $this->Followers();
    foreach ($follower as $f) {
      if ($f->follower->id == $id) return true;
    }
    return false;
  }
  public function Question()
  {
    return $this->hasMany('App\Models\Question', 'customer_id', 'id');
  }

  public function questionList()
  {
    $question = $this->Question;
    foreach ($question as $q) {
      $q->Hotel->HotelType;
      $q->Reply;
    }
    return $question;
  }

  public function Review()
  {
    return $this->hasMany('App\Models\Review', 'customer_id', 'id');
  }

  public function reViewList($id)
  {
    $reviews = Review::where('customer_id', $this->id)->get();
    foreach ($reviews as $r) {
      $r->customer_review = $r->CustomerReview($id, $r->id);
      $r->model = false;
      $r->Hotel;
      $r->hotel->image = HotelImage::where('hotel_id',$r->hotel->id)->where('is_primary',1)->first()->image_link;
      $r->hotel->services = $r->hotel->ServiceResource();
      foreach ($r->Comment as $c) {
        $c->customer = $c->Customer();
      };
    }
    return $reviews;
  }

  public function Comment()
  {
    return $this->hasMany('App\Models\Comment', 'customer_id', 'id');
  }

  public function Image()
  {
    return $this->hasMany('App\Models\UserImage', 'user_id', 'id');
  }

  public function isHotelManager()
  {
    foreach ($this->HotelManager as $key => $value) {
      return true;
    }
    return false;
  }

  public function isCustomer()
  {
    if ($this->getCustomer != null) return true;
    return false;
  }
  /**
   * Get the identifier that will be stored in the subject claim of the JWT.
   *
   * @return mixed
   */
  public function getJWTIdentifier()
  {
    return $this->getKey();
  }
  /**
   * Return a key value array, containing any custom claims to be added to the JWT.
   *
   * @return array
   */
  public function getJWTCustomClaims()
  {
    return [];
  }
}
