<?php

namespace App\Http\Controllers;

use Illuminate\Database\Schema\IndexDefinition;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\models\Langganan;
use Illuminate\View\View;

class LanggananController extends Controller
{
    public function index(): View
    {
        $langganan = Langganan::latest()->paginate(3);
        return view('Langganan.index',compact ('langganan'));
    }

    public function create(): View
    {
        return view('langganan.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|min:5',
            'start_date' => 'date',
            'end_date' => 'date|after_or_equal:start_date'
            ]);

        Langganan::create([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date
        ]); 

        return redirect()->route('Langganan.index')->with(['success' => 'Data input success']);
    }
}