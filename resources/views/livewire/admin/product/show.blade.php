<div class="container max-w-2xl mx-auto p-6 bg-white rounded-lg shadow-md">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-accent">{{ $product->name }}</h1>
        <p class="text-accent-content mt-1">Product Details</p>
    </div>

    <!-- Product Details -->
    <div class="space-y-6">
        <!-- Description -->
        <div>
            <h2 class="text-lg font-semibold text-gray-700">Description</h2>
            <p class="mt-1 text-gray-600">
                {{ $product->description ?? 'No description available' }}
            </p>
        </div>

        <!-- Price -->
        <div>
            <h2 class="text-lg font-semibold text-gray-700">Price</h2>
            <p class="mt-1 text-gray-600">${{ number_format($product->price, 2) }}</p>
        </div>

        <!-- Stock Information -->
        <div>
            <h2 class="text-lg font-semibold text-gray-700">Stock Information</h2>
            <div class="mt-1 space-y-2">
                <p class="text-gray-600">
                    Current Stock:
                    <span class="font-medium {{ $product->stock_quantity <= $product->threshold_quantity ? 'text-red-500' : 'text-green-500' }}">
                        {{ $product->stock_quantity }}
                    </span>
                </p>
                <p class="text-gray-600">
                    Threshold: <span class="font-medium">{{ $product->threshold_quantity }}</span>
                </p>
            </div>
            @if($product->stock_quantity <= $product->threshold_quantity)
                <div class="mt-2 p-3 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 rounded">
                    Warning: Stock level is at or below threshold!
                </div>
            @endif
        </div>

        <!-- Timestamps -->
        <div>
            <h2 class="text-lg font-semibold text-gray-700">Record Information</h2>
            <div class="mt-1 space-y-2">
                <p class="text-gray-600">Created: {{ $product->created_at->format('M d, Y H:i') }}</p>
                <p class="text-gray-600">Last Updated: {{ $product->updated_at->format('M d, Y H:i') }}</p>
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="mt-8 flex justify-end">
        <a
            href="{{ route('admin.products.index') }}"
            class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-200"
            wire:navigate
        >
            Back to Products
        </a>
    </div>
</div>
