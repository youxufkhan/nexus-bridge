<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Traits\FilterableController;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    use FilterableController;

    public function index(Request $request): \Illuminate\View\View
    {
        // Use Real Auth
        $agencyId = auth()->user()->agency_id;

        $query = \App\Models\Client::where('agency_id', $agencyId);

        // Filters
        $filterConfig = [
            [
                'key' => 'search',
                'type' => 'search',
                'label' => 'Search',
                'fields' => ['name', 'code'],
            ],
        ];

        // Sort Config
        $sortConfig = [
            'default' => 'created_at',
            'options' => [
                ['field' => 'name', 'label' => 'Name'],
                ['field' => 'code', 'label' => 'Code'],
                ['field' => 'created_at', 'label' => 'Date Onboarded'],
            ],
        ];

        $query = $this->applyFilters($query, $request, $filterConfig);
        $query = $this->applySorting($query, $request, $sortConfig);

        $clients = $query->paginate(15)->withQueryString();

        $currentFilters = $request->only(['search']);
        $currentSort = [
            'field' => $request->input('sort_by', 'created_at'),
            'direction' => $request->input('sort_direction', 'desc'),
        ];
        $activeCount = $this->getActiveFiltersCount($request, $filterConfig);

        return view('clients.index', compact('clients', 'filterConfig', 'sortConfig', 'currentFilters', 'currentSort', 'activeCount'));
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
            'code' => 'required|string|max:50|unique:clients,code,' . $client->id,
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