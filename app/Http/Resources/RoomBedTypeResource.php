<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoomBedTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $arrayData = [
            "bedTypeId" => $this->bed_type_id,
            // "room_id" => $this->room_id,
            "amount" => $this->amount,
        ];
        return $arrayData;
    }
}
