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
        $totalClients = Client::count();

        // 🔥 FIX: pakai tanggal, bukan status
       $activeClients = Client::whereDate('subscription_end_date', '>', today())->count();

$todayExpired = Client::whereDate('subscription_end_date', today())->count();






$warningClients = Client::whereDate('subscription_end_date', '>=', today())
    ->whereDate('subscription_end_date', '<=', today()->addDays(30))
    ->get()
    ->map(function ($client) {

        $endDate = Carbon::parse($client->subscription_end_date)->startOfDay();
        $today = Carbon::today();

        return [
            'id' => $client->id,  // 🔥 TAMBAH INI
            'name' => $client->name,
            'company' => $client->company,
            'email' => $client->email,
            'price' => 'Rp ' . number_format($client->revenue, 0, ',', '.'),
            
            // ✅ tanggal clean TANPA JAM
            'subscription_end_date' => $endDate->translatedFormat('d M Y'),

            // ✅ hari bulat
            'days_left' => max(0, $today->diffInDays($endDate, false))
        ];
    });


$expiringsoon = Client::whereBetween(
    DB::raw('DATE(subscription_end_date)'),
    [now()->toDateString(), now()->addDays(30)->toDateString()]
)->count();

      $inactiveClients = Client::whereDate('subscription_end_date', '<', today())->count();

        $totalrevenue = Client::sum('revenue');

$year = date('Y'); // tahun sekarang
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
}


    