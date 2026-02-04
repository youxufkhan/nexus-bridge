@extends('layouts.app')

@section('header', 'Enterprise Catalog')

@section('content')
<div
    class="overflow-hidden rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
    <table class="w-full text-left">
        <thead class="bg-slate-50/50 dark:bg-slate-800/30 border-b border-slate-200 dark:border-slate-800">
            <tr>
                <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Product Identity
                </th>
                <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">SKU Node</th>
                <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Owner Repository
                </th>
                <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Inventory Status
                </th>
                <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Base Unit Price
                </th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
            @forelse($products as $product)
            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-colors">
                <td class="px-8 py-5 text-sm font-black text-slate-900 dark:text-white">{{ $product->name }}</td>
                <td class="px-8 py-5 text-sm text-indigo-500 dark:text-indigo-400 font-mono font-bold">{{
                    $product->master_sku }}</td>
                <td class="px-8 py-5 text-sm text-slate-500 font-medium capitalize">{{ $product->client->name }}</td>
                <td class="px-8 py-5 text-sm">
                    <span
                        class="inline-flex px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 border border-emerald-100 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20">Active
                        Stock</span>
                </td>
                <td class="px-8 py-5 text-sm font-black text-slate-900 dark:text-white">
                    USD {{ number_format($product->price ?? 0, 2) }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-8 py-20 text-center text-slate-500 italic">
                    <div class="flex flex-col items-center">
                        <div
                            class="h-20 w-20 bg-slate-50 dark:bg-slate-800/50 rounded-3xl flex items-center justify-center text-4xl mb-6 grayscale opacity-50">
                            🏷️</div>
                        <h4 class="text-lg font-black text-slate-900 dark:text-white tracking-tight">Catalog Offline
                        </h4>
                        <p class="text-sm text-slate-500 mt-1">No products have been synchronized to the hub repository.
                        </p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-8">
    {{ $products->links() }}
</div>
@endsection