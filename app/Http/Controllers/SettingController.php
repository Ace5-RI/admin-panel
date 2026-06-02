<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'company_name' => Setting::get('company_name', 'Bali Solution Biz'),
            'company_address' => Setting::get('company_address', ''),
            'company_phone' => Setting::get('company_phone', ''),
            'company_email' => Setting::get('company_email', ''),
            'company_logo' => Setting::get('company_logo', ''),
            'bank_bca' => Setting::get('bank_bca', ''),
            'bank_mandiri' => Setting::get('bank_mandiri', ''),
            'bank_account_name' => Setting::get('bank_account_name', ''),
            'invoice_footer' => Setting::get('invoice_footer', 'Terima kasih atas kepercayaan Anda.'),
        ];
        
        return view('Langganan.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'company_address' => 'nullable|string|max:500',
            'company_phone' => 'nullable|string|max:20',
            'company_email' => 'nullable|email|max:255',
            'bank_bca' => 'nullable|string|max:50',
            'bank_mandiri' => 'nullable|string|max:50',
            'bank_account_name' => 'nullable|string|max:100',
            'invoice_footer' => 'nullable|string|max:500',
        ]);

        // Update semua setting
        Setting::set('company_name', $request->company_name);
        Setting::set('company_address', $request->company_address);
        Setting::set('company_phone', $request->company_phone);
        Setting::set('company_email', $request->company_email);
        Setting::set('bank_bca', $request->bank_bca);
        Setting::set('bank_mandiri', $request->bank_mandiri);
        Setting::set('bank_account_name', $request->bank_account_name);
        Setting::set('invoice_footer', $request->invoice_footer ?? 'Terima kasih atas kepercayaan Anda.');

        return response()->json([
            'success' => true,
            'message' => 'Pengaturan berhasil disimpan!'
        ]);
    }
    
    public function uploadLogo(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($request->hasFile('logo')) {
            // Hapus logo lama
            $oldLogo = Setting::get('company_logo');
            if ($oldLogo && file_exists(public_path($oldLogo))) {
                unlink(public_path($oldLogo));
            }
            
            // Upload logo baru
            $logo = $request->file('logo');
            $logoName = 'logo_' . time() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('uploads/settings'), $logoName);
            $logoPath = '/uploads/settings/' . $logoName;
            
            Setting::set('company_logo', $logoPath);
            
            return response()->json([
                'success' => true,
                'logo_url' => $logoPath,
                'message' => 'Logo berhasil diupload!'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Gagal upload logo!'
        ], 400);
    }
    
    public function apiGet()
    {
        $settings = [
            'company_name' => Setting::get('company_name', 'Bali Solution Biz'),
            'company_address' => Setting::get('company_address', ''),
            'company_phone' => Setting::get('company_phone', ''),
            'company_email' => Setting::get('company_email', ''),
            'company_logo' => Setting::get('company_logo', '/img/logos.png'),
            'bank_bca' => Setting::get('bank_bca', ''),
            'bank_mandiri' => Setting::get('bank_mandiri', ''),
            'bank_account_name' => Setting::get('bank_account_name', ''),
            'invoice_footer' => Setting::get('invoice_footer', 'Terima kasih atas kepercayaan Anda.'),
        ];
        
        return response()->json($settings);
    }
}