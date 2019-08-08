<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\AcceptOrder;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Hotel;

class EmailController extends Controller
{
    // private $user;
    // public function __construct()
    // {
    //     $this->user = User::find(2);
    // }
    public function ship(Request $request)
    {
        // return (new AcceptOrder())->render(); 
        //render ra mail

        // return Hotel::with("HotelType")->get();
        // return Hotel::all();
        // $user = User::find(2);
        // Mail::to($user)->send(new AcceptOrder($user)); 
    }
}
