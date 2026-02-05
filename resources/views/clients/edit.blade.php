@extends('layouts.app')

@section('header', 'Refine Portfolio: ' . $client->name)

@section('content')
<div class="max-w-2xl">
    <div
        class="bg-white dark:bg-slate-900 overflow-hidden shadow-sm rounded-3xl border border-slate-200 dark:border-slate-800">
        <div class="p-8">
            <form action="{{ route('dashboard.clients.update', $client) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2">Portfolio
                        Name</label>
                    <input type="text" name="name" value="{{ old('name', $client->name) }}"
                        class="w-full bg-slate-50 dark:bg-slate-800/50 border-slate-200 dark:border-slate-800 rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                    @error('name') <p class="mt-1 text-xs text-red-500 font-bold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2">Trace
                        Code (Unique)</label>
                    <input type="text" name="code" value="{{ old('code', $client->code) }}"
                        class="w-full bg-slate-50 dark:bg-slate-800/50 border-slate-200 dark:border-slate-800 rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                    @error('code') <p class="mt-1 text-xs text-red-500 font-bold">{{ $message }}</p> @enderror
                </div>

                <div class="pt-4 flex items-center justify-between">
                    <a href="{{ route('dashboard.clients.index') }}"
                        class="text-sm font-black text-slate-400 hover:text-slate-600 transition-colors">Discard
                        Changes</a>
                    <button type="submit"
                        class="px-10 py-4 bg-slate-900 dark:bg-indigo-600 text-white text-sm font-black rounded-2xl hover:scale-105 active:scale-95 transition-all shadow-xl shadow-indigo-500/20">
                        Stabilize Identity
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection