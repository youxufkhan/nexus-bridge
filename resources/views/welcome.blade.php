<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NexusBridge | Next-Gen eCommerce Integration</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>

<body class="h-full antialiased bg-white text-slate-900 border-t-4 border-indigo-600">
    <nav class="max-w-7xl mx-auto px-6 py-8 flex items-center justify-between">
        <span
            class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent underline decoration-indigo-200">NexusBridge</span>
        <div class="space-x-4 flex items-center">
            @auth
            <a href="{{ url('/dashboard') }}"
                class="px-6 py-2.5 rounded-full bg-indigo-600 text-white font-bold hover:bg-slate-900 transition-all shadow-lg shadow-indigo-500/20">Dashboard</a>
            @else
            <a href="{{ route('login') }}" class="text-slate-600 font-semibold hover:text-indigo-600 px-4">Login</a>
            <a href="{{ route('register') }}"
                class="px-6 py-2.5 rounded-full bg-indigo-600 text-white font-bold hover:bg-slate-900 transition-all shadow-lg shadow-indigo-500/20">Get
                Started</a>
            @endauth
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-6 py-20 lg:py-32">
        <div class="lg:grid lg:grid-cols-2 lg:gap-12 items-center">
            <div>
                <span
                    class="inline-block px-4 py-1.5 rounded-full bg-indigo-50 text-indigo-700 text-xs font-bold uppercase tracking-widest mb-6">Enterprise
                    Grade Integration</span>
                <h1 class="text-5xl lg:text-7xl font-bold leading-tight mb-8">
                    Scale Your <span class="text-indigo-600">E-commerce</span> Agency Faster.
                </h1>
                <p class="text-xl text-slate-500 mb-10 max-w-lg leading-relaxed">
                    The total operating system for modern e-commerce agencies. Manage dozens of clients, sync thousands
                    of orders, and automate manual tasks with NexusBridge.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 mb-20">
                    <a href="{{ route('register') }}"
                        class="px-10 py-5 rounded-2xl bg-slate-900 text-white font-bold text-center hover:scale-105 transition-all shadow-2xl">Start
                        Trial Free</a>
                    <a href="#"
                        class="px-10 py-5 rounded-2xl border border-slate-200 text-slate-600 font-bold text-center hover:bg-slate-50 transition-all">Watch
                        Demo</a>
                </div>
            </div>

            <div class="relative">
                <div
                    class="absolute -inset-4 rounded-3xl bg-gradient-to-tr from-indigo-500/20 to-purple-500/20 blur-3xl">
                </div>
                <div
                    class="relative bg-white border border-slate-200 rounded-3xl shadow-2xl overflow-hidden aspect-video flex items-center justify-center bg-slate-50 border-8 border-white">
                    <div class="text-indigo-600 text-6xl font-bold opacity-10 select-none">DASHBOARD_MOCKUP</div>
                    <div class="absolute inset-0 bg-gradient-to-b from-transparent to-white/20"></div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-32">
            <div class="p-8 rounded-3xl bg-slate-50 border border-slate-100">
                <div class="h-12 w-12 rounded-xl bg-white shadow-sm flex items-center justify-center text-2xl mb-6">🏢
                </div>
                <h3 class="text-xl font-bold mb-4 text-slate-900">Multi-Agency Arch</h3>
                <p class="text-slate-500">True tenant isolation. Manage multiple client portfolios with strict RBAC
                    access controls.</p>
            </div>
            <div class="p-8 rounded-3xl bg-slate-50 border border-slate-100">
                <div class="h-12 w-12 rounded-xl bg-white shadow-sm flex items-center justify-center text-2xl mb-6">🔌
                </div>
                <h3 class="text-xl font-bold mb-4 text-slate-900">Unified Adapters</h3>
                <p class="text-slate-500">Connect Walmart, Amazon, and TikTok Shop using a single standardized API
                    adapter layer.</p>
            </div>
            <div class="p-8 rounded-3xl bg-slate-50 border border-slate-100">
                <div class="h-12 w-12 rounded-xl bg-white shadow-sm flex items-center justify-center text-2xl mb-6">⚡
                </div>
                <h3 class="text-xl font-bold mb-4 text-slate-900">Real-time Sync</h3>
                <p class="text-slate-500">Automated ingestion background loops ensure your client data is never stale.
                </p>
            </div>
        </div>
    </main>

    <footer class="max-w-7xl mx-auto px-6 py-20 border-t border-slate-100 text-center text-slate-400 text-sm">
        &copy; 2026 NexusBridge Technologies. Enterprise E-commerce Solutions.
    </footer>
</body>

</html>