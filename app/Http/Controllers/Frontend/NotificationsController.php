<?php

namespace App\Http\Controllers\Frontend;

use Carbon\Carbon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Validator;
use Hash;
use Session;

class NotificationsController extends Controller
{
    //get notifications
    public function index(Request $req)
    {
        $user = Auth::user();
        $nofications = array();
        foreach ($user->notifications as $n) {
            $temp = $n->data;
            $temp['id'] = $n->id;
            $temp['read'] = $n->read_at?true:false;
            $nofications[] = $temp;
        };
        $count = $user->unreadNotifications->count();
        return response()->json([
            'data' => $nofications,
            'count' => $count
        ]);
    }
    //put/patch notifications/{notifications}
    public function update($id, Request $req)
    {
        // dd($id);
        $notification = Auth::user()->unreadNotifications->where('id', $id)->first();
        if ($notification == null) return response()->json([
            'status' => false
        ]);
        $notification->markAsRead();
        return response()->json([
            'status' => true,
        ]);
    }
    public function updateAll(Request $req)
    {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json([
            'status' => true
        ]);
    }
    public function deleteAll(Request $req)
    {
        Auth::user()->notifications()->delete();
        return response()->json([
            'status' => true
        ]);
    }
}
