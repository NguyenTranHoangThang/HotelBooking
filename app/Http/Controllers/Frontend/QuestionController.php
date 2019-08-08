<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Hotel;
use App\Models\Comment;
use App\Models\Review;
use App\Models\Quetsion;
use Auth;
use Validator;
use Hash;
use Session;
use App\Models\Question;
use App\Events\UserAskEvent;
use App\Notifications\UserAskNotification;

class QuestionController extends Controller
{
    //get question/create
    public function create(Request $req)
    {
        $validateData = Validator::make(
            $req->all(),
            [
                'title' => 'required',
                'content' => 'required'
            ],
            [
                'title.required' => 'Title is empty!',
                'content.required' => 'Content is empty!',
            ]
        );
        if ($validateData->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validateData->errors(),
            ]);
        }
        $question = Question::create([
            'title' => $req->title,
            'content' => $req->content,
            'customer_id' => Auth::user()->id,
            'hotel_id' => $req->hotel_id,
        ]);
        $ask = array();
        $ask['id'] = $question->id;
        $ask['title'] = $question->title;
        $ask['content'] = $question->content;
        $message = Auth::user()->name.' đã gửi một câu hỏi.';
        $questionTemp = array();
        $questionTemp['id'] = $question->id;
        $questionTemp['content'] = $question->content;
        $questionTemp['title'] = $question->title;
        $questionTemp['created_at'] = $question->created_at;
        $questionTemp['customer'] = $question->Customer();
        $questionTemp['reply'] = $question->Reply;
        Hotel::find($question->hotel_id)->notify(new UserAskNotification($question->hotel_id,$ask,$message));
        $notificationId = Hotel::find($question->hotel_id)->unreadNotifications->first()->id;
        broadcast(new UserAskEvent($question->hotel_id,$ask,$message,$questionTemp,$notificationId));
        return response()->json([
            'status' => true,
            'errors' => null,
        ]);
    }
}
