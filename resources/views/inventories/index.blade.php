@extends('layouts.app')

@section('header', 'Inventory Strategy')

@section('content')
<div
    class="overflow-hidden rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
    <!-- Desktop Table (Hidden on Mobile) -->
    <div class="hidden lg:block overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-slate-50/50 dark:bg-slate-800/30 border-b border-slate-200 dark:border-slate-800">
                <tr>
                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Master Item
                    </th>
                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">SKU Trace
                    </th>
                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Warehouse
                        Nodes
                    </th>
                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Total
                        Available
                    </th>
                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 text-right">
                        Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($products as $product)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-colors">
                    <td class="px-8 py-5 text-sm font-black text-slate-900 dark:text-white">{{ $product->name }}</td>
                    <td class="px-8 py-5 text-sm text-indigo-500 dark:text-indigo-400 font-mono font-bold">{{
                        $product->master_sku }}</td>
                    <td class="px-8 py-5 text-sm">
                        <div class="flex flex-wrap gap-2">
                            @foreach($product->inventories as $inventory)
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-[10px] font-bold text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                                {{ $inventory->warehouse->name }}: {{ $inventory->quantity_on_hand }}
                            </span>
                            @endforeach
                            @if($product->inventories->isEmpty())
                            <span class="text-[10px] text-slate-400 italic">No nodes assigned</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-8 py-5 text-sm font-black text-slate-900 dark:text-white">
                        {{ $product->inventories->sum('quantity_on_hand') }}
                    </td>
                    <td class="px-8 py-5 text-sm text-right">
                        <a href="{{ route('dashboard.inventories.adjust', $product) }}"
                            class="px-4 py-2 text-[10px] font-black uppercase tracking-widest bg-slate-100 hover:bg-indigo-600 hover:text-white text-slate-600 rounded-xl transition-all border border-slate-200 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300">
                            Adjust Stock
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-8 py-20 text-center text-slate-400 italic">Vault Empty.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Dense List (Visible on Mobile) -->
    <div class="lg:hidden divide-y divide-slate-100 dark:divide-slate-800">
        @forelse($products as $product)
        <div class="p-6 space-y-4">
            <div class="flex items-start justify-between">
                <div>
                    <h4 class="text-sm font-black text-slate-900 dark:text-white">{{ $product->name }}</h4>
                    <p class="text-[10px] text-slate-400 font-black font-mono mt-1 opacity-70">{{ $product->master_sku
                        }}</p>
                </div>
                <div class="text-right">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Global Stock</p>
                    <p class="text-lg font-black text-indigo-500 dark:text-indigo-400">{{
                        $product->inventories->sum('quantity_on_hand') }}</p>
                </div>
            </div>

            <div class="space-y-2">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Network Nodes</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($product->inventories as $inventory)
                    <span
                        class="inline-flex items-center px-2 py-0.5 rounded-lg bg-slate-50 dark:bg-slate-800 text-[9px] font-bold text-slate-600 dark:text-slate-400 border border-slate-100 dark:border-slate-700">
                        {{ $inventory->warehouse->name }}: {{ $inventory->quantity_on_hand }}
                    </span>
                    @endforeach
                </div>
            </div>

            <div class="pt-2">
                <a href="{{ route('dashboard.inventories.adjust', $product) }}"
                    class="flex items-center justify-center w-full py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-[10px] font-black uppercase tracking-[0.2em] hover:bg-indigo-50 dark:hover:bg-indigo-500/10 hover:text-indigo-600 transition-all border border-slate-100 dark:border-slate-700">
                    Adjust Distribution
                </a>
            </div>
        </div>
        @empty
        <div class="px-8 py-20 text-center">
            <div class="flex flex-col items-center">
                <div
                    class="h-20 w-20 bg-slate-50 dark:bg-slate-800/50 rounded-3xl flex items-center justify-center text-4xl mb-6 grayscale opacity-50">
                    📦</div>
                <h4 class="text-lg font-black text-slate-900 dark:text-white tracking-tight">Vault Empty</h4>
                <p class="text-sm text-slate-500 mt-1">Populate your catalog before establishing inventory levels.</p>
            </div>
        </div>
        @endforelse
    </div>
</div>
<div class="mt-8">
    {{ $products->links() }}
</div>
@endsection