<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\HotelResource;
use App\Http\Resources\RoomTypeResource;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\Province;
use App\Models\District;
use App\Models\Ward;
use App\Models\HotelType;
use App\Models\User;
use App\Models\Booking;
use App\Models\HotelImage;
use App\Models\ServiceRoomType;
use Validator;
use App\Models\RoomType;
use Hash;
use Carbon\Carbon;
use Auth;
use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Intervention\Image\ImageManagerStatic as Image;
use App\Models\Policy;
use App\Http\Resources\HotelCollection;
use Carbon\Carbon as CarbonCarbon;

class HotelController extends Controller
{
    /**
     * Display a listing of the resource.
     *"
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $arrayHotelRaw = Auth::user()->HotelManager->first()->Hotel;
        // return Auth::user()->HotelManager->first();
        $arrayHotel = [];
        $panel = [];
        foreach ($arrayHotelRaw as $key => $hotel) {
            $panel[] = false;
            $arrayHotel[] = new HotelResource($hotel);
        }
        return response()->json([
            "status" => true,
            "data" => $arrayHotel,
            "panel" => $panel,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->all();
        $data = [
            "name" => $request->name,
            "email" => $request->email,
            "meta_name" => preg_replace("/\s+/", "-", trim($request->name)),
            "hotel_type_id" => $request->hotelType,
            "description" => $request->description,
            "province" => $request->province,
            "district" => $request->district,
            "ward_id" => $request->ward,
            "address" => $request->address,
            "credit_card" => $request->credit_card,
            "phone_number" => $request->phone,
            "bank" => $request->bank,
            "tax_code" => $request->tax_code,
            "stars_num" => $request->stars_num,
            "child_age" => $request->child_age,
        ];
        $validator = Validator::make($data, [
            'name' => 'required|unique:hotel',
            'email' => 'required',
            'hotel_type_id' => 'required|exists:hotel_type,id',
            'description' => 'required',
            'province' => 'required|exists:province,id',
            'district' => 'required|exists:district,id',
            'ward_id' => 'required|exists:ward,id',
            'address' => 'required',
            'credit_card' => 'required',
            'phone_number' => 'required',
            'bank' => 'required',
            'tax_code' => 'required',
            'child_age' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "errors" => $validator->errors(),
            ]);
        }
        $data["hotel_manager_id"] = Auth::user()->id;
        $hotel = Hotel::create($data);
        $images = $request->images;
        $length = sizeof($images);
        for ($i = 0; $i < $length; $i++) {
            $name = time() . str_random(10);
            $image = Image::make($images[$i]["image_link"])->resize(800, 300)->save(public_path("images/hotel/" . $name));
            $primary = 0;
            if ($images[$i]["is_primary"] == 1) {
                $primary = 1;
            }
            HotelImage::create([
                "image_link" => $image->filename,
                "is_primary" => $primary,
                "hotel_id" => $hotel->id,
            ]);
        }
        $policy = [
            "hotel_id" => $hotel->id,
            "check_in" => $request->checkin,
            "check_out" => $request->checkout,
            "content" => $request->detailPolicy,
            "payment_method" => $request->payment_method,
        ];
        if ($request->cancelable == true) {
            $policy["cancelable"] = $request->cancel_day;
            $policy["can_refund"] = $request->refundRate;
        } else {
            $policy["cancelable"] = 0;
            $policy["can_refund"] = 0;
        }
        Policy::create($policy);
        return response()->json([
            "status" => true,
            "id" => $hotel->id,
            "hotel" => new HotelResource($hotel),
            "message" => "Hotel Created !!!",
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new HotelResource(Hotel::find($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // return $request->all();
        $data = [
            "name" => $request->name,
            "email" => $request->email,
            "meta_name" => preg_replace("/\s+/", "-", trim($request->name)),
            "hotel_type_id" => $request->hotelType,
            "hotel_manager_id" => $request->hotel_manager_id,
            "description" => $request->description,
            "province" => $request->province,
            "district" => $request->district,
            "ward_id" => $request->ward,
            "address" => $request->address,
            "credit_card" => $request->credit_card,
            "phone_number" => $request->phone,
            "bank" => $request->bank,
            "tax_code" => $request->tax_code,
            "stars_num" => $request->stars_num,
            "child_age" => $request->child_age,
        ];
        $validator = Validator::make($data, [
            'name' => 'required',
            'hotel_type_id' => 'required|exists:hotel_type,id',
            'description' => 'required',
            'province' => 'required|exists:province,id',
            'district' => 'required|exists:district,id',
            'ward_id' => 'required|exists:ward,id',
            'address' => 'required',
            'credit_card' => 'required',
            'phone_number' => 'required',
            'bank' => 'required',
            'tax_code' => 'required',
            "child_age" => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "errors" => $validator->errors(),
            ]);
        }
        $hotel = Hotel::find($id);
        $hotel->update($data);
        $images = $request->images;
        $length = sizeof($images);
        $temp = [];
        for ($i = 0; $i < $length; $i++) {
            //hinh cu
            if ($images[$i]["id"] != -1) {
                $explode = explode("/", $images[$i]["image_link"]);
                $imageName = ($explode[5]);
                $temp[] = $imageName;
                $primary = 0;
                if ($images[$i]["is_primary"] == 1) {
                    $primary = 1;
                }
                HotelImage::find($images[$i]["id"])->update(["is_primary" => $primary]);
            }
            //hinh moi
            else {
                $name = time() . str_random(10);
                $image = Image::make($images[$i]["image_link"])->resize(200, 200)->save(public_path("images/hotel/" . $name));
                $primary = 0;
                if ($images[$i]["is_primary"] == 1) {
                    $primary = 1;
                }
                $realImage = HotelImage::create([
                    "image_link" => $image->filename,
                    "is_primary" => $primary,
                    "hotel_id" => $hotel->id,
                ]);
                $temp[] = $name;
            }
        }
        $oldImage = HotelImage::where("hotel_id", $hotel->id)
            ->whereNotIn("image_link", $temp)
            ->delete();
        $policy = [
            "hotel_id" => $hotel->id,
            "check_in" => $request->checkin,
            "check_out" => $request->checkout,
            "content" => $request->detailPolicy,
            "payment_method" => $request->payment_method,
        ];
        if ($request->cancelable == true) {
            $policy["cancelable"] = $request->cancel_day;
            $policy["can_refund"] = $request->refundRate;
        } else {
            $policy["cancelable"] = 0;
            $policy["can_refund"] = 0;
        }
        $hotel->Policy->update($policy);
        return response()->json([
            "status" => true,
            "hotel" => new HotelResource($hotel),
            "message" => "Hotel updated !!!",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $hotel = Hotel::find($id);

        if ($hotel == null) {
            return response()->json([
                "status" => false,
                "data" => "can't find hotel with this ID",
            ]);
        } else {
            $hotelWithBookingList = $hotel->BookingList($id);
            $bookingCantDelete = [];
            foreach ($hotelWithBookingList as $hotel) {
                foreach ($hotel->room as $room) {
                    foreach ($room->booking as $booking) {
                        // return $room->booking;
                        $bookingCheckinExplode = explode("-", $booking->check_in);
                        $bookingCheckoutExplode = explode("-", $booking->check_out);
                        $todayExplode = explode("-", date("Y-m-d"));
                        $bookingCheckIn = Carbon::createMidnightDate($bookingCheckinExplode[0], $bookingCheckinExplode[1], $bookingCheckinExplode[2]);
                        $bookingCheckOut = Carbon::createMidnightDate($bookingCheckoutExplode[0], $bookingCheckoutExplode[1], $bookingCheckoutExplode[2]);
                        $today = Carbon::createMidnightDate($todayExplode[0], $todayExplode[1], $todayExplode[2]);
                        //cac don co ngay check in lon hon hom nay
                        if ($bookingCheckIn->greaterThan($today)) {

                            if ($booking->status_id == 1 || $booking->status_id == 2) {
                                $booking->reason = "Đơn đặt phòng cần xử lý";
                                $bookingCantDelete["sau"][] = $booking;
                                // $bookingCantDelete[] = $booking;
                            }
                            if ($booking->status_id == 4) {
                                $booking->reason = "Đơn đặt phòng cần hủy";
                                $bookingCantDelete["sau"][] = $booking;
                                // $bookingCantDelete[] = $booking;
                            }
                        }
                        //cac don co ngay check in be hon va ngay check out lon hon hom nay hom nay
                        else {

                            if ($bookingCheckOut->greaterThan($today)) {
                                if ($booking->status_id == 1 || $booking->status_id = 2) {
                                    $booking->reason = "Đơn đặt phòng cần xử lý";
                                }
                                $bookingCantDelete["giua"][] = $booking;
                                // $bookingCantDelete[] = $booking;
                            }
                        }
                    }
                }
            }
            // return $bookingCantDelete;
            if (sizeOf($bookingCantDelete) > 0) {
                return response()->json([
                    "status" => false,
                    "message" => "Can't delete",
                    "booking_list" => $bookingCantDelete,
                ]);
            } else {
                Hotel::destroy($id);
                return response()->json([
                    "status" => true,
                    "data" => "hotel deleted",
                ]);
            }
        }
    }
    public function addService(Request $request)
    {
        $hotel = Hotel::find($request->hotelId);
        $choice = $request->radio;
        $service = $request->serviceId;
        return $this->addAllRoom($request);
    }
    public function removeServiceRoom(Request $request)
    {
        $id = $request->hotelId;
        $hotel = Hotel::find($id);
        $allServiceRoomType = ServiceRoomType::where("hotel_id", $id)->where("service_id", $request->serviceId);
        $allServiceRoomType->delete();
        $hotel->update(["rank_point" => $hotel->rank_point - 1]);
    }
    public function addAllRoom(Request $request)
    {
        $id = $request->hotelId;
        $hotel = Hotel::where('id', $id)->with("Room")->get();
        $roomTypeIds = [];
        $temp = [];
        foreach ($hotel[0]->room as $r) {
            $temp[] = $r->room_type_id;
        }
        $temp = array_unique($temp);
        $roomTypeIds = $temp;
        $this->checkIfExist($request);
        foreach ($roomTypeIds as $roomTypeId) {
            ServiceRoomType::create(
                [
                    "service_id" => $request->serviceId,
                    "room_type_id" => $roomTypeId,
                    "hotel_id" => $hotel[0]->id,
                ]
            );
        }
        $hotel = Hotel::find($id);
    }
    public function addServiceRoom(Request $request)
    {
        $hotel = Hotel::find($request->hotelId);
        $this->checkIfExist($request);
        foreach ($request->chosenRoom as $roomTypeId) {
            ServiceRoomType::create(
                [
                    "service_id" => $request->serviceId,
                    "room_type_id" => $roomTypeId,
                    "hotel_id" => $hotel->id,
                ]
            );
        }
    }
    public function checkIfExist($request)
    {
        $hotel = Hotel::find($request->hotelId);
        $allServiceRoomType = ServiceRoomType::where("hotel_id", $hotel->id)->where("service_id", $request->serviceId);
        $count = count($allServiceRoomType->get());
        if ($count == 0) {
            $hotel->update(["rank_point" => $hotel->rank_point + 1]);
        }
        $allServiceRoomType->delete();
    }
    public function getAllRoomType(Request $request)
    {
        $hotel =  Hotel::find($request->hotelId);
        $arrayRoomTypes = [];
        $arrayRoomTypeId = [];
        foreach ($hotel->Room as $room) {
            $roomType = RoomType::find($room->room_type_id);
            if (empty($arrayRoomTypeId)) {
                $arrayRoomTypeId[] = $roomType->id;
                $arrayRoomTypes[] = new RoomTypeResource($roomType);
            } else {
                if (!$this->array_remove_duplicate($arrayRoomTypeId, $roomType->id)) {
                    $arrayRoomTypeId[] = $roomType->id;
                    $arrayRoomTypes[] = new RoomTypeResource($roomType);
                }
            }
        }
        return response()->json([
            "status" => true,
            "data" => $arrayRoomTypes,
        ]);
    }
    public function getAllRoomTypes()
    {
        # code...
    }
    public function array_remove_duplicate($array, $field)
    {
        foreach ($array as $key => $value) {
            if ($value == $field) {
                return true;
            }
        }
        return false;
    }
    public function search(Request $request)
    {
        // return $request->all();
        $place = $request->place;
        $checkIn = $request->checkIn;
        $checkOut = $request->checkOut;
        $stars = $request->stars;
        $services = $request->service;
        $districts = $request->district;
        $hotelTypes = $request->hotelType;
        $price = $request->price;
        $roomTypes = $request->roomType;
        if ($roomTypes  == null) {
            foreach (RoomType::all() as $roomType) {
                $roomTypes[] = $roomType->id;
            }
        }
        $checkIn = Carbon::createMidnightDate($checkIn);
        $checkOut = Carbon::createMidnightDate($checkOut);
        $data = [];
        $query = Hotel::whereHas("Ward", function ($query)   use ($place) {
            $query->whereHas("District", function ($query)   use ($place) {
                $query->whereHas("Province", function ($query)   use ($place) {
                    $query->where("name", $place);
                });
            });
        });
        $query = $this->filterByDate($query, $checkIn, $checkOut, $roomTypes);
        if (is_array($districts) && sizeOf($districts) > 0) {
            $query = $this->filterByDistrict($query, $districts);
        }

        if (is_array($stars) && sizeOf($stars) > 0) {
            $query = $this->filterByStar($query, $stars);
        }
        if (is_array($services) && sizeOf($services) > 0) {
            $query = $this->filterByService($query, $services);
        }
        if (is_array($hotelTypes) && sizeOf($hotelTypes) > 0) {
            $query = $this->filterByHotelType($query, $hotelTypes);
        }
        if (is_array($price) && sizeOf($price) > 0) {
            $query = $this->filterByPrice($query, $price);
        }
        $query = $this->filterByRankPoint($query);
        return response()->json([
            "status" => true,
            "data" => new HotelCollection($query->paginate(3)),
        ]);
    }
    public function filterByDate($query, $checkIn, $checkOut, $roomTypes)
    {
        $hotelIds = $query->get("id");
        $roomHaveBooking = Room::whereIn("hotel_id", $hotelIds)
            ->with("Booking")
            ->WhereHas("Booking")
            ->get();
        $roomDontHaveBooking = Room::whereIn("hotel_id", $hotelIds)
            ->whereDoesntHave("Booking")
            ->get("id");
        $ValidRoom = [];
        foreach ($roomDontHaveBooking as $room) {
            $ValidRoom[] = $room->id;
        }
        foreach ($roomHaveBooking as $room) {
            if (in_array($room->room_type_id, $roomTypes)) {
                $room_amount = $room->amount;
                foreach ($room->booking as $booking) {
                    $bookingCheckin = Carbon::createMidnightDate($booking->check_in);
                    $bookingCheckout = Carbon::createMidnightDate($booking->check_out);
                    if ($checkIn->lessThanOrEqualTo($bookingCheckin)) {
                        if ($checkOut->lessThanOrEqualTo($bookingCheckin)) {
                            $room_amount++;
                        }
                    }
                    if ($checkIn->greaterThanOrEqualTo($bookingCheckout)) {
                        if ($checkOut->greaterThanOrEqualTo($bookingCheckout)) {
                            $room_amount++;
                        }
                    }
                    $room_amount -= $booking->room_amount;
                }
                $room->amount = $room_amount;
                if ($room->amount != 0) {
                    $ValidRoom[] = $room->id;
                }
            }
        }
        $ValidRoom = array_unique($ValidRoom);
        return $query->whereHas("Room", function ($query) use ($ValidRoom) {
            $query->whereIn("id", $ValidRoom);
        });
    }
    public function filterByDistrict($query, $districts)
    {
        return $query->whereHas("Ward", function ($query) use ($districts) {
            $query->whereHas("District", function ($query) use ($districts) {
                $query->whereIn("id", $districts);
            });
        });
    }
    public function filterByStar($query, $stars)
    {
        return $query->whereIn("stars_num", $stars);
    }
    public function filterByService($query, $services)
    {
        return $query->whereHas("Service", function ($query) use ($services) {
            $query->whereIn("service_id", $services);
        });
    }
    public function filterByHotelType($query, $hotelTypes)
    {
        return $query->whereIn("hotel_type_id", $hotelTypes);
    }
    public function filterByPrice($query, $price)
    {
        // return $query->with("Room")->whereHas("Room",function($query) use ($price){
        return $query->whereHas("Room", function ($query) use ($price) {
            $query->whereBetween("price", $price);
        });
    }
    public function filterByRankPoint($query)
    {
        return $query->orderBy("rank_point", "desc");
    }
}
