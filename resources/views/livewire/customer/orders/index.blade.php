<x-ui.admin-page-layout
    title="My Orders"
    description="Track and manage your order history"
    :breadcrumbs="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Orders']
    ]"
    :stats="[
        ['label' => 'Total Orders', 'value' => number_format($stats['total_orders']), 'icon' => 'shopping-bag', 'iconBg' => 'bg-primary/10', 'iconColor' => 'text-primary'],
        ['label' => 'Pending', 'value' => number_format($stats['pending_orders']), 'icon' => 'clock', 'iconBg' => 'bg-warning/10', 'iconColor' => 'text-warning'],
        ['label' => 'Completed', 'value' => number_format($stats['completed_orders']), 'icon' => 'check-circle', 'iconBg' => 'bg-success/10', 'iconColor' => 'text-success'],
        ['label' => 'Total Spent', 'value' => '₵' . number_format($stats['total_spent'], 2), 'icon' => 'currency-dollar', 'iconBg' => 'bg-info/10', 'iconColor' => 'text-info']
    ]"
    :show-filters="true"
    search-placeholder="Search by order ID or product name..."
    :has-active-filters="$search || array_filter($filters)"
>
    <x-slot:actions>
        <flux:button href="{{ route('customers.orders.create') }}" variant="primary" icon="plus">
            Place New Order
        </flux:button>
    </x-slot:actions>

    <x-slot:filterSlot>
        <!-- Status Filter -->
        <div>
            <flux:field>
                <flux:label>Status</flux:label>
                <flux:select wire:model.live="filters.statusFilter" placeholder="All Statuses">
                    <flux:select.option value="pending">Pending</flux:select.option>
                    <flux:select.option value="completed">Completed</flux:select.option>
                    <flux:select.option value="failed">Failed</flux:select.option>
                </flux:select>
            </flux:field>
        </div>

        <!-- Date Range Filter -->
        <div>
            <flux:field>
                <flux:label>Date Range</flux:label>
                <flux:select wire:model.live="filters.dateFilter" placeholder="All Time">
                    <flux:select.option value="today">Today</flux:select.option>
                    <flux:select.option value="yesterday">Yesterday</flux:select.option>
                    <flux:select.option value="this_week">This Week</flux:select.option>
                    <flux:select.option value="this_month">This Month</flux:select.option>
                    <flux:select.option value="last_month">Last Month</flux:select.option>
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
            ['label' => 'Order ID', 'field' => 'transaction_id', 'sortable' => true],
            ['label' => 'Items', 'sortable' => false],
            ['label' => 'Amount', 'field' => 'total_amount', 'sortable' => true],
            ['label' => 'Payment Status', 'field' => 'payment_status', 'sortable' => true],
            ['label' => 'Payment Method', 'field' => 'payment_method', 'sortable' => true],
            ['label' => 'Order Date', 'field' => 'transaction_date', 'sortable' => true],
            ['label' => 'Actions']
        ]"
        :items="$transactions"
        empty-title="No Orders Found"
        empty-description="You haven't placed any orders yet"
        :has-active-filters="$search || array_filter($filters)"
        :sort-by="$sortBy"
        :sort-direction="$sortDirection"
    >
        @foreach($transactions as $transaction)
            <tr class="hover:bg-muted transition-colors">
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            <div class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center">
                                <flux:icon name="shopping-bag" class="size-5 text-primary" />
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-text-primary">
                                Order #{{ $transaction->transaction_id }}
                            </div>
                            <div class="text-sm text-text-secondary">
                                {{ $transaction->transaction_date->format('M j, Y') }}
                            </div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="text-sm text-text-primary">
                        {{ $transaction->items->count() }} item(s)
                    </div>
                    <div class="text-sm text-text-secondary">
                        @if($transaction->items->count() > 0)
                            {{ $transaction->items->take(2)->pluck('product.name')->join(', ') }}
                            @if($transaction->items->count() > 2)
                                <span class="text-muted">+{{ $transaction->items->count() - 2 }} more</span>
                            @endif
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-text-primary">₵{{ number_format($transaction->total_amount, 2) }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($transaction->payment_status === 'pending')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning/10 text-warning">
                            <flux:icon name="clock" class="w-3 h-3 mr-1" />
                            Pending
                        </span>
                    @elseif($transaction->payment_status === 'completed')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success/10 text-success">
                            <flux:icon name="check-circle" class="w-3 h-3 mr-1" />
                            Completed
                        </span>
                    @elseif($transaction->payment_status === 'failed')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-error/10 text-error">
                            <flux:icon name="x-circle" class="w-3 h-3 mr-1" />
                            Failed
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-muted/10 text-muted">
                            {{ ucfirst($transaction->payment_status) }}
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-text-primary">{{ ucfirst($transaction->payment_method) }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary">
                    <div>{{ $transaction->transaction_date->format('M j, Y') }}</div>
                    <div class="text-xs">{{ $transaction->transaction_date->format('g:i A') }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex items-center justify-end space-x-2">
                        <flux:button
                            href="{{ route('customers.orders.show', $transaction) }}"
                            variant="ghost"
                            size="sm"
                            icon="eye"
                            title="View Order Details"
                            wire:navigate
                        />
                        @if($transaction->payment_status === 'pending')
                            <flux:button
                                href="{{ route('customers.orders.edit', $transaction) }}"
                                variant="ghost"
                                size="sm"
                                icon="pencil"
                                title="Edit Order"
                                wire:navigate
                            />
                        @endif
                        @if($transaction->receipt)
{{--                            <flux:button--}}
{{--                                href="{{ route('customers.pickups.show', $transaction->receipt->pickup) }}"--}}
{{--                                variant="outline"--}}
{{--                                size="sm"--}}
{{--                                icon="truck"--}}
{{--                                title="View Pickup"--}}
{{--                                wire:navigate--}}
{{--                            >--}}
{{--                                Pickup--}}
{{--                            </flux:button>--}}
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
        :items="$transactions"
        item-name="orders"
        :has-active-filters="$search || array_filter($filters)"
    />
</x-ui.admin-page-layout>
