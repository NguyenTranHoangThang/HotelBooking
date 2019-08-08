<?php

namespace App\Http\Controllers\Frontend;

use Carbon\Carbon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Hotel;
use App\Models\Comment;
use App\Models\Booking;
use App\Models\Review;
use Auth;
use Validator;
use Hash;
use Session;
use App\Models\CustomerReview;
use App\Models\UserImage;
use App\Models\HotelImage;
use App\Http\Resources\HotelResource;
use App\Models\CouponCode;
use App\Models\CustomerFollowing;
use App\Http\Resources\ReviewCollection;

class ReviewController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $followingCustomer = CustomerFollowing::where("follower_id", $user->id)->pluck("followed_id");
        $followingCustomer[] = $user->id;
        $reviewList = Review::whereIn("customer_id", $followingCustomer)->orderBy('created_at', 'desc');
        $data = new ReviewCollection($reviewList->paginate(2));
        return $data;
    }
    //get review/create
    public function create(Request $req)
    {
        $validateData = Validator::make(
            $req->all(),
            [
                'title' => 'required',
                'content' => 'required',
                'hotel_id' => 'required',
                'booking_id' => 'required',
            ],
            [
                'title.required' => 'Title is empty!',
                'content.required' => 'Content is empty!',
                'hotel_id.required' => 'Hotel value is empty!',
                'booking_id.required' => 'Booking value is empty!',
            ]
        );
        if ($validateData->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validateData->errors(),
            ]);
        }
        $b = Booking::find($req->booking_id);
        if ($b == null || $b->reviewAble() == false || Hotel::find($req->hotel_id) == null) {
            return response()->json([
                'status' => false,
                'errors' => $validateData->errors(),
            ]);
        }
        $review = Review::create([
            'title' => $req->title,
            'content' => $req->content,
            'point' => $req->star,
            'customer_id' => Auth::user()->id,
            'hotel_id' => $req->hotel_id,
            'booking_id' => $req->booking_id,
            'can_comment' => $req->can_comment == 'true' ? 1 : 0,
        ]);
        Hotel::find($req->hotel_id)->getReviewPoint();
        $customerReview = CustomerReview::create([
            'customer_id' => $review->customer_id,
            'review_id' => $review->id,
            'status' => $req->notification == 'true' ? 1 : 0,
            'like' => 0,
            'useful' => 0,
        ]);
        return response()->json([
            'status' => true,
            'review' => $review,
        ]);
    }
    //get review/{review}
    public function show($id)
    {
        $review = Review::find($id);
        if ($review == null) {
            return response()->json([
                'status' => false,
                'review' => $review
            ]);
        }
        if (Auth::user()->id != $review->customer_id) {
            $review->customer = $review->Customer();
        }
        $review->customer_review = $review->CustomerReview(Auth::user()->id, $review->id);
        $review->model = true;
        $review->Hotel;
        $review->hotel->image = HotelImage::where('hotel_id', $review->hotel->id)->where('is_primary', 1)->first()->image_link;
        $review->hotel->services = $review->hotel->ServiceResource();
        foreach ($review->Comment as $c) {
            $c->customer = $c->Customer();
        };
        return response()->json([
            'status' => true,
            'review' => $review
        ]);
    }
    //put/patch review/{review}
    public function update($id)
    {
        $status = false;
        $review = Review::find($id);
        if ($review != null) {
            $status = true;
            switch ($review->can_comment) {
                case 1:
                    $review->update(['can_comment' => 0]);
                    break;
                case 0:
                    $review->update(['can_comment' => 1]);
                    break;
            }
        }
        return response()->json([
            'status' => $status
        ]);
    }
}
