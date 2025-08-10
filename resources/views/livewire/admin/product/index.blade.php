<x-ui.admin-page-layout
    title="Product Management"
    description="Manage your poultry feed inventory and categories"
    :breadcrumbs="[['label' => 'Products']]"
    :stats="[
        ['label' => 'Total Products', 'value' => number_format($stats['total']), 'icon' => 'squares-2x2', 'iconBg' => 'bg-primary/10', 'iconColor' => 'text-primary'],
        ['label' => 'In Stock', 'value' => number_format($stats['in_stock']), 'icon' => 'check-circle', 'iconBg' => 'bg-success/10', 'iconColor' => 'text-success'],
        ['label' => 'Low Stock', 'value' => number_format($stats['low_stock']), 'icon' => 'exclamation-triangle', 'iconBg' => 'bg-warning/10', 'iconColor' => 'text-warning'],
        ['label' => 'Out of Stock', 'value' => number_format($stats['out_of_stock']), 'icon' => 'x-circle', 'iconBg' => 'bg-error/10', 'iconColor' => 'text-error']
    ]"
    :show-filters="true"
    search-placeholder="Search by name or description..."
    :has-active-filters="$search || array_filter($filters)"
>
    <x-slot:actions>
        <flux:button href="{{ route('admin.products.create') }}" variant="primary" icon="plus">
            Add Product
        </flux:button>
    </x-slot:actions>

    <x-slot:filterSlot>
        <!-- Stock Status Filter -->
        <div>
            <flux:field>
                <flux:label>Stock Status</flux:label>
                <flux:select wire:model.live="filters.stock_status" placeholder="All Status">
                    <flux:select.option value="in_stock">In Stock</flux:select.option>
                    <flux:select.option value="low_stock">Low Stock</flux:select.option>
                    <flux:select.option value="out_of_stock">Out of Stock</flux:select.option>
                </flux:select>
            </flux:field>
        </div>

        <!-- Per Page -->
        <div class="flex items-end">
            <flux:field>
                <flux:label>Per Page</flux:label>
                <flux:select wire:model.live="perPage">
                    <flux:select.option value="15">15</flux:select.option>
                    <flux:select.option value="25">25</flux:select.option>
                    <flux:select.option value="50">50</flux:select.option>
                    <flux:select.option value="100">100</flux:select.option>
                </flux:select>
            </flux:field>
        </div>
    </x-slot:filterSlot>

    <!-- Main Table -->
    <x-ui.admin-table
        :headers="[
            ['label' => 'Product', 'field' => 'name', 'sortable' => true],
            ['label' => 'Price (₵)', 'field' => 'price', 'sortable' => true],
            ['label' => 'Stock', 'field' => 'stock_quantity', 'sortable' => true],
            ['label' => 'Status'],
            ['label' => 'Created', 'field' => 'created_at', 'sortable' => true],
            ['label' => 'Actions']
        ]"
        :items="$products"
        empty-title="No Products Found"
        empty-description="No products match your current filters"
        :has-active-filters="$search || array_filter($filters)"
        :sort-by="$sortBy"
        :sort-direction="$sortDirection"
    >
        @foreach($products as $item)
            <tr class="hover:bg-muted transition-colors">
                {{-- Table Row Template (just td elements, tr is handled by component) --}}
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            <div class="h-10 w-10 rounded-lg bg-muted flex items-center justify-center">
                                <flux:icon.cube class="h-6 w-6 text-text-secondary"/>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-text-primary max-w-xs truncate"
                                 title="{{ $item->name }}">
                                {{ $item->name }}
                            </div>
                            <div class="text-sm text-text-secondary">
                                ID: {{ $item->product_id }}
                            </div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-semibold text-text-primary">₵{{ number_format($item->price, 2) }}</div>
                    <div class="text-xs text-text-secondary">per bag</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-text-primary">{{ number_format($item->stock_quantity) }}</div>
                    <div class="text-xs text-text-secondary">of {{ number_format($item->threshold_quantity) }} min</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if ($item->stock_quantity === 0)
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-error/10 text-error">
                    <flux:icon.x-circle class="w-3 h-3 mr-1"/>
                    Out of Stock
                </span>
                    @elseif ($item->stock_quantity <= $item->threshold_quantity)
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning/10 text-warning">
                    <flux:icon.exclamation-triangle class="w-3 h-3 mr-1"/>
                    Low Stock
                </span>
                    @else
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success/10 text-success">
                    <flux:icon.check-circle class="w-3 h-3 mr-1"/>
                    In Stock
                </span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary">
                    <div>{{ $item->created_at->format('M j, Y') }}</div>
                    <div class="text-xs">{{ $item->created_at->format('g:i A') }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex items-center justify-end space-x-2">
                        <flux:button href="{{ route('admin.products.show', $item) }}" variant="ghost" size="sm"
                                     icon="eye" title="View Product"/>
                        <flux:button href="{{ route('admin.products.edit', $item) }}" variant="ghost" size="sm"
                                     icon="pencil" title="Edit Product"/>
                    </div>
                </td>
            </tr>
        @endforeach

        {{-- Empty Action Slot --}}
        <x-slot:emptyAction>
            <flux:button href="{{ route('admin.products.create') }}" variant="primary" icon="plus">
                Add First Product
            </flux:button>
        </x-slot:emptyAction>
    </x-ui.admin-table>

    <!-- Pagination -->
    <x-ui.admin-pagination
        :items="$products"
        item-name="products"
        :has-active-filters="$search || array_filter($filters)"
    />
</x-ui.admin-page-layout>
