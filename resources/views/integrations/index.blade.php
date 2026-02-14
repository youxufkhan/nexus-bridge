@extends('layouts.app')

@section('header', 'Connected Bridges')

@section('content')
<div class="flex flex-col space-y-8">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Active Infrastructure</h2>
            <p class="text-sm text-slate-500 mt-1">Managed connections between external market nodes and local vault.
            </p>
        </div>
        <a href="{{ route('dashboard.integrations.create') }}"
            class="px-8 py-3.5 text-sm font-black text-white bg-slate-900 dark:bg-indigo-600 rounded-2xl hover:scale-105 active:scale-95 transition-all shadow-xl shadow-slate-200 dark:shadow-indigo-500/20">
            Establish New Bridge
        </a>
    </div>

    <x-filter-sort-bar :filters="$filterConfig" :sortOptions="$sortConfig['options']" :currentFilters="$currentFilters"
        :currentSort="$currentSort" :activeCount="$activeCount" />

    <div
        class="overflow-hidden rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
        <!-- Desktop Table (Hidden on Mobile) -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50/50 dark:bg-slate-800/30 border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <x-sortable-th field="client_name" label="Node Cluster" :currentSort="$currentSort"
                            class="min-w-[200px]" />
                        <x-sortable-th field="platform_type" label="Network Type" :currentSort="$currentSort" />
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Protocol
                            Status</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Telemetry
                        </th>
                        <th
                            class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 text-right">
                            Sync Commands</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($connections as $connection)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-colors">
                        <td class="px-8 py-6">
                            <div class="flex items-center">
                                <div
                                    class="h-10 w-10 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-lg mr-4 border border-slate-200 dark:border-slate-700">
                                    {{ $connection->platform_type === 'walmart' ? '🔵' : '🌐' }}
                                </div>
                                <div>
                                    <div class="text-sm font-black text-slate-900 dark:text-white tracking-tight">{{
                                        $connection->client->name }}</div>
                                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">
                                        Cluster ID: {{ $connection->client->code }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-[10px] font-black uppercase tracking-widest text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                                {{ $connection->platform_type }}
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            <div
                                class="flex items-center text-green-500 font-bold text-[10px] uppercase tracking-widest">
                                <span class="h-2 w-2 rounded-full bg-green-500 mr-2 animate-pulse"></span>
                                Synchronized
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="space-y-1">
                                <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Orders:
                                    <span class="text-slate-600 dark:text-slate-300">{{
                                        $connection->settings['last_order_sync'] ?? 'Never' }}</span>
                                </div>
                                <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Catalog:
                                    <span class="text-slate-600 dark:text-slate-300">{{
                                        $connection->settings['last_product_sync'] ?? 'Never' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-right" x-data="{ showModal: false }">
                            <div class="flex items-center justify-end space-x-3">
                                <button @click="showModal = true"
                                    class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 rounded-xl transition-all group"
                                    title="Node Command Center">
                                    <svg class="w-6 h-6 group-hover:rotate-12 transition-transform" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </button>

                                <template x-teleport="body">
                                    <div x-show="showModal"
                                        class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                        x-transition:leave="transition ease-in duration-200"
                                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                                        <div @click.away="showModal = false"
                                            class="bg-white dark:bg-slate-900 w-full max-w-md rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden shadow-indigo-500/10"
                                            x-transition:enter="transition ease-out duration-300"
                                            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                                            x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                                            <div class="p-8">
                                                <div class="flex justify-between items-start mb-6">
                                                    <div>
                                                        <h3
                                                            class="text-xl font-black text-slate-900 dark:text-white tracking-tight">
                                                            Bridge Command Center</h3>
                                                        <p
                                                            class="text-xs text-slate-500 mt-1 uppercase font-bold tracking-widest">
                                                            Protocol: {{ $connection->platform_type }} // Cluster: {{
                                                            $connection->client->name }}</p>
                                                    </div>
                                                    <button @click="showModal = false"
                                                        class="text-slate-400 hover:text-slate-600 transition-colors">
                                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </div>

                                                <div class="space-y-3">
                                                    <form
                                                        action="{{ route('dashboard.integrations.sync-orders', $connection) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button type="submit"
                                                            class="w-full flex items-center justify-between p-5 bg-slate-50 dark:bg-slate-800/50 hover:bg-indigo-600 hover:text-white rounded-3xl transition-all group border border-slate-100 dark:border-slate-700/50">
                                                            <div class="text-left">
                                                                <div
                                                                    class="text-[10px] font-black uppercase tracking-widest opacity-60 group-hover:opacity-100">
                                                                    Synchronize</div>
                                                                <div class="text-sm font-black">Order Manifests</div>
                                                            </div>
                                                            <svg class="w-5 h-5 opacity-0 group-hover:opacity-100 translate-x-4 group-hover:translate-x-0 transition-all"
                                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                                            </svg>
                                                        </button>
                                                    </form>

                                                    <form
                                                        action="{{ route('dashboard.integrations.sync-products', $connection) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button type="submit"
                                                            class="w-full flex items-center justify-between p-5 bg-slate-50 dark:bg-slate-800/50 hover:bg-emerald-600 hover:text-white rounded-3xl transition-all group border border-slate-100 dark:border-slate-700/50">
                                                            <div class="text-left">
                                                                <div
                                                                    class="text-[10px] font-black uppercase tracking-widest opacity-60 group-hover:opacity-100">
                                                                    Synchronize</div>
                                                                <div class="text-sm font-black">Product Catalog</div>
                                                            </div>
                                                            <svg class="w-5 h-5 opacity-0 group-hover:opacity-100 translate-x-4 group-hover:translate-x-0 transition-all"
                                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                            <div
                                                class="bg-slate-50 dark:bg-slate-800/30 px-8 py-4 flex items-center text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">
                                                <span
                                                    class="h-1.5 w-1.5 rounded-full bg-green-500 mr-2 animate-pulse"></span>
                                                Operational Status: Uplink Active
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <div class="h-6 w-px bg-slate-100 dark:bg-slate-800"></div>

                                <a href="{{ route('dashboard.integrations.edit', $connection) }}"
                                    class="text-slate-400 hover:text-indigo-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>

                                <form action="{{ route('dashboard.integrations.destroy', $connection) }}" method="POST"
                                    class="inline" onsubmit="return confirm('Disconnect this bridge protocol?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-red-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center text-slate-400 italic">No connections establish
                            yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Dense List (Visible on Mobile) -->
        <div class="lg:hidden divide-y divide-slate-100 dark:divide-slate-800">
            @forelse($connections as $connection)
            <div class="p-6 space-y-4" x-data="{ showMobileModal: false }">
                <div class="flex items-start justify-between">
                    <div class="flex items-center">
                        <div
                            class="h-10 w-10 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-lg mr-4 border border-slate-200 dark:border-slate-700">
                            {{ $connection->platform_type === 'walmart' ? '🔵' : '🌐' }}
                        </div>
                        <div>
                            <h4 class="text-sm font-black text-slate-900 dark:text-white">{{ $connection->client->name
                                }}</h4>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">{{
                                $connection->platform_type }} Protocol</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button @click="showMobileModal = true"
                            class="p-2 text-slate-400 hover:text-indigo-600 bg-slate-50 dark:bg-slate-800 rounded-xl">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </button>
                        <a href="{{ route('dashboard.integrations.edit', $connection) }}"
                            class="p-2 text-slate-400 hover:text-indigo-600 bg-slate-50 dark:bg-slate-800 rounded-xl">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Status</p>
                        <p class="text-xs font-black text-emerald-500 uppercase tracking-tighter flex items-center">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 mr-2 animate-pulse"></span>
                            Active
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Cluster</p>
                        <p class="text-xs font-black text-indigo-500 uppercase tracking-tighter">{{
                            $connection->client->code }}</p>
                    </div>
                </div>

                <!-- Mobile Command Modal -->
                <template x-teleport="body">
                    <div x-show="showMobileModal"
                        class="fixed inset-0 z-[100] flex items-end justify-center bg-slate-900/60 backdrop-blur-sm"
                        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                        <div @click.away="showMobileModal = false"
                            class="bg-white dark:bg-slate-900 w-full rounded-t-[2.5rem] border-t border-slate-200 dark:border-slate-800 shadow-2xl p-8"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0">
                            <div class="w-12 h-1.5 bg-slate-200 dark:bg-slate-700 rounded-full mx-auto mb-8"></div>
                            <h3 class="text-xl font-black text-slate-900 dark:text-white tracking-tight mb-2">Bridge
                                Commands</h3>
                            <p class="text-xs text-slate-500 mb-8 uppercase font-bold tracking-widest">{{
                                $connection->platform_type }} Protocol // {{ $connection->client->name }}</p>

                            <div class="space-y-4">
                                <form action="{{ route('dashboard.integrations.sync-orders', $connection) }}"
                                    method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center justify-between p-6 bg-slate-50 dark:bg-slate-800 rounded-3xl font-black text-sm">
                                        Sync Order Manifests
                                        <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                        </svg>
                                    </button>
                                </form>
                                <form action="{{ route('dashboard.integrations.sync-products', $connection) }}"
                                    method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center justify-between p-6 bg-slate-50 dark:bg-slate-800 rounded-3xl font-black text-sm">
                                        Sync Product Catalog
                                        <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                            <button @click="showMobileModal = false"
                                class="w-full mt-6 py-4 text-slate-400 text-[10px] font-black uppercase tracking-[0.2em]">Close
                                Command Center</button>
                        </div>
                    </div>
                </template>
            </div>
            @empty
            <div class="px-8 py-20 text-center">
                <div class="flex flex-col items-center">
                    <div
                        class="h-20 w-20 bg-slate-50 dark:bg-slate-800/50 rounded-3xl flex items-center justify-center text-4xl mb-6 grayscale opacity-50">
                        🔗</div>
                    <h4 class="text-lg font-black text-slate-900 dark:text-white tracking-tight">Isolated Environment
                    </h4>
                    <p class="text-sm text-slate-500 mt-1">No active bridge protocols detected.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>

<div class="mt-8">
    {{ $connections->links() }}
</div>
</div>
@endsection