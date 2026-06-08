<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\Client;
use App\Models\subscription;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // ✅ VIEW DOANG
    public function index()
    {
        return view('Langganan.dashboard');
    }

    // ✅ API DATA
 // ✅ API DATA

// Tambahkan method ini
public function getAvailableYears()
{
    $oldestYear = Client::min(\DB::raw('YEAR(subscription_start_date)'));
    $latestYear = Client::max(\DB::raw('YEAR(subscription_start_date)'));
    
    if (!$oldestYear) $oldestYear = date('Y');
    if (!$latestYear) $latestYear = date('Y');
    
    return response()->json([
        'oldest_year' => $oldestYear,
        'latest_year' => $latestYear
    ]);
}

public function api(Request $request)
{
    $totalClients = Client::count();

    $activeClients = Client::whereDate('subscription_end_date', '>', today())->count();

    $warningClients = Client::whereDate('subscription_end_date', '>=', today())
    ->whereDate('subscription_end_date', '<=', today()->addDays(30))
    ->get()
    ->map(function ($client) {
        $endDate = Carbon::parse($client->subscription_end_date)->startOfDay();
        $today = Carbon::today();
        return [
            'id' => $client->id,
            'name' => $client->name,
            'company' => $client->company,
            'email' => $client->email,
            'phone' => $client->phone_number,
            'price' => 'Rp ' . number_format($client->revenue, 0, ',', '.'),
            'subscription_end_date' => $endDate->translatedFormat('d M Y'),
            'days_left' => max(0, $today->diffInDays($endDate, false)),
            'description' => $client->description, // <-- TAMBAHKAN INI
        ];
    });
      

    $expiringsoon = Client::whereBetween(
        DB::raw('DATE(subscription_end_date)'),
        [now()->toDateString(), now()->addDays(30)->toDateString()]
    )->count();

    $inactiveClients = Client::whereDate('subscription_end_date', '<', today())->count();
    $totalrevenue = Client::sum('revenue');

    // Ambil tahun dari request, default tahun sekarang
    $year = $request->get('year', date('Y'));
    $months = [];
    $totalActivePerMonth = [];
    $newClientData = [];

    for ($m = 1; $m <= 12; $m++) {
        // Ambil tanggal TERAKHIR bulan ini (23:59:59)
        $endOfMonth = Carbon::create($year, $m, 1)->endOfMonth();
        
        $months[] = $endOfMonth->format('M Y');
        
        // ========== CHART 1: Klien aktif pada TANGGAL AKHIR BULAN ==========
        // Pastikan subscription_start_date dan subscription_end_date tidak null
        $activeCount = Client::whereNotNull('subscription_start_date')
            ->whereNotNull('subscription_end_date')
            ->where(function($q) use ($endOfMonth) {
                $q->where('subscription_start_date', '<=', $endOfMonth)
                  ->where('subscription_end_date', '>=', $endOfMonth);
            })->count();
        
        $totalActivePerMonth[] = $activeCount;
        
        // ========== CHART 2: Klien Baru di bulan ini ==========
        $newClientData[] = Client::whereYear('subscription_start_date', $year)
            ->whereMonth('subscription_start_date', $m)
            ->count();
    }

    return response()->json([
        'totalClients' => $totalClients,
        'activeClients' => $activeClients,
        'inactiveClients' => $inactiveClients,
        'expiringSoon' => $expiringsoon,
        'totalrevenue' => $totalrevenue,
        'months' => $months,
        'clientdata' => $totalActivePerMonth,
        'revenuedata' => $newClientData,
        'warningClients' => $warningClients 
    ]);
}
    // ========== 🆕 TAMBAHKAN METHOD INI UNTUK DETAIL POPUP ==========

    /**
     * GET DETAIL TOTAL KLIEN (SEMUA)
     */
    public function getTotalKlienDetail()
    {
        $clients = Client::select('id', 'name', 'company', 'email', 'subscription_end_date')
            ->orderBy('name')
            ->get()
            ->map(function($client) {
                $endDate = Carbon::parse($client->subscription_end_date);
                $today = Carbon::today();
                
                if ($endDate->lt($today)) {
                    $status = 'Tidak Aktif';
                    $statusClass = 'status-berakhir';
                } elseif ($endDate->lte($today->copy()->addDays(30))) {
                    $status = 'Akan Berakhir';
                    $statusClass = 'status-warning';
                } else {
                    $status = 'Aktif';
                    $statusClass = 'status-aktif';
                }
                
                return [
                    'id' => $client->id,
                    'name' => $client->name,
                    'company' => $client->company,
                    'email' => $client->email,
                    'end_date' => $endDate->translatedFormat('d M Y'),
                    'status' => $status,
                    'status_class' => $statusClass
                ];
            });
        
        return response()->json([
            'success' => true,
            'total' => $clients->count(),
            'data' => $clients
        ]);
    }

    /**
     * GET DETAIL KLIEN AKTIF
     */
    public function getKlienAktifDetail()
    {
        $clients = Client::whereDate('subscription_end_date', '>', Carbon::today())
            ->select('id', 'name', 'company', 'email', 'subscription_end_date')
            ->orderBy('name')
            ->get()
            ->map(function($client) {
                $endDate = Carbon::parse($client->subscription_end_date);
                return [
                    'id' => $client->id,
                    'name' => $client->name,
                    'company' => $client->company,
                    'email' => $client->email,
                    'end_date' => $endDate->translatedFormat('d M Y'),
                    'status' => 'Aktif'
                ];
            });
        
        return response()->json([
            'success' => true,
            'total' => $clients->count(),
            'data' => $clients
        ]);
    }

    /**
     * GET DETAIL KLIEN TIDAK AKTIF (EXPIRED)
     */
    public function getKlienTidakAktifDetail()
    {
        $clients = Client::whereDate('subscription_end_date', '<', Carbon::today())
            ->select('id', 'name', 'company', 'email', 'subscription_end_date')
            ->orderBy('subscription_end_date', 'desc')
            ->get()
            ->map(function($client) {
                $endDate = Carbon::parse($client->subscription_end_date);
                return [
                    'id' => $client->id,
                    'name' => $client->name,
                    'company' => $client->company,
                    'email' => $client->email,
                    'end_date' => $endDate->translatedFormat('d M Y'),
                    'status' => 'Berakhir'
                ];
            });
        
        return response()->json([
            'success' => true,
            'total' => $clients->count(),
            'data' => $clients
        ]);
    }

