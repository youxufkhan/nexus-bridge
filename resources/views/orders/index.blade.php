@extends('layouts.app')

@section('header', 'Live Commerce Ingestion')

@section('content')
<div
    class="overflow-hidden rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
    <table class="w-full text-left">
        <thead class="bg-slate-50/50 dark:bg-slate-800/30 border-b border-slate-200 dark:border-slate-800">
            <tr>
                <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Order Reference
                </th>
                <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Client Source
                </th>
                <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Status</th>
                <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Infrastructure
                </th>
                <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Timestamp</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
            @forelse($orders as $order)
            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-colors">
                <td class="px-8 py-5 text-sm font-black text-indigo-600 dark:text-indigo-400">#{{
                    $order->external_order_id }}</td>
                <td class="px-8 py-5 text-sm font-black text-slate-900 dark:text-white capitalize">{{
                    $order->client->name }}</td>
                <td class="px-8 py-5 text-sm">
                    <span
                        class="inline-flex px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest 
                        {{ $order->status === 'shipped' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20' : 'bg-amber-50 text-amber-600 border border-amber-100 dark:bg-amber-500/10 dark:text-amber-400 dark:border-amber-500/20' }}">
                        {{ $order->status }}
                    </span>
                </td>
                <td class="px-8 py-5 text-sm text-slate-500 font-bold capitalize">{{
                    $order->integrationConnection->platform_type }} Bridge</td>
                <td class="px-8 py-5 text-sm text-slate-400 font-medium">{{ $order->created_at->format('M d, Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-8 py-20 text-center">
                    <div class="flex flex-col items-center">
                        <div
                            class="h-20 w-20 bg-slate-50 dark:bg-slate-800/50 rounded-3xl flex items-center justify-center text-4xl mb-6 grayscale opacity-50">
                            🛒</div>
                        <h4 class="text-lg font-black text-slate-900 dark:text-white tracking-tight">System Idle</h4>
                        <p class="text-sm text-slate-500 mt-1">No orders have entered the ingestion pipeline yet.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-8">
    {{ $orders->links() }}
</div>
@endsection