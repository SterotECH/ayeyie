<x-ui.admin-page-layout
    title="Pickup Management"
    description="Monitor and manage customer order pickups"
    :breadcrumbs="[['label' => 'Pickup Management']]"
    :stats="[
        ['label' => 'Pending Pickups', 'value' => number_format($stats['pending']), 'icon' => 'truck', 'iconBg' => 'bg-warning/10', 'iconColor' => 'text-warning'],
        ['label' => 'Completed Today', 'value' => number_format($stats['today']), 'icon' => 'check-circle', 'iconBg' => 'bg-success/10', 'iconColor' => 'text-success'],
        ['label' => 'Overdue Pickups', 'value' => number_format($stats['overdue']), 'icon' => 'exclamation-triangle', 'iconBg' => 'bg-error/10', 'iconColor' => 'text-error'],
        ['label' => 'Total Completed', 'value' => number_format($stats['completed']), 'icon' => 'archive-box', 'iconBg' => 'bg-primary/10', 'iconColor' => 'text-primary']
    ]"
    :show-filters="true"
    search-placeholder="Search by order ID, receipt code, or customer name..."
    :has-active-filters="$search || array_filter($filters)"
>
    <x-slot:actions>
        <flux:button href="{{ route('staff.orders.verify') }}" variant="primary" icon="qr-code" wire:navigate>
            Verify Pickup
        </flux:button>
    </x-slot:actions>

    <x-slot:filterSlot>
        <!-- Status Filter -->
        <div>
            <flux:field>
                <flux:label>Status</flux:label>
                <flux:select wire:model.live="filters.status" placeholder="All Statuses">
                    <flux:select.option value="pending">Pending</flux:select.option>
                    <flux:select.option value="completed">Completed</flux:select.option>
                </flux:select>
            </flux:field>
        </div>

        <!-- Date Range Filter -->
        <div>
            <flux:field>
                <flux:label>Date Range</flux:label>
                <flux:select wire:model.live="filters.dateRange" placeholder="All Dates">
                    <flux:select.option value="today">Today</flux:select.option>
                    <flux:select.option value="week">This Week</flux:select.option>
                    <flux:select.option value="overdue">Overdue</flux:select.option>
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
            ['label' => 'Order Details', 'field' => 'pickup_id', 'sortable' => false],
            ['label' => 'Customer', 'field' => 'customer', 'sortable' => false],
            ['label' => 'Status', 'field' => 'pickup_status', 'sortable' => true],
            ['label' => 'Pickup Date', 'field' => 'pickup_date', 'sortable' => true],
            ['label' => 'Actions']
        ]"
        :items="$pickups"
        empty-title="No Pickups Found"
        empty-description="No pickups match your current filters"
        :has-active-filters="$search || array_filter($filters)"
        :sort-by="$sortBy"
        :sort-direction="$sortDirection"
    >
        @foreach($pickups as $pickup)
            <tr class="hover:bg-muted transition-colors {{ $pickup->pickup_status === 'pending' && $pickup->pickup_date < now() ? 'bg-error/5' : '' }}">
                <!-- Order Details -->
                <td class="px-6 py-4">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-semibold text-text-primary">#{{ $pickup->receipt->transaction->transaction_id }}</span>
                            <span class="text-xs bg-muted text-text-secondary px-2 py-1 rounded-full font-mono">{{ $pickup->receipt->receipt_code }}</span>
                        </div>
                        <div class="text-xs text-text-secondary">
                            {{ $pickup->receipt->transaction->items->count() }} item(s) • ₵{{ number_format($pickup->receipt->transaction->total_amount, 2) }}
                        </div>
                        <div class="text-xs text-text-secondary">
                            Ordered: {{ $pickup->receipt->transaction->transaction_date->format('M j, g:i A') }}
                        </div>
                    </div>
                </td>

                <!-- Customer -->
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-8 w-8">
                            <div class="h-8 w-8 rounded-full bg-primary/10 flex items-center justify-center">
                                <span class="text-xs font-medium text-primary">
                                    {{ $pickup->receipt->transaction->customer ? strtoupper(substr($pickup->receipt->transaction->customer->name, 0, 2)) : 'WI' }}
                                </span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-medium text-text-primary">
                                {{ $pickup->receipt->transaction->customer?->name ?? 'Walk-in Customer' }}
                            </div>
                            @if($pickup->receipt->transaction->customer?->phone)
                                <div class="text-sm text-text-secondary">{{ $pickup->receipt->transaction->customer->phone }}</div>
                            @endif
                        </div>
                    </div>
                </td>

                <!-- Status -->
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($pickup->pickup_status === 'completed')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success/10 text-success">
                            <flux:icon name="check-circle" class="w-3 h-3 mr-1" />
                            Completed
                        </span>
                    @elseif($pickup->pickup_date < now())
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-error/10 text-error">
                            <flux:icon name="exclamation-triangle" class="w-3 h-3 mr-1" />
                            Overdue
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning/10 text-warning">
                            <flux:icon name="clock" class="w-3 h-3 mr-1" />
                            Pending
                        </span>
                    @endif
                </td>

                <!-- Pickup Date -->
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <div class="text-text-primary font-medium">
{{--                        {{ optional($pickup->pickup_date->format('M j, Y')) ?? 'N/A' }}--}}
                    </div>
                    <div class="text-text-secondary text-xs">
{{--                        {{ optional($pickup->pickup_date->format('g:i A')) ?? 'N/A' }}--}}
                        @if($pickup->pickup_status === 'pending')
{{--                            <span class="ml-1">({{ $pickup->pickup_date->diffForHumans() }})</span>--}}
                        @endif
                    </div>
                    @if($pickup->pickup_status === 'completed' && $pickup->user)
                        <div class="text-xs text-text-secondary mt-1">
                            Completed by: {{ $pickup->user->name }}
                        </div>
                    @endif
                </td>

                <!-- Actions -->
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex items-center justify-end space-x-2">
                        @if($pickup->pickup_status === 'pending')
                            <flux:button
                                href="{{ route('staff.orders.verify', ['receipt_code' => $pickup->receipt->receipt_code, 'transaction_id' => $pickup->receipt->transaction->transaction_id]) }}"
                                variant="ghost"
                                size="sm"
                                icon="check-circle"
                                title="Process Pickup"
                                wire:navigate
                            />
                        @endif

                        @if($pickup->receipt->transaction->payment_status === 'pending')
                            <flux:button
                                href="{{ route('staff.transactions.process-payment', ['receipt_code' => $pickup->receipt->receipt_code]) }}"
                                variant="ghost"
                                size="sm"
                                icon="currency-dollar"
                                title="Process Payment"
                                wire:navigate
                            />
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach

        {{-- Empty Action Slot --}}
        <x-slot:emptyAction>
            <div class="text-center">
                <p class="text-text-secondary text-sm mb-4">No pickups scheduled yet.</p>
                <flux:button href="{{ route('staff.orders.verify') }}" variant="primary" icon="qr-code" wire:navigate>
                    Start Processing Orders
                </flux:button>
            </div>
        </x-slot:emptyAction>
    </x-ui.admin-table>

    <!-- Pagination -->
    <x-ui.admin-pagination
        :items="$pickups"
        item-name="pickups"
        :has-active-filters="$search || array_filter($filters)"
    />
</x-ui.admin-page-layout>
