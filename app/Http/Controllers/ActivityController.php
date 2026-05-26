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
    'total_client' => Activity::where('type', 'create_client')->count(),  // ← TAMBAH
    'total_edit' => Activity::where('type', 'edit')->count(),
    'total_invoice' => Activity::where('type', 'invoice')->count(),
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

    public function clearAll()
{
    try {
        Activity::truncate(); // Hapus semua data
        return response()->json([
            'success' => true,
            'message' => 'Semua aktivitas berhasil dihapus'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}
public function delete($id)
{
    try {
        $activity = Activity::findOrFail($id);
        $activity->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Aktivitas berhasil dihapus'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

public function deleteMultiple(Request $request)
{
    try {
        $ids = $request->get('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data dipilih'], 400);
        }
        
        Activity::whereIn('id', $ids)->delete();
        
        return response()->json([
            'success' => true,
            'message' => count($ids) . ' aktivitas dihapus'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}
}

