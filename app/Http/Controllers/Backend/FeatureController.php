<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\RoomFeature;
use App\Models\Feature;
use App\Models\Hotel;
use App\Models\Room;
use App\Http\Resources\FeatureResource;

class FeatureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $features = Feature::all();
        $data = [];
        $panel = [];
        $hotel = Hotel::find($request->hotelId);
        $temp = [];
        
        $numberOfRoom = sizeOf($hotel->Room);
        foreach ($features as $feature) {
            $temp["feature"] = new FeatureResource($feature);
            // return $feature->RoomByHotel($request->hotelId);
            if (sizeOf($feature->RoomByHotel($request->hotelId)) == 0) {
                // return 2;
                $panel[] = false;
                $temp["room"]["chosenRoom"] = null;
                $temp["room"]["radio"] = "all";
            } elseif (sizeOf($feature->RoomByHotel($request->hotelId)) != $numberOfRoom) {
                $panel[] = true;
                $temp["room"]["chosenRoom"] = $feature->RoomByHotel($request->hotelId);
                $temp["room"]["radio"] = "some";
            } else {
                $panel[] = true;
                $temp["room"]["chosenRoom"] = null;
                $temp["room"]["radio"] = "all";
            }

            $data[] = $temp;
        }
        
        return response()->json([
            "status" => true,
            "data" => $data,
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
        //
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
    public function addFeature(Request $request)
    {
        return $this->addAllRoom($request);
    }
    public function addAllRoom(Request $request)
    {
        $hotel = Hotel::find($request->hotelId);
        if ($hotel != null) {
            $rooms = Room::where("hotel_id", $request->hotelId)->get();
            $this->checkIfExist($request);
            foreach ($rooms as $room) {
                RoomFeature::create(
                    [
                        "feature_id" => $request->featureId,
                        "room_id" => $room->id,
                    ]
                );
            }
        }
    }
    public function removeFeatureRoom(Request $request)
    {
        $hotel = Hotel::find($request->hotelId);
        $roomIds = [];
        foreach ($hotel->room as $room) {
            $roomIds[] = $room->id;
        }
        $query = RoomFeature::whereIn("room_id", $roomIds)->where("feature_id", $request->featureId)->delete();
        $hotel->update(["rank_point" => $hotel->rank_point - 1]);
    }
    public function addFeatureRoom(Request $request)
    {
        $hotel = Hotel::find($request->hotelId);
        $this->checkIfExist($request);
        foreach ($request->chosenRoom as $roomId) {
            RoomFeature::create(
                [
                    "feature_id" => $request->featureId,
                    "room_id" => $roomId,
                ]
            );
        }
    }
    public function checkIfExist($request)
    {
        $hotel = Hotel::where("id", $request->hotelId)->with("Room")->get();
        $feature_id = $request->featureId;
        $temp = [];
        foreach ($hotel[0]->Room as $room) {
            if (RoomFeature::where("room_id", $room->id)->where("feature_id", $feature_id)->first() != null) {
                $temp[] = $room->id;
            }
            RoomFeature::where("room_id", $room->id)->where("feature_id", $feature_id)->delete();
        }
        if (sizeOf($temp) == 0) {
            $hotel = Hotel::find($request->hotelId);
            $hotel->update(["rank_point" => $hotel->rank_point + 1]);
        }
    }
}
