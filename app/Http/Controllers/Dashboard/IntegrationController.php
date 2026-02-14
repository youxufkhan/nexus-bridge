<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Traits\FilterableController;
use App\Jobs\FetchOrdersJob;
use App\Models\IntegrationConnection;
use Illuminate\Http\Request;

class IntegrationController extends Controller
{
    use FilterableController;

    public function index(Request $request): \Illuminate\View\View
    {
        $agencyId = auth()->user()->agency_id;

        $query = IntegrationConnection::where('agency_id', $agencyId)
            ->with('client');

        // Filters
        $filterConfig = [
            [
                'key' => 'search',
                'type' => 'search',
                'label' => 'Search',
                'fields' => ['platform_type', 'client.name', 'client.code'],
            ],
            [
                'key' => 'platform',
                'type' => 'select',
                'label' => 'Platform',
                'field' => 'platform_type',
                'options' => [
                    'shopify' => 'Shopify',
                    'walmart' => 'Walmart',
                    'amazon' => 'Amazon',
                    'tiktok' => 'TikTok',
                ],
            ],
        ];

        // Sort Config
        $sortConfig = [
            'default' => 'created_at',
            'options' => [
                [
                    'field' => 'client_name',
                    'label' => 'Client',
                    'relation' => [
                        'table' => 'clients',
                        'foreign_key' => 'integration_connections.client_id',
                        'owner_key' => 'clients.id',
                        'field' => 'name'
                    ]
                ],
                ['field' => 'platform_type', 'label' => 'Platform'],
                ['field' => 'created_at', 'label' => 'Date Connected'],
            ],
        ];

        $query = $this->applyFilters($query, $request, $filterConfig);
        $query = $this->applySorting($query, $request, $sortConfig);

        $connections = $query->paginate(15)->withQueryString();

        $currentFilters = $request->only(['search', 'platform']);
        $currentSort = [
            'field' => $request->input('sort_by', 'created_at'),
            'direction' => $request->input('sort_direction', 'desc'),
        ];
        $activeCount = $this->getActiveFiltersCount($request, $filterConfig);

        return view('integrations.index', compact('connections', 'filterConfig', 'sortConfig', 'currentFilters', 'currentSort', 'activeCount'));
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