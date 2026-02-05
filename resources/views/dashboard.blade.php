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
            All Uplinks Active
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
            <h4 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">$---</h4>
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
            <h4 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">---</h4>
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
            <h4 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">---</h4>
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
            <h4 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">---</h4>
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
</div>
@endsection