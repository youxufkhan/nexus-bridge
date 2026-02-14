<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Traits\FilterableController;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    use FilterableController;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $agencyId = auth()->user()->agency_id;

        $query = Warehouse::where('agency_id', $agencyId);

        // Define filter configuration
        $filterConfig = [
            [
                'key' => 'search',
                'type' => 'search',
                'label' => 'Search',
                'fields' => ['name', 'location_code', 'address->city', 'address->country'],
            ],
        ];

        // Define sort configuration
        $sortConfig = [
            'default' => 'created_at',
            'options' => [
                ['field' => 'name', 'label' => 'Name'],
                ['field' => 'location_code', 'label' => 'Code'],
                ['field' => 'created_at', 'label' => 'Date Created'],
            ],
        ];

        // Apply filters and sorting
        $query = $this->applyFilters($query, $request, $filterConfig);
        $query = $this->applySorting($query, $request, $sortConfig);

        $warehouses = $query->paginate(15)->withQueryString();

        $currentFilters = $request->only(['search']);
        $currentSort = [
            'field' => $request->input('sort_by', 'created_at'),
            'direction' => $request->input('sort_direction', 'desc'),
        ];
        $activeCount = $this->getActiveFiltersCount($request, $filterConfig);

        return view('warehouses.index', compact('warehouses', 'filterConfig', 'sortConfig', 'currentFilters', 'currentSort', 'activeCount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('warehouses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(\App\Http\Requests\Dashboard\WarehouseRequest $request)
    {
        $validated = $request->validated();
        $validated['agency_id'] = auth()->user()->agency_id;

        Warehouse::create($validated);

        return redirect()->route('dashboard.warehouses.index')
            ->with('success', 'Warehouse created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Warehouse $warehouse)
    {
        $this->authorizeAccess($warehouse);

        return view('warehouses.edit', compact('warehouse'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(\App\Http\Requests\Dashboard\WarehouseRequest $request, Warehouse $warehouse)
    {
        $this->authorizeAccess($warehouse);
        $warehouse->update($request->validated());

        return redirect()->route('dashboard.warehouses.index')
            ->with('success', 'Warehouse updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Warehouse $warehouse)
    {
        $this->authorizeAccess($warehouse);
        $warehouse->delete();

        return redirect()->route('dashboard.warehouses.index')
            ->with('success', 'Warehouse deleted successfully.');
    }

    protected function authorizeAccess(Warehouse $warehouse): void
    {
        if ($warehouse->agency_id !== auth()->user()->agency_id) {
            abort(403);
        }
    }
}