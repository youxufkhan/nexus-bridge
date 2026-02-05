@extends('layouts.app')

@section('header', 'Live Commerce Ingestion')

@section('content')
<div
    class="overflow-hidden rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
    <!-- Desktop Table (Hidden on Mobile) -->
    <div class="hidden lg:block overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-slate-50/50 dark:bg-slate-800/30 border-b border-slate-200 dark:border-slate-800">
                <tr>
                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Date</th>
                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Order ID</th>
                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Customer</th>
                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Client</th>
                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Channel</th>
                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 text-right">
                        Total</th>
                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Status</th>
                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 text-right">
                        Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($orders as $order)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-colors">
                    <td class="px-8 py-5 text-sm text-slate-400 font-medium whitespace-nowrap">
                        {{ $order->created_at->format('M d, Y') }}
                        <span class="block text-[10px] opacity-70">{{ $order->created_at->format('H:i') }}</span>
                    </td>
                    <td class="px-8 py-5 text-sm font-black text-indigo-600 dark:text-indigo-400">
                        #{{ $order->external_order_id }}
                    </td>
                    <td class="px-8 py-5">
                        <div class="text-sm font-black text-slate-900 dark:text-white">{{ $order->customer_data['name']
                            ?? 'Unknown' }}</div>
                        <div class="text-[10px] text-slate-400 font-medium truncate max-w-[150px]">{{
                            $order->customer_data['email'] ?? 'No Email' }}</div>
                    </td>
                    <td class="px-8 py-5 text-sm font-black text-slate-900 dark:text-white capitalize">
                        {{ $order->client->name }}
                    </td>
                    <td class="px-8 py-5">
                        <span class="text-sm text-slate-500 font-bold capitalize">{{
                            $order->integrationConnection->platform_type }}</span>
                        <span
                            class="block text-[10px] text-slate-400 font-black uppercase tracking-widest mt-0.5">Bridge</span>
                    </td>
                    <td class="px-8 py-5 text-sm font-black text-slate-900 dark:text-white text-right">
                        ${{ number_format($order->total_amount, 2) }}
                        <span class="text-[10px] text-slate-400 ml-1">{{ $order->currency }}</span>
                    </td>
                    <td class="px-8 py-5 text-sm">
                        <span
                            class="inline-flex px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest 
                            {{ in_array(strtolower($order->status), ['shipped', 'completed', 'delivered']) ? 'bg-emerald-50 text-emerald-600 border border-emerald-100 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20' : 'bg-amber-50 text-amber-600 border border-amber-100 dark:bg-amber-500/10 dark:text-amber-400 dark:border-amber-500/20' }}">
                            {{ $order->status }}
                        </span>
                    </td>
                    <td class="px-8 py-5 text-sm text-right">
                        <a href="{{ route('dashboard.orders.show', $order) }}"
                            class="p-2 inline-flex items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-400 hover:text-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 transition-all shadow-sm">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-8 py-20 text-center text-slate-400 italic">No orders found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Dense List (Visible on Mobile) -->
    <div class="lg:hidden divide-y divide-slate-100 dark:divide-slate-800">
        @forelse($orders as $order)
        <div class="p-6 space-y-4">
            <div class="flex items-start justify-between">
                <div>
                    <h4 class="text-sm font-black text-indigo-600 dark:text-indigo-400">#{{ $order->external_order_id }}
                    </h4>
                    <p class="text-[10px] text-slate-400 font-medium lowercase">{{ $order->created_at->format('M d,
                        H:i') }}</p>
                </div>
                <span
                    class="inline-flex px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest 
                    {{ in_array(strtolower($order->status), ['shipped', 'completed', 'delivered']) ? 'bg-emerald-50 text-emerald-600 border border-emerald-100 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20' : 'bg-amber-50 text-amber-600 border border-amber-100 dark:bg-amber-500/10 dark:text-amber-400 dark:border-amber-500/20' }}">
                    {{ $order->status }}
                </span>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Customer</p>
                    <p class="text-xs font-bold text-slate-900 dark:text-white truncate">{{
                        $order->customer_data['name'] ?? 'Unknown' }}</p>
                </div>
                <div class="text-right">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Settlement</p>
                    <p class="text-xs font-black text-slate-900 dark:text-white">${{ number_format($order->total_amount,
                        2) }} {{ $order->currency }}</p>
                </div>
                <div>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Channel</p>
                    <p class="text-xs font-bold text-slate-500 capitalize">{{
                        $order->integrationConnection->platform_type }} Bridge</p>
                </div>
                <div class="text-right">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Portfolio</p>
                    <p class="text-xs font-black text-indigo-500 uppercase tracking-tighter">{{ $order->client->name }}
                    </p>
                </div>
            </div>

            <div class="pt-2">
                <a href="{{ route('dashboard.orders.show', $order) }}"
                    class="flex items-center justify-center w-full py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-[10px] font-black uppercase tracking-[0.2em] hover:bg-indigo-50 dark:hover:bg-indigo-500/10 hover:text-indigo-600 transition-all border border-slate-100 dark:border-slate-700">
                    Access Ingress
                </a>
            </div>
        </div>
        @empty
        <div class="px-8 py-20 text-center">
            <div class="flex flex-col items-center">
                <div
                    class="h-20 w-20 bg-slate-50 dark:bg-slate-800/50 rounded-3xl flex items-center justify-center text-4xl mb-6 grayscale opacity-50">
                    🛒</div>
                <h4 class="text-lg font-black text-slate-900 dark:text-white tracking-tight">System Idle</h4>
                <p class="text-sm text-slate-500 mt-1">No orders have entered the ingestion pipeline yet.</p>
            </div>
        </div>
        @endforelse
    </div>
</div>

<div class="mt-8">
    {{ $orders->links() }}
</div>
@endsection