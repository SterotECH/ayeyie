<x-ui.admin-page-layout
    title="Transaction History"
    description="View and monitor all transaction records"
    :breadcrumbs="[['label' => 'Transaction History']]"
    :stats="[
        ['label' => 'Total Transactions', 'value' => number_format($stats['total']), 'icon' => 'document-text', 'iconBg' => 'bg-primary/10', 'iconColor' => 'text-primary'],
        ['label' => 'Completed', 'value' => number_format($stats['completed']), 'icon' => 'check-circle', 'iconBg' => 'bg-success/10', 'iconColor' => 'text-success'],
        ['label' => 'Pending Payment', 'value' => number_format($stats['pending']), 'icon' => 'clock', 'iconBg' => 'bg-warning/10', 'iconColor' => 'text-warning'],
        ['label' => 'Today\'s Revenue', 'value' => '₵' . number_format($stats['today_revenue'], 2), 'icon' => 'currency-dollar', 'iconBg' => 'bg-success/10', 'iconColor' => 'text-success']
    ]"
    :show-filters="true"
    search-placeholder="Search by transaction ID, customer name, or receipt code..."
    :has-active-filters="$search || array_filter($filters)"
>
    <x-slot:actions>
        <flux:button href="{{ route('staff.transactions.process-payment') }}" variant="primary" icon="currency-dollar" wire:navigate>
            Process Payment
        </flux:button>
    </x-slot:actions>

    <x-slot:filterSlot>
        <!-- Payment Status Filter -->
        <div>
            <flux:field>
                <flux:label>Payment Status</flux:label>
                <flux:select wire:model.live="filters.payment_status" placeholder="All Statuses">
                    <flux:select.option value="pending">Pending</flux:select.option>
                    <flux:select.option value="completed">Completed</flux:select.option>
                    <flux:select.option value="failed">Failed</flux:select.option>
                </flux:select>
            </flux:field>
        </div>

        <!-- Payment Method Filter -->
        <div>
            <flux:field>
                <flux:label>Payment Method</flux:label>
                <flux:select wire:model.live="filters.payment_method" placeholder="All Methods">
                    <flux:select.option value="cash_on_delivery">Cash on Delivery</flux:select.option>
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
                    <flux:select.option value="month">This Month</flux:select.option>
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
            ['label' => 'Transaction', 'field' => 'transaction_id', 'sortable' => true],
            ['label' => 'Customer', 'field' => 'customer', 'sortable' => false],
            ['label' => 'Amount', 'field' => 'total_amount', 'sortable' => true],
            ['label' => 'Payment', 'field' => 'payment_status', 'sortable' => true],
            ['label' => 'Date', 'field' => 'transaction_date', 'sortable' => true],
            ['label' => 'Actions']
        ]"
        :items="$transactions"
        empty-title="No Transactions Found"
        empty-description="No transactions match your current filters"
        :has-active-filters="$search || array_filter($filters)"
        :sort-by="$sortBy"
        :sort-direction="$sortDirection"
    >
        @foreach($transactions as $transaction)
            <tr class="hover:bg-muted transition-colors">
                <!-- Transaction -->
                <td class="px-6 py-4">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-semibold text-text-primary">#{{ $transaction->transaction_id }}</span>
                            @if($transaction->receipt)
                                <span class="text-xs bg-muted text-text-secondary px-2 py-1 rounded-full font-mono">{{ $transaction->receipt->receipt_code }}</span>
                            @endif
                        </div>
                        <div class="text-xs text-text-secondary">
                            {{ $transaction->items->count() }} item(s) • {{ ucfirst(str_replace('_', ' ', $transaction->payment_method)) }}
                        </div>
                    </div>
                </td>

                <!-- Customer -->
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-8 w-8">
                            <div class="h-8 w-8 rounded-full bg-primary/10 flex items-center justify-center">
                                <span class="text-xs font-medium text-primary">
                                    {{ $transaction->customer ? strtoupper(substr($transaction->customer->name, 0, 2)) : 'WI' }}
                                </span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-medium text-text-primary">
                                {{ $transaction->customer?->name ?? 'Walk-in Customer' }}
                            </div>
                            @if($transaction->customer?->phone)
                                <div class="text-sm text-text-secondary">{{ $transaction->customer->phone }}</div>
                            @endif
                        </div>
                    </div>
                </td>

                <!-- Amount -->
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-bold text-text-primary">₵{{ number_format($transaction->total_amount, 2) }}</div>
                </td>

                <!-- Payment Status -->
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($transaction->payment_status === 'completed')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success/10 text-success">
                            <flux:icon name="check-circle" class="w-3 h-3 mr-1" />
                            Completed
                        </span>
                    @elseif($transaction->payment_status === 'pending')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning/10 text-warning">
                            <flux:icon name="clock" class="w-3 h-3 mr-1" />
                            Pending
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-error/10 text-error">
                            <flux:icon name="x-circle" class="w-3 h-3 mr-1" />
                            Failed
                        </span>
                    @endif
                </td>

                <!-- Date -->
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <div class="text-text-primary font-medium">
                        {{ $transaction->transaction_date->format('M j, Y') }}
                    </div>
                    <div class="text-text-secondary text-xs">
                        {{ $transaction->transaction_date->format('g:i A') }}
                    </div>
                </td>

                <!-- Actions -->
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex items-center justify-end space-x-2">
                        @if($transaction->payment_status === 'pending' && $transaction->payment_method === 'cash_on_delivery')
                            <flux:button 
                                href="{{ route('staff.transactions.process-payment', ['receipt_code' => $transaction->receipt?->receipt_code]) }}" 
                                variant="ghost" 
                                size="sm" 
                                icon="currency-dollar" 
                                title="Process Payment" 
                                wire:navigate
                            />
                        @endif
                        
                        @if($transaction->receipt && $transaction->payment_status === 'completed')
                            <flux:button 
                                href="{{ route('staff.orders.verify', ['receipt_code' => $transaction->receipt->receipt_code, 'transaction_id' => $transaction->transaction_id]) }}" 
                                variant="ghost" 
                                size="sm" 
                                icon="truck" 
                                title="Process Pickup" 
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
                <p class="text-text-secondary text-sm mb-4">No transactions found.</p>
                <flux:button href="{{ route('staff.transactions.process-payment') }}" variant="primary" icon="currency-dollar" wire:navigate>
                    Process First Payment
                </flux:button>
            </div>
        </x-slot:emptyAction>
    </x-ui.admin-table>

    <!-- Pagination -->
    <x-ui.admin-pagination 
        :items="$transactions" 
        item-name="transactions"
        :has-active-filters="$search || array_filter($filters)"
    />
</x-ui.admin-page-layout>