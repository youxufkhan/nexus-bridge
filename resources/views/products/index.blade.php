@extends('layouts.app')

@section('header', 'Enterprise Catalog')

@section('content')
<div
    class="overflow-hidden rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
    <!-- Desktop Table (Hidden on Mobile) -->
    <div class="hidden lg:block overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-slate-50/50 dark:bg-slate-800/30 border-b border-slate-200 dark:border-slate-800">
                <tr>
                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Product
                        Identity</th>
                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">SKU Node</th>
                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Owner
                        Repository</th>
                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Inventory
                        Status</th>
                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Base Unit
                        Price</th>
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
                    <td class="px-8 py-5 text-sm text-slate-500 font-medium capitalize">{{ $product->client->name }}
                    </td>
                    <td class="px-8 py-5 text-sm">
                        <span
                            class="inline-flex px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 border border-emerald-100 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20">Active
                            Stock</span>
                    </td>
                    <td class="px-8 py-5 text-sm font-black text-slate-900 dark:text-white">
                        USD {{ number_format($product->base_price ?? 0, 2) }}
                    </td>
                    <td class="px-8 py-5 text-sm text-right">
                        <a href="{{ route('dashboard.products.show', $product) }}"
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
                    <td colspan="6" class="px-8 py-20 text-center text-slate-400 italic">No products found.</td>
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
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">{{ $product->category
                        ?? 'General Catalog' }}</p>
                </div>
                <span
                    class="px-2 py-0.5 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-[9px] font-black font-mono">
                    {{ $product->master_sku }}
                </span>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Unit Price</p>
                    <p class="text-xs font-black text-slate-900 dark:text-white">USD {{
                        number_format($product->base_price ?? 0, 2) }}</p>
                </div>
                <div class="text-right">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Owner Repo</p>
                    <p class="text-xs font-black text-indigo-500 uppercase tracking-tighter">{{ $product->client->name
                        }}</p>
                </div>
            </div>

            <div class="pt-2">
                <a href="{{ route('dashboard.products.show', $product) }}"
                    class="flex items-center justify-center w-full py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-[10px] font-black uppercase tracking-[0.2em] hover:bg-indigo-50 dark:hover:bg-indigo-500/10 hover:text-indigo-600 transition-all border border-slate-100 dark:border-slate-700">
                    Access Intelligence
                </a>
            </div>
        </div>
        @empty
        <div class="px-8 py-20 text-center">
            <div class="flex flex-col items-center">
                <div
                    class="h-20 w-20 bg-slate-50 dark:bg-slate-800/50 rounded-3xl flex items-center justify-center text-4xl mb-6 grayscale opacity-50">
                    🏷️</div>
                <h4 class="text-lg font-black text-slate-900 dark:text-white tracking-tight">Catalog Offline</h4>
                <p class="text-sm text-slate-500 mt-1">No products have been synchronized to the hub repository.</p>
            </div>
        </div>
        @endforelse
    </div>
</div>

<div class="mt-8">
    {{ $products->links() }}
</div>
@endsection