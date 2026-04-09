<?php

namespace App\Http\Controllers;

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
        $totalClients = Client::count();

        // 🔥 FIX: pakai tanggal, bukan status
        $activeClients = Client::whereDate('subscription_end_date', '>', now())->count();

$expiringsoon = Client::whereBetween(
    DB::raw('DATE(subscription_end_date)'),
    [now()->toDateString(), now()->addDays(30)->toDateString()]
)->count();

       $inactiveClients = Client::where('subscription_end_date', '<=', now())->count();

        $totalrevenue = Client::sum('revenue');

        $months = [];
        $clientdata = [];
        $revenuedate = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);

           $months[] = $month->format('M Y');

            $clientdata[] = Client::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();

            $monthlyRevenue = Client::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('revenue');

            $revenuedate[] = $monthlyRevenue / 1000000;
        }

        return response()->json([
            'totalClients' => $totalClients,
            'activeClients' => $activeClients,
            'inactiveClients' => $inactiveClients, // 🔥 tambahan
            'expiringsoon' => $expiringsoon,
            'totalrevenue' => $totalrevenue,
            'months' => $months,
            'clientdata' => $clientdata,
            'revenuedata' => $revenuedate,
        ]);
    }
}