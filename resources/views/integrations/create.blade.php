@extends('layouts.app')

@section('header', 'Configure External Link')

@section('content')
<div class="max-w-4xl">
    <div
        class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-xl shadow-slate-200/50 dark:shadow-none overflow-hidden">
        <div
            class="p-8 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-tight">API
                    Infrastructure</h3>
                <p class="text-sm text-slate-500">Bridge your commerce platforms with the Nexus ingestion engine.</p>
            </div>
            <div class="p-3 bg-indigo-600 rounded-2xl shadow-lg shadow-indigo-500/30">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2.5">
                    <path
                        d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
                </svg>
            </div>
        </div>

        <form action="{{ route('dashboard.integrations.store') }}" method="POST" class="p-8 space-y-10">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label for="client_id"
                            class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Select
                            Portfolio</label>
                        <select id="client_id" name="client_id"
                            class="w-full rounded-2xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-4 text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all dark:text-white appearance-none">
                            @foreach($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->name }} ({{ $client->code }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label for="platform_type"
                            class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Target
                            Marketplace</label>
                        <select id="platform_type" name="platform_type"
                            class="w-full rounded-2xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-4 text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all dark:text-white appearance-none">
                            <option value="walmart">Walmart Marketplace</option>
                            <option value="amazon" disabled>Amazon Vendor Central (Upcoming)</option>
                            <option value="tiktok" disabled>TikTok Shop (Upcoming)</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="space-y-2">
                        <label for="client_id_key"
                            class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Client
                            ID</label>
                        <input type="text" name="client_id_key" id="client_id_key" required
                            placeholder="Walmart OAuth Client ID"
                            class="w-full rounded-2xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-4 text-sm font-mono focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all dark:text-white">
                    </div>

                    <div class="space-y-2">
                        <label for="client_secret"
                            class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Client
                            Secret</label>
                        <input type="password" name="client_secret" id="client_secret" required
                            placeholder="Walmart OAuth Client Secret"
                            class="w-full rounded-2xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-4 text-sm font-mono focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all dark:text-white">
                    </div>
                </div>
            </div>

            <div class="pt-8 flex items-center justify-end space-x-4 border-t border-slate-100 dark:border-slate-800">
                <a href="{{ route('dashboard.integrations.index') }}"
                    class="px-6 py-3 text-sm font-bold text-slate-500 hover:text-slate-900 dark:hover:text-white transition-colors">Cancel</a>
                <button type="submit"
                    class="px-10 py-4 text-sm font-black text-white bg-indigo-600 rounded-2xl hover:bg-slate-900 transition-all shadow-xl shadow-indigo-500/20">
                    Verify & Create Bridge
                </button>
            </div>
        </form>
    </div>
</div>
@endsection