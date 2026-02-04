@extends('layouts.app')

@section('header', 'Onboard New Portfolio')

@section('content')
<div class="max-w-3xl">
    <div
        class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-xl shadow-slate-200/50 dark:shadow-none overflow-hidden">
        <div class="p-8 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50">
            <h3 class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-tight">Portfolio Identity
            </h3>
            <p class="text-sm text-slate-500">Define the unique identifier and name for your new agency client.</p>
        </div>

        <form action="{{ route('dashboard.clients.store') }}" method="POST" class="p-8 space-y-8">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label for="name"
                        class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Legal Entity
                        Name</label>
                    <input type="text" name="name" id="name" required placeholder="e.g. Acme Corporation"
                        class="w-full rounded-2xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-4 text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all dark:text-white">
                </div>

                <div class="space-y-2">
                    <label for="code"
                        class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">System Trace
                        Code</label>
                    <input type="text" name="code" id="code" required placeholder="e.g. ACME-01"
                        class="w-full rounded-2xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-4 text-sm font-mono font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all dark:text-white uppercase">
                </div>
            </div>

            <div class="pt-8 flex items-center justify-end space-x-4 border-t border-slate-100 dark:border-slate-800">
                <a href="{{ route('dashboard.clients.index') }}"
                    class="px-6 py-3 text-sm font-bold text-slate-500 hover:text-slate-900 dark:hover:text-white transition-colors">Discard</a>
                <button type="submit"
                    class="px-10 py-3 text-sm font-black text-white bg-indigo-600 rounded-2xl hover:bg-slate-900 transition-all shadow-xl shadow-indigo-500/20">
                    Initialize Portfolio
                </button>
            </div>
        </form>
    </div>
</div>
@endsection