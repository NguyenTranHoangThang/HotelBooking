<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;

class MapController extends Controller
{
    public $key = "11f82a7e50bc28";
    public function getLocation(Request $request)
    {
        $client = new Client(['verify' => false]);
        $location = $request->address;
        $query = "https://us1.locationiq.com/v1/search.php?key=" . $this->key . "&q=" . $location . "&format=json";
        // $query = "https://api.locationiq.com/v1/autocomplete.php?key=" . $this->key . "&q=" . $location . "&format=json";
        // $data = json_decode(file_get_contents($query), true);
        $req = $client->request('get', $query);
        $response = $req->getBody();
        $data = json_decode($response, true);
        if (sizeOf($data)  == 0) {

            return response()->json([
                "status" => false,
                "message" => "sorry we can't find this location",
            ]);
        } else {
            $meter = 5000;
            $atm = $this->getPointsOfInterest($data[0], "atm", $meter);
            $restaurant = $this->getPointsOfInterest($data[0], "restaurant", $meter);
            $pub = $this->getPointsOfInterest($data[0], "pub", $meter);
            $supermarket = $this->getPointsOfInterest($data[0], "supermarket", $meter);
            $location = [
                "data" => $data[0],
                "atm" => $atm,
                "restaurant" => $restaurant,
                "pub" => $pub,
                "supermarket" => $supermarket,
            ];
            return response()->json([
                "status" => true,
                "location" => $location,
            ]);
        }
    }
    public function getPointsOfInterest($location, $poi, $meter)
    {
        $client = new Client(['verify' => false]);
        $query2 =
            "https://us1.locationiq.com/v1/nearby.php?key=" . $this->key .
            "&lat=" . $location["lat"] . "&lon=" . $location["lon"] . "&tag=" . $poi . "&radius=" . $meter . "&format=json";
        $req = $client->request('get', $query2);
        $response = $req->getBody();
        $data = json_decode($response, true);
        $result = [];
        foreach ($data as $place) {
            if (isset($place["name"])) {
                if (sizeOf($result) < 3) {
                    $result[] = $place;
                }
            }
        }
        return $result;
    }
}
