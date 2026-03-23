<?php

use App\Http\Controllers\Controller;
use illuminate\Support\Facades\Auth;
use App\Models\User;
use Session;

use illuminate\Http\Request;

class AuthController extends Controller
{
  public function showLogin()
  {
    return view('Langgnan.Login');
  }

  public function login(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'email' => 'required|email',
      'password' => 'required',
      'role' => 'required|in:admin,user',
    ]);

    if(validator()->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    }

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
      $user = Auth::user();

      if ($user->role !== $request->role) {
        Auth::logout();
        return redirect()->back()->with('error','Role tidak sesuai')->withInput();
      }

      $request->session()->regenerate();

      return redirect()->intended('/dashboard');
    }

    return redirect()->back()->with('error','Email atau password salah!')->withInput();
  }

  public function showRegister()
  {
    return view('Langgnan.Register');
  }

  public function register(Request $request)
  {
    $validator = validator::make($request->all(), [
      'name' => 'required|string|max:255',
      'email' => 'required|email|max:255|unique:users,email',
      'password' => 'required|string|min:6|confirmed',
    ]);

    if($validator->fails()) {
      return redirect()->bacl()->withErrors($validator)->withInput();
    }

    $user = User::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => Hash::make($request->password),
      'role' => 'user',
    ]);

    Auth::login($user);

    return redirect()->intended('/dashboard');
  }

  public function logout(Request $request)
  {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
  }
}