/**
 * GET CURRENT USER DATA
 */
public function getCurrentUser() 
{
    try {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }
        
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role ?? 'ADMIN'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to fetch user: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * UPDATE USER PROFILE
 */
public function update(Request $request) 
{
    try {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6'
        ]);
        
        $user->name = $request->name;
        $user->email = $request->email;
        
        if ($request->password && !empty($request->password)) {
            $user->password = bcrypt($request->password);
        }
        
        $user->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role ?? 'ADMIN'
            ]
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to update profile: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * DELETE USER ACCOUNT
 * 
 * 
 */

/**
 * Alias for update method (untuk route)
 */
public function updateProfile(Request $request)
{
    return $this->update($request);
}

/**
 * Alias for delete method (untuk route)
 */
public function deleteAccount(Request $request)
{
    return $this->delete($request);
}

public function delete(Request $request) 
{
    try {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }
        
        // Logout dan hapus user
        $user->delete();
        auth()->logout();
        
        // Invalidate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return response()->json([
            'success' => true,
            'message' => 'Account deleted successfully'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to delete account: ' . $e->getMessage()
        ], 500);
    }
}
    /**
     * GET DETAIL KLIEN AKAN BERAKHIR (30 HARI)
     */
    public function getKlienAkanBerakhirDetail()
    {
        $today = Carbon::today();
        $endDate = Carbon::today()->addDays(30);
        
        $clients = Client::whereDate('subscription_end_date', '>=', $today)
            ->whereDate('subscription_end_date', '<=', $endDate)
            ->select('id', 'name', 'company', 'email', 'subscription_end_date')
            ->orderBy('subscription_end_date', 'asc')
            ->get()
            ->map(function($client) {
                $endDate = Carbon::parse($client->subscription_end_date);
                $daysLeft = Carbon::today()->diffInDays($endDate, false);
                
                return [
                    'id' => $client->id,
                    'name' => $client->name,
                    'company' => $client->company,
                    'email' => $client->email,
                    'end_date' => $endDate->translatedFormat('d M Y'),
                    'days_left' => $daysLeft,
                    'status' => 'Akan Berakhir'
                ];
            });
        
        return response()->json([
            'success' => true,
            'total' => $clients->count(),
            'data' => $clients
        ]);
    }

   /**
 * GET DETAIL TOTAL PENDAPATAN (SORT BY REVENUE) - DENGAN FILTER TAHUN
 */
/**
 * GET DETAIL TOTAL PENDAPATAN - HANYA TAHUN YANG PUNYA DATA
 */
public function getTotalPendapatanDetail(Request $request)
{
    $tahun = $request->get('tahun', date('Y'));
    
    // Ambil klien yang revenue > 0 DAN tahunnya sesuai
    $clients = Client::whereYear('subscription_start_date', $tahun)
        ->where('revenue', '>', 0)
        ->select('id', 'name', 'company', 'revenue', 'subscription_start_date')
        ->orderBy('revenue', 'desc')
        ->get()
        ->map(function($client) {
            return [
                'id' => $client->id,
                'name' => $client->name,
                'company' => $client->company,
                'revenue' => $client->revenue,
                'revenue_formatted' => 'Rp ' . number_format($client->revenue, 0, ',', '.'),
                'start_date' => date('d M Y', strtotime($client->subscription_start_date))
            ];
        });
    
    $totalRevenue = $clients->sum('revenue');
    
    return response()->json([
        'success' => true,
        'total' => $clients->count(),
        'total_revenue' => $totalRevenue,
        'total_revenue_formatted' => 'Rp ' . number_format($totalRevenue, 0, ',', '.'),
        'data' => $clients,
        'tahun' => $tahun
    ]);
}

/**
 * GET DAFTAR TAHUN YANG PUNYA DATA PENDAPATAN
 */
public function getTahunPendapatan()
{
    $tahunList = Client::where('revenue', '>', 0)
        ->selectRaw('YEAR(subscription_start_date) as tahun')
        ->distinct()
        ->orderBy('tahun', 'asc')
        ->pluck('tahun');
    
    // Pastikan return array meskipun kosong
    return response()->json([
        'success' => true,
        'tahun_list' => $tahunList->isEmpty() ? [date('Y')] : $tahunList
    ]);
}

/**
 * GET TAHUN TERTUA DARI DATA KLIEN
 */
/**
 * GET TAHUN TERTUA DARI DATA KLIEN
 */
public function getOldestYear()
{
    $oldestYear = Client::min(\DB::raw('YEAR(subscription_start_date)'));
    
    return response()->json([
        'success' => true,
        'oldest_year' => $oldestYear ?? date('Y')  // ← perbaiki: $oldest_year jadi $oldestYear
    ]);
}
}