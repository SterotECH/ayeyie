<div class="p-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-accent">Edit Product: {{ $product->name }}</h1>
        <p class="text-accent-content mt-1">Modify product details</p>
    </div>

    <!-- Success Message -->
    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="submit" class="space-y-6">
        <!-- Name Field -->
        <div>
            <flux:input
                type="text"
                class="form-control @error('name') is-invalid @enderror"
                id="name"
                label="Product Name"
                wire:model="name"
                required
            />
            <p class="mt-1 text-sm text-gray-500">Enter a unique name (max 100 characters)</p>
        </div>

        <!-- Description Field -->
        <div>
            <flux:textarea
                class="form-control @error('description') is-invalid @enderror"
                id="description"
                label="Description"
                wire:model="description"
                rows="4"
            ></flux:textarea>
            <p class="mt-1 text-sm text-gray-500">Optional detailed description of the product</p>
        </div>

        <!-- Price Field -->
        <div>
            <flux:input
                type="number"
                step="0.01"
                label="Price"
                class="form-control @error('price') is-invalid @enderror"
                id="price"
                wire:model="price"
                required
            />
            <p class="mt-1 text-sm text-gray-500">Price per unit in Ghana Cedis (e.g., 29.99)</p>
        </div>

        <!-- Stock Quantity Field -->
        <div>
            <flux:input
                type="number"
                label="Stock Quantity"
                class="form-control @error('stock_quantity') is-invalid @enderror"
                id="stock_quantity"
                wire:model="stock_quantity"
                required
            />
            <p class="mt-1 text-sm text-gray-500">Current available inventory count</p>
        </div>

        <!-- Threshold Quantity Field -->
        <div>
            <flux:input
                type="number"
                label="Threshold Quantity"
                class="form-control @error('threshold_quantity') is-invalid @enderror"
                id="threshold_quantity"
                wire:model="threshold_quantity"
                required
            />
            <p class="mt-1 text-sm text-gray-500">Minimum stock level for low inventory alerts</p>
        </div>

        <!-- Buttons -->
        <div class="flex justify-end space-x-4">
            <flux:button
                href="{{ route('admin.products.show', $product) }}"
            >
                Cancel
            </flux:button>
            <flux:button
                type="submit"
                variant="primary"
            >
                Update Product
            </flux:button>
        </div>
    </form>
</div>
