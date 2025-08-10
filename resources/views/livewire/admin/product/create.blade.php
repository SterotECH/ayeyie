<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col items-start justify-between gap-4 md:flex-row md:items-center">
        <div>
            <h1 class="text-3xl font-bold text-text-primary">Create New Product</h1>
            <p class="text-text-secondary">Add a new feed product to your inventory</p>
        </div>
        <div class="flex items-center space-x-4">
            <nav class="flex items-center space-x-2 text-sm">
                <a href="{{ route('dashboard') }}" class="text-text-secondary hover:text-text-primary transition-colors">Dashboard</a>
                <flux:icon.chevron-right class="w-4 h-4 text-text-secondary" />
                <a href="{{ route('admin.products.index') }}" class="text-text-secondary hover:text-text-primary transition-colors">Products</a>
                <flux:icon.chevron-right class="w-4 h-4 text-text-secondary" />
                <span class="text-text-primary font-medium">Create</span>
            </nav>
        </div>
    </div>

    <!-- Create Form Card -->
    <div class="bg-card rounded-xl shadow-sm border border-border overflow-hidden">
        <!-- Card Header -->
        <div class="bg-muted px-6 py-4 border-b border-border">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-primary/10 rounded-lg flex items-center justify-center">
                        <flux:icon.plus class="w-5 h-5 text-primary" />
                    </div>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-semibold text-text-primary">Product Information</h3>
                    <p class="text-text-secondary text-sm">Enter the details for your new feed product</p>
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
                            <flux:label for="stock_quantity">Initial Stock *</flux:label>
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
                                <div class="flex items-center text-sm">
                                    <flux:icon.light-bulb class="w-4 h-4 text-accent mr-2" />
                                    <span class="text-text-secondary">Recommended threshold: 25-50 bags</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-between pt-6 border-t border-border">
                    <flux:button
                        variant="ghost"
                        href="{{ route('admin.products.index') }}"
                        icon="arrow-left"
                    >
                        Cancel
                    </flux:button>
                    
                    <div class="flex items-center space-x-3">
                        <flux:button
                            type="submit"
                            variant="primary"
                            icon="check"
                        >
                            Create Product
                        </flux:button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Help Section -->
    <div class="bg-card rounded-xl shadow-sm border border-border p-6">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <flux:icon.information-circle class="w-6 h-6 text-primary" />
            </div>
            <div class="ml-3">
                <h3 class="text-lg font-medium text-text-primary mb-2">Product Creation Tips</h3>
                <ul class="space-y-2 text-text-secondary">
                    <li class="flex items-start">
                        <flux:icon.check class="w-4 h-4 text-success mr-2 mt-0.5 flex-shrink-0" />
                        <span>Use descriptive names that include the feed type and target bird age</span>
                    </li>
                    <li class="flex items-start">
                        <flux:icon.check class="w-4 h-4 text-success mr-2 mt-0.5 flex-shrink-0" />
                        <span>Set appropriate low stock thresholds to avoid stockouts</span>
                    </li>
                    <li class="flex items-start">
                        <flux:icon.check class="w-4 h-4 text-success mr-2 mt-0.5 flex-shrink-0" />
                        <span>Include nutritional benefits and feeding instructions in descriptions</span>
                    </li>
                    <li class="flex items-start">
                        <flux:icon.check class="w-4 h-4 text-success mr-2 mt-0.5 flex-shrink-0" />
                        <span>Price competitively based on quality and market standards</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
