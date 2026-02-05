@extends('layouts.app')

@section('header', 'Adjust Inventory')

@section('content')
<div class="max-w-4xl">
    <div
        class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-xl shadow-slate-200/50 dark:shadow-none overflow-hidden">
        <div
            class="p-8 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-tight">{{ $product->name
                    }}</h3>
                <p class="text-sm text-slate-500 font-mono">{{ $product->master_sku }}</p>
            </div>
            <div class="text-right">
                <span
                    class="text-[10px] font-black uppercase tracking-widest text-indigo-500 bg-indigo-50 dark:bg-indigo-500/10 px-3 py-1 rounded-full border border-indigo-100 dark:border-indigo-500/20">Stock
                    Adjustment</span>
            </div>
        </div>

        <form action="{{ route('dashboard.inventories.update', $product) }}" method="POST" class="p-8 space-y-8">
            @csrf

            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($warehouses as $index => $warehouse)
                    @php
                    $inventory = $product->inventories->where('warehouse_id', $warehouse->id)->first();
                    @endphp
                    <div
                        class="p-6 rounded-3xl border border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50">
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-4">{{
                            $warehouse->name }}</label>
                        <input type="hidden" name="adjustments[{{ $index }}][warehouse_id]"
                            value="{{ $warehouse->id }}">

                        <div class="relative">
                            <input type="number" name="adjustments[{{ $index }}][quantity_on_hand]" value="{{ old("
                                adjustments.{$index}.quantity_on_hand", $inventory->quantity_on_hand ?? 0) }}"
                            class="w-full rounded-2xl border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900
                            p-4 pt-8 text-xl font-black focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500
                            outline-none transition-all dark:text-white">
                            <span
                                class="absolute top-3 left-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Available
                                Qty</span>
                        </div>

                        <div class="mt-4 flex items-center justify-between text-[10px] font-bold text-slate-500">
                            <span>Current: {{ $inventory->quantity_on_hand ?? 0 }}</span>
                            <span class="text-indigo-500">{{ $warehouse->location_code }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full py-12 text-center text-slate-500 italic">
                        No warehouses nodes available in your network.
                    </div>
                    @endforelse
                </div>
            </div>

            <div class="pt-8 flex items-center justify-end space-x-4 border-t border-slate-100 dark:border-slate-800">
                <a href="{{ route('dashboard.inventories.index') }}"
                    class="px-6 py-3 text-sm font-bold text-slate-500 hover:text-slate-900 dark:hover:text-white transition-colors">Cancel</a>
                <button type="submit" {{ $warehouses->isEmpty() ? 'disabled' : '' }}
                    class="px-10 py-3 text-sm font-black text-white bg-indigo-600 rounded-2xl hover:bg-slate-900
                    transition-all shadow-xl shadow-indigo-500/20 disabled:opacity-50 disabled:cursor-not-allowed">
                    Update Stock Matrix
                </button>
            </div>
        </form>
    </div>
</div>
@endsection