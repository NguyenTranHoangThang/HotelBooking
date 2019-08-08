<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HotelImageResource extends JsonResource
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
            "image_link" => asset('images/hotel/'.$this->image_link),
            "is_primary" => $this->is_primary,
            "id" => $this->id,
        ];
        return $arrayData;
    }
}
