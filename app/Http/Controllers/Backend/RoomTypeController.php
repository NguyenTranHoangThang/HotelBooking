<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\RoomType;
use App\Models\Room;
use App\Models\Hotel;
use App\Http\Resources\RoomTypeResource;

class RoomTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // return $request->hotelId;
        $roomModeId = $request->roomModeId;
        $hotel = Hotel::where("id", $request->hotelId)->with("Room")->get();
        $temp = [];
        foreach ($hotel[0]->room as $room) {
            if ($room->room_mode_id == $roomModeId) {
                $temp[] = $room->room_type_id;
            }
        }
        $data = RoomType::whereNotIn("id", $temp)->get();
        return response()->json([
            "status" => true,
            "data" => $data,
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $hotel_id = $id;
        if (Hotel::find($hotel_id) == null) {
            return response()->json([
                'status' => false,
            ]);
        }
        $roomTypes = array();
        $data = RoomType::whereHas("Room", function ($query) use ($hotel_id) {
            $query->where("hotel_id", $hotel_id);
        })->get();
        foreach ($data as $roomType) {
            $roomTypes[] = new RoomTypeResource($roomType);
        }
        return response()->json([
            'status' => true,
            'data' => $roomTypes,
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function getAllRoomType()
    {
        $roomTypes = RoomType::all();
        $result = [];
        foreach ($roomTypes as $roomType) {
            $result[] = new RoomTypeResource($roomType);
        }
        return response()->json([
            "status" => true,
            "data" => $result,
        ]);
    }
}
