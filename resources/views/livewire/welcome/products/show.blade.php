<?php

use Livewire\Volt\Component;
use function Livewire\Volt\{state};
use App\Models\Product;

new class extends Component {
    public Product $product;

    public function with(Product $product): array
    {
        return [
            'product' => $product,
        ];
    }
}; ?>

<div class="container mx-auto px-4 py-8">
    <div class="overflow-hidden rounded-lg bg-white shadow-lg">
        <div class="md:flex">
            <div class="md:w-1/2">
                <div class="flex h-full items-center justify-center bg-gray-200">
                    <svg class="h-24 w-24 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
            <div class="p-8 md:w-1/2">
                <div class="text-sm font-semibold uppercase tracking-wide text-indigo-600">Poetry Feed Product</div>
                <h2 class="mt-2 text-3xl font-bold leading-tight">{{ $product->name }}</h2>
                <div class="mt-4">
                    <span class="text-gray-600">Price:</span>
                    <span
                        class="ml-2 text-2xl font-bold text-indigo-600">${{ number_format($product->price, 2) }}</span>
                </div>
                <div class="mt-2">
                    <span class="text-gray-600">Availability:</span>
                    <span class="{{ $product->stock_quantity > 0 ? 'text-green-600' : 'text-red-600' }} ml-2">
                        {{ $product->stock_quantity > 0 ? 'In Stock (' . $product->stock_quantity . ')' : 'Out of Stock' }}
                    </span>
                </div>
                <div class="mt-6">
                    <h3 class="text-lg font-semibold">Description</h3>
                    <p class="mt-2 text-gray-600">{{ $product->description }}</p>
                </div>

                @if ($product->stock_quantity > 0)
                    <form class="mt-8" {{-- action="{{ route('cart.add') }}" --}} method="POST">
                        @csrf
                        <input name="product_id" type="hidden" value="{{ $product->product_id }}">
                        <div class="mb-4 flex items-center">
                            <label class="mr-4 text-gray-700" for="quantity">Quantity:</label>
                            <select class="form-select rounded-md border-gray-300" id="quantity" name="quantity">
                                @for ($i = 1; $i <= min(5, $product->stock_quantity); $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <flux:button class="flex w-full items-center justify-center" type="submit" variant="primary">
                            <div class="flex items-center justify-center">
                                <flux:icon.shopping-cart class="mr-2 size-6" />
                                Add to Cart
                            </div>
                        </flux:button>
                    </form>
                @else
                    <div class="mt-8">
                        <button class="w-full cursor-not-allowed rounded-md bg-gray-400 py-3 text-white" disabled>
                            Out of Stock
                        </button>
                    </div>
                @endif

                <div class="mt-6">
                    <a class="flex items-center text-indigo-600 hover:text-indigo-800" {{-- href="{{ route('products.index') }}" --}}>
                        <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Products
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
