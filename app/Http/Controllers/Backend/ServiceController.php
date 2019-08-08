<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceRoomType;
use App\Models\RoomType;
use App\Models\Room;
use App\Models\Hotel;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\RoomTypeResource;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $services = Service::all();
        $data = [];
        $panel = [];
        $roomTypes = Room::where("hotel_id", $request->hotelId)->get();
        $arrayRoomType = [];
        foreach ($roomTypes as $roomType) {
            $arrayRoomType[$roomType->room_type_id] =
                new RoomTypeResource(RoomType::find($roomType->room_type_id));
        }
        $totalRoomType = sizeOf($arrayRoomType);
        foreach ($services as $service) {
            $temp = [];
            $temp["service"] = new ServiceResource($service);
            $serviceRoomType = ServiceRoomType::where("service_id", $service->id)
                ->where("hotel_id", $request->hotelId)
                ->get();
            if ($serviceRoomType->isEmpty() === false) {
                $panel[] = true;
                $arrayRoomTypee = [];
                // dd($serviceRoomType);
                foreach ($serviceRoomType as $roomType) {
                    $arrayRoomTypee[] = $roomType->room_type_id;
                }
                // dd(sizeof($arrayRoomTypee));
                if (sizeof($arrayRoomTypee) == $totalRoomType) {
                    $temp["room"]["radio"] = "all";
                    $temp["room"]["chosenRoom"] = null;
                } else {
                    $temp["room"]["radio"] = "some";
                    $temp["room"]["chosenRoom"] = $arrayRoomTypee;
                }
            } else {
                $panel[] = false;
                $temp["room"]["radio"] = "all";
                $temp["room"]["chosenRoom"] = null;
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
    public function getAllService()
    {
        $services = Service::all();
        $data = [];
        foreach($services as $service){
            $data[] = new ServiceResource($service);
        }
        return response()->json([
            "status" => true,
            "data" => $data,
        ]);
    }
}
