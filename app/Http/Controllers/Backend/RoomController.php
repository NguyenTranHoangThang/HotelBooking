<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\RoomBedType;
use App\Models\Room;
use App\Models\RoomImage;
use App\Models\RoomType;
use App\Http\Resources\RoomResource;
use App\Models\Hotel;
use App\Models\Service;
use App\Models\ServiceRoomType;
use function GuzzleHttp\json_decode;
use App\Http\Resources\RoomBedTypeResource;
use Intervention\Image\ImageManagerStatic as Image;
use App\Http\Resources\RoomModeResource;
use Carbon\Carbon;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $rooms =  Room::where("hotel_id", $request->hotelId)->get();
        $result = [];
        $arrayRoomMode = [];
        foreach (Hotel::find($request->hotelId)->RoomMode() as $roomMode) {
            $arrayRoomMode[] = new RoomModeResource($roomMode);
        }
        foreach ($arrayRoomMode as $roomMode) {
            $temp = [];
            $temp['room_mode'] = $roomMode;
            $temp['room'] = $roomMode->roomByHotel($request->hotelId);
            $result[] = $temp;
        }
        return response()->json([
            "status" => true,
            "data" => $result,
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
        $room = Room::create($request->all());
        $beds = $request->bed;
        // return $beds;
        foreach ($beds as $bed) {
            // $bed = json_decode($bed);
            RoomBedType::updateOrCreate([
                "bed_type_id" => $bed["bedTypeId"],
                "room_id" => $room->id,
                "amount" => $bed["amount"],
            ]);
        }
        $images = $request->images;
        // return $request->images;
        $length = sizeof($images);
        for ($i = 0; $i < $length; $i++) {
            $name = time() . str_random(10);
            $image = Image::make($images[$i]["image_link"])->resize(200, 200)->save(public_path("images/room/" . $name));
            $primary = 0;
            if ($images[$i]["is_primary"] == 1) {
                $primary = 1;
            }
            RoomImage::create([
                "image_link" => $image->filename,
                "is_primary" => $primary,
                "room_id" => $room->id,
            ]);
        }
        return response()->json([
            "status" => true,
            "message" => "room created",
            "room" => new RoomResource($room),
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
        $room = Room::find($id);
        $beds = RoomBedType::where("room_id", $id)->get();
        $temp = [];
        foreach ($beds as $bed) {
            $temp[] = new RoomBedTypeResource($bed);
        }
        return response()->json([
            "status" => true,
            "data" => new RoomResource($room),
            "bed" => $temp,
        ]);
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
        $room = Room::find($id);
        if ($room != null) {
            $room->update($request->all());
            $beds = $request->bed;
            RoomBedType::where("room_id", $id)->delete();
            foreach ($beds as $bed) {
                // $bed = json_decode($bed);
                RoomBedType::updateOrCreate([
                    "bed_type_id" => $bed["bedTypeId"],
                    "room_id" => $id,
                    "amount" => $bed["amount"],
                ]);
            }
            $images = $request->images;
            $length = sizeof($images);
            $temp = [];
            // return $images;
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
                    RoomImage::find($images[$i]["id"])->update(["is_primary" => $primary]);
                }
                //hinh moi
                else {
                    $name = time() . str_random(10);
                    $image = Image::make($images[$i]["image_link"])->resize(200, 200)->save(public_path("images/room/" . $name));
                    $primary = 0;
                    if ($images[$i]["is_primary"] == 1) {
                        $primary = 1;
                    }
                    RoomImage::create([
                        "image_link" => $image->filename,
                        "is_primary" => $primary,
                        "room_id" => $room->id,
                    ]);
                }
            }
            $oldImage = RoomImage::where("room_id", $room->id)
                ->whereNotIn("image_link", $temp)
                ->delete();
            return response()->json([
                "status" => true,
                "message" => "room updated",
                "room" => new RoomResource($room),
            ]);
        } else {
            return response()->json([
                "status" => false,
                "messages" => "cant find room with this id"
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $room = Room::find($id);
        // if ($room != null) {
        //     $room->delete();
        //     return response()->json([
        //         "status" => true,
        //         "messages" => "room deleted!!!"
        //     ]);
        // } else {
        //     return response()->json([
        //         "status" => false,
        //         "messages" => "cant find room with this id"
        //     ]);
        // }
        $bookingCantDelete = [];
        $room = Room::find($id);
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
                }
                if ($booking->status_id == 4) {
                    $booking->reason = "Đơn đặt phòng cần hủy";
                    $bookingCantDelete["sau"][] = $booking;
                }
            }
            //cac don co ngay check in be hon va ngay check out lon hon hom nay hom nay
            else {
                if ($bookingCheckOut->greaterThan($today)) {
                    if ($booking->status_id == 1 || $booking->status_id = 2) {
                        $booking->reason = "Đơn đặt phòng cần xử lý";
                    }
                    $bookingCantDelete["giua"][] = $booking;
                }
            }
        }
        if (sizeOf($bookingCantDelete) > 0) {
            return response()->json([
                "status" => false,
                "message" => "Can't delete",
                "booking_list" => $bookingCantDelete,
            ]);
        } else {
            // Room::destroy($id);
            return response()->json([
                "status" => true,
                "messages" => "room deleted!!!"
            ]);
        }
    }
    public function getAllRoomByHotelId(Request $request)
    {
        $hotel = Hotel::find($request->hotelId);
        $result = [];
        foreach ($hotel->Room as $room) {
            $result[] = new RoomResource($room);
        }
        return response()->json([
            "status" => true,
            "data" => $result,
        ]);
    }
}
