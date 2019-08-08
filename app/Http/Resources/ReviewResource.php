<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Auth;
use App\Models\CustomerReview;
use App\Models\HotelImage;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {          
        $hotel= $this->Hotel;
        $hotel->image = HotelImage::where('hotel_id',$hotel->id)->where('is_primary',1)->first()->image_link;
        $hotel->services = $this->hotel->ServiceResource();
        $comments = $this->Comment;
        foreach($comments as $comment){
        $comment->customer = $comment->Customer();
        }
        $arrayData = [
            "id" => $this->id, 
            "title" => $this->title, 
            "content" => $this->content, 
            "point" => $this->point, 
            "comments" => $this->comments, 
            "likes" => $this->likes, 
            "customer" => $this->Customer(), 
            "model" => false, 
            "comment" => $comments,
            "can_comment" => $this->can_comment,
            "hotel" => $hotel, 
            "created_at" => $this->created_at, 
            // "customer_review" => CustomerReview::where("customer_id",Auth::user()->id)
            // ->where("review_id",$this->id)->first(),
            "customer_review" => $this->CustomerReview(Auth::user()->id,$this->id)

        ];
        return $arrayData;
    }
}
