<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\Booking;
use Auth;
use JWTAuth;
use Validator;
use Hash;
use Session;

class PaymentMethodController extends Controller
{
    //get paymentMethod
    public function index()
    {
        $arr = PaymentMethod::all();
        return response()->json(['data' => $arr, 'status' => true]);
    }
}
