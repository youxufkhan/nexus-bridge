<section>
    <header>
        <h2 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-widest">
            {{ __('Theme Preferences') }}
        </h2>

        <p class="mt-1 text-xs text-slate-500 font-medium">
            {{ __('Select your preferred workspace aesthetic. This will be saved to your browser.') }}
        </p>
    </header>

    <div class="mt-6 grid grid-cols-2 gap-4">
        <!-- Light Theme Card -->
        <button onclick="applyNewTheme('light')"
            class="group relative p-4 rounded-3xl border-2 transition-all bg-slate-50 hover:bg-white hover:border-indigo-500 text-left outline-none"
            id="theme-light-btn">
            <div
                class="h-20 bg-white rounded-2xl border border-slate-200 mb-4 flex items-center justify-center text-3xl shadow-sm transition-all group-hover:scale-105">
                ☀️
            </div>
            <p class="text-xs font-black text-slate-900 uppercase tracking-tighter">Daylight Mode</p>
            <p class="text-[10px] text-slate-400 font-bold">Standard high-contrast UI</p>
        </button>

        <!-- Dark Theme Card -->
        <button onclick="applyNewTheme('dark')"
            class="group relative p-4 rounded-3xl border-2 transition-all bg-slate-900 border-slate-800 hover:border-indigo-500 text-left outline-none"
            id="theme-dark-btn">
            <div
                class="h-20 bg-slate-950 rounded-2xl border border-slate-800 mb-4 flex items-center justify-center text-3xl shadow-sm transition-all group-hover:scale-105">
                🌙
            </div>
            <p class="text-xs font-black text-white uppercase tracking-tighter">Midnight Mode</p>
            <p class="text-[10px] text-slate-500 font-bold">Low-light optimized UI</p>
        </button>
    </div>

    @push('scripts')
    <script>
        function updateThemeButtonsState() {
            const isDark = document.documentElement.classList.contains('dark');
            const lightBtn = document.getElementById('theme-light-btn');
            const darkBtn = document.getElementById('theme-dark-btn');

            if (!lightBtn || !darkBtn) return;

            if (isDark) {
                darkBtn.classList.add('border-indigo-500', 'bg-indigo-500/5', 'ring-4', 'ring-indigo-500/10');
                darkBtn.classList.remove('border-slate-800');
                lightBtn.classList.remove('border-indigo-500', 'ring-4', 'ring-indigo-500/10');
                lightBtn.classList.add('border-transparent');
            } else {
                lightBtn.classList.add('border-indigo-500', 'bg-white', 'ring-4', 'ring-indigo-500/10');
                lightBtn.classList.remove('border-transparent');
                darkBtn.classList.remove('border-indigo-500', 'ring-4', 'ring-indigo-500/10');
                darkBtn.classList.add('border-slate-800', 'bg-slate-900');
            }
        }

        function applyNewTheme(theme) {
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
            }
            updateThemeButtonsState();
        }

        // Run immediately and on content load
        updateThemeButtonsState();
        document.addEventListener('DOMContentLoaded', updateThemeButtonsState);
    </script>
    @endpush
</section>