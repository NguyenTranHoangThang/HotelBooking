<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\RoomImage;
use App\Models\Room;
use Auth;
use JWTAuth;
use Validator;
use Hash;
use Session;
use App\Models\BookingStatus;
use App\Models\CouponCode;
use Illuminate\Support\Facades\Broadcast;
use App\Events\newBooking;
use App\Notifications\NewBooking as AppNewBooking;
use App\Models\Notification;
use Carbon\Carbon;
use App\Mail\BookingCreatedConfirm;
use Illuminate\Support\Facades\Mail;
use App\Models\Hotel;

class BookingController extends Controller
{
    //get booking
    public function index()
    {
        $user = Auth::user();
        $bookingList = $user->bookingList();
        if (sizeOf($bookingList) == 0) {
            return response()->json([
                'status' => false,
                'data' => $bookingList
            ]);
        }
        return response()->json([
            'status' => true,
            'data' => $bookingList
        ]);
    }
    //post booking
    public function store(Request $req)
    {
        $status = false;
        $mess = "";
        $booking = null;
        $b = $req->booking;
        // return $b['email'];
        $validateData = Validator::make(
            $b,
            [
                'payment' => 'required',
                'name' => 'required',
                'email' => 'required|email',
                'phone' => 'required',
                'address' => 'required',
                'checkIn' => 'required',
                'checkOut' => 'required',
            ]
        );
        if (!$validateData->fails()) {
            if (Carbon::parse($b['checkIn'])->diffInDays(Carbon::parse($b['checkOut'])) > 0 && Carbon::parse($b['checkIn'])->diffInDays(Carbon::parse(substr(Carbon::now(), 0, 10))) > 0) {
                $room = Room::find($b['roomId']);
                if (!$room || $room->amount < $b['roomAmount']) $status = false;
                else {
                    $status = true;
                    $mess = 'Thành công!';
                    $coupon_code = CouponCode::find($b['coupon_code_id']);
                    $discount = 0;
                    if ($coupon_code != null && (($coupon_code->apply_amount - $coupon_code->applied_amount) >0)) {
                        $coupon_code->update([
                            'applied_amount' => $coupon_code->applied_amount+1,
                        ]);
                        $discount = $coupon_code->discount_value;
                        $coupon_code = $coupon_code->code;
                    }
                    $booking = Booking::create([
                        'hotel_name' => $room->Hotel->name,
                        'room_price' => $room->price,
                        'room_amount' => $b['roomAmount'],
                        'contact_name' => $b['name'],
                        'contact_email' => $b['email'],
                        'contact_phone' => $b['phone'],
                        'contact_address' => $b['address'],
                        'special_request' => $b['request'],
                        'bank' => $room->Hotel->bank,
                        'check_in' => $b['checkIn'],
                        'check_out' => $b['checkOut'],
                        'room_id' => $b['roomId'],
                        'customer_id' => Auth::user()->id,
                        'payment_method_id' => $b['payment'],
                        'coupon_code' => $coupon_code,
                        'discount_value' => $discount,
                        'status_id' => 1,
                    ]);
                    $booking->Status;
                    $booking->cancel_status = $booking->Hotel()->CancelableStatus();;
                    $booking->PaymentMethod;
                    $hotel = $booking->Room->Hotel;
                    $booking->room->room_mode = $booking->Room->RoomMode;
                    $booking->room->room_type = $booking->Room->RoomType;
                    $booking->room->days = Carbon::parse($booking->check_in)->diffInDays(Carbon::parse($booking->check_out));
                    $temp = array();
                    $temp['id'] = $hotel->id;
                    $temp['name'] = $hotel->name;
                    $temp['type'] = $hotel->HotelType->name;
                    $booking->room->hotel = $temp;
                    $booking->room->image = RoomImage::where('room_id', $booking->room->id)->where('is_primary', 1)->first()->image_link;
                    $place = $temp['type']." - ".$temp['name'];
                    $days = $booking->room->days;
                    Mail::to(Auth::user())->queue(new BookingCreatedConfirm(Auth::user(),$booking,$place,$days));
                }
            } else {
                $status = false;
                $mess = 'Lỗi dữ liệu.';
            }
        } else return response()->json(['status' => $status, 'mess' => $validateData->errors()]);        
        $hotel = $booking->Room->Hotel;
        $hotel->notify(new AppNewBooking(Booking::find($booking->id)));
        $notificationId = $hotel->unreadNotifications->first()->id;
        Broadcast(new newBooking(Booking::find($booking->id),$notificationId,$hotel->id));
        return response()->json(['status' => $status, 'mess' => $mess, 'booking' => $booking]);
    }
    //get booking/{booking}
    public function show($id)
    { 
        $data = array();
        $booking = Booking::find($id);
        $data['check_in'] = $booking->check_in;
        $data['check_out'] = $booking->check_out;
        $data['cancel_status'] = $booking->CancelAble();
        $hotel = $booking->Room->Hotel; 
        $data['payment'] = $hotel->paymentMethods();
        $data['id'] = $booking->id;
        $data['status'] = $booking->status;
        $data['customer_id'] = $booking->customer_id;
        return response()->json([
            'booking' => $data
        ]);
    }
    //put/patch booking/{booking}
    public function update(Request $req, $id)
    {
        $status = false;
        $b = Booking::find($id);
        if ($b != null) {
            switch ($req->action) {
                case 'cancel':
                    if ($b->cancelAble()) {
                        if ($b->Status->id == 1) {
                            $b->delete();
                            $status = true;
                        } else {
                            $b->update(['status_id' => 5]);
                            $status = true;
                        }
                    }
                    break;
                case 'declined':
                    break;
                case 'accept':
                    break;
                case 'hotel-cancel':
                    break;
            }
        }
        return response()->json(['status' => $status]);
    }
}
