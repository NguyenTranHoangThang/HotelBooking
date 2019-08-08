<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use App\Models\CustomerFollowing;
use App\Models\Hotel;
use App\Models\HotelFollowing;
use Auth;
use Validator;
use Hash;
use Session;

class PageController extends Controller
{

  public function index()
  {
    return view('Frontend.home');
  }

  public function login()
  {
    return view('Frontend.login');
  }

  public function postLogin(Request $req)
  {
    $this->validate(
      $req,
      [
        'password' => 'required|min:3|max:10',
        'username' => 'required|min:3'

      ],
      [
        'username.required' => 'Empty required',
        'username.min' => 'Length required: username is more than 3 charaters',
        'password.required' => 'Password is not matched',
        'password.min' => 'Length required: password is more than 3 charaters',
        'password.max' => 'Length required: username is less than 10 charaters'
      ]
    );
    $arr = array('username' => $req->username, 'password' => $req->password);
    if (Auth::attempt($arr)) {
      $req->session()->put('userLogin', true);
      return redirect()->route('customer');
    }
    return redirect()->back();
  }

  public function register()
  {
    return view('Frontend.register');
  }

  public function about()
  {
    return view('Frontend.about');
  }

  public function contact()
  {
    return view('Frontend.contact');
  }

  public function booking()
  {
    return view('Frontend.booking');
  }

  public function room()
  {
    return view('Frontend.room');
  }

  public function allHotels()
  {
    return view('Frontend.hotels');
  }

  public function hotelInfo($id)
  {
    if (!Hotel::find($id)) return redirect()->route('allHotels');
    $hotel = Hotel::find($id);
    $followed = false;
    if (Auth::check()) {
      if (HotelFollowing::where('hotel_id', $id)->first() && HotelFollowing::where('customer_id', Auth::user()->id)->first()) {
        $followed = true;
      }
    }
    return view('Frontend.hotel-info', compact('hotel', 'followed'));
  }

  public function blog()
  {
    return view('Frontend.blog');
  }

  public function getSingleHotel($id)
  {
    $data = array();
    $data = Hotel::find(1);
    return response()->json(['data' => $data]);
  }

  public function postRegister(Request $req)
  {
    if ($req->policy == null) {
      return redirect()->back();
    }
    $this->validate(
      $req,
      [
        'username' => 'required|min:3|unique:users,username',
        'password' => 'required|min:3|max:10',
        're_password' => 'required|min:3|max:10',
        'email' => 'required|unique:users,email'
      ],
      [
        'username.required' => 'Empty required',
        'username.min' => 'Length required: username is more than 3 charaters',
        'username.unique' => 'Existence of username',
        'password.unique' => 'Existence of password',
        'password.required' => 'Empty required',
        'password.min' => 'Length required: password is more than 3 charaters',
        'password.max' => 'Length required: password is under 10 charaters',
        're_password.required' => 'Empty required',
        're_password.min' => 'Length required: password is more than 3 charaters',
        're_password.max' => 'Length required: password is under 10 charaters',
        'email.unique' => 'Existence of email',
        'email.required' => 'Empty required',
      ]
    );
    $user = new User();
    $user->username = $req->username;
    $user->name = $req->name;
    $user->phone_number = $req->phone;
    $user->password = Hash::make($req->password);
    $user->email = $req->email;
    $user->save();
    $customer = new Customer();
    $customer->user_id = $user->id;
    $customer->address = $req->address;
    $customer->save();
    return redirect()->route('home');
  }

  public function user($id)
  {
    if (!User::find($id) || !User::find($id)->isCustomer()) return redirect()->route('home');
    else if (Auth::check() && Auth::user()->id == $id) return redirect()->route('profile');
    $user = array('user' => User::find($id), 'customer' => Customer::where('user_id', $id)->first());
    $followed = false;
    if (Auth::check()) {
      if (CustomerFollowing::where('followed_id', $id)->first()) {
        $followed = true;
      }
    }
    return view('Frontend.user', compact('user', 'followed'));
  }
}
