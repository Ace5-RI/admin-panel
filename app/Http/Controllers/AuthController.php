<?php

use App\Http\Controllers\Controller;
use App\Models\Langganan;

use illuminate\Http\Request;

class AuthController extends Controller
{
   public function index()
   {
    return view('Langganan.dashboard');
   }
}