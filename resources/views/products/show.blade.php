@extends('layouts.app')

@section('header', 'Product Intelligence')

@section('content')
<div class="space-y-8">
    <!-- Header Card -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-8 shadow-sm">
        <div class="flex items-start justify-between">
            <div class="flex items-center space-x-6">
                <div
                    class="h-20 w-20 bg-indigo-50 dark:bg-indigo-500/10 rounded-2xl flex items-center justify-center text-3xl shadow-inner">
                    📦
                </div>
                <div>
                    <h2 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">{{ $product->name }}
                    </h2>
                    <div class="flex items-center mt-2 space-x-4">
                        <span class="text-sm font-bold text-indigo-500 dark:text-indigo-400 font-mono">{{
                            $product->master_sku }}</span>
                        <div class="h-1 w-1 rounded-full bg-slate-300 dark:bg-slate-700"></div>
                        <span class="text-sm font-medium text-slate-500">{{ $product->client->name }} Repository</span>
                        <div class="h-1 w-1 rounded-full bg-slate-300 dark:bg-slate-700"></div>
                        <span
                            class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 border border-emerald-100 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20">
                            {{ $product->status ?? 'Active' }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="text-right">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">MSRP / Baseline</p>
                <p class="text-3xl font-black text-slate-900 dark:text-white">{{ $product->base_currency ?? 'USD' }} {{
                    number_format($product->base_price ?? 0, 2) }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Technical Specifications -->
        <div class="lg:col-span-2 space-y-8">
            <div
                class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
                <div
                    class="px-8 py-5 bg-slate-50/50 dark:bg-slate-800/30 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                    <h3 class="text-xs font-black uppercase tracking-widest text-slate-400">Technical Specifications
                    </h3>
                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div class="p-8 grid grid-cols-2 gap-8">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Category /
                            Classification</p>
                        <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $product->category ?? 'General'
                            }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">UPC / GTIN Node
                        </p>
                        <p class="text-sm font-bold text-slate-900 dark:text-white font-mono">{{ $product->upc ??
                            $product->gtin ?? 'N/A' }}</p>
                    </div>
                    @if($product->dimensions)
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Infrastructure
                            Dimensions</p>
                        <p class="text-sm font-bold text-slate-900 dark:text-white uppercase">
                            {{ $product->dimensions['length'] ?? 0 }}x{{ $product->dimensions['width'] ?? 0 }}x{{
                            $product->dimensions['height'] ?? 0 }} {{ $product->dimensions['unit'] ?? 'in' }}
                        </p>
                    </div>
                    @endif
                    @if($product->weight)
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Payload Weight
                        </p>
                        <p class="text-sm font-bold text-slate-900 dark:text-white uppercase">{{
                            $product->weight['value'] ?? 0 }} {{ $product->weight['unit'] ?? 'lb' }}</p>
                    </div>
                    @endif
                </div>
                @if($product->description)
                <div class="px-8 pb-8">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Description /
                        Documentation</p>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">{{ $product->description }}
                    </p>
                </div>
                @endif
            </div>

            <!-- Inventory Matrix -->
            <div
                class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
                <div
                    class="px-8 py-5 bg-slate-50/50 dark:bg-slate-800/30 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                    <h3 class="text-xs font-black uppercase tracking-widest text-slate-400">Inventory Distribution Hub
                    </h3>
                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <table class="w-full text-left">
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($product->inventories as $inventory)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20">
                            <td class="px-8 py-4">
                                <p class="text-sm font-black text-slate-900 dark:text-white">{{
                                    $inventory->warehouse->name }}</p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight">{{
                                    $inventory->warehouse->location }}</p>
                            </td>
                            <td class="px-8 py-4 text-right">
                                <span class="text-lg font-black text-indigo-500 dark:text-indigo-400">{{
                                    number_format($inventory->quantity) }}</span>
                                <span class="text-[10px] font-black text-slate-400 uppercase ml-1">Units</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="px-8 py-10 text-center text-slate-400 italic text-sm">No inventory
                                records found for this product.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Integration Maps -->
        <div class="space-y-8">
            <div
                class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
                <div
                    class="px-8 py-5 bg-slate-50/50 dark:bg-slate-800/30 border-b border-slate-200 dark:border-slate-800">
                    <h3 class="text-xs font-black uppercase tracking-widest text-slate-400">Platform Cross-Maps</h3>
                </div>
                <div class="p-8 space-y-6">
                    @forelse($product->platformProductMaps as $map)
                    <div
                        class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <div class="flex items-center space-x-3">
                            <div
                                class="h-8 w-8 rounded-full bg-white dark:bg-slate-700 flex items-center justify-center text-xs shadow-sm capitalize">
                                {{ substr($map->platform_type, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-xs font-black text-slate-900 dark:text-white uppercase">{{
                                    $map->platform_type }}</p>
                                <p class="text-[10px] text-slate-400 font-mono font-bold">{{ $map->external_product_id
                                    }}</p>
                            </div>
                        </div>
                        <span class="text-[10px] font-black text-indigo-500 dark:text-indigo-400 font-mono">{{
                            $map->external_sku }}</span>
                    </div>
                    @empty
                    <p class="text-center text-slate-400 italic text-sm py-4">No active platform maps.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection