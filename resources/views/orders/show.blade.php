@extends('layouts.app')

@section('header', 'Order Intelligence')

@section('content')
<div class="space-y-8">
    <!-- Header Card -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-8 shadow-sm">
        <div class="flex items-start justify-between">
            <div class="flex items-center space-x-6">
                <div
                    class="h-20 w-20 bg-indigo-50 dark:bg-indigo-500/10 rounded-2xl flex items-center justify-center text-3xl shadow-inner">
                    🛒
                </div>
                <div>
                    <h2 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Order #{{
                        $order->external_order_id }}</h2>
                    <div class="flex items-center mt-2 space-x-4">
                        <span class="text-sm font-bold text-indigo-500 dark:text-indigo-400 capitalize">{{
                            $order->integrationConnection->platform_type }} Bridge</span>
                        <div class="h-1 w-1 rounded-full bg-slate-300 dark:bg-slate-700"></div>
                        <span class="text-sm font-medium text-slate-500">{{ $order->client->name }} Ingestion</span>
                        <div class="h-1 w-1 rounded-full bg-slate-300 dark:bg-slate-700"></div>
                        <span
                            class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest 
                            {{ $order->status === 'shipped' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20' : 'bg-amber-50 text-amber-600 border border-amber-100 dark:bg-amber-500/10 dark:text-amber-400 dark:border-amber-500/20' }}">
                            {{ $order->status }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="text-right">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Settlement Total</p>
                <p class="text-3xl font-black text-slate-900 dark:text-white">{{ $order->currency ?? 'USD' }} {{
                    number_format($order->total_amount ?? 0, 2) }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Order Items -->
        <div class="lg:col-span-2 space-y-8">
            <div
                class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
                <div
                    class="px-8 py-5 bg-slate-50/50 dark:bg-slate-800/30 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                    <h3 class="text-xs font-black uppercase tracking-widest text-slate-400">Line Items / Manifest</h3>
                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
                <table class="w-full text-left">
                    <thead class="bg-slate-50/30 dark:bg-slate-800/20 border-b border-slate-100 dark:border-slate-800">
                        <tr>
                            <th class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">
                                Product</th>
                            <th class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">SKU
                            </th>
                            <th
                                class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-center">
                                Qty</th>
                            <th
                                class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">
                                Price</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($order->items as $item)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20">
                            <td class="px-8 py-4">
                                <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $item->product_name }}
                                </p>
                            </td>
                            <td class="px-8 py-4">
                                <span class="text-[10px] font-black text-indigo-500 dark:text-indigo-400 font-mono">{{
                                    $item->sku }}</span>
                            </td>
                            <td class="px-8 py-4 text-center">
                                <span class="text-sm font-black text-slate-900 dark:text-white">{{ $item->quantity
                                    }}</span>
                            </td>
                            <td class="px-8 py-4 text-right">
                                <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $order->currency }} {{
                                    number_format($item->unit_price, 2) }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-8 py-10 text-center text-slate-400 italic text-sm">No items found
                                in this order manifest.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Financial Summary -->
            <div
                class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
                <div
                    class="px-8 py-5 bg-slate-50/50 dark:bg-slate-800/30 border-b border-slate-200 dark:border-slate-800">
                    <h3 class="text-xs font-black uppercase tracking-widest text-slate-400">Financial Ledger</h3>
                </div>
                <div class="p-8 space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-slate-500 font-medium">Subtotal</span>
                        <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $order->currency }} {{
                            number_format($order->total_amount - $order->total_tax - $order->total_shipping, 2)
                            }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-slate-500 font-medium">Tax Allocation</span>
                        <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $order->currency }} {{
                            number_format($order->total_tax, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-slate-500 font-medium">Logistics / Shipping</span>
                        <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $order->currency }} {{
                            number_format($order->total_shipping, 2) }}</span>
                    </div>
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex justify-between items-center">
                        <span class="text-base font-black text-slate-900 dark:text-white uppercase tracking-tight">Final
                            Settlement</span>
                        <span class="text-xl font-black text-indigo-600 dark:text-indigo-400">{{ $order->currency }} {{
                            number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer & Delivery -->
        <div class="space-y-8">
            <div
                class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
                <div
                    class="px-8 py-5 bg-slate-50/50 dark:bg-slate-800/30 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                    <h3 class="text-xs font-black uppercase tracking-widest text-slate-400">Customer Node</h3>
                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div class="p-8 space-y-6">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Legal Name /
                            Identity</p>
                        <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $order->customer_data['name'] ??
                            'Guest Identity' }}</p>
                        <p class="text-xs text-slate-500 mt-1 font-mono">{{ $order->customer_data['email'] ?? '' }}</p>
                    </div>
                    @if(isset($order->customer_data['shipping_address']))
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Logistics
                            Destination</p>
                        <div
                            class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 text-sm text-slate-600 dark:text-slate-400 leading-relaxed font-medium">
                            {{ $order->customer_data['shipping_address']['street'] ?? '' }}<br>
                            {{ $order->customer_data['shipping_address']['city'] ?? '' }}, {{
                            $order->customer_data['shipping_address']['state'] ?? '' }} {{
                            $order->customer_data['shipping_address']['zip'] ?? '' }}<br>
                            {{ $order->customer_data['shipping_address']['country'] ?? '' }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection