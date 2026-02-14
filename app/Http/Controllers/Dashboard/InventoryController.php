<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Traits\FilterableController;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    use FilterableController;

    public function index(Request $request)
    {
        $agencyId = auth()->user()->agency_id;

        // Fetch products with their related inventory and warehouse data
        $query = Product::where('agency_id', $agencyId)
            ->with(['inventories.warehouse'])
            ->withSum('inventories', 'quantity_on_hand');

        // Define filter configuration
        $filterConfig = [
            [
                'key' => 'search',
                'type' => 'search',
                'label' => 'Search',
                'fields' => ['name', 'master_sku'],
            ],
            [
                'key' => 'warehouse_id',
                'type' => 'relation',
                'label' => 'Warehouse',
                'relation' => 'inventories',
                'field' => 'warehouse_id',
                'options' => Warehouse::where('agency_id', $agencyId)->pluck('name', 'id')->toArray(),
            ]
        ];

        // Define sort configuration
        $sortConfig = [
            'default' => 'created_at',
            'options' => [
                ['field' => 'name', 'label' => 'Product Name'],
                ['field' => 'master_sku', 'label' => 'SKU'],
                ['field' => 'inventories_sum_quantity_on_hand', 'label' => 'Total Stock'],
                ['field' => 'created_at', 'label' => 'Date Added'],
            ],
        ];

        // Apply filters and sorting
        $query = $this->applyFilters($query, $request, $filterConfig);
        $query = $this->applySorting($query, $request, $sortConfig);

        $products = $query->paginate(15)->withQueryString();

        $currentFilters = $request->only(['search', 'warehouse_id']);
        $currentSort = [
            'field' => $request->input('sort_by', 'created_at'),
            'direction' => $request->input('sort_direction', 'desc'),
        ];
        $activeCount = $this->getActiveFiltersCount($request, $filterConfig);

        return view('inventories.index', compact(
            'products',
            'filterConfig',
            'sortConfig',
            'currentFilters',
            'currentSort',
            'activeCount'
        ));
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
            ->with('success', 'Inventory levels adjusted for ' . $product->name);
    }

    public function wizard()
    {
        $agencyId = auth()->user()->agency_id;
        $warehouses = Warehouse::where('agency_id', $agencyId)->get();

        return view('inventories.wizard', compact('warehouses'));
    }

    public function searchProducts(Request $request)
    {
        $query = $request->input('q', '');
        $agencyId = auth()->user()->agency_id;

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $products = Product::where('agency_id', $agencyId)
            ->where(function ($q) use ($query) {
            $q->where('name', 'ILIKE', "%{$query}%")
                ->orWhere('master_sku', 'ILIKE', "%{$query}%");
        })
            ->select('id', 'name', 'master_sku')
            ->limit(10)
            ->get();

        return response()->json($products);
    }

    public function fetchInventory(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
        ]);

        $agencyId = auth()->user()->agency_id;

        // Verify product belongs to agency
        $product = Product::where('id', $request->product_id)
            ->where('agency_id', $agencyId)
            ->firstOrFail();

        // Verify warehouse belongs to agency
        $warehouse = Warehouse::where('id', $request->warehouse_id)
            ->where('agency_id', $agencyId)
            ->firstOrFail();

        // Fetch or create inventory record
        $inventory = Inventory::where('product_id', $product->id)
            ->where('warehouse_id', $warehouse->id)
            ->first();

        return response()->json([
            'quantity_on_hand' => $inventory->quantity_on_hand ?? 0,
            'quantity_reserved' => $inventory->quantity_reserved ?? 0,
            'warehouse_name' => $warehouse->name,
            'product_name' => $product->name,
        ]);
    }

    public function processAdjustment(Request $request)
    {
        $request->validate([
            'adjustment_type' => 'required|in:ADJUST',
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'adjustment_quantity' => 'required|integer',
        ]);

        $agencyId = auth()->user()->agency_id;

        // Verify product belongs to agency
        $product = Product::where('id', $request->product_id)
            ->where('agency_id', $agencyId)
            ->firstOrFail();

        // Verify warehouse belongs to agency
        $warehouse = Warehouse::where('id', $request->warehouse_id)
            ->where('agency_id', $agencyId)
            ->firstOrFail();

        // Get or create inventory record
        $inventory = Inventory::firstOrCreate(
        [
            'agency_id' => $agencyId,
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
        ],
        [
            'quantity_on_hand' => 0,
            'quantity_reserved' => 0,
        ]
        );

        // Calculate new quantity
        $newQuantity = $inventory->quantity_on_hand + $request->adjustment_quantity;

        // Prevent negative inventory
        if ($newQuantity < 0) {
            return response()->json([
                'success' => false,
                'message' => 'Adjustment would result in negative inventory. Current stock: ' . $inventory->quantity_on_hand,
            ], 422);
        }

        // Update inventory
        $inventory->quantity_on_hand = $newQuantity;
        $inventory->save();

        return response()->json([
            'success' => true,
            'message' => 'Inventory adjusted successfully',
            'new_quantity' => $newQuantity,
        ]);
    }

    protected function authorizeProduct(Product $product): void
    {
        if ($product->agency_id !== auth()->user()->agency_id) {
            abort(403);
        }
    }
}