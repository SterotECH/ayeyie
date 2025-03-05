<div>
    <div class="container mx-auto px-6 py-12 max-w-7xl">
        <!-- Header -->
        <header class="flex items-center justify-between mb-12">
            <div class="flex items-center space-x-4">
                <h1 class="text-4xl font-extrabold text-accent dark:text-white tracking-tight">
                    Products
                </h1>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-accent text-accent-foreground">
                    {{ $products->total() }} products
                </span>
            </div>
            <flux:button
                wire:navigate
                href="{{ route('admin.products.create') }}"
                variant="primary"
                icon="plus"
            >
                Add Product
            </flux:button>
        </header>

        <!-- Main Content -->
        <div class="grid grid-cols-1 xl:grid-cols-4 gap-8">
            <!-- Filters -->
            <aside class="xl:col-span-1">
                <div class="bg-white dark:bg-zinc-800 rounded-lg p-6 shadow-lg">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                            Filters
                        </h2>
                        <button
                            wire:click="resetFilters"
                            class="text-amber-600 hover:text-amber-800 dark:text-amber-400 dark:hover:text-amber-300 text-sm font-medium transition-colors"
                        >
                            Clear
                        </button>
                    </div>

                    <div class="space-y-6">
                        <flux:input
                            type="search"
                            wire:model.live="search"
                            id="search"
                            placeholder="Search..."
                        />

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Price Range
                            </label>
                            <div class="grid grid-cols-2 gap-3">
                                <flux:input
                                    type="number"
                                    wire:model.live="filters.min_price"
                                    placeholder="Min"
                                    min="0"
                                    step="0.01"
                                />
                                <flux:input
                                    type="number"
                                    wire:model.live="filters.max_price"
                                    placeholder="Max"
                                    min="0"
                                    step="0.01"
                                />
                            </div>
                        </div>

                        <flux:checkbox
                            wire:model.live="filters.in_stock"
                            id="in_stock"
                            label="In Stock Only"
                        />
                    </div>
                </div>
            </aside>

            <!-- Products -->
            <main class="xl:col-span-3">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($products as $product)
                        <div class="group bg-white dark:bg-zinc-800 rounded-2xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                            <div class="p-5">
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white truncate">
                                        {{ $product->name }}
                                    </h3>
                                    <div class="h-2 w-2 rounded-full {{ $product->stock_quantity > 0 ? 'bg-green-500' : 'bg-red-500' }} group-hover:animate-pulse"></div>
                                </div>

                                <p class="text-2xl font-extrabold text-amber-600 dark:text-amber-400 mb-4">
                                    ${{ number_format($product->price, 2) }}
                                </p>

                                <div class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-400">
                                    <span>
                                        Stock: {{ $product->stock_quantity }}
                                    </span>
                                    <span>
                                        Min: {{ $product->threshold_quantity }}
                                    </span>
                                </div>

                                <div class="mt-4 flex space-x-3 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <flux:button
                                        wire:navigate
                                        href="{{ route('admin.products.show', $product) }}"
                                        icon="eye"
                                    >
                                        View
                                    </flux:button>
                                    <flux:button
                                        wire:navigate
                                        href="{{ route('admin.products.edit', $product) }}"
                                       icon="pencil-square"
                                    >
                                        Edit
                                    </flux:button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full bg-white dark:bg-zinc-800 rounded-2xl shadow-md p-12 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 dark:text-zinc-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                            </svg>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                                No Products Found
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400">
                                Try adjusting your filters or add a new product.
                            </p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div>
                    {{ $products->links() }}
                </div>
            </main>
        </div>
    </div>
</div>
