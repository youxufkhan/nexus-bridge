@props([
'filters' => [],
'sortOptions' => [],
'currentFilters' => [],
'currentSort' => ['field' => 'created_at', 'direction' => 'desc'],
'activeCount' => 0,
])

<div x-data="filterSort()" class="mb-6">
    <!-- Mobile/Desktop Filter Bar -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
        <!-- Top Bar: Search + Toggle Buttons -->
        <div class="p-4 md:p-6">
            <div class="flex flex-col md:flex-row gap-3">
                <!-- Search Input -->
                @if(collect($filters)->contains('type', 'search'))
                <div class="flex-1">
                    <div class="relative">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" x-model="search" @input.debounce.300ms="applyFilters()"
                            placeholder="Search..."
                            class="w-full pl-12 pr-4 py-3 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/20 transition-all">
                    </div>
                </div>
                @endif

                <!-- Filter Toggle Button -->
                <button @click="showFilters = !showFilters" type="button"
                    class="flex items-center justify-center gap-2 px-5 py-3 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:border-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 transition-all font-bold text-sm">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <span>Filters</span>
                    @if($activeCount > 0)
                    <span class="px-2 py-0.5 rounded-full bg-indigo-600 text-white text-xs font-black">{{ $activeCount
                        }}</span>
                    @endif
                </button>

                <!-- Sort Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" type="button"
                        class="flex items-center justify-between gap-2 px-5 py-3 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:border-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 transition-all font-bold text-sm w-full md:w-auto">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
                        </svg>
                        <span x-text="sortLabel"></span>
                        <svg class="w-4 h-4" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Sort Dropdown Menu -->
                    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-64 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xl z-50">
                        <div class="p-2">
                            @foreach($sortOptions as $option)
                            <button @click="setSort('{{ $option['field'] }}'); open = false" type="button"
                                class="w-full flex items-center justify-between px-4 py-2.5 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors text-left">
                                <span class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ $option['label']
                                    }}</span>
                                <div class="flex gap-1">
                                    <svg x-show="sortBy === '{{ $option['field'] }}' && sortDirection === 'asc'"
                                        class="w-4 h-4 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <svg x-show="sortBy === '{{ $option['field'] }}' && sortDirection === 'desc'"
                                        class="w-4 h-4 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Clear Filters Button -->
                @if($activeCount > 0)
                <button @click="clearFilters()" type="button"
                    class="flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-500/10 dark:hover:text-red-400 transition-all font-bold text-sm">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span class="hidden md:inline">Clear</span>
                </button>
                @endif
            </div>
        </div>

        <!-- Collapsible Filter Panel -->
        <div x-show="showFilters" x-collapse class="border-t border-slate-200 dark:border-slate-800">
            <form @submit.prevent="applyFilters()" class="p-4 md:p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($filters as $filter)
                    @if($filter['type'] !== 'search')
                    <div>
                        <label
                            class="block text-xs font-black text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                            {{ $filter['label'] }}
                        </label>

                        @if($filter['type'] === 'select' || $filter['type'] === 'relation')
                        <select x-model="filters.{{ $filter['key'] }}"
                            class="w-full px-4 py-2.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/20 transition-all">
                            <option value="">All</option>
                            @foreach($filter['options'] as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @endif

                        @if($filter['type'] === 'date_range')
                        <div class="grid grid-cols-2 gap-2">
                            <input type="date" x-model="filters.{{ $filter['key'] }}_from"
                                class="px-3 py-2 rounded-lg border-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                            <input type="date" x-model="filters.{{ $filter['key'] }}_to"
                                class="px-3 py-2 rounded-lg border-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                        </div>
                        @endif

                        @if($filter['type'] === 'number_range')
                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" x-model="filters.{{ $filter['key'] }}_min" placeholder="Min"
                                class="px-3 py-2 rounded-lg border-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                            <input type="number" x-model="filters.{{ $filter['key'] }}_max" placeholder="Max"
                                class="px-3 py-2 rounded-lg border-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                        </div>
                        @endif
                    </div>
                    @endif
                    @endforeach
                </div>

                <div class="mt-4 flex gap-3">
                    <button type="submit"
                        class="flex-1 px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-black text-sm uppercase tracking-wider transition-all shadow-lg shadow-indigo-500/30">
                        Apply Filters
                    </button>
                    <button @click="clearFilters()" type="button"
                        class="px-6 py-3 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700 font-bold text-sm transition-all">
                        Reset
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('filterSort', () => ({
            showFilters: {{ $activeCount > 0 ? 'true' : 'false' }},
        search: @json($currentFilters['search'] ?? ''),
        sortBy: @json($currentSort['field'] ?? 'created_at'),
        sortDirection: @json($currentSort['direction'] ?? 'desc'),
        filters: @json($currentFilters),
        sortOptions: @json($sortOptions),

        get sortLabel() {
        const option = this.sortOptions.find(o => o.field === this.sortBy);
        const arrow = this.sortDirection === 'asc' ? '↑' : '↓';
        return option ? `${option.label} ${arrow}` : 'Sort';
    },

        init() {
        // Ensure all filter keys exist in the filters object
        const filterKeys = @json(collect($filters) -> pluck('key') -> all());
        filterKeys.forEach(key => {
            if (key !== 'search' && !(key in this.filters)) {
                this.filters[key] = '';
            }
        });
    },

        setSort(field) {
        if(this.sortBy === field) {
        this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
    } else {
        this.sortBy = field;
        this.sortDirection = 'desc';
    }
    this.applyFilters();
            },

    applyFilters() {
        const params = new URLSearchParams(window.location.search);

        // Update search
        if (this.search) {
            params.set('search', this.search);
        } else {
            params.delete('search');
        }

        // Update filters
        Object.entries(this.filters).forEach(([key, value]) => {
            if (value !== null && value !== '' && key !== 'search') {
                params.set(key, value);
            } else {
                params.delete(key);
            }
        });

        // Update sorting
        params.set('sort_by', this.sortBy);
        params.set('sort_direction', this.sortDirection);

        // Check active count (client-side approximation for UI feedback, optional but good)
        // Actually server handles this, we just navigate.

        window.location.href = `${window.location.pathname}?${params.toString()}`;
    },

    clearFilters() {
        window.location.href = window.location.pathname;
    }
        }));
    });
</script>