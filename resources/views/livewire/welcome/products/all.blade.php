<section class="py-20 bg-background min-h-screen">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center rounded-full bg-primary/10 px-4 py-2 text-sm font-medium text-primary mb-4">
                <flux:icon.squares-2x2 class="mr-2 h-4 w-4" />
                Premium Feed Collection
            </div>
            <h1 class="text-4xl font-bold text-text-primary sm:text-5xl mb-4">
                All Products
            </h1>
            <p class="text-lg text-text-secondary max-w-2xl mx-auto">
                Browse our complete collection of premium poultry feed products, carefully selected for optimal nutrition and bird health.
            </p>
        </div>

        <!-- Filters and Search -->
        <div class="bg-card border border-border rounded-2xl p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <flux:field>
                        <flux:label>Search Products</flux:label>
                        <flux:input
                            wire:model.live.debounce.300ms="search"
                            placeholder="Search by name or description..."
                            icon="magnifying-glass"
                        />
                    </flux:field>
                </div>

                <!-- Sort By -->
                <div>
                    <flux:field>
                        <flux:label>Sort By</flux:label>
                        <flux:select wire:model.live="sortBy">
                            <flux:select.option value="name">Name</flux:select.option>
                            <flux:select.option value="price">Price</flux:select.option>
                            <flux:select.option value="stock_quantity">Stock</flux:select.option>
                            <flux:select.option value="created_at">Newest</flux:select.option>
                        </flux:select>
                    </flux:field>
                </div>

                <!-- Sort Direction -->
                <div>
                    <flux:field>
                        <flux:label>Order</flux:label>
                        <flux:select wire:model.live="sortDirection">
                            <flux:select.option value="asc">Ascending</flux:select.option>
                            <flux:select.option value="desc">Descending</flux:select.option>
                        </flux:select>
                    </flux:field>
                </div>
            </div>

            <!-- Filter Actions -->
            <div class="flex items-center justify-between mt-4 pt-4 border-t border-border">
                <div class="text-text-secondary text-sm">
                    Showing {{ $products->count() }} of {{ $products->total() }} products
                </div>
                @if($search )
                    <flux:button variant="ghost" wire:click="clearFilters">
                        <flux:icon.x-mark class="w-4 h-4 mr-2" />
                        Clear Filters
                    </flux:button>
                @endif
            </div>
        </div>

        <!-- Products Grid -->
        @if($products->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 mb-12">
                @foreach ($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $products->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-muted rounded-full flex items-center justify-center mx-auto mb-6">
                    <flux:icon.magnifying-glass class="w-12 h-12 text-text-secondary" />
                </div>
                <h3 class="text-xl font-semibold text-text-primary mb-2">No Products Found</h3>
                <p class="text-text-secondary mb-8">
                    @if($search)
                        No products match your current search criteria. Try adjusting your filters.
                    @else
                        We're working hard to stock our shelves with quality feed products.
                    @endif
                </p>
                @if($search)
                    <flux:button variant="primary" wire:click="clearFilters">
                        <flux:icon.arrow-path class="w-4 h-4 mr-2" />
                        Clear Filters
                    </flux:button>
                @endif
            </div>
        @endif

        <!-- Back to Homepage -->
        <div class="text-center mt-12">
            <flux:button variant="ghost" href="/">
                <flux:icon.arrow-left class="w-4 h-4 mr-2" />
                Back to Homepage
            </flux:button>
        </div>
    </div>
</section>
