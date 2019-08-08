<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\UserImage;
use App\Models\Customer;
use Auth;
use JWTAuth;
use Validator;
use Hash;
use Session;

class AuthController extends Controller
{
    public function postLogin(Request $req)
    {
        $validateData = Validator::make(
            $req->all(),
            [
                'username' => 'required|min:4',
                'password' => 'required|min:4'
            ],
            [
                'username.required' => 'Chưa nhập username!',
                'username.min' => 'Username phải hơn 3 ký tự!',
                'password.required' => 'Chưa nhập pasword!',
                'password.min' => 'Password phải hơn 3 ký tự!'
            ]
        );
        if ($validateData->fails()) {
            $errors = $validateData->errors()->all();
            return response()->json([
                'token' => "",
                'status' => false,
                'errors' => $errors[0],
            ]);
        }
        $arr = array('username' => $req->username, 'password' => $req->password);
        if (!($token = JWTAuth::attempt($arr))) {
            return response()->json([
                'token' => "",
                'status' => false,
                'errors' => "Username hoặc password không đúng!",
            ]);
        } else {
            $user = Auth::user();
            if ($user->isCustomer()) {
                return response()->json(
                    [
                        'token' => $token,
                        'status' => true,
                        'errors' => "",
                    ]
                );
            }else{
                return response()->json(
                    [
                        'token' => null,
                        'status' => false,
                        'errors' => "Thông tin tài khoản không đúng!",
                    ]
                );
            }
        }
    }
    public function getUserLogin()
    {
        $user = Auth::user();
        $user->Customer;
        $avatar = UserImage::where('user_id', $user->id)->where('is_primary', 1)->first();
        $user->avatar = $avatar;
        $user->booking = $user->bookingList();
        return response()->json([
            'user' => $user,
        ]);
    }
    public function postLogout()
    {
        JWTAuth::invalidate();
        return response()->json(['status' => true]);
    }
    public function postRegister(Request $req)
    {
        $validateData = Validator::make(
            $req->all(),
            [
                'username' => 'required|min:3|unique:users,username',
                'password' => 'required|min:4',
                'email' => 'required|email|unique:users,email',
            ],
            [
                'username.required' => 'Chưa nhập username!',
                'username.min' => 'Username phải hơn 3 ký tự!',
                'username.unique' => 'Username đã tồn tại!',
                'password.required' => 'Chưa nhập password!',
                'password.min' => 'Password phải hơn 3 ký tự!',
                'email.required' => 'Email rỗng!',
                'email.email' => 'Lỗi kiểu e-mail!',
                'email.unique' => 'E-mail đã được đăng ký!',
            ]
        );
        $status = false;
        if ($validateData->fails()) {
            $errors = $validateData->errors()->all();
            return response()->json([
                'status' => $status,
                'errors' => $errors[0],
            ]);
        }
        $status = true;
        $user = new User();
        $user->username = $req->username;
        $user->name = $req->name;
        $user->password = Hash::make($req->password);
        $user->email = $req->email;
        $user->phone_number = $req->phone;
        $user->api_token = Str::random(60);
        $user->remember_token = Str::random(10);
        $user->save();
        UserImage::create([
            'image_link' => rand(1,25).'.png',
            'is_primary' => 1,
            'user_id' => $user->id,
            'name' => "",
        ]);
        $customer = new Customer();
        $customer->user_id = $user->id;
        $customer->address = $req->address;
        $customer->save();
        $arr = array('username' => $user->username, 'password' => $req->password);
        $token = JWTAuth::attempt($arr);
        return response()->json([
            'status' => $status,
            'token' => $token,
            'errors' => array("username" => "", "password" => "", "email" => ""),
        ]);
    }
}
