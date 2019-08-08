<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CouponCode;
use App\Models\Hotel;
use Validator;
use App\Models\RoomType;
use Hash;
use Carbon\Carbon;
use Auth;
use App\Models\ApplyCouponCodeRoomType;
use App\Models\HotelFollowing;
use App\Notifications\NewCouponCodeNotification;
use App\Http\Resources\HotelFrontendResource;
use App\Events\NewCouponCode;
use App\Models\User;

class CouponCodeController extends Controller
{
    //get code
    public function index(Request $req)
    {
        $hotel = Hotel::find($req->hotelId);
        $couponCodes = array();
        $expiredCouponCodes = array();
        $waitingCouponCodes = array();
        if ($hotel == null)
            return response()->json([
                'status' => false,
                'couponCode' => $couponCodes,
                'expiredCouponCode' => $expiredCouponCodes,
                'waitingCouponCode' => $waitingCouponCodes,
            ]);
        foreach ($hotel->CouponCode as $couponCode) {
            if ($couponCode->start_at != null) {
                if ($couponCode->end_at != null && !Carbon::now()->lessThan(Carbon::parse($couponCode->end_at))) {
                    $couponCode->status = false;
                    $expiredCouponCodes[] = $couponCode;
                } else {
                    $couponCode->status = true;
                    if (Carbon::now()->lessThan(Carbon::parse($couponCode->start_at))) {
                        $waitingCouponCodes[] = $couponCode;
                    } else {
                        $couponCode->days = Carbon::now()->diffInDays($couponCode->start_at);
                        $couponCodes[] = $couponCode;
                    }
                }
            } else {
                $couponCode->status = true;
                $waitingCouponCodes[] = $couponCode;
            }
        }
        return response()->json([
            'status' => true,
            'couponCode' => $couponCodes,
            'expiredCouponCode' => $expiredCouponCodes,
            'waitingCouponCode' => $waitingCouponCodes,
        ]);
    }

    //get code/create
    public function create()
    {
        return;
    }

    //post code
    public function store(Request $req)
    {
        $couponCode = $req->code;
        $validateData = Validator::make(
            $couponCode,
            [
                'code' => 'required',
                'title' => 'required',
                'discount' => 'required|min:1',
                'amount' => 'required|min:1',
            ]
        );
        if ($validateData->fails()) {
            return response()->json([
                'status' => false,
                'data' => $validateData->errors(),
            ]);
        }
        $oldCouponCodes = CouponCode::where('code', $couponCode['code'])->get();
        if (sizeOf($oldCouponCodes) > 0) {
            return response()->json([
                'status' => false,
                'messages' => "Code đã tạo.",
            ]);
        }
        $start_at = strlen($couponCode['start_at']) == 0 ? null : $couponCode['start_at'];
        $end_at = strlen($couponCode['end_at']) == 0 ? null : $couponCode['end_at'];
        $newCouponCode = CouponCode::create([
            'code' => $couponCode['code'],
            'title' => $couponCode['title'],
            'start_at' => $start_at,
            'end_at' => $end_at,
            'content' => $couponCode['content'],
            'discount_value' => $couponCode['discount'],
            'apply_amount' => $couponCode['amount'],
            'applied_amount' => 0,
            'hotel_id' => $req->hotelId,
        ]);
        $roomTypes = $req->roomTypes;
        foreach ($roomTypes as $key => $value) {
            ApplyCouponCodeRoomType::create([
                'coupon_code_id' => $newCouponCode->id,
                'room_type_id' => $value
            ]);
        }
        if ($newCouponCode->start_at == null || Carbon::now()->lessThan(Carbon::parse($newCouponCode->start_at))) {
            $newCouponCode->waiting = true;
        } else {
            $newCouponCode->during = true;
            $newCouponCode->days = Carbon::now()->diffInDays(Carbon::parse($newCouponCode->start_at));
        }
        $hotel = new HotelFrontendResource(Hotel::find($req->hotelId));
        $message = $hotel->name . " vừa cập nhật một mã khuyến mãi.";
        $hotelFollowers = HotelFollowing::where('hotel_id', $req->hotelId)->get('customer_id');
        foreach ($hotelFollowers as $follower) {
            User::find($follower->customer_id)->notify(new NewCouponCodeNotification($hotel, $message, $newCouponCode));
        }
        broadcast(new NewCouponCode($hotel, $message, $newCouponCode));
        return response()->json([
            'status' => true,
            'data' => $newCouponCode,
        ]);
    }

    //get code/{code}
    public function show($id)
    {
        return;
    }

    //code/{code}/edit
    public function edit($id)
    {
        return;
    }

    //put/patch code/{code}
    public function update($id, Request $req)
    {
        $couponCode = CouponCode::find($id);
        if ($couponCode == null) {
            return response()->json([
                'status' => false
            ]);
        }
        $now = Carbon::now()->toDateString();
        if ($req->flag == 0) {
            $couponCode->update([
                'end_at' => $now,
            ]);
            $couponCode->status = false;
        } else if ($req->flag == 1) {
            $end_at = $req->end_at;
            $couponCode->update([
                'end_at' => $end_at,
            ]);
        }
        return response()->json([
            'status' => true,
            'couponCode' => $couponCode,
        ]);
    }

    //delete code/{code}
    public function destroy($id)
    {
        CouponCode::find($id)->delete();
        return response()->json([
            'status' => true,
        ]);
    }
}
