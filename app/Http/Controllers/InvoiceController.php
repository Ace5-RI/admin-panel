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
    $client = Client::find($id);

    if (!$client) {
        return response()->json(['success' => false, 'message' => 'Klien tidak ditemukan'], 404);
    }

    $data = [
        'client' => $client,
        'nomor' => 'INV/' . date('Y') . '/' . str_pad($client->id, 4, '0', STR_PAD_LEFT),
        'tanggal' => date('d F Y'),
        'jatuh_tempo' => date('d F Y', strtotime($client->subscription_end_date)),
        'status' => 'AKTIF'
    ];

    try {
        Activity::create([
            'type' => 'invoice',
            'title' => 'Generate Invoice',
            'detail' => "Membuat invoice untuk klien: {$client->name}",
            'user_name' => auth()->user()->name ?? 'System',
            'user_email' => auth()->user()->email ?? 'system',
            'ip_address' => request()->ip(),
            'status' => 'success'
        ]);
    } catch (\Exception $e) {}

    $pdf = Pdf::loadView('invoice', $data);
    $filename = "invoice_{$client->id}.pdf";

    // Kalau POST (dari tombol WA) → simpan file dan return JSON URL
    if (request()->isMethod('post')) {
        $path = storage_path('app/public/invoices/');
        if (!file_exists($path)) mkdir($path, 0777, true);
        $pdf->save($path . $filename);
        $url = url('storage/invoices/' . $filename);
        return response()->json(['success' => true, 'url' => $url, 'nomor' => $data['nomor']]);
    }

    // Kalau GET → stream langsung di browser
    return $pdf->stream($filename);
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