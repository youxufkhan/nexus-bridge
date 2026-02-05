<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Jobs\FetchOrdersJob;
use App\Models\IntegrationConnection;
use Illuminate\Http\Request;

class IntegrationController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        $agencyId = auth()->user()->agency_id;
        $connections = IntegrationConnection::where('agency_id', $agencyId)
            ->with('client')
            ->get();

        return view('integrations.index', compact('connections'));
    }

    public function create(): \Illuminate\View\View
    {
        $agencyId = auth()->user()->agency_id;
        $clients = \App\Models\Client::where('agency_id', $agencyId)->get();

        return view('integrations.create', compact('clients'));
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'platform_type' => 'required|in:walmart,amazon,tiktok',
            'client_id_key' => 'required|string',
            'client_secret' => 'required|string',
        ]);

        $connection = IntegrationConnection::create([
            'agency_id' => auth()->user()->agency_id,
            'client_id' => $validated['client_id'],
            'platform_type' => $validated['platform_type'],
            'credentials' => [
                'client_id' => $validated['client_id_key'],
                'client_secret' => $validated['client_secret'],
            ],
            'settings' => [
                'last_sync_time' => null,
            ],
        ]);

        // Dispatch initial sync
        FetchOrdersJob::dispatch($connection);

        return redirect()->route('dashboard.integrations.index')
            ->with('success', 'Integration connected and initial sync started!');
    }

    public function edit(IntegrationConnection $integration): \Illuminate\View\View
    {
        $this->authorizeConnection($integration);

        return view('integrations.edit', compact('integration'));
    }

    public function update(Request $request, IntegrationConnection $integration): \Illuminate\Http\RedirectResponse
    {
        $this->authorizeConnection($integration);

        $validated = $request->validate([
            'client_id_key' => 'required|string',
            'client_secret' => 'required|string',
        ]);

        $credentials = $integration->credentials;
        $credentials['client_id'] = $validated['client_id_key'];
        $credentials['client_secret'] = $validated['client_secret'];

        $integration->update([
            'credentials' => $credentials,
        ]);

        return redirect()->route('dashboard.integrations.index')
            ->with('success', 'Integration credentials updated successfully.');
    }

    public function destroy(IntegrationConnection $integration): \Illuminate\Http\RedirectResponse
    {
        $this->authorizeConnection($integration);

        $integration->delete();

        return redirect()->route('dashboard.integrations.index')
            ->with('success', 'Integration disconnected successfully.');
    }

    public function syncOrders(IntegrationConnection $connection): \Illuminate\Http\RedirectResponse
    {
        $this->authorizeConnection($connection);
        FetchOrdersJob::dispatch($connection);

        return back()->with('success', 'Order sync triggered successfully.');
    }

    public function syncProducts(IntegrationConnection $connection): \Illuminate\Http\RedirectResponse
    {
        $this->authorizeConnection($connection);
        \App\Jobs\FetchProductsJob::dispatch($connection);

        return back()->with('success', 'Product sync triggered successfully.');
    }

    protected function authorizeConnection(IntegrationConnection $connection): void
    {
        if ($connection->agency_id !== auth()->user()->agency_id) {
            abort(403);
        }
    }
}
