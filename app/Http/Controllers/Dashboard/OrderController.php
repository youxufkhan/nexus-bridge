<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $agencyId = auth()->user()->agency_id;
        $orders = \App\Models\Order::where('agency_id', $agencyId)
            ->with(['client', 'integrationConnection'])
            ->latest()
            ->paginate(20);

        return view('orders.index', compact('orders'));
    }
}