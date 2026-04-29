<?php

namespace App\Http\Controllers;
use App\Models\Client;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($clientId)
    {
        $client = Client::findOrFail($clientId);
        
        return view('payment.page',[
            'client' => $client,
            'amount' => $client->revenue,
            'invoice_number' => 'INV/' . date('Ymd') . '/' .str_pad($clientId, 4, '0', STR_PAD_LEFT)
        ]);
    }

    public function process(Request $request)
    {

    }

    public function invoice($clientId, $invoiceId)
    {
        $client = Client::findOrFail($clientId);

        $pdf = PDF::loadview('invoice.subscription', ['client' => $client]);
        
        return $pdf->download('invoice_{$invoiceId}.pdf');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
