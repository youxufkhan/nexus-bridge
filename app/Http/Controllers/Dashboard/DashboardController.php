<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        // Gross Throughput
        $grossThroughput = Order::sum('total_amount');

        // Total Ingested (Orders)
        $totalOrders = Order::count();

        // Master Catalog (Products)
        $totalProducts = Product::count();

        // Active Nodes (Warehouses)
        $activeNodes = Warehouse::count();

        // Active Uplinks (Integrations)
        $activeUplinks = \App\Models\IntegrationConnection::count();

        // Recent Orders
        $recentOrders = Order::with('client')->latest()->limit(5)->get();

        return view('dashboard', [
            'grossThroughput' => $grossThroughput,
            'totalOrders' => $totalOrders,
            'totalProducts' => $totalProducts,
            'activeNodes' => $activeNodes,
            'activeUplinks' => $activeUplinks,
            'recentOrders' => $recentOrders,
        ]);
    }
}
