@extends('layouts.app')

@section('header', 'Infrastructure Bridges')

@section('actions')
<a href="{{ route('dashboard.integrations.create') }}"
    class="inline-flex items-center px-6 py-2.5 text-sm font-black text-white bg-indigo-600 rounded-2xl hover:bg-slate-900 shadow-xl shadow-indigo-500/20 transition-all group">
    <svg class="w-5 h-5 mr-1 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24"
        stroke="currentColor" stroke-width="2.5">
        <path
            d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
    </svg>
    New Configuration
</a>
@endsection

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    @forelse($connections as $connection)
    <div
        class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-8 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all relative overflow-hidden group">
        <div class="absolute top-0 right-0 p-6">
            <span
                class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 border border-emerald-100 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20">
                Operational
            </span>
        </div>
        <div class="flex items-center space-x-5">
            <div
                class="h-16 w-16 rounded-2xl bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-3xl group-hover:scale-110 transition-transform shadow-inner shadow-black/5 dark:shadow-white/5">
                @if($connection->platform_type === 'walmart') 📦 @elseif($connection->platform_type === 'amazon') ☁️
                @else 🔗 @endif
            </div>
            <div>
                <h3 class="text-lg font-black text-slate-900 dark:text-white capitalize tracking-tight">{{
                    $connection->platform_type }} Bridge</h3>
                <p class="text-sm font-bold text-indigo-500 dark:text-indigo-400 uppercase tracking-tighter">{{
                    $connection->client->name }}</p>
            </div>
        </div>

        <div class="mt-8 pt-8 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none">
                Last Handshake:<br>
                <span class="text-slate-500 dark:text-slate-300 mt-1 inline-block">
                    {{ $connection->settings['last_sync_time'] ?
                    \Carbon\Carbon::parse($connection->settings['last_sync_time'])->diffForHumans() : 'Standby' }}
                </span>
            </div>
            <button
                class="px-5 py-2 text-xs font-black text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-500/10 rounded-xl hover:bg-indigo-600 hover:text-white transition-all">Force
                Sync</button>
        </div>
    </div>
    @empty
    <div
        class="col-span-full py-24 text-center border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-3xl">
        <div class="flex flex-col items-center">
            <div
                class="h-20 w-20 bg-slate-50 dark:bg-slate-800/50 rounded-3xl flex items-center justify-center text-4xl mb-6 grayscale opacity-50">
                📡</div>
            <h4 class="text-lg font-black text-slate-900 dark:text-white tracking-tight">No Active Bridges</h4>
            <p class="text-sm text-slate-500 mt-1">Configure your first marketplace integration to start ingesting data.
            </p>
        </div>
    </div>
    @endforelse
</div>
@endsection