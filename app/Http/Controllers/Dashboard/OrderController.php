<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Traits\FilterableController;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use FilterableController;

    public function index(Request $request)
    {
        $agencyId = auth()->user()->agency_id;

        // Base query
        $query = \App\Models\Order::where('agency_id', $agencyId)
            ->with(['client', 'integrationConnection']);

        // Define filter configuration
        $filterConfig = [
            [
                'key' => 'search',
                'type' => 'search',
                'label' => 'Search',
                'fields' => ['external_order_id', 'client.name', 'currency'],
            ],
            [
                'key' => 'status',
                'type' => 'select',
                'label' => 'Status',
                'field' => 'status',
                'options' => [
                    'pending' => 'Pending',
                    'processing' => 'Processing',
                    'shipped' => 'Shipped',
                    'delivered' => 'Delivered',
                    'cancelled' => 'Cancelled',
                    'refunded' => 'Refunded',
                ],
            ],
            [
                'key' => 'date',
                'type' => 'date_range',
                'label' => 'Order Date',
                'field' => 'created_at',
            ],
            [
                'key' => 'amount',
                'type' => 'number_range',
                'label' => 'Amount',
                'field' => 'total_amount',
            ],
        ];

        // Define sort configuration
        $sortConfig = [
            'default' => 'created_at',
            'options' => [
                ['field' => 'created_at', 'label' => 'Date'],
                ['field' => 'external_order_id', 'label' => 'Order ID'],
                ['field' => 'total_amount', 'label' => 'Total Amount'],
                ['field' => 'status', 'label' => 'Status'],
                [
                    'field' => 'client_name',
                    'label' => 'Client',
                    'relation' => [
                        'table' => 'clients',
                        'foreign_key' => 'orders.client_id',
                        'owner_key' => 'clients.id',
                        'field' => 'name'
                    ]
                ],
            ],
        ];

        // Apply filters and sorting
        $query = $this->applyFilters($query, $request, $filterConfig);
        $query = $this->applySorting($query, $request, $sortConfig);

        // Paginate results
        $orders = $query->paginate(20)->withQueryString();

        // Prepare view data
        $currentFilters = $request->only(['search', 'status', 'date_from', 'date_to', 'amount_min', 'amount_max']);
        $currentSort = [
            'field' => $request->input('sort_by', 'created_at'),
            'direction' => $request->input('sort_direction', 'desc'),
        ];
        $activeCount = $this->getActiveFiltersCount($request, $filterConfig);

        return view('orders.index', compact(
            'orders',
            'filterConfig',
            'sortConfig',
            'currentFilters',
            'currentSort',
            'activeCount'
        ));
    }

    public function show(\App\Models\Order $order): \Illuminate\View\View
    {
        if ($order->agency_id !== auth()->user()->agency_id) {
            abort(403);
        }

        $order->load(['client', 'integrationConnection', 'items']);

        return view('orders.show', compact('order'));
    }
}