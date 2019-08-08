<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CustomerFollowing;
use App\Models\HotelFollowing;
use Request;
use Auth;
use Validator;
use Hash;
use Session;

class CustomerController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }
  public function index()
  {
    return view('Frontend.customer.testLogin');
  }
  public function logout()
  {
    Auth::logout();
    return redirect()->route('home');
  }
  public function followUser($id)
  {
    $follow = new CustomerFollowing();
    $follow->follower_id = Auth::user()->id;
    $follow->followed_id = $id;
    $follow->save();
    return redirect()->back();
  }
  public function unfollowUser($id)
  {
    $follow = CustomerFollowing::where('followed_id', $id)->where('follower_id', Auth::user()->id);
    $follow->delete();
    return redirect()->back();
  }
  public function followHotel($id)
  {
    $follow = new HotelFollowing();
    $follow->customer_id = Auth::user()->id;
    $follow->hotel_id = $id;
    $follow->save();
    return redirect()->back();
  }
  public function unfollowHotel($id)
  {
    $follow = HotelFollowing::where('hotel_id', $id)->where('customer_id', Auth::user()->id);
    $follow->delete();
    return redirect()->back();
  }
}
