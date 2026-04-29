<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Activity;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Http\Request;


class InvoiceController extends Controller
{
    // Method untuk generate PDF, upload ke Cloudinary, dan balikin link
public function generateAndSend($id)
{
    $client = Client::findOrFail($id);

    // 1. Data invoice
    $data = [
        'client' => $client,
        'nomor' => 'INV/' . date('Y') . '/' . str_pad($client->id, 4, '0', STR_PAD_LEFT),
        'tanggal' => date('d F Y'),
        'jatuh_tempo' => date('d F Y', strtotime($client->subscription_end_date))
    ];

    // 2. Generate PDF
    $pdf = Pdf::loadView('pdf.invoice', $data);

    // 3. Simpan ke storage
    $fileName = "invoice_{$client->id}.pdf";
    $path = storage_path("app/public/invoices/{$fileName}");

    // bikin folder kalau belum ada
    if (!file_exists(dirname($path))) {
        mkdir(dirname($path), 0777, true);
    }

    file_put_contents($path, $pdf->output());

    // 4. URL publik
    $pdfUrl = config('app.url') . "/storage/invoices/{$fileName}";

    // 5. Log activity
    Activity::create([
        'type' => 'invoice',
        'title' => 'Generate Invoice',
        'detail' => "Membuat invoice untuk klien: {$client->name}",
        'user_name' => auth()->user()->name,
        'user_email' => auth()->user()->email,
        'status' => 'success'
    ]);

    // 6. Return
    return response()->json([
        'success' => true,
        'url' => $pdfUrl,
        'nomor' => $data['nomor']
    ]);
}
}