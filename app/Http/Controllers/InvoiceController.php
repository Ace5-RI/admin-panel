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
            $client = Client::findOrFail($id);

            $data = [
                'client' => $client,
                'nomor' => 'INV/' . date('Y') . '/' . str_pad($client->id, 4, '0', STR_PAD_LEFT),
                'tanggal' => date('d F Y'),
                'jatuh_tempo' => date('d F Y', strtotime($client->subscription_end_date))
            ];

            $pdf = Pdf::loadView('pdf.invoice', $data);
            $pdfContent = $pdf->output();

            // Simpan ke public/invoices
            $filename = "invoice_{$client->id}.pdf";
            $path = public_path("invoices/{$filename}");
            
            if (!file_exists(public_path('invoices'))) {
                mkdir(public_path('invoices'), 0777, true);
            }
            
            file_put_contents($path, $pdfContent);

            // 🔥 PAKAI NGROK URL LANGSUNG
            $pdfUrl = "https://earwig-tidings-ranked.ngrok-free.dev/invoices/{$filename}";

            Activity::create([
                'type' => 'invoice',
                'title' => 'Generate Invoice',
                'detail' => "Membuat invoice untuk klien: {$client->name}",
                'user_name' => auth()->user()->name ?? 'System',
                'user_email' => auth()->user()->email ?? 'system',
                'status' => 'success'
            ]);

            return response()->json([
                'success' => true,
                'url' => $pdfUrl,
                'nomor' => $data['nomor']
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
            ], 500);
        }
    }
}