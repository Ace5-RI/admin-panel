<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Activity;  // ✅ TAMBAHKAN INI
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::orderBy('created_at', 'desc')->get();
        return view('Langganan.klien', compact('clients'));
    }

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
            'phone_number' => $request->nomer,
            'subscription_start_date' => $request->mulai,
            'subscription_end_date' => $request->akhir,
            'revenue' => $request->pendapatan,
            'status' => 'aktif',
        ]);

        // ✅ LOG: Tambah Klien
        Activity::create([
            'type' => 'create',
            'title' => 'Menambah Klien Baru',
            'detail' => "Menambahkan klien: {$client->name} ({$client->company})",
            'user_name' => auth()->user()->name ?? 'System',
            'user_email' => auth()->user()->email ?? 'system',
            'status' => 'success',
            'ip_address' => $request->ip()
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

    public function update(Request $request, $id)
    {
        $clientId = $request->id ?: $id;
        $client = Client::findOrFail($clientId);
        $oldName = $client->name;  // ✅ SIMPAN NAMA LAMA

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email,' . $clientId,
            'subscription_end_date' => 'required|date',
            'revenue' => 'required|numeric|min:0',
            'phone_number' => 'nullable|string|max:15',
            'status' => 'required|in:aktif,tidak,expired',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $client->update([
            'name' => $request->name,
            'company' => $request->company,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'subscription_end_date' => $request->subscription_end_date,
            'revenue' => $request->revenue,
            'status' => $request->status,
        ]);

        // ✅ LOG: Edit Klien
        Activity::create([
            'type' => 'edit',
            'title' => 'Mengubah Data Klien',
            'detail' => "Mengubah data klien: {$oldName} → {$client->name}",
            'user_name' => auth()->user()->name ?? 'System',
            'user_email' => auth()->user()->email ?? 'system',
            'status' => 'success',
            'ip_address' => $request->ip()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Klien berhasil diupdate!',
            'data' => $client,
        ]);
    } 

    public function destroy($id)
    {
        try {
            $client = Client::findOrFail($id);
            $clientName = $client->name;
            $client->delete();

            // ✅ LOG: Hapus Klien
            Activity::create([
                'type' => 'delete',
                'title' => 'Menghapus Klien',
                'detail' => "Menghapus klien: {$clientName}",
                'user_name' => auth()->user()->name ?? 'System',
                'user_email' => auth()->user()->email ?? 'system',
                'status' => 'warning',    
                'ip_address' => request()->ip()
            ]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data klien berhasil dihapus!'
                ]);
            }
            return redirect()->route('klien.index')->with('success', 'Klien berhasil dihapus!');

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Klien gagal dihapus!'
            ], 500);
        }
    }

    public function getData()
    {
        $clients = Client::orderBy('created_at', 'desc')->get();
        return response()->json([
            'success' => true,
            'data' => $clients
        ]);
    }

    public function sendReminder($id)
    {
        $client = Client::findOrFail($id);
        
        $response = Fonnte::sendMessage($client->phone, 'Halo, ini pesan dari sistem');

        return back()->with('success', 'Pesan Terkirim!');
    }
}