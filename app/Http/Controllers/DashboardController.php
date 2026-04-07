<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\subscription;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalClients = Client::count();

        $activeClients = Client::where('status', 'active')->where('subscription_end_date','>',now())->count();

        $expiringsoon = Client::where('status','active')->whereBetween('subscription_end_date',[now(), now()->addDays(30)])->count();
        $totalrevenue = Client::where('status', 'paid')->sum('amount');
        
        $clientsLastMonth = Client::whereLastMonth('created_at', now()->subMonth()->month)->count();
        $clientsPresent = $clientsLastMonth > 0 ? round(($totalClients - $clientsLastMonth) / $clientsLastMonth * 100, 2) : 0;
        $monthlyRevenue = Payment::where('status','paid')->whereYear('payment_date',now()->year)->select(
            DB::raw('MONTH(payment_date) as month' ),
            DB::raw('SUM(amount) as total')
        )->groupBy('month')->orderBy('month')->get();
    }
}
