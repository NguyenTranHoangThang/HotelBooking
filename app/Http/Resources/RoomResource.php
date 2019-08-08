<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\RoomImageResource;
use App\Models\ServiceRoomType;

class RoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $roomType = $this->RoomType->name;
        $roomMode = $this->RoomMode->name;
        $full_name = $this->room_name."($roomType - $roomMode)";
        $arrayImage = [];
        foreach($this->Image as $image){
            $arrayImage[] = new RoomImageResource($image);
        }
        $service = [];
        $temp = $this->RoomType->ServiceRoomTypeByHotel($this->hotel_id);
        foreach($temp as $t){
            $service[] = $t->ServiceResource();
        }
        $feature = [];
        $temp = $this->Feature;
        foreach($temp as $t){
            $feature[] = $t->FeatureResource();
        }

        $arrayData = [
            "id" => $this->id,
            "room_name" => $this->room_name,
            "description" => $this->description,
            "price" => $this->price,
            "max_adult_amount" => $this->max_adult_amount,
            "max_child_amount" => $this->max_child_amount,
            "free_child_amount" => $this->free_child_amount,
            "child_age" => $this->Hotel->child_age,
            "room_size" => $this->room_size,
            "amount" => $this->amount,
            "room_type" => $roomType,
            "room_type_id" => $this->RoomType->id,
            "room_mode" => $roomMode,
            "room_mode_id" => $this->RoomMode->id,
            "full_name" => $full_name,
            "images" => $arrayImage,
            "service" => $service,
            "feature" => $feature,
        ];
        return $arrayData;
    }
}
