<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function index()
    {
        return view('auth.index');
    }
}
