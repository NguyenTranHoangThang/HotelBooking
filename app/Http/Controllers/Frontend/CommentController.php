<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Events\MessageSentEvent;
use App\Models\User;
use App\Models\Review;
use App\Models\UserImage;
use App\Models\Customer;
use Auth;
use JWTAuth;
use Validator;
use Hash;
use Session;
use App\Models\CustomerReview;
use Illuminate\Notifications\Notification;
use App\Notifications\CommentNotification;

class CommentController extends Controller
{
    //get comment/create
    public function store(Request $req)
    {
        $data = $req->comment;
        $review = Review::find($data['review_id']);
        if ($review == null || strlen($data['content']) == 0)
            return response()->json([
                'status' => false,
            ]);
        $comment = Comment::create([
            'content' => $data['content'],
            'review_id' => $review->id,
            'customer_id' => Auth::user()->id,
        ]);
        $review->update([
            'comments' => $review->comments + 1,
        ]);
        $customerReview = CustomerReview::where('review_id', $review->id)->where('customer_id', Auth::user()->id)->first();
        if ($customerReview == null) {
            $customerReview = CustomerReview::create([
                'customer_id' => Auth::user()->id,
                'review_id' => $review->id,
                'status' => 1,
                'like' => 0,
                'useful' => 0,
            ]);
        } else {
            if ($customerReview->status == 0) {
                $customerReview->update([
                    'status' => 1
                ]);
            }
        }
        $comment->customer = $comment->Customer();
        $user = array();
        $user['name'] = Auth::user()->name;
        $user['id'] = Auth::user()->id;
        $user['avatar'] = $comment->customer->avatar->image_link;
        $link = ['name' => 'review', 'id' => $review->id];
        $customer_review = CustomerReview::where('review_id', $review->id)->where('customer_id', '<>', Auth::user()->id)->where('status', 1)->get();
        broadcast(new MessageSentEvent($user, $link, 'Đã kèm một bình luận.', $comment->content));
        foreach ($customer_review as $customer) {
            User::find($customer->customer_id)->notify(new CommentNotification($user, $comment->review_id));
        }
        return response()->json([
            'status' => true,
            'comment' => $comment
        ]);
    }
}
