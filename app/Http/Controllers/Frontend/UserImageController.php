<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserImage;

class UserImageController extends Controller
{
    //get image
    public function index()
    {
        return;
    }

    //get image/create
    public function create()
    {
        return;
    }

    //post image
    public function store()
    {
        return;
    }

    //get image/{hotels}
    public function show($id)
    {
        return;
    }

    //image/{image}/edit
    public function edit($id)
    {
        return;
    }

    //put/patch image/{image}
    public function update($id, Request $req)
    {
        if (UserImage::find($id)) {
            $img = UserImage::find($id);
            $img->update(['image_link' => $req->link]);
            return response()->json(['mess' => true]);
        };
        return response()->json(['mess' => false]);;
    }

    //delete image/{image}
    public function destroy($id)
    {
        return;
    }
}
