<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\HotelImageResource;
use App\Http\Resources\PolicyResource;
use App\Http\Resources\RoomResource;
use App\Models\HotelImage;
use App\Models\Policy;

class HotelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $arrayImage = [];
        foreach($this->Image as $image){
            $arrayImage[] = new HotelImageResource($image);
        }
        $arrayData = [
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "description" => $this->description,
            "meta_name" => $this->meta_name,
            "credit_card" => $this->credit_card,
            // "review_point" => $this->getReviewPoint(),
            "bank" => $this->bank,
            "tax_code" => $this->tax_code,
            "hotel_type" => $this->HotelType->name,
            "hotel_type_id" => $this->hotel_type_id,
            "coin" => $this->coin,
            "stars_num" => $this->stars_num,
            "address" => $this->address,
            "phone" => $this->phone_number,
            "images" => $arrayImage,
            "minPrice" => $this->minPrice(),
            "maxPrice" => $this->maxPrice(),
            "ward" => $this->ward_id,
            "child_age" => $this->child_age,
            // "image" => HotelImage::where('hotel_id',$this->id)->where('is_primary',1)->first()->image_link,
            "policy" =>  new PolicyResource($this->Policy),
        ];
        return $arrayData;
    }
}
