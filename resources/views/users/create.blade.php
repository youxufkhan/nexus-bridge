@extends('layouts.app')

@section('header', 'Invite Team Member')

@section('content')
<div class="max-w-3xl">
    <div
        class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-xl shadow-slate-200/50 dark:shadow-none overflow-hidden">
        <div class="p-8 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50">
            <h3 class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-tight">Access Control</h3>
            <p class="text-sm text-slate-500">Provide credentials and select the system role for the new team member.
            </p>
        </div>

        <form action="{{ route('dashboard.users.store') }}" method="POST" class="p-8 space-y-8">
            @csrf

            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label for="name"
                            class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Full
                            Name</label>
                        <input type="text" name="name" id="name" required placeholder="John Doe"
                            class="w-full rounded-2xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-4 text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all dark:text-white">
                    </div>

                    <div class="space-y-2">
                        <label for="email"
                            class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Email
                            Address</label>
                        <input type="email" name="email" id="email" required placeholder="john@agency.com"
                            class="w-full rounded-2xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-4 text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all dark:text-white">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label for="role"
                            class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Authority
                            Level</label>
                        <select name="role" id="role"
                            class="w-full rounded-2xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-4 text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all dark:text-white appearance-none">
                            <option value="agent">Standard Agent</option>
                            <option value="agency_admin">Agency Administrator</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label for="password"
                            class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Access Key
                            (Password)</label>
                        <input type="password" name="password" id="password" required
                            class="w-full rounded-2xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-4 text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all dark:text-white">
                    </div>
                </div>
            </div>

            <div class="pt-8 flex items-center justify-end space-x-4 border-t border-slate-100 dark:border-slate-800">
                <a href="{{ route('dashboard.users.index') }}"
                    class="px-6 py-3 text-sm font-bold text-slate-500 hover:text-slate-900 dark:hover:text-white transition-colors">Cancel</a>
                <button type="submit"
                    class="px-10 py-3 text-sm font-black text-white bg-indigo-600 rounded-2xl hover:bg-slate-900 transition-all shadow-xl shadow-indigo-500/20">
                    Register Member
                </button>
            </div>
        </form>
    </div>
</div>
@endsection