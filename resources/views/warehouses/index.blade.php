@extends('layouts.app')

@section('header', 'Warehouse Network')

@section('actions')
<a href="{{ route('dashboard.warehouses.create') }}"
    class="px-6 py-2.5 text-sm font-black text-white bg-indigo-600 rounded-2xl hover:bg-slate-900 transition-all shadow-xl shadow-indigo-500/20 flex items-center">
    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
        <path d="M12 4v16m8-8H4" />
    </svg>
    Establish Warehouse
</a>
@endsection

@section('content')
<div
    class="overflow-hidden rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
    <!-- Desktop Table (Hidden on Mobile) -->
    <div class="hidden lg:block overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-slate-50/50 dark:bg-slate-800/30 border-b border-slate-200 dark:border-slate-800">
                <tr>
                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Warehouse
                        Name
                    </th>
                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Location
                        Code
                    </th>
                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">City /
                        Region
                    </th>
                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 text-right">
                        Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($warehouses as $warehouse)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-colors">
                    <td class="px-8 py-5 text-sm font-black text-slate-900 dark:text-white">{{ $warehouse->name }}</td>
                    <td class="px-8 py-5 text-sm text-indigo-500 dark:text-indigo-400 font-mono font-bold">{{
                        $warehouse->location_code }}</td>
                    <td class="px-8 py-5 text-sm text-slate-500 font-medium">
                        {{ $warehouse->address['city'] ?? 'N/A' }}, {{ $warehouse->address['country'] ?? 'N/A' }}
                    </td>
                    <td class="px-8 py-5 text-sm text-right space-x-3">
                        <a href="{{ route('dashboard.warehouses.edit', $warehouse) }}"
                            class="text-slate-400 hover:text-indigo-600 transition-colors">
                            <svg class="w-5 h-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>
                        <form action="{{ route('dashboard.warehouses.destroy', $warehouse) }}" method="POST"
                            class="inline-block" onsubmit="return confirm('Archive this warehouse node?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-slate-400 hover:text-red-600 transition-colors">
                                <svg class="w-5 h-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-8 py-20 text-center text-slate-400 italic">No warehouses established.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Dense List (Visible on Mobile) -->
    <div class="lg:hidden divide-y divide-slate-100 dark:divide-slate-800">
        @forelse($warehouses as $warehouse)
        <div class="p-6 space-y-4">
            <div class="flex items-start justify-between">
                <div>
                    <h4 class="text-sm font-black text-slate-900 dark:text-white">{{ $warehouse->name }}</h4>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">
                        {{ $warehouse->address['city'] ?? 'N/A' }} / {{ $warehouse->address['country'] ?? 'N/A' }}</p>
                </div>
                <span
                    class="px-2 py-0.5 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-[9px] font-black font-mono">
                    {{ $warehouse->location_code }}
                </span>
            </div>

            <div class="flex items-center justify-end space-x-4 pt-2">
                <a href="{{ route('dashboard.warehouses.edit', $warehouse) }}"
                    class="text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-indigo-600">Modify</a>
                <form action="{{ route('dashboard.warehouses.destroy', $warehouse) }}" method="POST" class="inline"
                    onsubmit="return confirm('Archive node?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-red-600">Archive</button>
                </form>
            </div>
        </div>
        @empty
        <div class="px-8 py-20 text-center">
            <div class="flex flex-col items-center">
                <div
                    class="h-20 w-20 bg-slate-50 dark:bg-slate-800/50 rounded-3xl flex items-center justify-center text-4xl mb-6 grayscale opacity-50">
                    🏢</div>
                <h4 class="text-lg font-black text-slate-900 dark:text-white tracking-tight">Infrastructure Empty</h4>
                <p class="text-sm text-slate-500 mt-1">Establish your first warehouse node to start managing inventory.
                </p>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection