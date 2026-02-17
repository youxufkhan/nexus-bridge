@extends('layouts.app')

@section('header', 'Nexus Terminal')

@section('content')
<div class="space-y-10">
    <!-- Pulse Narrative -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h3 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">System Pulse</h3>
            <p class="text-sm text-slate-500 mt-1">Real-time telemetry across all bridge protocols.</p>
        </div>
        <div
            class="flex items-center space-x-2 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 px-4 py-2 rounded-2xl border border-emerald-500/20 text-[10px] font-black uppercase tracking-widest">
            <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse mr-2"></span>
            {{ $activeUplinks }} Uplinks Active
        </div>
    </div>

    <!-- Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Revenue Card -->
        <div
            class="bg-white dark:bg-slate-900 rounded-[2rem] p-8 border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-xl hover:shadow-indigo-500/5 transition-all group overflow-hidden relative">
            <div
                class="absolute -right-4 -top-4 text-slate-50 dark:text-slate-800/20 text-8xl font-black group-hover:scale-110 transition-transform">
                $</div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Gross Throughput</p>
            <h4 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">${{
                number_format($grossThroughput, 2) }}</h4>
            <div class="mt-4 flex items-center text-emerald-500 text-xs font-bold">
                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                        d="M5 10l7-7m0 0l7 7m-7-7v18" />
                </svg>
                Real-time Sync
            </div>
        </div>

        <!-- Orders Card -->
        <div
            class="bg-white dark:bg-slate-900 rounded-[2rem] p-8 border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-xl hover:shadow-purple-500/5 transition-all group overflow-hidden relative">
            <div
                class="absolute -right-4 -top-4 text-slate-50 dark:text-slate-800/20 text-8xl font-black group-hover:scale-110 transition-transform">
                #</div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Total Ingested</p>
            <h4 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">{{ number_format($totalOrders)
                }}</h4>
            <div class="mt-4 flex items-center text-purple-500 text-xs font-bold">
                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                Bridge Protocols
            </div>
        </div>

        <!-- Products Card -->
        <div
            class="bg-white dark:bg-slate-900 rounded-[2rem] p-8 border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-xl hover:shadow-amber-500/5 transition-all group overflow-hidden relative">
            <div
                class="absolute -right-4 -top-4 text-slate-50 dark:text-slate-800/20 text-8xl font-black group-hover:scale-110 transition-transform">
                📦</div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Master Catalog</p>
            <h4 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">{{
                number_format($totalProducts) }}</h4>
            <div class="mt-4 flex items-center text-amber-500 text-xs font-bold">
                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                        d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                </svg>
                Sync Optimized
            </div>
        </div>

        <!-- Warehouse Card -->
        <div
            class="bg-white dark:bg-slate-900 rounded-[2rem] p-8 border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-xl hover:shadow-blue-500/5 transition-all group overflow-hidden relative">
            <div
                class="absolute -right-4 -top-4 text-slate-50 dark:text-slate-800/20 text-8xl font-black group-hover:scale-110 transition-transform">
                🏢</div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Active Nodes</p>
            <h4 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">{{ number_format($activeNodes)
                }}</h4>
            <div class="mt-4 flex items-center text-blue-500 text-xs font-bold">
                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Global Network
            </div>
        </div>
    </div>

    <!-- Main Body Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Recent Orders -->
        <div class="lg:col-span-2 space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">Recent Transmissions
                    </h3>
                    <p class="text-xs text-slate-500 mt-1">Latest inbound order protocols.</p>
                </div>
                <a href="{{ route('dashboard.orders.index') }}"
                    class="text-[10px] font-black uppercase tracking-widest text-indigo-500 hover:text-indigo-600 transition-colors">
                    View All Activity
                </a>
            </div>

            <div
                class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm text-slate-900 dark:text-white">
                <!-- Desktop Table View -->
                <div class="hidden lg:block overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="border-b border-slate-100 dark:border-slate-800/50 text-slate-900 dark:text-white">
                                <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    Order ID</th>
                                <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    Client</th>
                                <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    Amount</th>
                                <th
                                    class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">
                                    Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                            @forelse($recentOrders as $order)
                            <tr class="group hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-colors cursor-pointer"
                                onclick="window.location='{{ route('dashboard.orders.show', $order) }}'">
                                <td class="px-8 py-6">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-sm font-bold text-slate-900 dark:text-white tracking-tight">#{{
                                            $order->external_order_id ?? $order->id }}</span>
                                        <span class="text-[10px] text-slate-400 font-medium">{{
                                            $order->created_at->diffForHumans() }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="text-sm font-semibold text-slate-600 dark:text-slate-400">{{
                                        $order->client->name ?? 'N/A' }}</span>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="text-sm font-black text-slate-900 dark:text-white">${{
                                        number_format($order->total_amount, 2) }}</span>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <span
                                        @class([ 'inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter'
                                        , 'bg-emerald-500/10 text-emerald-600'=> in_array($order->status, ['completed',
                                        'paid']),
                                        'bg-amber-500/10 text-amber-600' => $order->status === 'pending',
                                        'bg-slate-500/10 text-slate-600' => !in_array($order->status, ['completed',
                                        'paid', 'pending']),
                                        ])>
                                        {{ $order->status }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-8 py-12 text-center text-slate-900 dark:text-white">
                                    <div class="flex flex-col items-center">
                                        <span class="text-2xl mb-2">📡</span>
                                        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">No active
                                            transmissions detected</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile List View -->
                <div class="lg:hidden divide-y divide-slate-100 dark:divide-slate-800/50">
                    @forelse($recentOrders as $order)
                    <div class="p-6 space-y-4 active:bg-slate-50/50 dark:active:bg-slate-800/20 transition-colors cursor-pointer"
                        onclick="window.location='{{ route('dashboard.orders.show', $order) }}'">
                        <div class="flex items-start justify-between">
                            <div class="flex flex-col">
                                <span class="text-sm font-black text-slate-900 dark:text-white tracking-tight">#{{
                                    $order->external_order_id ?? $order->id }}</span>
                                <span class="text-[10px] text-slate-400 font-medium uppercase tracking-widest">{{
                                    $order->created_at->diffForHumans() }}</span>
                            </div>
                            <span
                                @class([ 'inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter'
                                , 'bg-emerald-500/10 text-emerald-600'=> in_array($order->status, ['completed',
                                'paid']),
                                'bg-amber-500/10 text-amber-600' => $order->status === 'pending',
                                'bg-slate-500/10 text-slate-600' => !in_array($order->status, ['completed', 'paid',
                                'pending']),
                                ])>
                                {{ $order->status }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">{{
                                $order->client->name ?? 'N/A' }}</span>
                            <span class="text-sm font-black text-slate-900 dark:text-white">${{
                                number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="px-8 py-12 text-center text-slate-900 dark:text-white">
                        <div class="flex flex-col items-center">
                            <span class="text-2xl mb-2">📡</span>
                            <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">No pulse detected
                            </p>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div class="space-y-8">
            <div>
                <h3 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">Quick Access</h3>
                <p class="text-xs text-slate-500 mt-1">Accelerated system commands.</p>
            </div>

            <div class="grid grid-cols-1 gap-4">
                <a href="{{ route('dashboard.products.create') }}"
                    class="flex items-center p-4 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 hover:border-indigo-500/50 hover:shadow-lg hover:shadow-indigo-500/5 transition-all group">
                    <div
                        class="w-12 h-12 rounded-2xl bg-indigo-500/10 text-indigo-500 flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <div>
                        <span class="block text-sm font-bold text-slate-900 dark:text-white">New Product</span>
                        <span class="block text-[10px] text-slate-500 font-medium uppercase tracking-widest">Catalog
                            Entry</span>
                    </div>
                </a>

                <a href="{{ route('dashboard.inventories.wizard') }}"
                    class="flex items-center p-4 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 hover:border-purple-500/50 hover:shadow-lg hover:shadow-purple-500/5 transition-all group">
                    <div
                        class="w-12 h-12 rounded-2xl bg-purple-500/10 text-purple-500 flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <span class="block text-sm font-bold text-slate-900 dark:text-white">Sync Inventory</span>
                        <span class="block text-[10px] text-slate-500 font-medium uppercase tracking-widest">Stock
                            Alignment</span>
                    </div>
                </a>

                <a href="{{ route('dashboard.integrations.index') }}"
                    class="flex items-center p-4 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 hover:border-emerald-500/50 hover:shadow-lg hover:shadow-emerald-500/5 transition-all group">
                    <div
                        class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div>
                        <span class="block text-sm font-bold text-slate-900 dark:text-white">Active Hooks</span>
                        <span class="block text-[10px] text-slate-500 font-medium uppercase tracking-widest">System
                            Bridging</span>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection