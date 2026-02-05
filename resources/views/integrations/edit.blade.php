@extends('layouts.app')

@section('header', 'Refine connectivity: ' . ucfirst($integration->platform_type))

@section('content')
<div class="max-w-2xl">
    <div
        class="bg-white dark:bg-slate-900 overflow-hidden shadow-sm rounded-3xl border border-slate-200 dark:border-slate-800">
        <div class="p-8">
            <form action="{{ route('dashboard.integrations.update', $integration) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2">API
                        Client ID</label>
                    <input type="text" name="client_id_key" value="{{ $integration->credentials['client_id'] ?? '' }}"
                        class="w-full bg-slate-50 dark:bg-slate-800/50 border-slate-200 dark:border-slate-800 rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                    @error('client_id_key') <p class="mt-1 text-xs text-red-500 font-bold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2">API
                        Client Secret</label>
                    <input type="password" name="client_secret"
                        value="{{ $integration->credentials['client_secret'] ?? '' }}"
                        class="w-full bg-slate-50 dark:bg-slate-800/50 border-slate-200 dark:border-slate-800 rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                    @error('client_secret') <p class="mt-1 text-xs text-red-500 font-bold">{{ $message }}</p> @enderror
                </div>

                <div class="pt-4 flex items-center justify-between">
                    <a href="{{ route('dashboard.integrations.index') }}"
                        class="text-sm font-black text-slate-400 hover:text-slate-600 transition-colors">Abort
                        Changes</a>
                    <button type="submit"
                        class="px-10 py-4 bg-slate-900 dark:bg-indigo-600 text-white text-sm font-black rounded-2xl hover:scale-105 active:scale-95 transition-all shadow-xl shadow-indigo-500/20">
                        Update Bridge
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection