<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hotel;
use App\Models\Ward;
use App\Models\District;
use App\Models\Province;
use Auth;
use Validator;
use Hash;
use Session;

class SearchController extends Controller
{
    public function index(Request $req)
    {
        $hotels = 'ABC';
        $place = $req->place;
        $province = Province::where('name',$place)->with(["District" => function($query) use ($hotels){
            $query->with(["Ward"=>function($query)use ($hotels){
                $query->with(["Hotel"=>function($query) use ($hotels){
                   $hotels = $query->get();
                }]);;
            }]);
        }])->get();
        // $district = $province->District()->where('province_id',$province->id)->get();
        // $wards = $district->Ward->with('Hotel');
        return response()->json(['status' => true, 'data' => $hotels]);
    }
}
