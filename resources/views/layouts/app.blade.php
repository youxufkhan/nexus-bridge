<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>NexusBridge | Enterprise Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                },
            },
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
            (function () {
                try {
                    const isDark = localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);
                    if (isDark) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                } catch (e) { console.error('Theme sync error:', e); }
            })();
    </script>
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }

        .sidebar-active {
            background: rgba(99, 102, 241, 0.1);
            color: #818cf8;
            border-right: 4px solid #6366f1;
        }

        .dark .sidebar-active {
            background: rgba(129, 140, 248, 0.1);
        }

        .transition-colors {
            transition-duration: 300ms;
        }
    </style>
</head>

<body class="h-full antialiased bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 transition-colors">
    <div x-data="{ 
        sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
        mobileMenuOpen: false,
        toggleSidebar() {
            this.sidebarCollapsed = !this.sidebarCollapsed;
            localStorage.setItem('sidebarCollapsed', this.sidebarCollapsed);
        }
    }" class="flex min-h-screen relative">

        <!-- Mobile Sidebar Overlay -->
        <div x-show="mobileMenuOpen" x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[55] lg:hidden"
            @click="mobileMenuOpen = false"></div>

        <!-- Sidebar -->
        <aside :class="[
                sidebarCollapsed ? 'lg:w-20' : 'lg:w-64',
                mobileMenuOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
            ]"
            class="border-r border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 flex flex-col fixed inset-y-0 left-0 h-full z-[60] transition-all duration-300 ease-in-out w-64 lg:static lg:h-screen">

            <!-- Mobile Close Button -->
            <button @click="mobileMenuOpen = false"
                class="absolute top-5 right--4 lg:hidden p-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-500 transform translate-x-full ml-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <div class="p-6 pb-4 flex items-center justify-between">
                <span x-show="contentReady && (!sidebarCollapsed || mobileMenuOpen)"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    class="text-2xl font-black bg-gradient-to-r from-indigo-500 to-purple-500 bg-clip-text text-transparent tracking-tighter">NexusBridge</span>
                <span x-show="sidebarCollapsed && !mobileMenuOpen"
                    class="text-xl font-black bg-gradient-to-r from-indigo-500 to-purple-500 bg-clip-text text-transparent tracking-tighter mx-auto">NB</span>
            </div>

            <nav class="flex-1 px-4 space-y-1 overflow-y-auto mt-4 overflow-x-hidden" x-data="{ contentReady: false }"
                x-init="setTimeout(() => contentReady = true, 100)">
                @can('access-admin')
                <div x-show="!sidebarCollapsed || mobileMenuOpen"
                    class="px-4 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Platform</div>
                <div x-show="sidebarCollapsed && !mobileMenuOpen" class="h-8"></div>
                <a href="{{ route('admin.agencies.index') }}"
                    class="flex items-center px-4 py-2.5 text-sm font-bold rounded-2xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-all {{ request()->routeIs('admin.agencies.*') ? 'sidebar-active' : 'text-slate-600 dark:text-slate-400' }}"
                    title="SaaS Tenants">
                    <svg class="w-5 h-5 flex-shrink-0 text-red-500"
                        :class="(sidebarCollapsed && !mobileMenuOpen) ? 'mx-auto' : 'mr-3'" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span x-show="!sidebarCollapsed || mobileMenuOpen">SaaS Tenants</span>
                </a>
                <div class="my-4 border-t border-slate-100 dark:border-slate-800"></div>
                @endcan

                <div x-show="!sidebarCollapsed || mobileMenuOpen"
                    class="px-4 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Agency Hub</div>
                <a href="{{ route('dashboard') }}"
                    class="flex items-center px-4 py-2.5 text-sm font-bold rounded-2xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-all {{ request()->routeIs('dashboard') ? 'sidebar-active' : 'text-slate-600 dark:text-slate-400' }}"
                    title="Dashboard">
                    <svg class="w-5 h-5 flex-shrink-0"
                        :class="(sidebarCollapsed && !mobileMenuOpen) ? 'mx-auto' : 'mr-3'" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span x-show="!sidebarCollapsed || mobileMenuOpen">Dashboard</span>
                </a>
                <a href="{{ route('dashboard.clients.index') }}"
                    class="flex items-center px-4 py-2.5 text-sm font-bold rounded-2xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-all {{ request()->routeIs('dashboard.clients.*') ? 'sidebar-active' : 'text-slate-600 dark:text-slate-400' }}"
                    title="Portfolios">
                    <svg class="w-5 h-5 flex-shrink-0"
                        :class="(sidebarCollapsed && !mobileMenuOpen) ? 'mx-auto' : 'mr-3'" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span x-show="!sidebarCollapsed || mobileMenuOpen">Portfolios</span>
                </a>
                <a href="{{ route('dashboard.orders.index') }}"
                    class="flex items-center px-4 py-2.5 text-sm font-bold rounded-2xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-all {{ request()->routeIs('dashboard.orders.*') ? 'sidebar-active' : 'text-slate-600 dark:text-slate-400' }}"
                    title="Live Orders">
                    <svg class="w-5 h-5 flex-shrink-0"
                        :class="(sidebarCollapsed && !mobileMenuOpen) ? 'mx-auto' : 'mr-3'" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span x-show="!sidebarCollapsed || mobileMenuOpen">Live Orders</span>
                </a>
                <a href="{{ route('dashboard.products.index') }}"
                    class="flex items-center px-4 py-2.5 text-sm font-bold rounded-2xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-all {{ request()->routeIs('dashboard.products.*') ? 'sidebar-active' : 'text-slate-600 dark:text-slate-400' }}"
                    title="Catalog">
                    <svg class="w-5 h-5 flex-shrink-0"
                        :class="(sidebarCollapsed && !mobileMenuOpen) ? 'mx-auto' : 'mr-3'" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    <span x-show="!sidebarCollapsed || mobileMenuOpen">Catalog</span>
                </a>
                <a href="{{ route('dashboard.warehouses.index') }}"
                    class="flex items-center px-4 py-2.5 text-sm font-bold rounded-2xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-all {{ request()->routeIs('dashboard.warehouses.*') ? 'sidebar-active' : 'text-slate-600 dark:text-slate-400' }}"
                    title="Warehouses">
                    <svg class="w-5 h-5 flex-shrink-0"
                        :class="(sidebarCollapsed && !mobileMenuOpen) ? 'mx-auto' : 'mr-3'" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span x-show="!sidebarCollapsed || mobileMenuOpen">Warehouses</span>
                </a>
                <a href="{{ route('dashboard.inventories.index') }}"
                    class="flex items-center px-4 py-2.5 text-sm font-bold rounded-2xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-all {{ request()->routeIs('dashboard.inventories.*') && !request()->routeIs('dashboard.inventories.wizard') ? 'sidebar-active' : 'text-slate-600 dark:text-slate-400' }}"
                    title="Inventory">
                    <svg class="w-5 h-5 flex-shrink-0"
                        :class="(sidebarCollapsed && !mobileMenuOpen) ? 'mx-auto' : 'mr-3'" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <span x-show="!sidebarCollapsed || mobileMenuOpen">Inventory</span>
                </a>
                <a href="{{ route('dashboard.inventories.wizard') }}"
                    class="flex items-center px-4 py-2.5 text-sm font-bold rounded-2xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-all {{ request()->routeIs('dashboard.inventories.wizard') ? 'sidebar-active' : 'text-slate-600 dark:text-slate-400' }}"
                    title="Adjust Stock">
                    <svg class="w-5 h-5 flex-shrink-0"
                        :class="(sidebarCollapsed && !mobileMenuOpen) ? 'mx-auto' : 'mr-3'" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                    </svg>
                    <span x-show="!sidebarCollapsed || mobileMenuOpen">Adjust Stock</span>
                </a>
                <a href="{{ route('dashboard.integrations.index') }}"
                    class="flex items-center px-4 py-2.5 text-sm font-bold rounded-2xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-all {{ request()->routeIs('dashboard.integrations.*') ? 'sidebar-active' : 'text-slate-600 dark:text-slate-400' }}"
                    title="Connectivity">
                    <svg class="w-5 h-5 flex-shrink-0"
                        :class="(sidebarCollapsed && !mobileMenuOpen) ? 'mx-auto' : 'mr-3'" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path
                            d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
                    </svg>
                    <span x-show="!sidebarCollapsed || mobileMenuOpen">Connectivity</span>
                </a>
                <a href="{{ route('dashboard.users.index') }}"
                    class="flex items-center px-4 py-2.5 text-sm font-bold rounded-2xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-all {{ request()->routeIs('dashboard.users.*') ? 'sidebar-active' : 'text-slate-600 dark:text-slate-400' }}"
                    title="Team Members">
                    <svg class="w-5 h-5 flex-shrink-0"
                        :class="(sidebarCollapsed && !mobileMenuOpen) ? 'mx-auto' : 'mr-3'" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span x-show="!sidebarCollapsed || mobileMenuOpen">Team Members</span>
                </a>
            </nav>

            <!-- Sidebar Footer -->
            <div
                class="p-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 backdrop-blur-sm">
                <!-- User Profile Section -->
                <div class="relative group">
                    <!-- Trigger -->
                    <div :class="(sidebarCollapsed && !mobileMenuOpen) ? 'justify-center' : ''"
                        class="flex items-center p-3 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm group-hover:border-indigo-400 transition-all cursor-pointer">
                        <div
                            class="h-10 w-10 flex-shrink-0 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-black shadow-lg shadow-indigo-500/30 ring-2 ring-white dark:ring-slate-700">
                            {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                        </div>
                        <div x-show="!sidebarCollapsed || mobileMenuOpen"
                            class="ml-3 flex-1 min-w-0 text-left line-clamp-1">
                            <p
                                class="text-xs font-black text-slate-900 dark:text-white truncate uppercase tracking-tighter">
                                {{ Auth::user()->name ?? 'Account' }}</p>
                            <p class="text-[10px] font-bold text-slate-400 truncate">{{ Auth::user()->email ?? '' }}</p>
                        </div>
                    </div>

                    <!-- Hover Action Menu -->
                    <div :class="(sidebarCollapsed && !mobileMenuOpen) ? 'left-full -ml-3 bottom-0' : 'bottom-full left-0 pb-2'"
                        class="absolute hidden lg:group-hover:block transition-all z-50">
                        <div :class="(sidebarCollapsed && !mobileMenuOpen) ? 'w-56 ml-3' : 'w-full'"
                            class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-2xl p-2 ring-1 ring-black/5 overflow-hidden animate-in fade-in slide-in-from-bottom-2 duration-200">
                            <a href="{{ route('profile.edit') }}"
                                class="flex items-center px-4 py-2.5 text-[12px] font-bold text-slate-600 dark:text-slate-300 rounded-xl hover:bg-indigo-50 dark:hover:bg-slate-800 hover:text-indigo-600 transition-colors">
                                <svg class="w-4 h-4 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Account settings
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center px-4 py-2.5 text-[12px] font-black text-red-500 rounded-xl hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">
                                    <svg class="w-4 h-4 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <main
            class="flex-1 flex flex-col min-w-0 min-h-screen bg-slate-50 dark:bg-slate-950 transition-all duration-300 ease-in-out">
            <header
                class="h-20 bg-white/80 dark:bg-slate-950/80 backdrop-blur-xl border-b border-slate-200 dark:border-slate-800 flex items-center justify-between px-4 lg:px-10 sticky top-0 z-40 transition-all duration-300">
                <div class="flex items-center">
                    <!-- Mobile Menu Toggle -->
                    <button @click="mobileMenuOpen = true"
                        class="mr-4 p-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-500 hover:text-indigo-500 transition-colors focus:outline-none ring-1 ring-slate-200 dark:ring-slate-700 lg:hidden">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <!-- Desktop Toggle -->
                    <button @click="toggleSidebar()"
                        class="mr-6 p-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-500 hover:text-indigo-500 transition-colors focus:outline-none ring-1 ring-slate-200 dark:ring-slate-700 hidden lg:block">
                        <svg class="w-5 h-5 transition-transform duration-300"
                            :class="sidebarCollapsed ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                        </svg>
                    </button>
                    <div class="min-w-0">
                        <h2
                            class="text-[8px] lg:text-[10px] font-black text-indigo-500 uppercase tracking-[0.3em] mb-0.5 lg:mb-1 truncate">
                            Nexus / SaaS</h2>
                        <h1
                            class="text-lg lg:text-2xl font-black tracking-tight text-slate-900 dark:text-white truncate">
                            @yield('header', 'Dashboard Content')</h1>
                    </div>
                </div>
                <div class="flex items-center space-x-2 lg:space-x-4 ml-4">
                    @yield('actions')
                </div>
            </header>

            <div class="p-4 lg:p-10 max-w-7xl mx-auto w-full">
                @if(session('success'))
                <div
                    class="mb-8 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-sm font-black flex items-center animate-in fade-in zoom-in duration-500">
                    <div class="p-1.5 rounded-full bg-emerald-500 text-white mr-3 shadow-lg shadow-emerald-500/20">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                            <path d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    {{ session('success') }}
                </div>
                @endif

                <div class="relative z-0">
                    @yield('content')
                </div>

                @isset($slot)
                <div class="mt-8">
                    {{ $slot }}
                </div>
                @endisset
            </div>
        </main>
    </div>

    @stack('scripts')
</body>

</html>