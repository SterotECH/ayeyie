<x-ui.admin-page-layout
    title="Stock Alerts"
    description="Monitor and manage inventory alerts and stock levels"
    :breadcrumbs="[['label' => 'Stock Alerts']]"
    :stats="[
        ['label' => 'Critical Alerts', 'value' => number_format($stats['critical']), 'icon' => 'exclamation-triangle', 'iconBg' => 'bg-error/10', 'iconColor' => 'text-error'],
        ['label' => 'Total Unresolve', 'value' => number_format($stats['total_unresolved']), 'icon' => 'exclamation-circle', 'iconBg' => 'bg-warning/10', 'iconColor' => 'text-warning'],
        ['label' => 'Out of Stock', 'value' => number_format($stats['out_of_stock']), 'icon' => 'x-circle', 'iconBg' => 'bg-error/10', 'iconColor' => 'text-error'],
        ['label' => 'Resolved Today', 'value' => number_format($stats['resolved_today']), 'icon' => 'bell', 'iconBg' => 'bg-primary/10', 'iconColor' => 'text-primary']
    ]"
    :show-filters="true"
    search-placeholder="Search products..."
    :has-active-filters="$search || array_filter($filters)"
>
    <x-slot:actions>
{{--        <flux:button href="{{ route('staff.products.index') }}" variant="outline" icon="cube" wire:navigate>--}}
{{--            Manage Stock--}}
{{--        </flux:button>--}}
    </x-slot:actions>

    <x-slot:filterSlot>
        <!-- Severity Filter -->
        <div>
            <flux:field>
                <flux:label>Alert Level</flux:label>
                <flux:select wire:model.live="filters.severity" placeholder="All Levels">
                    <flux:select.option value="critical">Critical</flux:select.option>
                    <flux:select.option value="low">Low Stock</flux:select.option>
                    <flux:select.option value="out_of_stock">Out of Stock</flux:select.option>
                </flux:select>
            </flux:field>
        </div>

        <!-- Status Filter -->
        <div>
            <flux:field>
                <flux:label>Status</flux:label>
                <flux:select wire:model.live="filters.status" placeholder="All Statuses">
                    <flux:select.option value="active">Active</flux:select.option>
                    <flux:select.option value="acknowledged">Acknowledged</flux:select.option>
                    <flux:select.option value="resolved">Resolved</flux:select.option>
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
                </flux:select>
            </flux:field>
        </div>
    </x-slot:filterSlot>

    <!-- Main Table -->
    <x-ui.admin-table
        :headers="[
            ['label' => 'Product', 'field' => 'product', 'sortable' => false],
            ['label' => 'Alert Level', 'field' => 'alert_level', 'sortable' => true],
            ['label' => 'Stock Status', 'field' => 'current_quantity', 'sortable' => false],
            ['label' => 'Status', 'field' => 'status', 'sortable' => true],
            ['label' => 'Triggered', 'field' => 'triggered_at', 'sortable' => true],
            ['label' => 'Actions']
        ]"
        :items="$alerts"
        empty-title="No Stock Alerts"
        empty-description="No stock alerts match your current filters"
        :has-active-filters="$search || array_filter($filters)"
        :sort-by="$sortBy"
        :sort-direction="$sortDirection"
    >
        @foreach($alerts as $alert)
            <tr class="hover:bg-muted transition-colors {{ $alert->alert_level === 'critical' || $alert->alert_level === 'out_of_stock' ? 'bg-error/5' : ($alert->alert_level === 'low' ? 'bg-warning/5' : '') }}">
                <!-- Product -->
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            <div class="h-10 w-10 rounded-lg bg-primary/10 flex items-center justify-center">
                                <flux:icon name="cube" class="w-5 h-5 text-primary" />
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-text-primary">{{ $alert->product->name }}</div>
                            <div class="text-sm text-text-secondary">SKU: {{ $alert->product->sku }}</div>
                        </div>
                    </div>
                </td>

                <!-- Alert Level -->
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($alert->alert_level === 'critical')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-error/10 text-error">
                            <flux:icon name="exclamation-triangle" class="w-3 h-3 mr-1" />
                            Critical
                        </span>
                    @elseif($alert->alert_level === 'out_of_stock')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-error/10 text-error">
                            <flux:icon name="x-circle" class="w-3 h-3 mr-1" />
                            Out of Stock
                        </span>
                    @elseif($alert->alert_level === 'low')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning/10 text-warning">
                            <flux:icon name="exclamation-circle" class="w-3 h-3 mr-1" />
                            Low Stock
                        </span>
                    @endif
                </td>

                <!-- Stock Status -->
                <td class="px-6 py-4">
                    <div class="space-y-1">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-text-secondary">Current:</span>
                            <span class="font-semibold {{ $alert->current_quantity <= 0 ? 'text-error' : ($alert->current_quantity <= $alert->threshold ? 'text-warning' : 'text-success') }}">
                                {{ number_format($alert->current_quantity) }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-text-secondary">Threshold:</span>
                            <span class="text-text-primary">{{ number_format($alert->threshold) }}</span>
                        </div>
                        @if($alert->getShortageAmount() > 0)
                            <div class="text-xs text-error">
                                Shortage: {{ number_format($alert->getShortageAmount()) }} units
                            </div>
                        @endif
                    </div>
                </td>

                <!-- Status -->
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($alert->status === 'active')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-error/10 text-error">
                            <flux:icon name="bell" class="w-3 h-3 mr-1" />
                            Active
                        </span>
                    @elseif($alert->status === 'acknowledged')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning/10 text-warning">
                            <flux:icon name="eye" class="w-3 h-3 mr-1" />
                            Acknowledged
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success/10 text-success">
                            <flux:icon name="check-circle" class="w-3 h-3 mr-1" />
                            Resolved
                        </span>
                    @endif
                </td>

                <!-- Triggered -->
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <div class="text-text-primary">{{ $alert->triggered_at->format('M j, Y') }}</div>
                    <div class="text-text-secondary text-xs">{{ $alert->triggered_at->format('g:i A') }}</div>
                    <div class="text-text-secondary text-xs">{{ $alert->triggered_at->diffForHumans() }}</div>

                    @if($alert->status === 'acknowledged' && $alert->acknowledgedBy)
                        <div class="text-xs text-text-secondary mt-1">
                            By: {{ $alert->acknowledgedBy->name }}
                        </div>
                    @elseif($alert->status === 'resolved' && $alert->resolvedBy)
                        <div class="text-xs text-text-secondary mt-1">
                            By: {{ $alert->resolvedBy->name }}
                        </div>
                    @endif
                </td>

                <!-- Actions -->
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex items-center justify-end space-x-2">
                        @if($alert->status === 'active')
                            <flux:button
                                wire:click="acknowledgeAlert({{ $alert->alert_id }})"
                                variant="ghost"
                                size="sm"
                                icon="eye"
                                title="Acknowledge Alert"
                            />
                            <flux:button
                                wire:click="resolveAlert({{ $alert->alert_id }})"
                                variant="ghost"
                                size="sm"
                                icon="check-circle"
                                title="Mark as Resolved"
                            />
                        @elseif($alert->status === 'acknowledged')
                            <flux:button
                                wire:click="resolveAlert({{ $alert->alert_id }})"
                                variant="ghost"
                                size="sm"
                                icon="check-circle"
                                title="Mark as Resolved"
                            />
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach

        {{-- Empty Action Slot --}}
        <x-slot:emptyAction>
            <div class="text-center">
                <p class="text-text-secondary text-sm mb-4">No stock alerts found.</p>
                <p class="text-text-secondary text-xs">Stock alerts will appear here when inventory levels drop below thresholds.</p>
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
