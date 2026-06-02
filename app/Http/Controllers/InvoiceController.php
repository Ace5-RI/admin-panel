<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Activity;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function generateAndSend($id)
    {
        try {
            $client = Client::find($id);
            
            if (!$client) {
                return response()->json([
                    'success' => false,
                    'message' => 'Klien tidak ditemukan'
                ], 404);
            }
            
            $data = [
                'client' => $client,
                'nomor' => 'INV/' . date('Y') . '/' . str_pad($client->id, 4, '0', STR_PAD_LEFT),
                'tanggal' => date('d F Y'),
                'jatuh_tempo' => date('d F Y', strtotime($client->subscription_end_date))
            ];
            
            if (!file_exists(public_path('invoices'))) {
                mkdir(public_path('invoices'), 0777, true);
            }
            
            $pdf = Pdf::loadView('invoice', $data);
            $filename = "invoice_{$client->id}.pdf";
            $path = public_path("invoices/{$filename}");
            
            file_put_contents($path, $pdf->output());
            
            $pdfUrl = "https://earwig-tidings-ranked.ngrok-free.dev/invoices/{$filename}";
            
            // 🔥 RECORD ACTIVITY 🔥
            Activity::create([
                'type' => 'invoice',
                'title' => 'Generate Invoice',
                'detail' => "Membuat invoice untuk klien: {$client->name} (ID: {$client->id})",
                'user_name' => auth()->user()->name ?? 'System',
                'user_email' => auth()->user()->email ?? 'system',
                'ip_address' => request()->ip(),
                'status' => 'success'
            ]);
            
            return response()->json([
                'success' => true,
                'url' => $pdfUrl,
                'nomor' => $data['nomor']
            ]);
            
        } catch (\Exception $e) {
            // 🔥 RECORD ERROR ACTIVITY 🔥
            Activity::create([
                'type' => 'invoice',
                'title' => 'Generate Invoice Gagal',
                'detail' => "Gagal membuat invoice untuk ID: {$id} - Error: {$e->getMessage()}",
                'user_name' => auth()->user()->name ?? 'System',
                'user_email' => auth()->user()->email ?? 'system',
                'ip_address' => request()->ip(),
                'status' => 'error'
            ]);
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }
    
    public function show($id)
    {
        $client = Client::findOrFail($id);
        
        $tanggal = date('d F Y');
        $jatuh_tempo = date('d F Y', strtotime($client->subscription_end_date));
        $nomor = 'INV/' . date('Y') . '/' . str_pad($client->id, 4, '0', STR_PAD_LEFT);
        
        $daysLeft = ceil((strtotime($client->subscription_end_date) - time()) / (60 * 60 * 24));
        if ($daysLeft <= 0) {
            $status = 'EXPIRED';
        } elseif ($daysLeft <= 7) {
            $status = $daysLeft . ' HARI LAGI';
        } else {
            $status = 'AKTIF';
        }
        
        return view('invoice', compact('client', 'tanggal', 'jatuh_tempo', 'nomor', 'status'));
    }
}