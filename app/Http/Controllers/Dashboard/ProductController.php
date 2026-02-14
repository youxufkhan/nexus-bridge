<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Traits\FilterableController;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use FilterableController;

    public function index(Request $request)
    {
        $agencyId = auth()->user()->agency_id;

        // Build base query
        $query = \App\Models\Product::where('agency_id', $agencyId)
            ->with('client');

        // Define filter configuration
        $filterConfig = [
            [
                'key' => 'search',
                'type' => 'search',
                'label' => 'Search',
                'fields' => ['name', 'master_sku', 'client.name'],
            ],
            [
                'key' => 'client_id',
                'type' => 'select',
                'label' => 'Client',
                'field' => 'client_id',
                'options' => \App\Models\Client::where('agency_id', $agencyId)
                ->pluck('name', 'id')
                ->toArray(),
            ],
            [
                'key' => 'price',
                'type' => 'number_range',
                'label' => 'Price Range',
                'field' => 'base_price',
            ],
        ];

        // Define sort configuration
        $sortConfig = [
            'default' => 'created_at',
            'options' => [
                ['field' => 'name', 'label' => 'Name'],
                ['field' => 'master_sku', 'label' => 'SKU'],
                [
                    'field' => 'client_name',
                    'label' => 'Client',
                    'relation' => [
                        'table' => 'clients',
                        'foreign_key' => 'products.client_id',
                        'owner_key' => 'clients.id',
                        'field' => 'name'
                    ]
                ],
                ['field' => 'base_price', 'label' => 'Price'],
                ['field' => 'created_at', 'label' => 'Date Added'],
            ],
        ];

        // Apply filters and sorting
        $query = $this->applyFilters($query, $request, $filterConfig);
        $query = $this->applySorting($query, $request, $sortConfig);

        // Paginate results
        $products = $query->paginate(20)->withQueryString();

        // Prepare data for view
        $currentFilters = $request->only(['search', 'client_id', 'price_min', 'price_max']);
        $currentSort = [
            'field' => $request->input('sort_by', 'created_at'),
            'direction' => $request->input('sort_direction', 'desc'),
        ];
        $activeCount = $this->getActiveFiltersCount($request, $filterConfig);

        return view('products.index', compact(
            'products',
            'filterConfig',
            'sortConfig',
            'currentFilters',
            'currentSort',
            'activeCount'
        ));
    }

    public function show(\App\Models\Product $product): \Illuminate\View\View
    {
        if ($product->agency_id !== auth()->user()->agency_id) {
            abort(403);
        }

        $product->load(['client', 'platformProductMaps', 'inventories.warehouse']);

        return view('products.show', compact('product'));
    }
}