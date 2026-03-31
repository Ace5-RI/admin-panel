<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // ================= LOGIN VIEW =================
    public function showLogin()
    {
        return view('Langganan.Login');
    }

    // ================= LOGIN =================
    public function login(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required',
        'role' => 'required|in:admin,user',
    ]);

    if($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => $validator->errors()->first(),
        ], 422);
    }

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        // 🔹 PENTING: generate session SEBELUM return JSON
        $request->session()->regenerate();

        $user = Auth::user();

        // cek role
        if ($user->role !== $request->role) {
            Auth::logout();
            return response()->json([
                'success' => false,
                'message' => 'Role tidak sesuai! Harus: ' . strtoupper($user->role),
            ], 403);
        }

        // ✅ RETURN JSON setelah session valid
        return response()->json([
            'success' => true,
            'message' => 'Login berhasil!',
            'user' => $user
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'Email atau password salah!',
    ], 401);


}
    

    // ================= REGISTER VIEW =================
    public function showRegister()
    {
        return view('Langganan.Register');
    }

    // ================= REGISTER =================
    public function register(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email',
        'phone_number' => 'required',
        'password' => 'required|string|min:6|confirmed',
    ]);

    if($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => $validator->errors()->first(),
        ], 422);
    }

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'phone_number' => $request->phone_number,
        'password' => Hash::make($request->password),
        'role' => 'user'
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Registrasi berhasil!',
        'user' => $user
    ]);
}

    
  

    // ================= ACCOUNT =================
    public function Account(Request $request)
    {
        return response()->json([
            'success' => true,
            'user' => $request->user()
        ]);
    }

    // ================= LOGOUT =================
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Logout berhasil!',
            ]);
        }

        return redirect('/');
    }
}