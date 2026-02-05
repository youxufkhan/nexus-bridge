@extends('layouts.app')

@section('header', 'Human Resources')

@section('actions')
<a href="{{ route('dashboard.users.create') }}"
    class="inline-flex items-center px-6 py-2.5 text-sm font-black text-white bg-indigo-600 rounded-2xl hover:bg-slate-900 shadow-xl shadow-indigo-500/20 transition-all group">
    <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24"
        stroke="currentColor" stroke-width="2.5">
        <path d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
    </svg>
    Invite Member
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
                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Team Member
                    </th>
                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Authority
                    </th>
                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Digital Node
                        (Email)</th>
                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 text-right">
                        Auth Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @foreach($users as $user)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-colors">
                    <td class="px-8 py-5 flex items-center">
                        <div
                            class="h-10 w-10 rounded-xl bg-gradient-to-tr from-slate-100 to-slate-200 dark:from-slate-800 dark:to-slate-700 text-slate-600 dark:text-slate-300 flex items-center justify-center font-black text-sm mr-4 shadow-sm">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <span class="text-sm font-black text-slate-900 dark:text-white">{{ $user->name }}</span>
                    </td>
                    <td class="px-8 py-5 text-sm">
                        <span
                            class="inline-flex px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest 
                            {{ $user->role === 'agency_admin' ? 'bg-purple-50 text-purple-700 border border-purple-100 dark:bg-purple-500/10 dark:text-purple-400 dark:border-purple-500/20' : 'bg-blue-50 text-blue-700 border border-blue-100 dark:bg-blue-500/10 dark:text-blue-400 dark:border-blue-500/20' }}">
                            {{ str_replace('_', ' ', $user->role) }}
                        </span>
                    </td>
                    <td class="px-8 py-5 text-sm text-slate-500 font-medium lowercase">{{ $user->email }}</td>
                    <td class="px-8 py-5 text-sm text-right">
                        <span
                            class="inline-flex items-center text-emerald-500 font-bold text-xs uppercase tracking-widest">
                            <span
                                class="h-1.5 w-1.5 rounded-full bg-emerald-500 mr-2 shadow-[0_0_8px_rgba(16,185,129,0.5)]"></span>
                            Verified
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Mobile Dense List (Visible on Mobile) -->
    <div class="lg:hidden divide-y divide-slate-100 dark:divide-slate-800">
        @foreach($users as $user)
        <div class="p-6 space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div
                        class="h-10 w-10 rounded-xl bg-gradient-to-tr from-slate-100 to-slate-200 dark:from-slate-800 dark:to-slate-700 text-slate-600 dark:text-slate-300 flex items-center justify-center font-black text-sm mr-4 shadow-sm">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div>
                        <h4 class="text-sm font-black text-slate-900 dark:text-white">{{ $user->name }}</h4>
                        <p class="text-[10px] text-slate-400 font-medium lowercase truncate max-w-[150px]">{{
                            $user->email }}</p>
                    </div>
                </div>
                <span
                    class="inline-flex px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest 
                    {{ $user->role === 'agency_admin' ? 'bg-purple-50 text-purple-700 border border-purple-100 dark:bg-purple-500/10 dark:text-purple-400 dark:border-purple-500/20' : 'bg-blue-50 text-blue-700 border border-blue-100 dark:bg-blue-500/10 dark:text-blue-400 dark:border-blue-500/20' }}">
                    {{ str_replace('_', ' ', $user->role) }}
                </span>
            </div>

            <div class="pt-2 flex items-center text-[10px] font-black uppercase tracking-[0.2em] text-emerald-500">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 mr-2 shadow-[0_0_8px_rgba(16,185,129,0.5)]"></span>
                Digital Auth Verified
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection