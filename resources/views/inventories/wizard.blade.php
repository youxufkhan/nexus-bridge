@extends('layouts.app')

@section('header', 'Adjust Inventory')

@section('content')
<div class="max-w-3xl mx-auto" x-data="inventoryWizard()">
    <!-- Progress Indicator -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <template x-for="i in 4" :key="i">
                <div class="flex items-center flex-1">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full font-black text-sm transition-all"
                        :class="step >= i ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'bg-slate-200 dark:bg-slate-800 text-slate-400'">
                        <span x-text="i"></span>
                    </div>
                    <div x-show="i < 4" class="flex-1 h-1 mx-2 rounded-full transition-all"
                        :class="step > i ? 'bg-indigo-600' : 'bg-slate-200 dark:bg-slate-800'"></div>
                </div>
            </template>
        </div>
        <p class="text-sm font-bold text-slate-500 text-center" x-text="stepTitle"></p>
    </div>

    <!-- Wizard Card -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl">
        <div class="p-8 md:p-12">

            <!-- Step 1: Adjustment Type -->
            <div x-show="step === 1" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                <h2 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white mb-6">Select Adjustment Type
                </h2>
                <div class="space-y-4">
                    <label
                        class="flex items-center p-6 rounded-2xl border-2 cursor-pointer transition-all hover:border-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-500/10"
                        :class="adjustmentType === 'ADJUST' ? 'border-indigo-600 bg-indigo-50 dark:bg-indigo-500/10' : 'border-slate-200 dark:border-slate-800'">
                        <input type="radio" x-model="adjustmentType" value="ADJUST" class="sr-only">
                        <div class="flex-1">
                            <div class="flex items-center mb-2">
                                <svg class="w-6 h-6 mr-3 text-indigo-600" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                </svg>
                                <span class="text-lg font-black text-slate-900 dark:text-white">Adjust Inventory</span>
                            </div>
                            <p class="text-sm text-slate-500 ml-9">Add or remove stock from warehouse</p>
                        </div>
                        <div x-show="adjustmentType === 'ADJUST'" class="ml-4">
                            <svg class="w-6 h-6 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Step 2: Product Search -->
            <div x-show="step === 2" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0"
                class="overflow-visible">
                <h2 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white mb-6">Select Product</h2>

                <div class="relative" x-data="{ showDropdown: false }">
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Search by name or
                        Master SKU</label>
                    <div class="relative">
                        <input type="text" x-model="searchQuery"
                            @input.debounce.300ms="searchProducts(); showDropdown = true"
                            @focus="showDropdown = searchResults.length > 0" @click.away="showDropdown = false"
                            placeholder="Start typing to search..."
                            class="w-full h-14 px-5 pr-12 text-base font-medium rounded-2xl border-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/20 transition-all">
                        <svg class="absolute right-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>

                    <!-- Search Results Dropdown -->
                    <div x-show="showDropdown && searchResults.length > 0"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        class="absolute z-50 w-full mt-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-2xl overflow-hidden">
                        <template x-for="product in searchResults" :key="product.id">
                            <button type="button" @click="selectProduct(product); showDropdown = false"
                                class="w-full flex items-center justify-between px-5 py-4 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 transition-colors text-left border-b border-slate-100 dark:border-slate-700 last:border-0">
                                <div>
                                    <p class="font-black text-slate-900 dark:text-white" x-text="product.name"></p>
                                    <p class="text-sm text-slate-500 font-mono" x-text="'SKU: ' + product.master_sku">
                                    </p>
                                </div>
                                <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </template>
                    </div>

                    <!-- Selected Product Display -->
                    <div x-show="selectedProduct" x-transition
                        class="mt-4 p-5 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 rounded-2xl">
                        <div class="flex items-start justify-between">
                            <div>
                                <p
                                    class="text-xs font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-widest mb-1">
                                    Selected Product</p>
                                <p class="font-black text-slate-900 dark:text-white" x-text="selectedProduct?.name"></p>
                                <p class="text-sm text-slate-600 dark:text-slate-400 font-mono"
                                    x-text="'SKU: ' + selectedProduct?.master_sku"></p>
                            </div>
                            <button @click="selectedProduct = null; searchQuery = ''; searchResults = []"
                                class="text-slate-400 hover:text-red-500 transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div x-show="searchQuery.length > 0 && searchResults.length === 0 && !loading"
                        class="mt-4 p-4 text-center text-sm text-slate-500">
                        No products found
                    </div>
                </div>
            </div>

            <!-- Step 3: Warehouse Selection -->
            <div x-show="step === 3" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                <h2 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white mb-6">Select Warehouse</h2>

                <div class="space-y-3">
                    <template x-for="warehouse in warehouses" :key="warehouse.id">
                        <label
                            class="flex items-center p-5 rounded-2xl border-2 cursor-pointer transition-all hover:border-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-500/10"
                            :class="selectedWarehouseId === warehouse.id ? 'border-indigo-600 bg-indigo-50 dark:bg-indigo-500/10' : 'border-slate-200 dark:border-slate-800'">
                            <input type="radio" :value="warehouse.id" x-model="selectedWarehouseId"
                                @change="selectWarehouse(warehouse); fetchInventory()" class="sr-only">
                            <div class="flex-1">
                                <p class="font-black text-slate-900 dark:text-white" x-text="warehouse.name"></p>
                                <p class="text-sm text-slate-500"
                                    x-text="warehouse.location || 'No location specified'"></p>
                            </div>
                            <div x-show="selectedWarehouse?.id === warehouse.id">
                                <svg class="w-6 h-6 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </label>
                    </template>
                </div>
            </div>

            <!-- Step 4: Quantity Adjustment -->
            <div x-show="step === 4" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                <h2 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white mb-6">Adjust Quantity</h2>

                <!-- Current Inventory Display -->
                <div
                    class="mb-8 p-6 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-200 dark:border-slate-700">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Current Stock</p>
                    <p class="text-4xl font-black text-slate-900 dark:text-white" x-text="currentInventory + ' units'">
                    </p>
                    <p class="text-sm text-slate-500 mt-1" x-text="selectedWarehouse?.name"></p>
                </div>

                <!-- Adjustment Input -->
                <div class="mb-8">
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Adjustment
                        Amount</label>
                    <div class="flex items-center gap-3 w-full">
                        <button @click="adjustmentQuantity = Math.max(adjustmentQuantity - 1, -currentInventory)"
                            class="flex-shrink-0 w-14 h-14 flex items-center justify-center rounded-2xl bg-slate-100 dark:bg-slate-800 hover:bg-red-500 hover:text-white text-slate-600 dark:text-slate-300 font-black text-2xl transition-all">
                            −
                        </button>
                        <input type="number" x-model.number="adjustmentQuantity"
                            class="flex-1 min-w-0 h-14 px-5 text-center text-2xl font-black rounded-2xl border-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/20 transition-all">
                        <button @click="adjustmentQuantity++"
                            class="flex-shrink-0 w-14 h-14 flex items-center justify-center rounded-2xl bg-slate-100 dark:bg-slate-800 hover:bg-emerald-500 hover:text-white text-slate-600 dark:text-slate-300 font-black text-2xl transition-all">
                            +
                        </button>
                    </div>
                    <p class="text-xs text-slate-500 mt-2 text-center">Use + to add stock, − to remove stock</p>
                </div>

                <!-- Before/After Preview -->
                <div
                    class="p-6 bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-indigo-500/10 dark:to-purple-500/10 rounded-2xl border border-indigo-200 dark:border-indigo-500/20">
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div>
                            <p class="text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Before</p>
                            <p class="text-2xl font-black text-slate-900 dark:text-white" x-text="currentInventory"></p>
                        </div>
                        <div class="flex items-center justify-center">
                            <svg class="w-8 h-8 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-black text-slate-500 uppercase tracking-widest mb-2">After</p>
                            <p class="text-2xl font-black"
                                :class="newInventory >= 0 ? 'text-emerald-600' : 'text-red-600'" x-text="newInventory">
                            </p>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-indigo-200 dark:border-indigo-500/20">
                        <p class="text-center text-sm font-bold"
                            :class="adjustmentQuantity >= 0 ? 'text-emerald-600' : 'text-red-600'">
                            <span
                                x-text="adjustmentQuantity >= 0 ? '+' + adjustmentQuantity : adjustmentQuantity"></span>
                            units
                        </p>
                    </div>
                </div>

                <div x-show="newInventory < 0"
                    class="mt-4 p-4 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 rounded-2xl">
                    <p class="text-sm font-bold text-red-600 dark:text-red-400">⚠️ Warning: This adjustment would result
                        in negative inventory</p>
                </div>
            </div>
        </div>

        <!-- Navigation Footer -->
        <div
            class="bg-slate-50 dark:bg-slate-800/50 px-8 md:px-12 py-6 flex items-center justify-between border-t border-slate-200 dark:border-slate-800">
            <button @click="goBack()" x-show="step > 1"
                class="flex items-center px-6 py-3 text-sm font-bold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back
            </button>
            <div x-show="step === 1"></div>

            <button @click="goNext()" x-show="step < 4" :disabled="!canProceed"
                :class="canProceed ? 'bg-indigo-600 hover:bg-indigo-700 text-white shadow-lg shadow-indigo-500/30' : 'bg-slate-200 dark:bg-slate-700 text-slate-400 cursor-not-allowed'"
                class="flex items-center px-8 py-3 text-sm font-black rounded-2xl transition-all">
                Continue
                <svg class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            <button @click="submitAdjustment()" x-show="step === 4" :disabled="newInventory < 0 || loading"
                :class="newInventory >= 0 && !loading ? 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-lg shadow-emerald-500/30' : 'bg-slate-200 dark:bg-slate-700 text-slate-400 cursor-not-allowed'"
                class="flex items-center px-8 py-3 text-sm font-black rounded-2xl transition-all">
                <span x-show="!loading">Confirm Adjustment</span>
                <span x-show="loading">Processing...</span>
            </button>
        </div>
    </div>
