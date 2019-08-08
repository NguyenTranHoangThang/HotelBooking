<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserImage;
use App\Models\Booking;
use App\Models\BookingStatus;
use Validator;
use Hash;
use Session;

class BookingStatusController extends Controller
{
    //get status
    public function index()
    {
        return response()->json([
            'data' => BookingStatus::all()
        ]);
    }
}
