@extends('layouts.app')

@section('header', 'System Orchestration')

@section('content')
<div class="space-y-12">
    <!-- Action Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h3 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">Active SaaS Tenants</h3>
            <p class="text-sm text-slate-500">Manage and monitor all enterprise agencies on the NexusBridge platform.
            </p>
        </div>
    </div>

    <!-- Create Card -->
    <div
        class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-8 shadow-xl shadow-slate-200/50 dark:shadow-none">
        <form action="{{ route('admin.agencies.store') }}" method="POST" class="flex flex-col md:flex-row gap-4">
            @csrf
            <div class="flex-1">
                <input type="text" name="name" required placeholder="Agency Business Name (e.g. Skyline Digital)"
                    class="w-full rounded-2xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-4 text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all dark:text-white">
            </div>
            <button type="submit"
                class="px-10 py-4 text-sm font-black text-white bg-indigo-600 rounded-2xl hover:bg-slate-900 transition-all shadow-xl shadow-indigo-500/20 whitespace-nowrap">
                Provision New Agency
            </button>
        </form>
    </div>

    <!-- Listing -->
    <div
        class="overflow-hidden rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
        <div class="overflow-x-auto min-w-0">
            <table class="w-full text-left min-w-[700px]">
                <thead class="bg-slate-50/50 dark:bg-slate-800/30 border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Node ID
                        </th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Business
                            Identity</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                            Onboarding
                            Date</th>
                        <th
                            class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 text-right">
                            Operational Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($agencies as $agency)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-colors">
                        <td class="px-8 py-5 text-sm font-mono text-slate-400">#{{ $agency->id }}</td>
                        <td class="px-8 py-5 text-sm font-black text-slate-900 dark:text-white">{{ $agency->name }}</td>
                        <td class="px-8 py-5 text-sm text-slate-500 font-medium">{{ $agency->created_at->format('M d,
                            Y') }}
                        </td>
                        <td class="px-8 py-5 text-right">
                            <span
                                class="inline-flex items-center px-4 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest bg-emerald-50 text-emerald-600 border border-emerald-100 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20">
                                Active
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection