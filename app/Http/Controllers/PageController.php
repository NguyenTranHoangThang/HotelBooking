<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class PageController extends Controller
{
    public function index() {
	    return view('web');
	}
    public function getCommunityPage() {
	    return view('Frontend.community');
	}
	public function adminIndex()
	{
		return view('Backend.admin');
	}
	public function managerIndex()
	{
		return view('Backend.manager');
	}
	public function redirect() {
	    return redirect('/');
	}
}
