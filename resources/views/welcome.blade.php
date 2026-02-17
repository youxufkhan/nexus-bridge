<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NexusBridge | Next-Gen eCommerce Integration</title>
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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }

        .dark .bg-indigo-50 {
            background-color: rgba(79, 70, 229, 0.1);
        }

        .dark .text-indigo-700 {
            color: #818cf8;
        }
    </style>
</head>

<body
    class="h-full antialiased bg-white dark:bg-slate-950 text-slate-900 dark:text-slate-100 border-t-4 border-indigo-600 transition-colors duration-500">
    <nav class="max-w-7xl mx-auto px-6 py-8 flex items-center justify-between">
        <span
            class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent underline decoration-indigo-200 dark:decoration-indigo-800/30">NexusBridge</span>
        <div class="space-x-4 flex items-center">
            <button id="theme-toggle" type="button"
                class="p-2.5 rounded-xl bg-slate-100 dark:bg-slate-900 text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all focus:outline-none ring-1 ring-slate-200 dark:ring-slate-800 mr-2">
                <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                </svg>
                <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                        fill-rule="evenodd" clip-rule="evenodd"></path>
                </svg>
            </button>
            @auth
            <a href="{{ url('/dashboard') }}"
                class="px-6 py-2.5 rounded-full bg-indigo-600 text-white font-bold hover:bg-slate-900 transition-all shadow-lg shadow-indigo-500/20">Dashboard</a>
            @else
            <a href="{{ route('login') }}"
                class="text-slate-600 dark:text-slate-400 font-semibold hover:text-indigo-600 dark:hover:text-indigo-400 px-4 transition-colors">Login</a>
            <a href="{{ route('register') }}"
                class="px-6 py-2.5 rounded-full bg-indigo-600 text-white font-bold hover:bg-slate-900 dark:hover:bg-white dark:hover:text-slate-900 transition-all shadow-lg shadow-indigo-500/20">Get
                Started</a>
            @endauth
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-6 py-20 lg:py-32">
        <div class="lg:grid lg:grid-cols-2 lg:gap-12 items-center">
            <div>
                <span
                    class="inline-block px-4 py-1.5 rounded-full bg-indigo-50 text-indigo-700 text-xs font-bold uppercase tracking-widest mb-6 dark:bg-indigo-500/10 dark:text-indigo-400">Enterprise
                    Grade Integration</span>
                <h1 class="text-5xl lg:text-7xl font-bold leading-tight mb-8 dark:text-white transition-colors">
                    Scale Your <span class="text-indigo-600 dark:text-indigo-400">E-commerce</span> Agency Faster.
                </h1>
                <p class="text-xl text-slate-500 dark:text-slate-400 mb-10 max-w-lg leading-relaxed transition-colors">
                    The total operating system for modern e-commerce agencies. Manage dozens of clients, sync thousands
                    of orders, and automate manual tasks with NexusBridge.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 mb-20">
                    <a href="{{ route('register') }}"
                        class="px-10 py-5 rounded-2xl bg-slate-900 dark:bg-white dark:text-slate-900 text-white font-bold text-center hover:scale-105 transition-all shadow-2xl">Start
                        Trial Free</a>
                    <a href="#"
                        class="px-10 py-5 rounded-2xl border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 font-bold text-center hover:bg-slate-50 dark:hover:bg-slate-900 transition-all">Watch
                        Demo</a>
                </div>
            </div>

            <div class="relative">
                <div
                    class="absolute -inset-4 rounded-3xl bg-gradient-to-tr from-indigo-500/20 to-purple-500/20 blur-3xl">
                </div>
                <div
                    class="relative bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-2xl overflow-hidden aspect-video border-8 border-white dark:border-slate-900 group transition-colors">
                    <img src="{{ asset('images/dashboard-mockup.png') }}" alt="Nexus Bridge Dashboard Interface"
                        class="w-full h-full object-cover object-top transform group-hover:scale-105 transition-transform duration-700">
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-white/20 dark:from-slate-950/20 via-transparent to-transparent pointer-events-none">
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-32">
            <div
                class="p-8 rounded-3xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800 transition-colors">
                <div
                    class="h-12 w-12 rounded-xl bg-white dark:bg-slate-800 shadow-sm flex items-center justify-center text-2xl mb-6 transition-colors">
                    🏢
                </div>
                <h3 class="text-xl font-bold mb-4 text-slate-900 dark:text-white transition-colors">Multi-Agency Arch
                </h3>
                <p class="text-slate-500 dark:text-slate-400 transition-colors">True tenant isolation. Manage multiple
                    client portfolios with strict RBAC
                    access controls.</p>
            </div>
            <div
                class="p-8 rounded-3xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800 transition-colors">
                <div
                    class="h-12 w-12 rounded-xl bg-white dark:bg-slate-800 shadow-sm flex items-center justify-center text-2xl mb-6 transition-colors">
                    🔌
                </div>
                <h3 class="text-xl font-bold mb-4 text-slate-900 dark:text-white transition-colors">Unified Adapters
                </h3>
                <p class="text-slate-500 dark:text-slate-400 transition-colors">Connect Walmart, Amazon, and TikTok Shop
                    using a single standardized API
                    adapter layer.</p>
            </div>
            <div
                class="p-8 rounded-3xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800 transition-colors">
                <div
                    class="h-12 w-12 rounded-xl bg-white dark:bg-slate-800 shadow-sm flex items-center justify-center text-2xl mb-6 transition-colors">
                    ⚡
                </div>
                <h3 class="text-xl font-bold mb-4 text-slate-900 dark:text-white transition-colors">Real-time Sync</h3>
                <p class="text-slate-500 dark:text-slate-400 transition-colors">Automated ingestion background loops
                    ensure your client data is never stale.
                </p>
            </div>
        </div>
    </main>

    <footer
        class="max-w-7xl mx-auto px-6 py-20 border-t border-slate-100 dark:border-slate-900 text-center text-slate-400 dark:text-slate-600 text-sm transition-colors">
        &copy; 2026 NexusBridge Technologies. Enterprise E-commerce Solutions.
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
            const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
            const themeToggleBtn = document.getElementById('theme-toggle');

            // Set initial icon visibility based on current theme
            if (document.documentElement.classList.contains('dark')) {
                themeToggleLightIcon.classList.remove('hidden');
            } else {
                themeToggleDarkIcon.classList.remove('hidden');
            }

            themeToggleBtn.addEventListener('click', function() {
                // toggle icons
                themeToggleDarkIcon.classList.toggle('hidden');
                themeToggleLightIcon.classList.toggle('hidden');

                // Update theme and persist
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.theme = 'light';
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.theme = 'dark';
                }
            });
        });
    </script>
</body>

</html>