</div>

<script>
    function inventoryWizard() {
        return {
            step: 1,
            adjustmentType: 'ADJUST',
            searchQuery: '',
            searchResults: [],
            selectedProduct: null,
            selectedWarehouse: null,
            selectedWarehouseId: null,
            warehouses: @json($warehouses),
            currentInventory: 0,
            adjustmentQuantity: 0,
            loading: false,

            get stepTitle() {
                const titles = {
                    1: 'Step 1 of 4: Select Adjustment Type',
                    2: 'Step 2 of 4: Choose Product',
                    3: 'Step 3 of 4: Select Warehouse',
                    4: 'Step 4 of 4: Enter Adjustment'
                };
                return titles[this.step];
            },

            get canProceed() {
                if (this.step === 1) return this.adjustmentType !== null;
                if (this.step === 2) return this.selectedProduct !== null;
                if (this.step === 3) return this.selectedWarehouseId !== null;
                return false;
            },

            get newInventory() {
                return this.currentInventory + this.adjustmentQuantity;
            },

            async searchProducts() {
                if (this.searchQuery.length < 2) {
                    this.searchResults = [];
                    return;
                }

                this.loading = true;
                try {
                    const response = await fetch(`{{ route('dashboard.inventories.search-products') }}?q=${encodeURIComponent(this.searchQuery)}`);
                    this.searchResults = await response.json();
                } catch (error) {
                    console.error('Search failed:', error);
                    this.searchResults = [];
                } finally {
                    this.loading = false;
                }
            },

            selectProduct(product) {
                this.selectedProduct = product;
                this.searchQuery = product.name;
            },

            selectWarehouse(warehouse) {
                this.selectedWarehouse = warehouse;
                this.selectedWarehouseId = warehouse.id;
            },

            async fetchInventory() {
                if (!this.selectedProduct || !this.selectedWarehouseId) return;

                this.loading = true;
                try {
                    const response = await fetch(`{{ route('dashboard.inventories.fetch-inventory') }}?product_id=${this.selectedProduct.id}&warehouse_id=${this.selectedWarehouseId}`);
                    const data = await response.json();
                    this.currentInventory = data.quantity_on_hand;
                } catch (error) {
                    console.error('Failed to fetch inventory:', error);
                } finally {
                    this.loading = false;
                }
            },

            async submitAdjustment() {
                // Validate all required fields
                if (!this.selectedProduct || !this.selectedWarehouseId) {
                    alert('Please select both a product and warehouse before submitting.');
                    return;
                }

                if (this.newInventory < 0) {
                    alert('Adjustment would result in negative inventory.');
                    return;
                }

                this.loading = true;
                try {
                    const response = await fetch(`{{ route('dashboard.inventories.process-adjustment') }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            adjustment_type: this.adjustmentType,
                            product_id: this.selectedProduct.id,
                            warehouse_id: this.selectedWarehouseId,
                            adjustment_quantity: this.adjustmentQuantity
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        window.location.href = '{{ route('dashboard.inventories.index') }}';
                    } else {
                        alert(data.message);
                    }
                } catch (error) {
                    console.error('Adjustment failed:', error);
                    alert('Failed to process adjustment. Please try again.');
                } finally {
                    this.loading = false;
                }
            },

            goNext() {
                if (this.canProceed && this.step < 4) {
                    this.step++;
                    if (this.step === 4) {
                        this.fetchInventory();
                    }
                }
            },

            goBack() {
                if (this.step > 1) {
                    this.step--;
                }
            }
        };
    }
</script>
@endsection