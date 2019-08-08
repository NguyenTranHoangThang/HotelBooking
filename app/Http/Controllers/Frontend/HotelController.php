<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hotel;
use App\Models\HotelType;
use App\Models\HotelFollowing;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\RoomMode;
use App\Models\RoomBedType;
use App\Models\BedType;
use App\Models\Feature;
use App\Models\ServiceRoomType;
use App\Models\Service;
use App\Http\Resources\ServiceResource;
use Carbon\Carbon;
use App\Models\HotelImage;
use App\Models\RoomImage;
use App\User;

class HotelController extends Controller
{
    //get hotels
    public function index()
    {
        $status = false;
        $hotels = Hotel::all();
        if ($hotels->count() > 0) {
            $status = true;
            foreach ($hotels as $h) {
                $h->maxPrice = $h->maxPrice();
                $h->minPrice = $h->minPrice();
            }
        }
        return response()->json(['status' => $status, 'data' => $hotels]);
    }
    //get top 5
    public function getTop5(Request $req)
    {
        $hotel =  array();
        foreach (Hotel::orderBy('rank_point', 'desc')->take(5)->get()->toArray() as $key => $value) {
            $tam = array();
            $tam['hotel'] = $value;
            if (HotelFollowing::where('customer_id', $req->id)->where('hotel_id', $value['id'])->first() != null) $tam['follow'] = true;
            else $tam['follow'] = false;
            $hotel[] = $tam;
        }
        return response()->json(['top5' => $hotel]);
    }
    //get hotels/{hotels}
    public function show(Request $req, $id)
    {
        $status = false;
        $hotel = null;
        if ($id > 0) {
            $hotel = Hotel::find($id);
            if ($hotel != null) {
                $hotel->Policy;
                $hotel->paymentMethods = $hotel->paymentMethods();
                $hotel->image = HotelImage::where('hotel_id', $hotel->id)->where('is_primary', 1)->first()->image_link;
                $hotel->images = HotelImage::where('hotel_id', $hotel->id)->get();
                $status = true;
                $hotel->hotel_type = $hotel->HotelTypeResource();
                $hotel->service = $hotel->ServiceResource();
                $hotel->followed = false;
                $hotel->coupon_code = $hotel->getCouponCode();
                if ($req->userID != null) {
                    if (HotelFollowing::where('customer_id', $req->userID)->where('hotel_id', $hotel->id)->first() != null)
                        $hotel->followed = true;
                }
            } else {
                $hotel = Hotel::withTrashed()->find($id);
                if ($hotel != null) {
                    $hotel->Policy;
                    $hotel->paymentMethods = $hotel->paymentMethods();
                    $hotel->image = HotelImage::where('hotel_id', $hotel->id)->where('is_primary', 1)->first()->image_link;
                    $hotel->images = HotelImage::where('hotel_id', $hotel->id)->get();
                    $status = false;
                    $hotel->hotel_type = $hotel->HotelTypeResource();
                    $hotel->service = $hotel->ServiceResource();
                    $hotel->followed = false;
                    $hotel->coupon_code = $hotel->getCouponCode();
                    if ($req->userID != null || User::find($req->userID)!=null) {
                        if (HotelFollowing::where('customer_id', $req->userID)->where('hotel_id', $hotel->id)->first() != null)
                            $hotel->followed = true;
                    }
                }
            }
        }
        return response()->json(['status' => $status, 'data' => $hotel]);
    }
    public function questionsByHotel(Request $req)
    {
        $questions = array();
        return response()->json([
            'questions' => Hotel::find($req->hotel_id)->questionList() ? Hotel::find($req->hotel_id)->questionList() : $questions,
        ]);
    }
    public function reviewsByHotel(Request $req)
    {
        $hotel = Hotel::find($req->hotel_id);
        $reviews = array();
        $status = false;
        if ($hotel != null) {
            $reviews = $hotel->reviewList($req->user_id);
        }
        return response()->json([
            'status' => $status,
            'reviews' => $reviews,
        ]);
    }
    public function roomsByHotel(Request $req)
    {
        $id = $req->hotel_id;
        $hotel = Hotel::find($id);
        if ($hotel == null) {
            return response()->json([
                'status' => false,
                'room' => array()
            ]);
        }
        $rooms = Room::where('hotel_id', $hotel->id)->orderBy('price')->paginate(2);
        foreach ($rooms as $r) {
            $r->couponCode = $r->couponCode();
            $r->image = RoomImage::where('room_id', $r->id)->where('is_primary', 1)->first()->image_link;
            $r->images = RoomImage::where('room_id', $r->id)->orderBy('is_primary', 'asc')->get();
            $r->room_mode = $r->RoomModeResource();
            $r->room_type = $r->RoomTypeResource();
            $tempRoomType = $r->room_type;
            $r->room_bed_type = $r->RoomBedTypeResource();
            $checkinExplode = explode("-", $req->check_in);
            $checkoutExplode = explode("-", $req->check_out);
            $checkIn = Carbon::createMidnightDate($checkinExplode[0], $checkinExplode[1], $checkinExplode[2]);
            $checkOut = Carbon::createMidnightDate($checkoutExplode[0], $checkoutExplode[1], $checkoutExplode[2]);
            $r->days = $checkIn->diffInDays($checkOut);
            $r->amount = $r->availableRoomAmount($checkIn, $checkOut);
            $temp = array();
            foreach ($r->roomFeature() as $f) {
                $temp[] = $f->FeatureResource();
            }
            $r->feature = $temp;
            $servicesRoomType = ServiceRoomType::where('room_type_id', $tempRoomType->id)->where('hotel_id', $id)->get();
            $teampService = null;
            foreach ($servicesRoomType as $sRT) {
                $teampService[] = new ServiceResource($sRT->Service);
            }
            $r->service = $teampService;
            $r->bookingAmount = 1;
        }
        return response()->json([
            'status' => true,
            'room' => $rooms
        ]);
    }
    public function getHotel($id)
    {
        return response()->json(['data' => Hotel::find($id)->toArray()]);
    }
}
