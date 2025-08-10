<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col items-start justify-between gap-4 md:flex-row md:items-center">
        <div>
            <h1 class="text-3xl font-bold text-text-primary">Edit Product</h1>
            <p class="text-text-secondary">Update {{ $product->name }} details and inventory</p>
        </div>
        <div class="flex items-center space-x-4">
            <nav class="flex items-center space-x-2 text-sm">
                <a href="{{ route('dashboard') }}" class="text-text-secondary hover:text-text-primary transition-colors">Dashboard</a>
                <flux:icon.chevron-right class="w-4 h-4 text-text-secondary" />
                <a href="{{ route('admin.products.index') }}" class="text-text-secondary hover:text-text-primary transition-colors">Products</a>
                <flux:icon.chevron-right class="w-4 h-4 text-text-secondary" />
                <a href="{{ route('admin.products.show', $product) }}" class="text-text-secondary hover:text-text-primary transition-colors">{{ Str::limit($product->name, 20) }}</a>
                <flux:icon.chevron-right class="w-4 h-4 text-text-secondary" />
                <span class="text-text-primary font-medium">Edit</span>
            </nav>
        </div>
    </div>

    <!-- Current Product Overview -->
    <div class="bg-card rounded-xl shadow-sm border border-border p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center">
                    <flux:icon.cube class="w-6 h-6 text-primary" />
                </div>
            </div>
            <div class="ml-4 flex-1">
                <h3 class="text-lg font-semibold text-text-primary">{{ $product->name }}</h3>
                <p class="text-text-secondary text-sm">Product ID: #{{ $product->product_id }}</p>
            </div>
            <div class="text-right">
                <p class="text-2xl font-bold text-text-primary">₵{{ number_format($product->price, 2) }}</p>
                <div class="flex items-center mt-1">
                    @if ($product->stock_quantity <= $product->threshold_quantity)
                        <flux:icon.exclamation-triangle class="w-4 h-4 text-warning mr-1" />
                        <span class="text-warning text-sm font-medium">{{ $product->stock_quantity }} bags</span>
                    @else
                        <flux:icon.check-circle class="w-4 h-4 text-success mr-1" />
                        <span class="text-success text-sm font-medium">{{ $product->stock_quantity }} bags</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Form Card -->
    <div class="bg-card rounded-xl shadow-sm border border-border overflow-hidden">
        <!-- Card Header -->
        <div class="bg-muted px-6 py-4 border-b border-border">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-accent/10 rounded-lg flex items-center justify-center">
                        <flux:icon.pencil class="w-5 h-5 text-accent" />
                    </div>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-semibold text-text-primary">Update Product Information</h3>
                    <p class="text-text-secondary text-sm">Modify the product details below</p>
                </div>
            </div>
        </div>

        <!-- Form Content -->
        <div class="p-6">
            <form wire:submit.prevent="submit" class="space-y-6">
                <!-- Basic Information Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Product Name -->
                    <div class="md:col-span-2">
                        <flux:field>
                            <flux:label for="name">Product Name *</flux:label>
                            <flux:input
                                id="name"
                                type="text"
                                wire:model="name"
                                placeholder="e.g., Premium Broiler Starter Feed"
                                icon="cube"
                            />
                            <flux:error name="name" />
                            <flux:description>Enter a descriptive name for your feed product</flux:description>
                        </flux:field>
                    </div>

                    <!-- Price -->
                    <div>
                        <flux:field>
                            <flux:label for="price">Price (₵) *</flux:label>
                            <flux:input
                                id="price"
                                type="number"
                                step="0.01"
                                wire:model="price"
                                placeholder="0.00"
                                icon="currency-dollar"
                            />
                            <flux:error name="price" />
                            <flux:description>Price per 25kg bag in Ghana Cedis</flux:description>
                        </flux:field>
                    </div>

                    <!-- Stock Quantity -->
                    <div>
                        <flux:field>
                            <flux:label for="stock_quantity">Current Stock *</flux:label>
                            <flux:input
                                id="stock_quantity"
                                type="number"
                                wire:model="stock_quantity"
                                placeholder="0"
                                icon="archive-box"
                            />
                            <flux:error name="stock_quantity" />
                            <flux:description>Number of bags currently available</flux:description>
                        </flux:field>
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <flux:field>
                        <flux:label for="description">Product Description</flux:label>
                        <flux:textarea
                            id="description"
                            wire:model="description"
                            rows="4"
                            placeholder="Describe the feed composition, benefits, and target poultry..."
                        />
                        <flux:error name="description" />
                        <flux:description>Optional detailed description of the feed product</flux:description>
                    </flux:field>
                </div>

                <!-- Inventory Management -->
                <div class="bg-muted rounded-lg p-4">
                    <div class="flex items-center mb-4">
                        <flux:icon.bell class="w-5 h-5 text-warning mr-2" />
                        <h4 class="font-semibold text-text-primary">Inventory Alert Settings</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <flux:field>
                                <flux:label for="threshold_quantity">Low Stock Threshold *</flux:label>
                                <flux:input
                                    id="threshold_quantity"
                                    type="number"
                                    wire:model="threshold_quantity"
                                    placeholder="50"
                                    icon="exclamation-triangle"
                                />
                                <flux:error name="threshold_quantity" />
                                <flux:description>Alert when stock falls below this level</flux:description>
                            </flux:field>
                        </div>
                        <div class="flex items-center pt-6">
                            <div class="bg-card rounded-lg p-3 border border-border">
                                @php
                                    $currentStock = (int)$this->stock_quantity;
                                    $threshold = (int)$this->threshold_quantity;
                                @endphp
                                @if($currentStock <= $threshold && $currentStock > 0)
                                    <div class="flex items-center text-sm">
                                        <flux:icon.exclamation-triangle class="w-4 h-4 text-warning mr-2" />
                                        <span class="text-warning font-medium">Low stock warning active</span>
                                    </div>
                                @elseif($currentStock === 0)
                                    <div class="flex items-center text-sm">
                                        <flux:icon.x-circle class="w-4 h-4 text-error mr-2" />
                                        <span class="text-error font-medium">Out of stock</span>
                                    </div>
                                @else
                                    <div class="flex items-center text-sm">
                                        <flux:icon.check-circle class="w-4 h-4 text-success mr-2" />
                                        <span class="text-success font-medium">Stock levels healthy</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-between pt-6 border-t border-border">
                    <div class="flex items-center space-x-3">
                        <flux:button
                            variant="ghost"
                            href="{{ route('admin.products.show', $product) }}"
                            icon="arrow-left"
                        >
                            Cancel
                        </flux:button>
                        <flux:button
                            variant="outline"
                            href="{{ route('admin.products.index') }}"
                            icon="squares-2x2"
                        >
                            All Products
                        </flux:button>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <flux:button
                            type="submit"
                            variant="primary"
                            icon="check"
                        >
                            Update Product
                        </flux:button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Change Summary -->
    <div class="bg-card rounded-xl shadow-sm border border-border p-6">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <flux:icon.information-circle class="w-6 h-6 text-primary" />
            </div>
            <div class="ml-3">
                <h3 class="text-lg font-medium text-text-primary mb-2">Important Notes</h3>
                <ul class="space-y-2 text-text-secondary">
                    <li class="flex items-start">
                        <flux:icon.check class="w-4 h-4 text-success mr-2 mt-0.5 flex-shrink-0" />
                        <span>Price changes will affect all future orders and quotations</span>
                    </li>
                    <li class="flex items-start">
                        <flux:icon.check class="w-4 h-4 text-success mr-2 mt-0.5 flex-shrink-0" />
                        <span>Stock quantity updates will trigger automatic reorder alerts if below threshold</span>
                    </li>
                    <li class="flex items-start">
                        <flux:icon.check class="w-4 h-4 text-success mr-2 mt-0.5 flex-shrink-0" />
                        <span>Changes are saved immediately and will be reflected across the system</span>
                    </li>
                    <li class="flex items-start">
                        <flux:icon.check class="w-4 h-4 text-success mr-2 mt-0.5 flex-shrink-0" />
                        <span>Product modification history is maintained for audit purposes</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
