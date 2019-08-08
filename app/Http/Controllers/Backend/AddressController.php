<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProvinceResource;
use App\Models\Province;
use App\Models\District;
use App\Models\Ward;
use App\Models\Hotel;

class AddressController extends Controller
{
    public function postCountries(Request $request)
    {
        $data = $request->all();
        foreach ($data as $province) {
            $thisProvince = Province::create([
                "name" => $province["name"],
                "code" => $province["code"],
            ]);
            if ($province["districts"] != null) {
                foreach ($province["districts"] as $district) {
                    $thisDistrict = District::create([
                        "province_id" => $thisProvince->id,
                        "name" => $district["name"],
                    ]);
                    if ($district["wards"] != null) {
                        foreach ($district["wards"] as $ward) {
                            Ward::create([
                                "district_id" => $thisDistrict->id,
                                "name" => $ward["name"],
                            ]);
                        }
                    }
                }
            }
        }
        return "o.k";
    }
    public function getProvince()
    {
        $arrayProvince = Province::all();
        $arrayData = [];
        foreach($arrayProvince as $province){
            $data = new ProvinceResource($province);
            array_push($arrayData,$data);
        }
        return response()->json([
            "status" => true,
            "data" =>  $arrayData,
        ]);
    }
    public function getDistrict(Request $request)
    {
        $arrayDistrict = District::where('province_id',$request->provinceId)->get();
        // return $arrayDistrict;
        $arrayData = [];
        foreach($arrayDistrict as $district){
            $data = new ProvinceResource($district);
            array_push($arrayData,$data);
        }
        return response()->json([
            "status" => true,
            "data" =>  $arrayData,
        ]);
    }
    public function getWard(Request $request)
    {
        $arrayWard = Ward::where('district_id',$request->districtId)->get();
        $arrayData = [];
        foreach($arrayWard as $ward){
            $data = new ProvinceResource($ward);
            array_push($arrayData,$data);
        }
        return response()->json([
            "status" => true,
            "data" =>  $arrayData,
        ]);
    }
    public function getAddress(Request $request)
    {
        $hotel = Hotel::find($request->hotel_id);
        if($hotel == null){
            return response()->json([
                "status" => false,
                "message" => "can't find hotel with this id"
            ]);
        }
        $wardId = $hotel->ward_id;
        $ward = Ward::find($wardId);
        $district = District::where("id",$ward->district_id)->first();
        $province = Province::where("id",$district->province_id)->first();
        return response()->json([
            "status" => true,
            "data" => [
                "province_id" => $province->id,
                "district_id" => $district->id,
                "ward_id" => $ward->id,
            ],
        ]);
    }
    public function getDistrictByPronvinceName(Request $request)
    {
        $provinceName = $request->province;
        $province = Province::where("name",$provinceName)->first();
        if($province == null){
            return response()->json([
                "status" => false,
            ]);
        }else{
            $provinceId = $province->id;
        }
        $arrayDistrict = District::where('province_id',$provinceId)->get();
        $arrayData = [];
        foreach($arrayDistrict as $district){
            $data = new ProvinceResource($district);
            array_push($arrayData,$data);
        }
        return response()->json([
            "status" => true,
            "data" =>  $arrayData,
        ]);
    }
}
