<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Activity;
class AuthController extends Controller
{
    // ================= LOGIN VIEW =================
    public function showLogin()
    {
        return view('Langganan.login');
    }

    // ================= LOGIN =================
    public function login(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => $validator->errors()->first(),
        ], 422);
    }

    $credentials = [
        'email' => $request->email,
        'password' => $request->password
    ];

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        $user = Auth::user();

        if ($user->role !== 'admin') {
            Auth::logout();
            
            // ✅ LOG ACTIVITY: Login Gagal (bukan admin)
            Activity::create([
                'type' => 'login',
                'title' => 'Login Gagal',
                'detail' => "Percobaan login dengan email: {$request->email} (bukan admin)",
                'user_name' => $user->name,
                'user_email' => $request->email,
                'status' => 'error',
                'ip_address' => $request->ip()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak! Hanya admin yang dapat login.',
            ], 403);
        }

        // ✅ LOG ACTIVITY: Login Berhasil
        Activity::create([
            'type' => 'login',
            'title' => 'Login Sistem',
            'detail' => "Admin {$user->name} berhasil login ke dashboard",
            'user_name' => $user->name,
            'user_email' => $user->email,
            'status' => 'success',
            'ip_address' => $request->ip()
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil!',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
            'token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    // ✅ LOG ACTIVITY: Login Gagal (password salah)
    Activity::create([
        'type' => 'login',
        'title' => 'Login Gagal',
        'detail' => "Percobaan login gagal dengan email: {$request->email} (password salah)",
        'user_name' => 'Unknown',
        'user_email' => $request->email,
        'status' => 'error',
        'ip_address' => $request->ip()
    ]);

    return response()->json([
        'success' => false,
        'message' => 'Email atau password salah!',
    ], 401);
}

    // ================= REGISTER =================
    public function register(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email',
        'phone_number' => 'nullable|string|max:20',
        'password' => 'required|string|min:6|confirmed',
    ]);

    if ($validator->fails()) {
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
        'role' => 'admin'  // ← UBAH jadi admin biar bisa login
    ]);

    // ✅ LOG ACTIVITY: Registrasi Berhasil
    Activity::create([
        'type' => 'create',
        'title' => 'Registrasi User Baru',
        'detail' => "User baru terdaftar: {$user->name} ({$user->email}) dengan role admin",
        'user_name' => $user->name,
        'user_email' => $user->email,
        'status' => 'success',
        'ip_address' => $request->ip()
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Registrasi berhasil!',
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ],
    ], 201);
}

   public function logout(Request $request)
{
    $user = Auth::user();
    
    if ($user) {
        Activity::create([
            'type' => 'login',
            'title' => 'Logout Sistem',
            'detail' => "Admin {$user->name} telah logout dari dashboard",
            'user_name' => $user->name,
            'user_email' => $user->email,
            'status' => 'success',
            'ip_address' => $request->ip()
        ]);
    }

    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    // LANGSUNG REDIRECT KE LOGIN
    return redirect('/login');
}
}