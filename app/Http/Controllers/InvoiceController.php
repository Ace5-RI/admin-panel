<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Activity;
use Barryvdh\DomPDF\Facade\Pdf;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    // Method untuk generate PDF, upload ke Cloudinary, dan balikin link
    public function generateAndSend($id)
    {
        $client = Client::findOrFail($id);
        
        // 1. Generate PDF
        $data = [
            'client' => $client,
            'nomor' => 'INV/' . date('Y') . '/' . str_pad($client->id, 4, '0', STR_PAD_LEFT),
            'tanggal' => date('d F Y'),
            'jatuh_tempo' => date('d F Y', strtotime($client->subscription_end_date))
        ];
        
        $pdf = Pdf::loadView('pdf.invoice', $data);
        $pdfContent = $pdf->output();
        
        // 2. Upload ke Cloudinary
        $uploadResult = Cloudinary::upload("data:application/pdf;base64," . base64_encode($pdfContent), [
            'folder' => 'invoices',
            'public_id' => "invoice_{$client->id}_{$data['nomor']}"
        ]);
        
        // 3. Dapatkan URL publik
        $pdfUrl = $uploadResult->getSecurePath();
        
        // 4. Log activity
        Activity::create([
            'type' => 'invoice',
            'title' => 'Generate Invoice',
            'detail' => "Membuat invoice untuk klien: {$client->name} dan upload ke cloud",
            'user_name' => auth()->user()->name,
            'user_email' => auth()->user()->email,
            'status' => 'success'
        ]);
        
        // 5. Balikin URL
        return response()->json([
            'success' => true,
            'url' => $pdfUrl,
            'nomor' => $data['nomor']
        ]);
    }
}