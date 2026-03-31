<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\subscription;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function Symfony\Component\Clock\now;

class DashboardController extends Controller
{
    public function index()
    {
        $totalClients = Client::count();

        $activeClients = Client::where('status', 'active')->where('Subscription_end_date','>',now())->count();

        $expiringsoon = Client::where('status','active')->whereBetween('subcription_end_date',[now(), now()->addDays(30)])->count();
        $totalrevenue = Client::where('status', 'paid')->sum(amount);
    }
}
