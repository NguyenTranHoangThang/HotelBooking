<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Hotel;
use Validator;
use App\Models\RoomType;
use Hash;
use Carbon\Carbon;
use Auth;

class QuestionController extends Controller
{
    //get question
    public function index(Request $req)
    {
        $hotel = Hotel::find($req->hotelId);
        $questions = array();
        $oldQuestions = array();
        $newQuestions = array();
        if ($hotel == null)
            return response()->json([
                'status' => false,
                'questions' => $questions,
                'oldQuestions' => $oldQuestions,
                'newQuestions' => $newQuestions,
            ]);
        $arr = $hotel->questionList();
        foreach($arr as $q){
            $temp = $q;
            $temp->reply = $q->Reply;
            if($q->Reply !=null){
                $oldQuestions[] = $temp;
            }else{
                $newQuestions[] = $temp;
            }
            $questions[] = $temp;
        }
        return response()->json([
            'status' => true,
            'questions' => $questions,
            'oldQuestions' => $oldQuestions,
            'newQuestions' => $newQuestions,
        ]);
    }

    //get question/create
    public function create()
    {
        return;
    }

    //post question
    public function store()
    {
        return;
    }

    //get question/{question}
    public function show($id)
    {
        return;
    }

    //question/{question}/edit
    public function edit($id)
    {
        return;
    }

    //put/patch question/{question}
    public function update($id, Request $req)
    {
        return;
    }

    //delete question/{question}
    public function destroy($id)
    {
        return;
    }
}
