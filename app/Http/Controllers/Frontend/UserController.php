<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserImage;
use App\Models\Customer;
use App\Models\CustomerFollowing;
use App\Models\HotelFollowing;
use App\Models\Hotel;
use Auth;
use Validator;
use Hash;
use Session;

class UserController extends Controller
{
    //get top 5
    public function getTop5(Request $req)
    {
        $user = array();
        $customer = Customer::where('user_id', '<>', $req->id)->orderBy('coin', 'desc')->take(5)->get()->toArray();
        foreach ($customer as $key => $value) {
            $tam = array();
            $tam['user'] = User::find($value['user_id']);
            $tam['avatar'] = UserImage::where('user_id', $value['user_id'])->where('is_primary', 1)->first();
            if (CustomerFollowing::where('follower_id', $req->id)->where('followed_id', $value['user_id'])->first() != null) $tam['follow'] = true;
            else $tam['follow'] = false;
            $user[] = $tam;
        }
        return response()->json(['user' => $user, 'customer' => $customer]);
    }
    //check user
    public function getUser(Request $req)
    {
        $user = Auth::user();
        $user->Customer;
        $avatar = UserImage::where('user_id', $user->id)->where('is_primary', 1)->first();
        $user->avatar = $avatar;
        $user->review = $user->reViewList($user->id);
        $user->question = $user->QuestionList();
        $user->booking = $user->bookingList();        
        return response()->json([
            'user' => $user
        ]);
    }
    //check user password
    public function checkUserPassword(Request $req)
    {
        if (User::find($req->id)) {
            $user = User::find($req->id);
            if (Auth::attempt(['username' => $user->username, 'password' => $req->password])) {
                return response()->json(['mess' => true]);;
            }
        }
        return response()->json(['mess' => false]);
    }
    //check user password
    public function updateUserPassword(Request $req)
    {
        if (User::find($req->id)) {
            $user = User::find($req->id);
            if (Auth::attempt(['username' => $user->username, 'password' => $req->password])) {
                $user->update(['password' => Hash::make($req->newPassword)]);
                return response()->json(['status' => true]);
            }
        }
        return response()->json(['status' => false]);
    }
    //follow
    public function follow(Request $req)
    {
        $f = null;
        if ($req->type == 0) {
            if (User::find($req->followed)) {
                $f = CustomerFollowing::create([
                    'follower_id' => Auth::user()->id,
                    'followed_id' => $req->followed
                ]);
            }
        } else {
            if (Hotel::find($req->followed)) {
                $f = HotelFollowing::create([
                    'customer_id' => Auth::user()->id,
                    'hotel_id' => $req->followed
                ]);
            }
        }
        return response()->json(['data' => $f]);
    }
    //unfollow
    public function unfollow(Request $req)
    {
        if ($req->type == 0) {
            if (User::find($req->followed)) {
                CustomerFollowing::where('follower_id', Auth::user()->id)->where('followed_id', $req->followed)->delete();
            }
        } else {
            if (Hotel::find($req->followed)) {
                HotelFollowing::where('customer_id',Auth::user()->id)->where('hotel_id', $req->followed)->delete();
            }
        }
        return response()->json(['data' => true]);
    }
    public function getUserInfo(Request $req)
    {
        $id = $req->id;
        $user = User::find($id);
        $user->Customer;
        $avatar = UserImage::where('user_id', $user->id)->where('is_primary', 1)->first();
        $user->avatar = $avatar;
        $followers = $user->Followers();
        $user->follow = false;
        $user->review = $user->reViewList(Auth::user()->id);
        if (sizeOf($followers) > 0) {
            foreach ($followers as $f) {
                if ($f->follower_id ==  Auth::user()->id) {
                    $user->follow = true;
                }
            }
        }
        if ($id == Auth::user()->id)
            return response()->json([
                'status' => false,
                'user' => $user,
            ]);
        return response()->json([
            'status' => true,
            'user' => $user,
        ]);
    }
    //put/patch user/{user}
    public function update($id, Request $req)
    {
        if (User::find($id)) {
            $user = User::find($id);
            $user->update([
                'name' => $req->name,
                'phone_number' => $req->phone_number,
            ]);
            $customer = Customer::where('user_id', $id)->first()->update(['address' => $req->address]);
            return response()->json(['status' => true]);
        }
        return response()->json(['status' => false]);
    }
}
