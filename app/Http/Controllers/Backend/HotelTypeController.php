<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\HotelType;
use App\Http\Resources\HotelTypeResource;

class HotelTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $arrayHotelType = HotelType::all();
        $arrayData = [];
        foreach($arrayHotelType as $hotelType){
            $data = new HotelTypeResource($hotelType);
            // $data = $hotelType->name;
            array_push($arrayData,$data);
        }
        return response()->json([
            "status" => true,
            "data" =>  $arrayData,
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
    public function getAllHotelType()
    {
        $arrayHotelType = HotelType::all();
        $arrayData = [];
        foreach($arrayHotelType as $hotelType){
            $data = new HotelTypeResource($hotelType);
            // $data = $hotelType->name;
            array_push($arrayData,$data);
        }
        return response()->json([
            "status" => true,
            "data" =>  $arrayData,
        ]);
    }
}
