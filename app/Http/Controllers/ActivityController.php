<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public function index()
    {
        return view('langganan.aktivitas');
    }

    public function getActivities(Request $request)
    {
        $type = $request->get('type', 'all');
        
        $query = Activity::orderBy('created_at', 'desc');
        
        if ($type !== 'all') {
            $query->where('type', $type);
        }
        
        $activities = $query->get();
        
        $stats = [
            'total_login' => Activity::where('type', 'login')->count(),
            'total_invoice' => Activity::where('type', 'invoice')->count(),
            'total_edit' => Activity::whereIn('type', ['edit', 'delete'])->count(),
            'total_payment' => Activity::where('type', 'payment')->count(),
        ];
        
        return response()->json([
            'success' => true,
            'activities' => $activities,
            'stats' => $stats
        ]);
    }
    
    public static function log($type, $title, $detail = null, $status = 'success')
    {
        $user = Auth::user();
        
        return Activity::create([
            'type' => $type,
            'title' => $title,
            'detail' => $detail,
            'user_name' => $user ? $user->name : 'System',
            'user_email' => $user ? $user->email : null,
            'status' => $status,
            'ip_address' => request()->ip()
        ]);
    }
}