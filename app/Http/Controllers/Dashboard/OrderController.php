<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

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

    public function show(\App\Models\Order $order): \Illuminate\View\View
    {
        if ($order->agency_id !== auth()->user()->agency_id) {
            abort(403);
        }

        $order->load(['client', 'integrationConnection', 'items']);

        return view('orders.show', compact('order'));
    }
}
