<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col items-start justify-between gap-4 md:flex-row md:items-center">
        <div>
            <h1 class="text-3xl font-bold text-text-primary">{{ $product->name }}</h1>
            <p class="text-text-secondary">Product Details & Information</p>
        </div>
        <div class="flex items-center space-x-4">
            <nav class="flex items-center space-x-2 text-sm">
                <a href="{{ route('dashboard') }}" class="text-text-secondary hover:text-text-primary transition-colors">Dashboard</a>
                <flux:icon.chevron-right class="w-4 h-4 text-text-secondary" />
                <a href="{{ route('admin.products.index') }}" class="text-text-secondary hover:text-text-primary transition-colors">Products</a>
                <flux:icon.chevron-right class="w-4 h-4 text-text-secondary" />
                <span class="text-text-primary font-medium">{{ Str::limit($product->name, 20) }}</span>
            </nav>
            <flux:button href="{{ route('admin.products.edit', $product) }}" variant="primary" icon="pencil">
                Edit Product
            </flux:button>
        </div>
    </div>

    <!-- Product Overview Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Product Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Product Details Card -->
            <div class="bg-card rounded-xl shadow-sm border border-border overflow-hidden">
                <!-- Card Header -->
                <div class="bg-muted px-6 py-4 border-b border-border">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-primary/10 rounded-lg flex items-center justify-center">
                                <flux:icon.cube class="w-5 h-5 text-primary" />
                            </div>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-semibold text-text-primary">Product Information</h3>
                            <p class="text-text-secondary text-sm">Basic details and description</p>
                        </div>
                    </div>
                </div>

                <!-- Card Content -->
                <div class="p-6 space-y-6">
                    <!-- Product Name -->
                    <div>
                        <label class="text-sm font-medium text-text-secondary">Product Name</label>
                        <p class="text-text-primary text-lg font-semibold mt-1">{{ $product->name }}</p>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="text-sm font-medium text-text-secondary">Description</label>
                        <div class="mt-1">
                            @if($product->description)
                                <p class="text-text-primary leading-relaxed">{{ $product->description }}</p>
                            @else
                                <p class="text-text-secondary italic">No description provided</p>
                            @endif
                        </div>
                    </div>

                    <!-- Product ID -->
                    <div>
                        <label class="text-sm font-medium text-text-secondary">Product ID</label>
                        <p class="text-text-primary font-mono text-sm mt-1 bg-muted px-3 py-1 rounded-md inline-block">
                            #{{ $product->product_id }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Pricing & Stock Card -->
            <div class="bg-card rounded-xl shadow-sm border border-border overflow-hidden">
                <!-- Card Header -->
                <div class="bg-muted px-6 py-4 border-b border-border">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-accent/10 rounded-lg flex items-center justify-center">
                                <flux:icon.banknotes class="w-5 h-5 text-accent" />
                            </div>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-semibold text-text-primary">Pricing & Inventory</h3>
                            <p class="text-text-secondary text-sm">Current pricing and stock levels</p>
                        </div>
                    </div>
                </div>

                <!-- Card Content -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Price -->
                        <div class="text-center p-4 bg-muted rounded-lg">
                            <div class="flex items-center justify-center mb-2">
                                <flux:icon.currency-dollar class="w-5 h-5 text-accent mr-2" />
                                <span class="text-text-secondary text-sm font-medium">Unit Price</span>
                            </div>
                            <p class="text-3xl font-bold text-text-primary">₵{{ number_format($product->price, 2) }}</p>
                            <p class="text-text-secondary text-sm mt-1">per 25kg bag</p>
                        </div>

                        <!-- Stock Level -->
                        <div class="text-center p-4 bg-muted rounded-lg">
                            <div class="flex items-center justify-center mb-2">
                                <flux:icon.archive-box class="w-5 h-5 text-primary mr-2" />
                                <span class="text-text-secondary text-sm font-medium">Current Stock</span>
                            </div>
                            <p class="text-3xl font-bold {{ $product->stock_quantity <= $product->threshold_quantity ? 'text-warning' : 'text-success' }}">
                                {{ number_format($product->stock_quantity) }}
                            </p>
                            <p class="text-text-secondary text-sm mt-1">bags available</p>
                        </div>
                    </div>

                    <!-- Stock Progress Bar -->
                    <div class="mt-6">
                        @php
                            $stockPercentage = $product->stock_quantity > 0 ? min(100, ($product->stock_quantity / max($product->threshold_quantity * 2, 1)) * 100) : 0;
                        @endphp
                        <div class="flex items-center justify-between text-sm mb-2">
                            <span class="text-text-secondary">Stock Level Progress</span>
                            <span class="text-text-primary font-medium">{{ number_format($stockPercentage, 1) }}%</span>
                        </div>
                        <div class="w-full bg-muted rounded-full h-2">
                            <div class="h-2 rounded-full transition-all duration-300 {{ $product->stock_quantity <= $product->threshold_quantity ? 'bg-gradient-to-r from-warning to-error' : 'bg-gradient-to-r from-success to-accent' }}"
                                 style="width: {{ $stockPercentage }}%"></div>
                        </div>
                        <div class="flex justify-between text-xs text-text-secondary mt-1">
                            <span>0</span>
                            <span>Threshold: {{ $product->threshold_quantity }}</span>
                            <span>{{ $product->threshold_quantity * 2 }}+</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Stock Status Card -->
            <div class="bg-card rounded-xl shadow-sm border border-border overflow-hidden">
                <!-- Card Header -->
                <div class="bg-muted px-6 py-4 border-b border-border">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            @if ($product->stock_quantity === 0)
                                <div class="w-8 h-8 bg-error/10 rounded-lg flex items-center justify-center">
                                    <flux:icon.x-circle class="w-5 h-5 text-error" />
                                </div>
                            @elseif ($product->stock_quantity <= $product->threshold_quantity)
                                <div class="w-8 h-8 bg-warning/10 rounded-lg flex items-center justify-center">
                                    <flux:icon.exclamation-triangle class="w-5 h-5 text-warning" />
                                </div>
                            @else
                                <div class="w-8 h-8 bg-success/10 rounded-lg flex items-center justify-center">
                                    <flux:icon.check-circle class="w-5 h-5 text-success" />
                                </div>
                            @endif
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-semibold text-text-primary">Stock Status</h3>
                            <p class="text-text-secondary text-sm">Current inventory status</p>
                        </div>
                    </div>
                </div>

                <!-- Status Content -->
                <div class="p-6">
                    @if ($product->stock_quantity === 0)
                        <div class="text-center">
                            <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-error/10 text-error mb-4">
                                <flux:icon.x-circle class="w-4 h-4 mr-2" />
                                Out of Stock
                            </div>
                            <p class="text-text-secondary text-sm">This product is currently unavailable. Consider restocking immediately.</p>
                        </div>
                    @elseif ($product->stock_quantity <= $product->threshold_quantity)
                        <div class="text-center">
                            <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-warning/10 text-warning mb-4">
                                <flux:icon.exclamation-triangle class="w-4 h-4 mr-2" />
                                Low Stock Warning
                            </div>
                            <p class="text-text-secondary text-sm mb-4">Stock level is at or below the threshold of {{ $product->threshold_quantity }} bags.</p>
                            <div class="bg-warning/10 border border-warning/20 rounded-lg p-3">
                                <p class="text-warning text-sm font-medium">Action Required</p>
                                <p class="text-text-secondary text-xs mt-1">Consider reordering to maintain adequate inventory levels.</p>
                            </div>
                        </div>
                    @else
                        <div class="text-center">
                            <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-success/10 text-success mb-4">
                                <flux:icon.check-circle class="w-4 h-4 mr-2" />
                                In Stock
                            </div>
                            <p class="text-text-secondary text-sm">Stock levels are healthy and above the minimum threshold.</p>
                        </div>
                    @endif

                    <!-- Threshold Info -->
                    <div class="mt-4 pt-4 border-t border-border">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-text-secondary">Minimum Threshold:</span>
                            <span class="text-text-primary font-medium">{{ $product->threshold_quantity }} bags</span>
                        </div>
                        <div class="flex items-center justify-between text-sm mt-2">
                            <span class="text-text-secondary">Available Stock:</span>
                            <span class="text-text-primary font-medium">{{ $product->stock_quantity }} bags</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Record Information Card -->
            <div class="bg-card rounded-xl shadow-sm border border-border overflow-hidden">
                <!-- Card Header -->
                <div class="bg-muted px-6 py-4 border-b border-border">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-secondary/10 rounded-lg flex items-center justify-center">
                                <flux:icon.clock class="w-5 h-5 text-secondary" />
                            </div>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-semibold text-text-primary">Record Information</h3>
                            <p class="text-text-secondary text-sm">Creation and modification dates</p>
                        </div>
                    </div>
                </div>

                <!-- Record Content -->
                <div class="p-6 space-y-4">
                    <div>
                        <label class="text-sm font-medium text-text-secondary">Created Date</label>
                        <p class="text-text-primary mt-1">{{ $product->created_at->format('M j, Y') }}</p>
                        <p class="text-text-secondary text-sm">{{ $product->created_at->format('g:i A') }}</p>
                    </div>
                    
                    <div class="border-t border-border pt-4">
                        <label class="text-sm font-medium text-text-secondary">Last Updated</label>
                        <p class="text-text-primary mt-1">{{ $product->updated_at->format('M j, Y') }}</p>
                        <p class="text-text-secondary text-sm">{{ $product->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="bg-card rounded-xl shadow-sm border border-border overflow-hidden">
                <!-- Card Header -->
                <div class="bg-muted px-6 py-4 border-b border-border">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-accent/10 rounded-lg flex items-center justify-center">
                                <flux:icon.cog-6-tooth class="w-5 h-5 text-accent" />
                            </div>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-semibold text-text-primary">Quick Actions</h3>
                            <p class="text-text-secondary text-sm">Management options</p>
                        </div>
                    </div>
                </div>

                <!-- Actions Content -->
                <div class="p-6 space-y-3">
                    <flux:button href="{{ route('admin.products.edit', $product) }}" variant="outline" class="w-full" icon="pencil">
                        Edit Product
                    </flux:button>
                    
                    <flux:button href="{{ route('admin.products.index') }}" variant="ghost" class="w-full" icon="arrow-left">
                        Back to Products
                    </flux:button>
                </div>
            </div>
        </div>
    </div>
</div>
