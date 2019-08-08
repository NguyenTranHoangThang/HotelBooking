<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function getAllNotification(Request $request)
    {
        $result = [];
        // return $request->all();
        // foreach(Hotel::find(15)->unreadNotifications as $notification){
        foreach (Hotel::find($request->hotelId)->notifications as $notification) {
            $result[] = $notification;
        }
        $result = collect($result)->sortByDesc("created_at");
        return $result;
    }
    public function markAsRead(Request $request)
    {
        $id = $request->notificationId;
        $notification = Notification::where("id", $id)->first();
        if ($notification->read_at == null) {
            $notification->update(["read_at" => now()]);
            return response()->json([
                "status" => true,
                "message" => "success",
            ]);
        }else{
            return response()->json([
                "status" => false,
            ]);
        }
    }
}
