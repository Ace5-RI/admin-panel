<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = Client::orderBy('created_at', 'desc')->get();
        return view('Langganan.klien', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'perusahaan' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'pendapatan' => 'required|numeric|min:0',
            'mulai' => 'required|date', 
            'akhir' => 'required|date', 
            'nomer' => 'required|string|max:15',
        ]);

        if ($validator->fails()) {
            // Cek apakah request dari AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $client = Client::create([
            'name' => $request->nama,
            'company' => $request->perusahaan,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'subscription_start_date' => $request->mulai,
            'subscription_end_date' => $request->akhir,
            'revenue' => $request->pendapatan,
            'status' => 'aktif', // Ubah dari 'aktif' ke 'active'
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Klien berhasil ditambahkan!',
                'data' => $client
            ], 201);
        }

        return redirect()->back()->with('success', 'Klien berhasil ditambahkan!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Ambil id dari request body (bukan dari parameter URL)
        $id = $request->id;
        $client = Client::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email,' . $id,
            'subscription_end_date' => 'required|date',
            'revenue' => 'required|numeric|min:0',
            'phone_number' => 'nullable|string|max:15',
            'status' => 'required|in:active,inactive,expired',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $client->update([
            'name' => $request->name,
            'company' => $request->company,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'subscription_end_date' => $request->subscription_end_date,
            'revenue' => $request->revenue,
            'status' => $request->status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Klien berhasil diupdate!',
            'data' => $client,
        ]);
    } 

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $client = Client::findOrFail($id);
            $client->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data klien berhasil dihapus!'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                ''
            ]);
        }
    }

    /**
     * Get client data for AJAX (optional)
     */
    public function getData()
    {
        $clients = Client::orderBy('created_at', 'desc')->get();
        return response()->json([
            'success' => true,
            'data' => $clients
        ]);
    }
}