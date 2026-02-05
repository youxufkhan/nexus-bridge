@extends('layouts.app')

@section('header', 'Establish Warehouse')

@section('content')
<div class="max-w-3xl">
    <div
        class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-xl shadow-slate-200/50 dark:shadow-none overflow-hidden">
        <div class="p-8 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50">
            <h3 class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-tight">Node Configuration
            </h3>
            <p class="text-sm text-slate-500">Define the physical location and identity of this warehouse.</p>
        </div>

        <form action="{{ route('dashboard.warehouses.store') }}" method="POST" class="p-8 space-y-8">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label for="name"
                        class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Warehouse
                        Name</label>
                    <input type="text" name="name" id="name" required placeholder="e.g. Central Hub"
                        value="{{ old('name') }}"
                        class="w-full rounded-2xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-4 text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all dark:text-white">
                </div>

                <div class="space-y-2">
                    <label for="location_code"
                        class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Location
                        Code</label>
                    <input type="text" name="location_code" id="location_code" required placeholder="e.g. WH-001"
                        value="{{ old('location_code') }}"
                        class="w-full rounded-2xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-4 text-sm font-mono font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all dark:text-white uppercase">
                </div>
            </div>

            <div class="space-y-4">
                <h4 class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Physical Address</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <input type="text" name="address[street]" placeholder="Street Address"
                            value="{{ old('address.street') }}"
                            class="w-full rounded-2xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-4 text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all dark:text-white">
                    </div>
                    <input type="text" name="address[city]" placeholder="City" value="{{ old('address.city') }}"
                        class="w-full rounded-2xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-4 text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all dark:text-white">
                    <input type="text" name="address[state]" placeholder="State / Province"
                        value="{{ old('address.state') }}"
                        class="w-full rounded-2xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-4 text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all dark:text-white">
                    <input type="text" name="address[country]" placeholder="Country"
                        value="{{ old('address.country') }}"
                        class="w-full rounded-2xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-4 text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all dark:text-white">
                    <input type="text" name="address[postal_code]" placeholder="Postal Code"
                        value="{{ old('address.postal_code') }}"
                        class="w-full rounded-2xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-4 text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all dark:text-white">
                </div>
            </div>

            <div class="pt-8 flex items-center justify-end space-x-4 border-t border-slate-100 dark:border-slate-800">
                <a href="{{ route('dashboard.warehouses.index') }}"
                    class="px-6 py-3 text-sm font-bold text-slate-500 hover:text-slate-900 dark:hover:text-white transition-colors">Discard</a>
                <button type="submit"
                    class="px-10 py-3 text-sm font-black text-white bg-indigo-600 rounded-2xl hover:bg-slate-900 transition-all shadow-xl shadow-indigo-500/20">
                    Initialize Node
                </button>
            </div>
        </form>
    </div>
</div>
@endsection