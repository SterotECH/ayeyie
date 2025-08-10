<x-ui.admin-page-layout
    title="Stock Alerts"
    description="Monitor products with low stock levels and manage inventory alerts"
    :breadcrumbs="[['label' => 'Stock Alerts']]"
    :stats="[
        ['label' => 'Total Unresolved', 'value' => number_format($stats['total_unresolved']), 'icon' => 'exclamation-triangle', 'iconBg' => 'bg-primary/10', 'iconColor' => 'text-primary'],
        ['label' => 'Critical Alerts', 'value' => number_format($stats['critical']), 'icon' => 'exclamation-triangle', 'iconBg' => 'bg-error/10', 'iconColor' => 'text-error'],
        ['label' => 'Out of Stock', 'value' => number_format($stats['out_of_stock']), 'icon' => 'x-circle', 'iconBg' => 'bg-error/10', 'iconColor' => 'text-error'],
        ['label' => 'Resolved Today', 'value' => number_format($stats['resolved_today']), 'icon' => 'check-circle', 'iconBg' => 'bg-success/10', 'iconColor' => 'text-success']
    ]"
    :show-filters="true"
    search-placeholder="Search products or alert messages..."
    :has-active-filters="$search || array_filter($filters)"
>
    <x-slot:filterSlot>
        <!-- Date Filter -->
        <div>
            <flux:field>
                <flux:label>Date Range</flux:label>
                <flux:select wire:model.live="filters.dateFilter" placeholder="All Dates">
                    <flux:select.option value="today">Today</flux:select.option>
                    <flux:select.option value="week">This Week</flux:select.option>
                    <flux:select.option value="month">This Month</flux:select.option>
                </flux:select>
            </flux:field>
        </div>

        <!-- Alert Level Filter -->
        <div>
            <flux:field>
                <flux:label>Alert Level</flux:label>
                <flux:select wire:model.live="filters.thresholdFilter" placeholder="All Levels">
                    <flux:select.option value="critical">Critical & Out of Stock</flux:select.option>
                    <flux:select.option value="warning">Medium & Low</flux:select.option>
                </flux:select>
            </flux:field>
        </div>

        <!-- Resolution Status Filter -->
        <div>
            <flux:field>
                <flux:label>Status</flux:label>
                <flux:select wire:model.live="filters.resolvedFilter" placeholder="All Status">
                    <flux:select.option value="0">Unresolved</flux:select.option>
                    <flux:select.option value="1">Resolved</flux:select.option>
                </flux:select>
            </flux:field>
        </div>

        <!-- Actions -->
        <div class="flex items-end gap-2">
            <flux:button wire:click="checkAllProductsStock" variant="primary" size="sm">
                <flux:icon name="arrow-path" class="size-4" />
                Check All Products
            </flux:button>
            <flux:button wire:click="toggleBulkActions" variant="outline" size="sm">
                <flux:icon name="check" class="size-4" />
                Bulk Actions
            </flux:button>
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

    {{-- Bulk Actions Bar --}}
    @if($showBulkActions)
        <div class="bg-warning/10 border border-warning/20 rounded-lg p-4 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <span class="text-sm font-medium">{{ count($selectedAlerts) }} alert(s) selected</span>
                    <flux:button wire:click="resolveBulkAlerts" variant="primary" size="sm">
                        Resolve Selected
                    </flux:button>
                </div>
                <flux:button wire:click="toggleBulkActions" variant="outline" size="sm">
                    Cancel
                </flux:button>
            </div>
        </div>
    @endif

    <!-- Main Table -->
    <x-ui.admin-table 
        :headers="array_merge(
            $showBulkActions ? [['label' => '', 'field' => '', 'sortable' => false]] : [],
            [
                ['label' => 'Alert Level', 'field' => 'alert_level', 'sortable' => true],
                ['label' => 'Product', 'field' => 'product_name', 'sortable' => true],
                ['label' => 'Stock Info', 'field' => 'current_quantity', 'sortable' => true],
                ['label' => 'Status', 'field' => 'is_resolved', 'sortable' => true],
                ['label' => 'Triggered At', 'field' => 'triggered_at', 'sortable' => true],
                ['label' => 'Actions']
            ]
        )"
        :items="$alerts"
        empty-title="No Stock Alerts Found"
        empty-description="No stock alerts match your current filters"
        :has-active-filters="$search || array_filter($filters)"
        :sort-by="$sortBy"
        :sort-direction="$sortDirection"
    >
        @foreach($alerts as $item)
            <tr class="hover:bg-muted transition-colors">
                @if($showBulkActions)
                    <td class="px-6 py-4">
                        <flux:checkbox wire:model="selectedAlerts" value="{{ $item->alert_id }}" />
                    </td>
                @endif
                <td class="px-6 py-4">
                    <div class="flex items-center gap-2">
                        <flux:icon name="{{ $item->getAlertLevelEnum()->getIcon() }}" class="size-4 {{ $item->getAlertLevelEnum()->getColorClass() }}" />
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $item->getAlertLevelEnum()->getBadgeClass() }}">
                            {{ $item->getAlertLevelEnum()->getLabel() }}
                        </span>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            <div class="h-10 w-10 rounded-lg bg-warning/10 flex items-center justify-center">
                                <flux:icon.cube class="w-5 h-5 text-warning" />
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-text-primary">{{ $item->product->name }}</div>
                            <div class="text-sm text-text-secondary">ID: {{ $item->product->product_id }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="text-sm">
                        <div>Current: <span class="font-medium {{ $item->current_quantity <= 0 ? 'text-error' : 'text-primary' }}">{{ number_format($item->current_quantity) }}</span></div>
                        <div>Threshold: <span class="font-medium">{{ number_format($item->threshold) }}</span></div>
                        @if($item->getShortageAmount() > 0)
                            <div class="text-error text-xs">Need {{ $item->getShortageAmount() }} more</div>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4">
                    @if($item->is_resolved)
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-success/10 text-success">
                            Resolved
                        </span>
                        @if($item->resolved_at)
                            <div class="text-xs text-secondary mt-1">{{ $item->resolved_at->diffForHumans() }}</div>
                        @endif
                    @else
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-warning/10 text-warning">
                            Unresolved
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary">
                    <div>{{ $item->triggered_at->format('M j, Y') }}</div>
                    <div class="text-xs">{{ $item->triggered_at->diffForHumans() }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex items-center justify-end space-x-2">
                        @if(!$item->is_resolved)
                            <flux:button wire:click="resolveAlert({{ $item->alert_id }})" variant="primary" size="sm">
                                Resolve
                            </flux:button>
                        @endif
                        <flux:button href="{{ route('admin.stock_alerts.show', $item) }}" variant="ghost" size="sm" icon="eye" title="View Alert Details" />
                    </div>
                </td>
            </tr>
        @endforeach

        {{-- Empty Action Slot --}}
        <x-slot:emptyAction>
            <div class="text-center">
                <flux:icon name="check-circle" class="size-12 text-success mx-auto mb-4" />
                <p class="text-text-primary font-medium mb-2">No stock alerts found</p>
                <p class="text-text-secondary text-sm mb-4">No stock alerts match your current filters.</p>
                <flux:button wire:click="checkAllProductsStock" variant="primary" size="sm">
                    Check All Products
                </flux:button>
            </div>
        </x-slot:emptyAction>
    </x-ui.admin-table>

    <!-- Pagination -->
    <x-ui.admin-pagination 
        :items="$alerts" 
        item-name="alerts"
        :has-active-filters="$search || array_filter($filters)"
    />
</x-ui.admin-page-layout>
