<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        // Use Real Auth
        $agencyId = auth()->user()->agency_id;
        $clients = \App\Models\Client::where('agency_id', $agencyId)->get();

        return view('clients.index', compact('clients'));
    }

    public function create(): \Illuminate\View\View
    {
        return view('clients.create');
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
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

    public function edit(\App\Models\Client $client): \Illuminate\View\View
    {
        $this->authorizeAction($client);

        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, \App\Models\Client $client): \Illuminate\Http\RedirectResponse
    {
        $this->authorizeAction($client);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:clients,code,'.$client->id,
        ]);

        $client->update($validated);

        return redirect()->route('dashboard.clients.index')
            ->with('success', 'Portfolio updated successfully.');
    }

    public function destroy(\App\Models\Client $client): \Illuminate\Http\RedirectResponse
    {
        $this->authorizeAction($client);
        $client->delete();

        return redirect()->route('dashboard.clients.index')
            ->with('success', 'Portfolio archived.');
    }

    protected function authorizeAction(\App\Models\Client $client): void
    {
        if ($client->agency_id !== auth()->user()->agency_id) {
            abort(403);
        }
    }
}
