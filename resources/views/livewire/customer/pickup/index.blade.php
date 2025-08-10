<x-ui.admin-page-layout
    title="My Pickups"
    description="Track and manage your order pickups"
    :breadcrumbs="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Pickups']
    ]"
    :stats="[
        ['label' => 'Total Pickups', 'value' => number_format($stats['total_pickups']), 'icon' => 'truck', 'iconBg' => 'bg-primary/10', 'iconColor' => 'text-primary'],
        ['label' => 'Pending', 'value' => number_format($stats['pending_pickups']), 'icon' => 'clock', 'iconBg' => 'bg-warning/10', 'iconColor' => 'text-warning'],
        ['label' => 'Completed', 'value' => number_format($stats['completed_pickups']), 'icon' => 'check-circle', 'iconBg' => 'bg-success/10', 'iconColor' => 'text-success'],
        ['label' => 'Today', 'value' => number_format($stats['todays_pickups']), 'icon' => 'calendar', 'iconBg' => 'bg-info/10', 'iconColor' => 'text-info']
    ]"
    :show-filters="true"
    search-placeholder="Search by pickup ID or receipt code..."
    :has-active-filters="$search || array_filter($filters)"
>
    <x-slot:actions>
        <flux:button href="{{ route('customers.orders.index') }}" variant="outline" icon="shopping-bag">
            View Orders
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
                <flux:select wire:model.live="filters.dateRange" placeholder="All Time">
                    <flux:select.option value="today">Today</flux:select.option>
                    <flux:select.option value="week">This Week</flux:select.option>
                    <flux:select.option value="month">This Month</flux:select.option>
                </flux:select>
            </flux:field>
        </div>

        <!-- Per Page -->
        <div class="flex items-end">
            <flux:field>
                <flux:label>Per Page</flux:label>
                <flux:select wire:model.live="perPage">
                    <flux:select.option value="10">10</flux:select.option>
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
            ['label' => 'Pickup ID', 'field' => 'pickup_id', 'sortable' => true],
            ['label' => 'Receipt', 'field' => 'receipt_code', 'sortable' => false],
            ['label' => 'Order Items', 'sortable' => false],
            ['label' => 'Status', 'field' => 'pickup_status', 'sortable' => true],
            ['label' => 'Pickup Date', 'field' => 'pickup_date', 'sortable' => true],
            ['label' => 'Processed By', 'sortable' => false],
            ['label' => 'Actions']
        ]"
        :items="$pickups"
        empty-title="No Pickups Found"
        empty-description="You haven't scheduled any pickups yet"
        :has-active-filters="$search || array_filter($filters)"
        :sort-by="$sortBy"
        :sort-direction="$sortDirection"
    >
        @foreach($pickups as $pickup)
            <tr class="hover:bg-muted transition-colors">
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            <div class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center">
                                <flux:icon name="truck" class="size-5 text-primary" />
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-text-primary">
                                Pickup #{{ $pickup->pickup_id }}
                            </div>
                            <div class="text-sm text-text-secondary">
                                {{ $pickup->created_at->format('M j, Y') }}
                            </div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-text-primary font-medium">{{ $pickup->receipt->receipt_code ?? 'N/A' }}</div>
                    <div class="text-sm text-text-secondary">
                        Order #{{ $pickup->receipt->transaction->transaction_id ?? 'N/A' }}
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="text-sm text-text-primary">
                        {{ $pickup->receipt->transaction->items->count() ?? 0 }} item(s)
                    </div>
                    <div class="text-sm text-text-secondary">
                        @if($pickup->receipt->transaction->items->count() > 0)
                            {{ $pickup->receipt->transaction->items->take(2)->pluck('product.name')->join(', ') }}
                            @if($pickup->receipt->transaction->items->count() > 2)
                                <span class="text-muted">+{{ $pickup->receipt->transaction->items->count() - 2 }} more</span>
                            @endif
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($pickup->pickup_status === 'pending')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning/10 text-warning">
                            <flux:icon name="clock" class="w-3 h-3 mr-1" />
                            Pending
                        </span>
                    @elseif($pickup->pickup_status === 'completed')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success/10 text-success">
                            <flux:icon name="check-circle" class="w-3 h-3 mr-1" />
                            Completed
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-error/10 text-error">
                            <flux:icon name="x-circle" class="w-3 h-3 mr-1" />
                            {{ ucfirst($pickup->pickup_status) }}
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary">
                    @if($pickup->pickup_date)
                        <div>{{ $pickup->pickup_date->format('M j, Y') }}</div>
                        <div class="text-xs">{{ $pickup->pickup_date->format('g:i A') }}</div>
                    @else
                        <div class="text-muted">Not yet picked up</div>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($pickup->user)
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-6 w-6">
                                <div class="h-6 w-6 rounded-full bg-secondary/10 flex items-center justify-center">
                                    <span class="text-xs font-medium text-secondary">
                                        {{ substr($pickup->user->name, 0, 1) }}
                                    </span>
                                </div>
                            </div>
                            <div class="ml-2">
                                <div class="text-sm text-text-primary">{{ $pickup->user->name }}</div>
                            </div>
                        </div>
                    @else
                        <div class="text-sm text-muted">Not assigned</div>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex items-center justify-end space-x-2">
                        <flux:button
                            href="{{ route('customers.pickups.show', $pickup) }}"
                            variant="ghost"
                            size="sm"
                            icon="eye"
                            title="View Pickup Details"
                            wire:navigate
                        />
                        @if($pickup->pickup_status === 'pending')
                            <flux:button
                                variant="outline"
                                size="sm"
                                icon="phone"
                                title="Contact Store"
                            >
                                Contact
                            </flux:button>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach

        {{-- Empty Action Slot --}}
        <x-slot:emptyAction>
            <flux:button href="{{ route('customers.orders.create') }}" variant="primary" icon="plus">
                Place Your First Order
            </flux:button>
        </x-slot:emptyAction>
    </x-ui.admin-table>

    <!-- Pagination -->
    <x-ui.admin-pagination
        :items="$pickups"
        item-name="pickups"
        :has-active-filters="$search || array_filter($filters)"
    />
</x-ui.admin-page-layout>
