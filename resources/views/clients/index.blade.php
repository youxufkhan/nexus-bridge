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
<!-- Filter and Sort Bar -->
<x-filter-sort-bar :filters="$filterConfig" :sortOptions="$sortConfig['options']" :currentFilters="$currentFilters"
    :currentSort="$currentSort" :activeCount="$activeCount" />

<div
    class="overflow-hidden rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
    <!-- Desktop Table (Hidden on Mobile) -->
    <div class="hidden lg:block overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-slate-50/50 dark:bg-slate-800/30 border-b border-slate-200 dark:border-slate-800">
                <tr>
                    <x-sortable-th field="name" label="Identity" :currentSort="$currentSort" class="min-w-[200px]" />
                    <x-sortable-th field="code" label="Trace Code" :currentSort="$currentSort" />
                    <x-sortable-th field="created_at" label="Operational Since" :currentSort="$currentSort" />
                    <th class="px-8 py-5 text-right"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($clients as $client)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-colors group">
                    <td class="px-8 py-5 text-sm font-black text-slate-900 dark:text-white">{{ $client->name }}</td>
                    <td class="px-8 py-5 text-sm text-indigo-500 dark:text-indigo-400 font-mono font-bold">{{
                        $client->code }}</td>
                    <td class="px-8 py-5 text-sm text-slate-500 font-medium">{{ $client->created_at->diffForHumans() }}
                    </td>
                    <td class="px-8 py-5 text-right flex items-center justify-end space-x-2">
                        <a href="{{ route('dashboard.clients.edit', $client) }}"
                            class="text-slate-400 hover:text-indigo-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>
                        <form action="{{ route('dashboard.clients.destroy', $client) }}" method="POST"
                            class="inline-block"
                            onsubmit="return confirm('Archive this portfolio and all its connections?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-slate-400 hover:text-red-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-8 py-20 text-center text-slate-400 italic">No portfolios found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Dense List (Visible on Mobile) -->
    <div class="lg:hidden divide-y divide-slate-100 dark:divide-slate-800">
        @forelse($clients as $client)
        <div class="p-6 space-y-4">
            <div class="flex items-start justify-between">
                <div>
                    <h4 class="text-sm font-black text-slate-900 dark:text-white">{{ $client->name }}</h4>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Trace: {{
                        $client->code }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('dashboard.clients.edit', $client) }}"
                        class="text-slate-400 hover:text-indigo-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </a>
                    <form action="{{ route('dashboard.clients.destroy', $client) }}" method="POST" class="inline"
                        onsubmit="return confirm('Archive this portfolio?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-slate-400 hover:text-red-600">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Onboarded</p>
                    <p class="text-xs font-bold text-slate-500">{{ $client->created_at->format('M d, Y') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Status</p>
                    <p class="text-xs font-black text-emerald-500 uppercase tracking-tighter">Operational</p>
                </div>
            </div>
        </div>
        @empty
        <div class="px-8 py-20 text-center">
            <div class="flex flex-col items-center">
                <div
                    class="h-20 w-20 bg-slate-50 dark:bg-slate-800/50 rounded-3xl flex items-center justify-center text-4xl mb-6 grayscale opacity-50">
                    📁</div>
                <h4 class="text-lg font-black text-slate-900 dark:text-white tracking-tight">Empty Repository</h4>
                <p class="text-sm text-slate-500 mt-1">No portfolios have been onboarded yet.</p>
            </div>
        </div>
        @endforelse
    </div>
</div>

<div class="mt-8">
    {{ $clients->links() }}
</div>
@endsection