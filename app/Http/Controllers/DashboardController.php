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
        return view('langganan.dashboard');
    }

    // ✅ API DATA
    public function api()
    {
        // ... (kode api kamu yang sudah ada, tetap pertahankan)
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
                    'price' => 'Rp ' . number_format($client->revenue, 0, ',', '.'),
                    'subscription_end_date' => $endDate->translatedFormat('d M Y'),
                    'days_left' => max(0, $today->diffInDays($endDate, false))
                ];
            });

        $expiringsoon = Client::whereBetween(
            DB::raw('DATE(subscription_end_date)'),
            [now()->toDateString(), now()->addDays(30)->toDateString()]
        )->count();

        $inactiveClients = Client::whereDate('subscription_end_date', '<', today())->count();
        $totalrevenue = Client::sum('revenue');

        $year = date('Y');
        $months = [];
        $clientData = [];
        $revenueData = [];

        for ($m = 1; $m <= 12; $m++) {
            $months[] = date('M Y', strtotime("$year-$m-01"));
            $clientData[] = Client::whereYear('created_at', $year)
                ->whereMonth('subscription_start_date', $m)
                ->count();
            $monthlyRevenue = Client::whereYear('created_at', $year)
                ->whereMonth('subscription_start_date', $m)
                ->sum('revenue');
            $revenueData[] = $monthlyRevenue / 1000000;
        }

        return response()->json([
            'totalClients' => $totalClients,
            'activeClients' => $activeClients,
            'inactiveClients' => $inactiveClients,
            'expiringSoon' => $expiringsoon,
            'totalrevenue' => $totalrevenue,
            'months' => $months,
            'clientdata' => $clientData,
            'revenuedata' => $revenueData,
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
     * GET DETAIL TOTAL PENDAPATAN (SORT BY REVENUE)
     */
    public function getTotalPendapatanDetail()
    {
        $clients = Client::where('revenue', '>', 0)
            ->select('id', 'name', 'company', 'revenue')
            ->orderBy('revenue', 'desc')
            ->get()
            ->map(function($client) {
                return [
                    'id' => $client->id,
                    'name' => $client->name,
                    'company' => $client->company,
                    'revenue' => $client->revenue,
                    'revenue_formatted' => 'Rp ' . number_format($client->revenue, 0, ',', '.')
                ];
            });
        
        $totalRevenue = $clients->sum('revenue');
        
        return response()->json([
            'success' => true,
            'total' => $clients->count(),
            'total_revenue' => $totalRevenue,
            'total_revenue_formatted' => 'Rp ' . number_format($totalRevenue, 0, ',', '.'),
            'data' => $clients
        ]);
    }
}