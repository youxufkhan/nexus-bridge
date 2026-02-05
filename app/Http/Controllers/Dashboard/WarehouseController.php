<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $warehouses = Warehouse::where('agency_id', auth()->user()->agency_id)->latest()->get();
        return view('warehouses.index', compact('warehouses'));
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