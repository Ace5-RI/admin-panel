<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use illuminate\Support\Facades\Auth;
use App\Models\User;
use Session;

use illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator; 

class AuthController extends Controller
{
  public function showLogin()
  {
    return view('Langganan.Login');
  }

  public function login(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'email' => 'required|email',
      'password' => 'required',
      'role' => 'required|in:admin,user',
    ]);

    if($validator()->fails()) {
      if ($request->expectsJson()) {
        return response()->json([
          'success' => false,
          'message' => $validator->errors()->first(),
        ], 422);
      }
      return redirect()->back()->withErrors($validator)->withInput();
    }

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
      $user = Auth::user();

      if ($user->role !== $request->role) {
        Auth::logout();

        $error = 'Role anda tidak sesuai, silahkan pilih role yang benar!' . strtoupper($user->role);

        if ($request->expectsJson()) {
          return response()->json([
            'success' => false,
            'message' => $error,
          ], 403);
        }
        return redirect()->back()->with('error',$error);
      }

      if ($request->expectsJson()) {
        return response()->json([
          'success' => true,
          'message' => 'Berhasil login!',
          'user' => $user,
          'token' => $user->createToken('auth_token')->plainTextToken,
          'redirect' => route('dashboard'),
        ]);
      }

      $request->session()->regenerate();

      return redirect()->intended('/dashboard');
    }

    $error = 'Email atau password salah!';

    if ($request->expectsJson()) {
      return response()->json([
        'success' => false,
        'message' => $error,
      ], 401);
    }
    return redirect()->back()->with('error', $error)->withInput();
  }

  public function showRegister()
  {
    return view('Langganan.Register');
  }
  

  
  public function register(Request $request)
  {
    $validator = validator::make($request->all(), [
      'name' => 'required|string|max:255',
      'email' => 'required|email|max:255|unique:users,email',
      'password' => 'required|string|min:6|confirmed',
    ]);

    if($validator->fails()) {
      if(validator()->fails()) {
      if ($request->respectJSon()) {
        return response()->json([
        'success' => false,
        'message' => $validator->errors()->first(),
        ], 422);
      }
    }
    return redirect()->back()->withErrors($validator)->withInput();
    }

    $user = User::create([
      'name' => $request->name,
      'email' => $request->email,
      'phone_number' => $request->phone_number,
      'address' => $request->address,
      'password' => Hash::make($request->password),
      'role' => $request->role ?? 'user',
    ]);

    Auth::login($user);
    if ($request->respectJSon()) {
      return response()->json([
        'success' => true,
        'message' => 'Registrasi Sukses!',
        'user' => $user,
        'token' => $user->createToken('auth_token')->plainTextToken,
      ]);
    }

    return redirect()->route('dashboard')->with('success', 'Selamat Datang!');
  }

  public function Account(Request $request)
  {
    return response()->json([
      'success' => true,
      'user' => $request->user()
    ]);
  }

  public function logout(Request $request)
  {
    if ($request->user() && $request->user()->token()) {
      $request->user()->currentAccessToken()->delete();
    }

    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    if ($request->expectsJson()) {
      return response()->json([
        'success' => true,
        'message' => 'Logout Berhasil!',
      ]);
    }
    return redirect('/');
  }
}