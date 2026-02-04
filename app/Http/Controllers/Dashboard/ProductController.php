<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
}