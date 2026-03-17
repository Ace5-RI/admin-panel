<?php

use App\Http\Controllers\Controller;
use illuminate\Support\Facades\Auth;
use App\Models\User;
use Session;

use illuminate\Http\Request;

class AuthController extends Controller
{
  public function login(Request $request)
  {
    if (Auth::check()) {
        return redirect('langganan.dashboard');
    }else{
        return view('langganan.login');
    }
  }

  public function actionlogin(Request $request)
  {
    $data = [
        'email' => $request->input('email'),
        'password' => $request->input('password')
    ];

    if (Auth::attempt($data)) {
        return redirect('home');
    }else{
        Session::flash('error', 'Email atau Password Salah');
        return redirect('/');
    }
  }

  public function actionlogout()
  {
    Auth::logout();
    return redirect('/');
  }
}