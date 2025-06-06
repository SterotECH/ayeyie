<?php

use Livewire\Volt\Component;
use function Livewire\Volt\{state, mount};
use App\Models\Product;

new class extends Component {
    public function with(): array
    {
        return [
            'products' => Product::paginate(12),
        ];
    }
}; ?>

<div class="container mx-auto px-4 py-8">
    <h1 class="mb-8 text-center text-3xl font-bold">Poetry Feed Products</h1>

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="relative mb-4 rounded border border-green-400 bg-green-100 px-4 py-3 text-green-700" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        @forelse ($products as $product)
            <div
                class="overflow-hidden rounded-lg bg-zinc-50 shadow-md transition-shadow duration-300 hover:shadow-lg dark:bg-zinc-800">
                <div class="p-6">
                    <h2 class="mb-2 text-xl font-bold">{{ $product->name }}</h2>
                    <p class="mb-4 text-gray-600">{{ Str::limit($product->description, 100) }}</p>
                    <div class="flex items-center justify-between">
                        <span class="text-accent text-2xl font-bold">${{ number_format($product->price, 2) }}</span>
                        <span class="text-accent-content text-sm">{{ $product->stock_quantity }} in stock</span>
                    </div>
                    <div class="mt-4">
                        <flux:button class="w-full" href="{{ route('welcome.products.show', $product) }}"
                            variant="primary">
                            View Details
                        </flux:button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <p class="text-center text-xl text-gray-500">No products available.</p>
            </div>
        @endforelse
    </div>
</div>
