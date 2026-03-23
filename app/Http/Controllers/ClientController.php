<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = Client::orderBy('created_at', 'desc')->paginate(10);
        return view('Langganan.klien', compact('clients'));
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

    $validator = Validator::make($request->all(),[
        'name' => 'required|string|max:255',
        'company' => 'required|string|max:255',
        'email' => 'required|email|unique|clients,email',
        'subscription_end_date' => 'required|date',
        'revenue' => 'required|numeric|min:0',
        'address' => 'nullable|string'
    ]);

        if ($validator->fails()) {
        return redirect()->back()
        ->withErrors($validator)
        ->withInput();
    }

    client::create([
        'name' => $request->name,
        'company' => $request->company,
        'email' => $request->email,
        'subscription_end_date' => $request->subscription_end_date,
        'revenue' => $request->revenue,
        'address' => $request->address,
        'status' => 'active',
    ]);

    return redirect()->route('Langganan.klien')->with('success', 'Client berhasil ditambahkam!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
