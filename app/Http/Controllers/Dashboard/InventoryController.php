<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $agencyId = auth()->user()->agency_id;

        // Fetch products with their related inventory and warehouse data
        $products = Product::where('agency_id', $agencyId)
            ->with(['inventories.warehouse'])
            ->latest()
            ->paginate(15);

        return view('inventories.index', compact('products'));
    }

    public function adjust(Product $product)
    {
        $this->authorizeProduct($product);

        $agencyId = auth()->user()->agency_id;
        $warehouses = Warehouse::where('agency_id', $agencyId)->get();

        // Eager load inventory for these warehouses
        $product->load(['inventories' => function ($query) use ($warehouses) {
            $query->whereIn('warehouse_id', $warehouses->pluck('id'));
        }]);

        return view('inventories.adjust', compact('product', 'warehouses'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorizeProduct($product);

        $validated = $request->validate([
            'adjustments' => 'required|array',
            'adjustments.*.warehouse_id' => 'required|exists:warehouses,id',
            'adjustments.*.quantity_on_hand' => 'required|integer|min:0',
        ]);

        $agencyId = auth()->user()->agency_id;

        foreach ($validated['adjustments'] as $adjustment) {
            // Verify warehouse belongs to agency
            $warehouse = Warehouse::where('id', $adjustment['warehouse_id'])
                ->where('agency_id', $agencyId)
                ->firstOrFail();

            Inventory::updateOrCreate(
                [
                    'agency_id' => $agencyId,
                    'product_id' => $product->id,
                    'warehouse_id' => $warehouse->id,
                ],
                [
                    'quantity_on_hand' => $adjustment['quantity_on_hand'],
                    'quantity_reserved' => 0, // Default for manual adjustment
                ]
            );
        }

        return redirect()->route('dashboard.inventories.index')
            ->with('success', 'Inventory levels adjusted for '.$product->name);
    }

    protected function authorizeProduct(Product $product): void
    {
        if ($product->agency_id !== auth()->user()->agency_id) {
            abort(403);
        }
    }
}
