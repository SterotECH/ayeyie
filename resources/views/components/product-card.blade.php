@props(['product'])

<div class="group bg-card rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-2 border border-border overflow-hidden">
    <!-- Product Image/Icon -->
    <div class="relative bg-gradient-to-br from-primary/10 via-accent/5 to-secondary/10 p-8 text-center">
        <div class="w-16 h-16 bg-primary/20 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
            <flux:icon.{{ $product->category?->icon ?? 'cube' }} class="w-8 h-8 text-primary" />
        </div>

        <!-- Stock Status Badge -->
        <div class="absolute top-4 right-4">
            @if($product->stock_quantity > $product->threshold_quantity)
                <div class="bg-success text-background px-2 py-1 rounded-full text-xs font-medium">
                    In Stock
                </div>
            @elseif($product->stock_quantity > 0)
                <div class="bg-warning text-background px-2 py-1 rounded-full text-xs font-medium">
                    Low Stock
                </div>
            @else
                <div class="bg-error text-background px-2 py-1 rounded-full text-xs font-medium">
                    Out of Stock
                </div>
            @endif
        </div>
    </div>

    <!-- Product Content -->
    <div class="p-6">
        <h3 class="text-lg font-semibold text-text-primary mb-2 group-hover:text-primary transition-colors line-clamp-1">
            {{ $product->name }}
        </h3>
        <p class="text-text-secondary text-sm mb-4 leading-relaxed">
            {{ Str::limit($product->description, 80) }}
        </p>

        <!-- Price and Stock Info -->
        <div class="flex items-center justify-between mb-4">
            <div>
                <span class="text-2xl font-bold text-primary">₵{{ number_format($product->price, 2) }}</span>
                <span class="text-text-secondary text-sm block">per bag</span>
            </div>
            <div class="text-right">
                <span class="text-text-secondary text-sm block">{{ $product->stock_quantity }} bags</span>
                <span class="text-text-secondary text-xs">available</span>
            </div>
        </div>

        <!-- Action Button -->
        <flux:button icon="eye" href="{{ route('welcome.products.show', $product) }}" variant="primary" class="w-full bg-primary hover:bg-primary-hover text-background px-6 py-3 rounded-xl font-semibold transition-all duration-300 hover:scale-105 inline-flex items-center" wire:navigate>
            View Details
        </flux:button>
    </div>
</div>
