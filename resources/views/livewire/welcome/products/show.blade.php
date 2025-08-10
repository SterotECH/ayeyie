<section class="py-20 bg-background min-h-screen">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center space-x-2 text-sm mb-8">
            <a href="/" class="text-text-secondary hover:text-primary transition-colors">
                <flux:icon.home class="w-4 h-4"/>
            </a>
            <flux:icon.chevron-right class="w-4 h-4 text-text-secondary"/>
            <a href="{{ route('welcome.products.index') }}"
               class="text-text-secondary hover:text-primary transition-colors">Products</a>
            <flux:icon.chevron-right class="w-4 h-4 text-text-secondary"/>
            <span class="text-primary font-medium">{{ $product->name }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">
            <!-- Product Image/Visual -->
            <div class="space-y-6">
                <!-- Main Product Display -->
                <div
                    class="relative bg-gradient-to-br from-primary/10 via-accent/5 to-secondary/10 rounded-3xl p-12 text-center aspect-square flex items-center justify-center">
                    <div class="text-center">
                        <div class="w-32 h-32 bg-primary/20 rounded-3xl flex items-center justify-center mx-auto mb-6">
                            <flux:icon.cube class="w-16 h-16 text-primary"/>
                        </div>
                    </div>

                    <!-- Stock Status Badge -->
                    <div class="absolute top-6 right-6">
                        @if($product->stock_quantity > $product->threshold_quantity)
                            <div
                                class="bg-success text-background px-4 py-2 rounded-full text-sm font-semibold shadow-lg">
                                <flux:icon.check class="w-4 h-4 inline mr-1"/>
                                In Stock
                            </div>
                        @elseif($product->stock_quantity > 0)
                            <div
                                class="bg-warning text-background px-4 py-2 rounded-full text-sm font-semibold shadow-lg">
                                <flux:icon.exclamation-triangle class="w-4 h-4 inline mr-1"/>
                                Low Stock
                            </div>
                        @else
                            <div
                                class="bg-error text-background px-4 py-2 rounded-full text-sm font-semibold shadow-lg">
                                <flux:icon.x-mark class="w-4 h-4 inline mr-1"/>
                                Out of Stock
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Product Features -->
                <div class="grid grid-cols-3 gap-4">
                    <div class="bg-card border border-border rounded-2xl p-4 text-center">
                        <flux:icon.shield-check class="w-6 h-6 text-success mx-auto mb-2"/>
                        <span class="text-text-secondary text-sm font-medium">Quality Assured</span>
                    </div>
                    <div class="bg-card border border-border rounded-2xl p-4 text-center">
                        <flux:icon.truck class="w-6 h-6 text-primary mx-auto mb-2"/>
                        <span class="text-text-secondary text-sm font-medium">Fast Delivery</span>
                    </div>
                    <div class="bg-card border border-border rounded-2xl p-4 text-center">
                        <flux:icon.heart class="w-6 h-6 text-accent mx-auto mb-2"/>
                        <span class="text-text-secondary text-sm font-medium">Healthy Birds</span>
                    </div>
                </div>
            </div>

            <!-- Product Information -->
            <div class="space-y-8">
                <!-- Product Header -->
                <div>
                    <div
                        class="inline-flex items-center rounded-full bg-primary/10 px-4 py-2 text-sm font-medium text-primary mb-4">
                        <flux:icon.sparkles class="mr-2 h-4 w-4"/>
                        Premium Poultry Feed
                    </div>
                    <h1 class="text-4xl font-bold text-text-primary mb-4">{{ $product->name }}</h1>
                    <p class="text-text-secondary text-lg leading-relaxed">{{ $product->description }}</p>
                </div>

                <!-- Pricing Section -->
                <div class="bg-card border border-border rounded-2xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <span
                                class="text-4xl font-bold text-primary">₵{{ number_format($product->price, 2) }}</span>
                            <span class="text-text-secondary text-lg block">per 25kg bag</span>
                        </div>
                        <div class="text-right">
                            <span class="text-text-primary font-semibold text-lg">{{ $product->stock_quantity }}</span>
                            <span class="text-text-secondary text-sm block">bags available</span>
                        </div>
                    </div>

                    <!-- Stock Progress Bar -->
                    <div class="mb-4">
                        @php
                            $stockPercentage = $product->stock_quantity > 0 ? min(100, ($product->stock_quantity / ($product->threshold_quantity * 2)) * 100) : 0;
                        @endphp
                        <div class="flex items-center justify-between text-sm mb-2">
                            <span class="text-text-secondary">Stock Level</span>
                            <span class="text-text-primary font-medium">{{ number_format($stockPercentage, 1) }}%</span>
                        </div>
                        <div class="w-full bg-muted rounded-full h-2">
                            <div
                                class="bg-gradient-to-r from-primary to-accent h-2 rounded-full transition-all duration-300"
                                style="width: {{ $stockPercentage }}%"></div>
                        </div>
                    </div>

                    <!-- Purchase Form -->
                    @if ($product->stock_quantity > 0)
                        <div class="space-y-4">
                            <!-- Quantity Selection -->
                            <div>
                                <flux:field>
                                    <flux:label>Quantity (bags)</flux:label>
                                    <flux:select wire:model.live="quantity" placeholder="Select quantity">
                                        @for ($i = 1; $i <= min(50, $product->stock_quantity); $i++)
                                            <flux:select.option
                                                value="{{ $i }}">{{ $i }} {{ Str::plural('bag', $i) }}</flux:select.option>
                                        @endfor
                                    </flux:select>
                                    <flux:error name="quantity"/>
                                </flux:field>

                                <!-- Total Price Display -->
                                <div class="mt-3 p-3 bg-muted rounded-lg">
                                    <div class="flex items-center justify-between">
                                        <span class="text-text-secondary">Total Price:</span>
                                        <span
                                            class="font-bold text-primary text-xl">₵{{ number_format($totalPrice, 2) }}</span>
                                    </div>
                                    <div class="text-text-secondary text-sm mt-1">
                                        {{ $quantity }} {{ Str::plural('bag', $quantity) }} ×
                                        ₵{{ number_format($product->price, 2) }} each
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-col sm:flex-row gap-4">
                                <flux:button
                                    wire:click="addToCart"
                                    variant="primary"
                                    class="flex-1" icon="shopping-cart"
                                    :disabled="$quantity > $product->stock_quantity">
                                    Add to Cart
                                </flux:button>

                                <flux:button
                                    wire:click="quickOrder"
                                    variant="outline"
                                    icon="bolt"
                                    class="border-secondary text-secondary hover:bg-secondary hover:text-background"
                                    :disabled="$quantity > $product->stock_quantity">
                                    Quick Order
                                </flux:button>
                            </div>

                            @if($cartItemCount > 0)
                                <div class="mt-4 p-4 bg-primary/5 rounded-lg border border-primary/20">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <flux:icon name="shopping-cart" class="w-5 h-5 text-primary mr-2" />
                                            <span class="text-primary font-medium">{{ $cartItemCount }} item(s) in cart</span>
                                        </div>
                                        @auth
                                            <flux:button
                                                wire:click="viewCart"
                                                variant="ghost"
                                                size="sm"
                                                class="text-primary hover:bg-primary/10">
                                                View Cart
                                            </flux:button>
                                        @endauth
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="space-y-4">
                            <flux:button variant="ghost" disabled class="w-full" icon="x-mark">
                                Out of Stock
                            </flux:button>
                            <p class="text-text-secondary text-sm text-center">
                                This product is currently unavailable. Contact us for restock information.
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Product Benefits -->
                <div class="bg-card border border-border rounded-2xl p-6">
                    <h3 class="text-xl font-semibold text-text-primary mb-4">
                        <flux:icon.star class="w-5 h-5 text-accent inline mr-2"/>
                        Key Benefits
                    </h3>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <flux:icon.check-circle class="w-5 h-5 text-success mr-3"/>
                            <span class="text-text-secondary">Scientifically balanced nutrition formula</span>
                        </div>
                        <div class="flex items-center">
                            <flux:icon.check-circle class="w-5 h-5 text-success mr-3"/>
                            <span class="text-text-secondary">Promotes healthy growth and egg production</span>
                        </div>
                        <div class="flex items-center">
                            <flux:icon.check-circle class="w-5 h-5 text-success mr-3"/>
                            <span class="text-text-secondary">Made from premium quality ingredients</span>
                        </div>
                        <div class="flex items-center">
                            <flux:icon.check-circle class="w-5 h-5 text-success mr-3"/>
                            <span class="text-text-secondary">Free from harmful additives and chemicals</span>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="flex items-center justify-between">
                    <flux:button variant="ghost" href="/" icon="arrow-left" wire:navigate>
                        Back to Homepage
                    </flux:button>
                </div>
            </div>
        </div>

        <div class="bg-muted rounded-3xl p-8">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-text-primary mb-2">Explore More Products</h2>
                <p class="text-text-secondary">Discover our complete range of premium feed products</p>
            </div>
            <div class="text-center">
                <flux:button variant="primary" href="{{ route('welcome.products.index') }}" wire:navigate>
                    <flux:icon.squares-2x2 class="w-5 h-5 mr-2"/>
                    Browse All Products
                </flux:button>
            </div>
        </div>
    </div>
</section>
