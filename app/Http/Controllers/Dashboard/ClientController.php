<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        // Use Real Auth
        $agencyId = auth()->user()->agency_id;
        $clients = \App\Models\Client::where('agency_id', $agencyId)->get();
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:clients,code',
        ]);

        // Use Real Auth
        $validated['agency_id'] = auth()->user()->agency_id;

        \App\Models\Client::create($validated);

        return redirect()->route('dashboard.clients.index');
    }
}