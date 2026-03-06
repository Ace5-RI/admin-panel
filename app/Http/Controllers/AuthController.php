<?php

use App\Http\Controllers\Controller;
use App\Models\User;

use illuminate\Http\Request;

class AuthController extends Controller
{
   public function index()
   {
    
    return view('Langganan.dashboard');
   }

   public function create()
   {

   }

   public function store()
   {

   }

   public function edit()
   {

   }

   public function delete()
   {

   }


   public function login(Request $request)
   {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);
   }

   
}