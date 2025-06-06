<div>
    <div class="mb-6 flex flex-col items-start justify-between md:flex-row md:items-center">
        <div>
            <h1 class="text-accent text-2xl font-bold">Orders</h1>
            <p class="text-accent/50 text-sm">Manage your transactions</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('dashboard') }}">
                Dashboard
            </a>
            <span class="mx-2 text-gray-500">/</span>
            <span class="text-sm text-gray-900">Orders</span>
        </div>
    </div>

    <div class="mb-6 rounded-lg bg-zinc-50 p-4 shadow dark:bg-zinc-800">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
            <!-- Search -->
            <div class="col-span-1 md:col-span-2">
                <label class="sr-only" for="search">Search</label>
                <div class="relative">
                    <flux:input id="search" icon="magnifying-glass" wire:model.live="search"
                        placeholder="Search by transaction ID..." />
                </div>
            </div>

            <!-- Date Range Filter -->
            <div>
                <label class="sr-only" for="dateFilter">Date Filter</label>
                <flux:select id="dateFilter" wire:model.live="dateFilter">
                    <flux:select.option value="">All Dates</flux:select.option>
                    <flux:select.option value="today">Today</flux:select.option>
                    <flux:select.option value="yesterday">Yesterday</flux:select.option>
                    <flux:select.option value="this_week">This Week</flux:select.option>
                    <flux:select.option value="this_month">This Month</flux:select.option>
                    <flux:select.option value="last_month">Last Month</flux:select.option>
                </flux:select>
            </div>

            <!-- Payment Status Filter -->
            <div>
                <label class="sr-only" for="statusFilter">Status Filter</label>
                <flux:select id="statusFilter" wire:model.live="statusFilter">
                    <flux:select.option value="">All Statuses</flux:select.option>
                    <flux:select.option value="pending">Pending</flux:select.option>
                    <flux:select.option value="completed">Completed</flux:select.option>
                    <flux:select.option value="failed">Failed</flux:select.option>
                </flux:select>
            </div>
        </div>
    </div>
    <!-- Add Order Button -->
    <div class="mb-4 flex justify-end">
        <flux:button href="{{ route('customers.orders.create') }}" wire:navigate variant="primary" icon="plus">
            New Order
        </flux:button>
    </div>

    <!-- Desktop Table View -->
    <div class="hidden overflow-hidden rounded-lg bg-zinc-50 shadow md:block dark:bg-zinc-800">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-900">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                        scope="col">
                        Transaction ID
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                        scope="col">
                        Amount
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                        scope="col">
                        Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                        scope="col">
                        Date
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500"
                        scope="col">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-zinc-50 dark:divide-gray-900 dark:bg-zinc-800">
                @forelse($transactions as $transaction)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="whitespace-nowrap px-6 py-4">
                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                #{{ $transaction->transaction_id }}</div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <div class="text-sm text-gray-900 dark:text-gray-100">
                                ${{ number_format($transaction->total_amount, 2) }}</div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            @if ($transaction->payment_status === 'pending')
                                <span
                                    class="inline-flex rounded-full bg-yellow-100 px-2 text-xs font-semibold leading-5 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                    Pending
                                </span>
                            @elseif ($transaction->payment_status === 'completed')
                                <span
                                    class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    Completed
                                </span>
                            @elseif ($transaction->payment_status === 'failed')
                                <span
                                    class="inline-flex rounded-full bg-red-100 px-2 text-xs font-semibold leading-5 text-red-800 dark:bg-red-900 dark:text-red-200">
                                    Failed
                                </span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $transaction->transaction_date->format('M d, Y H:i') }}</div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-right">
                            <div class="flex justify-end space-x-2">
                                <flux:button href="{{ route('customers.orders.show', $transaction->transaction_id) }}"
                                    variant="filled">
                                    <flux:icon.eye class="size-4" />
                                </flux:button>
                                @if ($transaction->payment_status === 'pending')
                                    {{-- <flux:button
                                        href="{{ route('customers.orders.edit', $transaction->transaction_id) }}"
                                        variant="filled">
                                        <flux:icon.pencil-square class="size-4" />
                                    </flux:button> --}}
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400" colspan="6">
                            No orders found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- Mobile Card View -->
    <div class="space-y-4 md:hidden">
        @forelse($transactions as $transaction)
            <div class="overflow-hidden rounded-lg bg-zinc-50 shadow dark:bg-zinc-800">
                <div class="flex justify-between px-4 py-5 sm:px-6">
                    <div>
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Order
                            #{{ $transaction->transaction_id }}</h3>
                        {{-- <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                            {{ $transaction->customer_user_id ? $transaction->customer->name : 'Walk-in Customer' }}
                        </p> --}}
                    </div>
                    @if ($transaction->payment_status === 'pending')
                        <span
                            class="inline-flex h-6 items-center rounded-full bg-yellow-100 px-2 text-xs font-semibold leading-5 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                            Pending
                        </span>
                    @elseif ($transaction->payment_status === 'completed')
                        <span
                            class="inline-flex h-6 items-center rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800 dark:bg-green-900 dark:text-green-200">
                            Completed
                        </span>
                    @elseif ($transaction->payment_status === 'failed')
                        <span
                            class="inline-flex h-6 items-center rounded-full bg-red-100 px-2 text-xs font-semibold leading-5 text-red-800 dark:bg-red-900 dark:text-red-200">
                            Failed
                        </span>
                    @endif
                </div>
                <div class="border-t border-gray-200 dark:border-gray-700">
                    <dl>
                        <div class="bg-gray-50 px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 dark:bg-gray-700">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Amount</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0 dark:text-gray-100">
                                ${{ number_format($transaction->total_amount, 2) }}
                            </dd>
                        </div>
                        <div class="bg-zinc-50 px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 dark:bg-zinc-800">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Date</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0 dark:text-gray-100">
                                {{ $transaction->transaction_date->format('M d, Y H:i') }}
                            </dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 dark:bg-gray-700">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Payment Method</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0 dark:text-gray-100">
                                {{ ucfirst($transaction->payment_method) }}
                            </dd>
                        </div>
                    </dl>
                </div>
                <div class="bg-zinc-50 px-4 py-4 text-right dark:bg-zinc-800">
                    <div class="flex justify-end space-x-2">
                        <flux:button href="{{ route('customers.orders.show', $transaction) }}" variant="filled">
                            <flux:icon.eye class="-ml-1 mr-2 size-5" /> View
                        </flux:button>
                        @if ($transaction->payment_status === 'pending')
                            {{-- <flux:button href="{{ route('customers.orders.edit', $transaction->transaction_id) }}"
                                variant="filled">
                                <flux:icon.pencil-square class="-ml-1 mr-2 size-5" /> Edit
                            </flux:button> --}}
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-lg bg-zinc-50 p-6 text-center text-gray-500 shadow dark:bg-zinc-800 dark:text-gray-400">
                No orders found.
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $transactions->links() }}
    </div>
</div>
