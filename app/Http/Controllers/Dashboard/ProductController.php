<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index()
    {
        $agencyId = auth()->user()->agency_id;
        $products = \App\Models\Product::where('agency_id', $agencyId)
            ->with('client')
            ->latest()
            ->paginate(20);

        return view('products.index', compact('products'));
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
