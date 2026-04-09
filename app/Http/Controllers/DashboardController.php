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
        $clientsPercent = $clientsLastMonth > 0 ? round(($totalClients - $clientsLastMonth) / $clientsLastMonth * 100, 1) : 0;

        $activeLastMonth = Client::where('status','active')->whereMonth('created_at', now()->subMonth()->month)->count();
        $activePercent = $activeLastMonth > 0 ? round(($activeClients - $activeLastMonth) / $activeLastMonth *100, 1) : 0;
        
        $revenueLastMonth = Payment::where('status','active')->whereMonth('payment_date', now()->subMonth()->month)->sum('amount');
        $revenuePercent = $revenueLastMonth > 0 ? round(($totalrevenue - $revenueLastMonth) / $revenueLastMonth * 100, 1) : 0;

        $monthlyRevenue = Payment::where('status','paid')->whereYear('payment_date',now()->year)->select(
            DB::raw('MONTH(payment_date) as month' ),
            DB::raw('SUM(amount) as total')
        )->groupBy('month')->orderBy('month')->get();

        $months = [];
        $clientdata = [];
        $revenue = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $months[] = $month->format('M Y');

            $clientCount = Client::whereYear('created_dat', $month->year)->whereMonth('created_at', $month->month)->count();
            $clientdata[] = $clientCount;

            $revenue = Payment::where('status', 'paid')->whereYear('payment_date', $month->year)->whereMonth('payment_date', $month->month)->sum('amount');
            $revenuedate[] = $revenue / 1000000;
        }

        $expiringclients = Client::where('status','active')->whereBetween('subscription_end_date',[now(),now()->addDays(30)])->orderBy('subscription_end_date','asc')->limit(3)->get();

        return view('dashboard', compact(
            'totalClients',
            'activeClients',
            'expiringsoon',
            'totalrevenue',
            'clientsPercent',
            'activePercent',
            'revemuePercent',
            'months',
            'clientdata',
            'revenuedate',
            'expiringclients',
        ));
    }
}
