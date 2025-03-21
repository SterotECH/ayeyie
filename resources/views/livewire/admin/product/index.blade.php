<div>
    <div class="py-12">
        <!-- Header -->
        <header class="mb-12 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <h1 class="text-accent text-4xl font-extrabold tracking-tight dark:text-white">
                    Products
                </h1>
                <span
                    class="bg-accent text-accent-foreground inline-flex items-center rounded-full px-3 py-1 text-sm font-medium">
                    {{ $products->total() }} products
                </span>
            </div>
            <flux:button href="{{ route('admin.products.create') }}" wire:navigate variant="primary" icon="plus">
                Add Product
            </flux:button>
        </header>

        <!-- Main Content -->
        <div class="grid grid-cols-1 gap-8 xl:grid-cols-4">
            <!-- Filters -->
            <aside class="xl:col-span-1">
                <div class="rounded-lg bg-white p-6 shadow-lg dark:bg-zinc-800">
                    <div class="mb-6 flex items-center justify-between">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                            Filters
                        </h2>
                        <button
                            class="text-sm font-medium text-amber-600 transition-colors hover:text-amber-800 dark:text-amber-400 dark:hover:text-amber-300"
                            wire:click="resetFilters">
                            Clear
                        </button>
                    </div>

                    <div class="space-y-6">
                        <flux:input id="search" type="search" wire:model.live="search" placeholder="Search..." />

                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Price Range
                            </label>
                            <div class="grid grid-cols-2 gap-3">
                                <flux:input type="number" wire:model.live="filters.min_price" placeholder="Min"
                                    min="0" step="0.01" />
                                <flux:input type="number" wire:model.live="filters.max_price" placeholder="Max"
                                    min="0" step="0.01" />
                            </div>
                        </div>

                        <flux:checkbox id="in_stock" wire:model.live="filters.in_stock" label="In Stock Only" />
                    </div>
                </div>
            </aside>

            <!-- Products -->
            <main class="xl:col-span-3">
                <div class="mb-4 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @forelse($products as $product)
                        <div
                            class="group overflow-hidden rounded-2xl bg-white shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-xl dark:bg-zinc-800">
                            <div class="p-5">
                                <div class="mb-3 flex items-center justify-between">
                                    <h3 class="truncate text-lg font-bold text-gray-900 dark:text-white">
                                        {{ $product->name }}
                                    </h3>
                                    <div
                                        class="{{ $product->stock_quantity > 0 ? 'bg-green-500' : 'bg-red-500' }} h-2 w-2 rounded-full group-hover:animate-pulse">
                                    </div>
                                </div>

                                <p class="mb-4 text-2xl font-extrabold text-amber-600 dark:text-amber-400">
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

                                <div
                                    class="mt-4 flex space-x-3 opacity-0 transition-opacity duration-200 group-hover:opacity-100">
                                    <flux:button href="{{ route('admin.products.show', $product) }}" wire:navigate
                                        icon="eye">
                                        View
                                    </flux:button>
                                    <flux:button href="{{ route('admin.products.edit', $product) }}" wire:navigate
                                        icon="pencil-square">
                                        Edit
                                    </flux:button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full rounded-2xl bg-white p-12 text-center shadow-md dark:bg-zinc-800">
                            <svg class="mx-auto mb-4 h-16 w-16 text-gray-400 dark:text-zinc-600"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                            </svg>
                            <h3 class="mb-2 text-xl font-semibold text-gray-900 dark:text-white">
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
