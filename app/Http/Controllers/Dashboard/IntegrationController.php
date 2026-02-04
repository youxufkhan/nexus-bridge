<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IntegrationController extends Controller
{
    public function index()
    {
        $agencyId = auth()->user()->agency_id;
        $connections = \App\Models\IntegrationConnection::where('agency_id', $agencyId)
            ->with('client')
            ->get();

        return view('integrations.index', compact('connections'));
    }

    public function create()
    {
        $agencyId = auth()->user()->agency_id;
        $clients = \App\Models\Client::where('agency_id', $agencyId)->get();
        return view('integrations.create', compact('clients'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'platform_type' => 'required|in:walmart,amazon,tiktok', // TODO: sync with adapter list
            'api_key' => 'required|string',
            'client_id_key' => 'nullable|string', // Some platforms need this
        ]);

        $connection = \App\Models\IntegrationConnection::create([
            'agency_id' => auth()->user()->agency_id,
            'client_id' => $validated['client_id'],
            'platform_type' => $validated['platform_type'],
            // Store credentials as JSON
            'credentials' => [
                'api_key' => $validated['api_key'],
                'client_id' => $validated['client_id_key'] ?? null,
            ],
            'settings' => ['last_sync_time' => null],
        ]);

        // Dispatch initial sync
        \App\Jobs\FetchOrdersJob::dispatch($connection);

        return redirect()->route('dashboard.integrations.index')
            ->with('success', 'Integration connected and sync started!');
    }
}