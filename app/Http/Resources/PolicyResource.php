<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class PolicyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // $temp = ["hello"];
        // return $temp;
        if ($this->cancelable > 0) {
            $cancelable = true;
        } else $cancelable = false;
        
        $arrayData = [
            "checkin" => Carbon::parse($this->check_in)->format('H:i'),
            "checkout" => Carbon::parse($this->check_out)->format('H:i'),
            "cancel_day" => $this->cancelable,
            "refundRate" => $this->can_refund,
            "detailPolicy" => $this->content,
            "cancelable" => $cancelable,
        ];
        if($this->payment_method == 2){
            $arrayData["online"] = true;
            $arrayData["offline"] = true;
        }elseif($this->payment_method == 1){
            $arrayData["online"] = true;
            $arrayData["offline"] = false;
        }else{
            $arrayData["online"] = false;
            $arrayData["offline"] = true;
        }
        return $arrayData;
    }
}
