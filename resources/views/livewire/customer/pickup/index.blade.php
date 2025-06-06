<div>
    <div class="mb-6 flex flex-col items-start justify-between md:flex-row md:items-center">
        <div>
            <h1 class="text-accent text-2xl font-bold">Pickups</h1>
            <p class="text-accent/50 text-sm">Manage your pickup requests</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('dashboard') }}">
                Dashboard
            </a>
            <span class="mx-2 text-gray-500">/</span>
            <span class="text-sm text-gray-900">Pickups</span>
        </div>
    </div>

    <div class="mb-6 rounded-lg bg-zinc-50 p-4 shadow dark:bg-zinc-800">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <!-- Search -->
            <div class="col-span-1 md:col-span-1">
                <label class="sr-only" for="search">Search</label>
                <div class="relative">
                    <flux:input id="search" icon="magnifying-glass" wire:model.live="search"
                        placeholder="Search by receipt ID..." />
                </div>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="sr-only" for="statusFilter">Status Filter</label>
                <flux:select id="statusFilter" wire:model.live="status">
                    <flux:select.option value="">All Statuses</flux:select.option>
                    <flux:select.option value="pending">Pending</flux:select.option>
                    <flux:select.option value="completed">Completed</flux:select.option>
                </flux:select>
            </div>

            <!-- Sort -->
            <div>
                <label class="sr-only" for="sortFilter">Sort</label>
                <flux:select id="sortFilter" wire:model.live="sortField">
                    <flux:select.option value="pickup_id">Sort by ID</flux:select.option>
                    <flux:select.option value="receipt_id">Sort by Receipt</flux:select.option>
                    <flux:select.option value="pickup_date">Sort by Date</flux:select.option>
                </flux:select>
            </div>
        </div>
    </div>

    <!-- Desktop Table View -->
    <div class="hidden overflow-hidden rounded-lg bg-zinc-50 shadow md:block dark:bg-zinc-800">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-900">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                        scope="col">
                        ID
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                        scope="col">
                        Receipt Code
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                        scope="col">
                        Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                        scope="col">
                        Pickup Date
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500"
                        scope="col">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-zinc-50 dark:divide-gray-900 dark:bg-zinc-800">
                @forelse($this->pickups as $pickup)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="whitespace-nowrap px-6 py-4">
                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                #{{ $pickup->pickup_id }}</div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <div class="text-sm text-gray-900 dark:text-gray-100">
                                {{ $pickup->receipt->receipt_code }}</div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            @if ($pickup->pickup_status === 'pending')
                                <span
                                    class="inline-flex rounded-full bg-yellow-100 px-2 text-xs font-semibold leading-5 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                    Pending
                                </span>
                            @elseif ($pickup->pickup_status === 'completed')
                                <span
                                    class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    Completed
                                </span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $pickup->pickup_date ? $pickup->pickup_date->format('M d, Y H:i') : 'Not picked up yet' }}
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-right">
                            <div class="flex justify-end space-x-2">
                                <flux:modal.trigger name="pickup">
                                    <flux:button wire:click="viewDetails({{ $pickup->pickup_id }})" variant="filled"
                                        icon="eye">
                                        View
                                    </flux:button>
                                </flux:modal.trigger>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400" colspan="5">
                            No pickups found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Card View -->
    <div class="space-y-4 md:hidden">
        @forelse($this->pickups as $pickup)
            <div class="overflow-hidden rounded-lg bg-zinc-50 shadow dark:bg-zinc-800">
                <div class="flex justify-between px-4 py-5 sm:px-6">
                    <div>
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Pickup
                            #{{ $pickup->pickup_id }}</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                            {{ $pickup->receipt->receipt_code }}
                        </p>
                    </div>
                    @if ($pickup->pickup_status === 'pending')
                        <span
                            class="inline-flex h-6 items-center rounded-full bg-yellow-100 px-2 text-xs font-semibold leading-5 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                            Pending
                        </span>
                    @elseif ($pickup->pickup_status === 'completed')
                        <span
                            class="inline-flex h-6 items-center rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800 dark:bg-green-900 dark:text-green-200">
                            Completed
                        </span>
                    @endif
                </div>
                <div class="border-t border-gray-200 dark:border-gray-700">
                    <dl>
                        <div class="bg-gray-50 px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 dark:bg-gray-700">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Receipt Code</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0 dark:text-gray-100">
                                {{ $pickup->receipt->receipt_code }}
                            </dd>
                        </div>
                        <div class="bg-zinc-50 px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 dark:bg-zinc-800">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Pickup Date</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0 dark:text-gray-100">
                                {{ $pickup->pickup_date ? $pickup->pickup_date->format('M d, Y H:i') : 'Not picked up yet' }}
                            </dd>
                        </div>
                    </dl>
                </div>
                <div class="bg-zinc-50 px-4 py-4 text-right dark:bg-zinc-800">
                    <div class="flex justify-end space-x-2">
                        <flux:modal.trigger name="pickup">
                            <flux:button wire:click="viewDetails({{ $pickup->pickup_id }})" variant="filled"
                                icon="eye">
                                View
                            </flux:button>
                        </flux:modal.trigger>

                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-lg bg-zinc-50 p-6 text-center text-gray-500 shadow dark:bg-zinc-800 dark:text-gray-400">
                No pickups found.
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $this->pickups->links() }}
    </div>

    <!-- Pickup Details Modal -->
    {{-- @if ($showDetailsModal) --}}
    <flux:modal name="pickup">
        <flux:heading> Pickup Details</flux:heading>
        @if ($selectedPickup)
            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 dark:bg-zinc-800">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 w-full text-center sm:ml-4 sm:mt-0 sm:text-left">
                        <div class="mt-4 space-y-4">
                            <div class="border-b pb-4 dark:border-gray-700">
                                <h4 class="font-semibold text-gray-700 dark:text-gray-300">Pickup Information
                                </h4>
                                <div class="mt-2 grid grid-cols-2 gap-2 text-sm">
                                    <div class="text-gray-500 dark:text-gray-400">ID:</div>
                                    <div class="text-gray-900 dark:text-gray-100">
                                        {{ $selectedPickup->pickup_id ?? '' }}</div>

                                    <div class="text-gray-500 dark:text-gray-400">Receipt:</div>
                                    <div class="text-gray-900 dark:text-gray-100">
                                        {{ $selectedPickup->receipt_id ?? '' }}</div>

                                    <div class="text-gray-500 dark:text-gray-400">Status:</div>
                                    <div>
                                        <span
                                            class="{{ $selectedPickup?->pickup_status === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }} inline-flex rounded-full px-2 text-xs font-semibold leading-5">
                                            {{ ucfirst($selectedPickup->pickup_status ?? '') }}
                                        </span>
                                    </div>

                                    <div class="text-gray-500 dark:text-gray-400">Pickup Date:</div>
                                    <div class="text-gray-900 dark:text-gray-100">
                                        {{ $selectedPickup?->pickup_date ? $selectedPickup->pickup_date->format('M d, Y h:i A') : 'Not picked up yet' }}
                                    </div>

                                    <div class="text-gray-500 dark:text-gray-400">Processed By:</div>
                                    <div class="text-gray-900 dark:text-gray-100">
                                        {{ $selectedPickup->user->name ?? 'N/A' }}</div>
                                </div>
                            </div>

                            @if ($pickupTransaction)
                                <div>
                                    <h4 class="font-semibold text-gray-700 dark:text-gray-300">Transaction
                                        Details</h4>
                                    <div class="mt-2 grid grid-cols-2 gap-2 text-sm">
                                        <div class="text-gray-500 dark:text-gray-400">Transaction ID:</div>
                                        <div class="text-gray-900 dark:text-gray-100">
                                            {{ $pickupTransaction->transaction_id }}</div>

                                        <div class="text-gray-500 dark:text-gray-400">Amount:</div>
                                        <div class="text-gray-900 dark:text-gray-100">
                                            ${{ number_format($pickupTransaction->total_amount, 2) }}</div>

                                        <div class="text-gray-500 dark:text-gray-400">Payment Method:</div>
                                        <div class="text-gray-900 dark:text-gray-100">
                                            {{ ucfirst($pickupTransaction->payment_method) }}</div>

                                        <div class="text-gray-500 dark:text-gray-400">Payment Status:</div>
                                        <div>
                                            <span
                                                class="{{ $pickupTransaction?->payment_status === 'completed'
                                                    ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                                    : ($pickupTransaction?->payment_status === 'pending'
                                                        ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'
                                                        : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200') }} inline-flex rounded-full px-2 text-xs font-semibold leading-5">
                                                {{ ucfirst($pickupTransaction?->payment_status) ?? '' }}
                                            </span>
                                        </div>

                                        <div class="text-gray-500 dark:text-gray-400">Transaction Date:</div>
                                        <div class="text-gray-900 dark:text-gray-100">
                                            {{ $pickupTransaction->transaction_date->format('M d, Y h:i A') }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="gap-x-4 bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 dark:bg-gray-700">
                @if ($selectedPickup?->pickup_status === 'pending')
                    <flux:button variant="primary" wire:click="contactStore">
                        Contact Store
                    </flux:button>
                @endif
            </div>
        @endif
    </flux:modal>
    {{-- @endif --}}
</div>
