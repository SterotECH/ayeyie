<div>
    <div class="mb-6 flex flex-col items-start justify-between md:flex-row md:items-center">
        <div>
            <h1 class="text-accent text-2xl font-bold">Products</h1>
            <p class="text-accent/50 text-sm">Manage your inventory</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('dashboard') }}">
                Dashboard
            </a>
            <span class="mx-2 text-gray-500">/</span>
            <span class="text-sm text-gray-900">Products</span>
        </div>
    </div>

    <div class="mb-6 rounded-lg bg-zinc-50 p-4 shadow dark:bg-zinc-800">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
            <!-- Search -->
            <div class="col-span-1 md:col-span-2">
                <label class="sr-only" for="search">Search</label>
                <div class="relative">
                    <flux:input id="search" icon="magnifying-glass" wire:model.live="search"
                        placeholder="Search products..." />
                </div>
            </div>

            <!-- Price Range Filter -->
            <div>
                <label class="sr-only" for="priceFilter">Price Filter</label>
                <flux:select id="priceFilter" wire:model="priceFilter">
                    <flux:select.option value="">All Prices</flux:select.option>
                    <flux:select.option value="low">Under $25</flux:select.option>
                    <flux:select.option value="medium">$25 - $100</flux:select.option>
                    <flux:select.option value="high">Over $100</flux:select.option>
                </flux:select>
            </div>

            <!-- Stock Filter -->
            <div>
                <label class="sr-only" for="stockFilter">Stock Filter</label>
                <flux:select id="stockFilter" wire:model="stockFilter">
                    <flux:select.option value="">All Stock Levels</flux:select.option>
                    <flux:select.option value="in_stock">In Stock</flux:select.option>
                    <flux:select.option value="low">Low Stock</flux:select.option>
                    <flux:select.option value="out_of_stock">Out of Stock</flux:select.option>
                </flux:select>
            </div>
        </div>
    </div>

    <!-- Add Product Button -->
    <div class="mb-4 flex justify-end">
        <flux:button href="{{ route('admin.products.create') }}" wire:navigate variant="primary" icon="plus">
            Add Product
        </flux:button>
    </div>

    <!-- Desktop Table View -->
    <div class="hidden overflow-hidden rounded-lg bg-zinc-50 shadow md:block dark:bg-zinc-800">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-900">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                        scope="col">
                        Product
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                        scope="col">
                        Price
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                        scope="col">
                        Stock
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                        scope="col">
                        Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                        scope="col">
                        Threshold
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500"
                        scope="col">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-zinc-50 dark:divide-gray-900 dark:bg-zinc-800">
                @forelse($products as $product)
                    <tr class="hover:bg-gray-50 dark:bg-gray-700">
                        <td class="whitespace-nowrap px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <div class="text-sm text-gray-900">${{ number_format($product->price, 2) }}</div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $product->stock_quantity }}</div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            @if ($product->stock_quantity === 0)
                                <span
                                    class="inline-flex rounded-full bg-red-100 px-2 text-xs font-semibold leading-5 text-red-800">
                                    Out of Stock
                                </span>
                            @elseif ($product->stock_quantity <= $product->threshold_quantity)
                                <span
                                    class="inline-flex rounded-full bg-yellow-100 px-2 text-xs font-semibold leading-5 text-yellow-800">
                                    Low Stock
                                </span>
                            @else
                                <span
                                    class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800">
                                    In Stock
                                </span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <div class="text-sm text-gray-500">{{ $product->threshold_quantity }}</div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-right">
                            <div class="flex justify-end space-x-2">
                                <flux:button href="{{ route('admin.products.show', $product) }}" variant="filled">
                                    <flux:icon.eye class="size-4" />
                                </flux:button>
                                <flux:button href="{{ route('admin.products.edit', $product) }}" variant="filled">
                                    <flux:icon.pencil-square class="size-4" />
                                </flux:button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-6 py-4 text-center text-sm text-gray-500" colspan="6">
                            No products found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Card View -->
    <div class="space-y-4 md:hidden">
        @forelse($products as $product)
            <div class="overflow-hidden rounded-lg bg-zinc-50 shadow dark:bg-zinc-800">
                <div class="flex justify-between px-4 py-5 sm:px-6">
                    <div>
                        <h3 class="text-lg font-medium leading-6 text-gray-900">{{ $product->name }}</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">${{ number_format($product->price, 2) }}</p>
                    </div>
                    @if ($product->stock_quantity === 0)
                        <span
                            class="inline-flex h-6 items-center rounded-full bg-red-100 px-2 text-xs font-semibold leading-5 text-red-800">
                            Out of Stock
                        </span>
                    @elseif ($product->stock_quantity <= $product->threshold_quantity)
                        <span
                            class="inline-flex h-6 items-center rounded-full bg-yellow-100 px-2 text-xs font-semibold leading-5 text-yellow-800">
                            Low Stock
                        </span>
                    @else
                        <span
                            class="inline-flex h-6 items-center rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800">
                            In Stock
                        </span>
                    @endif
                </div>
                <div class="border-t border-gray-200">
                    <dl>
                        <div class="bg-gray-50 px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 dark:bg-gray-700">
                            <dt class="text-sm font-medium text-gray-500">Stock</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                {{ $product->stock_quantity }}
                            </dd>
                        </div>
                        <div class="bg-zinc-50 px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 dark:bg-zinc-800">
                            <dt class="text-sm font-medium text-gray-500">Threshold</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                {{ $product->threshold_quantity }}
                            </dd>
                        </div>
                    </dl>
                </div>
                <div class="bg-gray-50 px-4 py-4 text-right dark:bg-gray-700">
                    <div class="flex justify-end space-x-2">
                        <flux:button href="{{ route('admin.products.show', $product) }}" variant="filled">
                            <flux:icon.eye class="-ml-1 mr-2 size-5" /> View
                        </flux:button>
                        <flux:button href="{{ route('admin.products.edit', $product) }}" variant="filled">
                            <flux:icon.pencil-square class="-ml-1 mr-2 size-5" /> Edit
                        </flux:button>
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-lg bg-zinc-50 p-6 text-center text-gray-500 shadow dark:bg-zinc-800">
                No products found.
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>
</div>
