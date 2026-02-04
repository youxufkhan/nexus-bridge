@extends('layouts.app')

@section('header', 'Agency Portfolios')

@section('actions')
<a href="{{ route('dashboard.clients.create') }}"
    class="inline-flex items-center px-6 py-2.5 text-sm font-black text-white bg-indigo-600 rounded-2xl hover:bg-slate-900 shadow-xl shadow-indigo-500/20 transition-all group">
    <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24"
        stroke="currentColor" stroke-width="2.5">
        <path d="M12 4v16m8-8H4" />
    </svg>
    Onboard Portfolio
</a>
@endsection

@section('content')
<div
    class="overflow-hidden rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
    <table class="w-full text-left">
        <thead class="bg-slate-50/50 dark:bg-slate-800/30 border-b border-slate-200 dark:border-slate-800">
            <tr>
                <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Identity</th>
                <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Trace Code</th>
                <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Operational Since
                </th>
                <th class="px-8 py-5 text-right"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
            @forelse($clients as $client)
            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-colors group">
                <td class="px-8 py-5 text-sm font-black text-slate-900 dark:text-white">{{ $client->name }}</td>
                <td class="px-8 py-5 text-sm text-indigo-500 dark:text-indigo-400 font-mono font-bold">{{ $client->code
                    }}</td>
                <td class="px-8 py-5 text-sm text-slate-500 font-medium">{{ $client->created_at->diffForHumans() }}</td>
                <td class="px-8 py-5 text-right">
                    <button
                        class="px-4 py-1.5 text-xs font-black text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 rounded-xl transition-all">Manage
                        Hub</button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-8 py-20 text-center">
                    <div class="flex flex-col items-center">
                        <div
                            class="h-20 w-20 bg-slate-50 dark:bg-slate-800/50 rounded-3xl flex items-center justify-center text-4xl mb-6 grayscale opacity-50">
                            📁</div>
                        <h4 class="text-lg font-black text-slate-900 dark:text-white tracking-tight">Empty Repository
                        </h4>
                        <p class="text-sm text-slate-500 mt-1">No portfolios have been onboarded to this agency yet.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection