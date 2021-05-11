<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        return view('index');
    }
}
