<div>
    <div class="mb-6 flex flex-col items-start justify-between md:flex-row md:items-center">
        <div>
            <h1 class="text-accent text-2xl font-bold">Stock Alerts</h1>
            <p class="text-accent/50 text-sm">Monitor products with low stock levels</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('dashboard') }}">
                Dashboard
            </a>
            <span class="mx-2 text-gray-500">/</span>
            <span class="text-sm text-gray-900">Inventory</span>
        </div>
    </div>

    <div class="mb-6 rounded-lg bg-white p-4 shadow">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
            <!-- Search -->
            <div class="col-span-1 md:col-span-2">
                <label class="sr-only" for="search">Search</label>
                <div class="relative">
                    <flux:input id="search" icon="magnifying-glass" wire:model.debounce.300ms="search"
                        placeholder="Search products or alerts" />
                </div>
            </div>

            <!-- Date Filter -->
            <div>
                <label class="sr-only" for="dateFilter">Date Filter</label>
                <flux:select id="dateFilter" wire:model="dateFilter">
                    <flux:select.option value="">All Dates</flux:select.option>
                    <flux:select.option value="today">Today</flux:select.option>
                    <flux:select.option value="week">This Week</flux:select.option>
                    <flux:select.option value="month">This Month</flux:select.option>
                </flux:select>
            </div>

            <!-- Threshold Filter -->
            <div>
                <label class="sr-only" for="thresholdFilter">Threshold Filter</label>
                <flux:select class="block w-full rounded-md border border-gray-300 bg-gray-50 py-2 pl-3 pr-10 text-sm"
                    id="thresholdFilter" wire:model="thresholdFilter">
                    <flux:select.option value="">All Levels</flux:select.option>
                    <flux:select.option value="critical">Critical</flux:select.option>
                    <flux:select.option value="warning">Warning</flux:select.option>
                </flux:select>
            </div>
        </div>
    </div>

    <!-- Desktop Table View -->
    <div class="hidden overflow-hidden rounded-lg bg-white shadow md:block">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                        scope="col">
                        Product
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                        scope="col">
                        Quantity
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                        scope="col">
                        Threshold
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
                        Action
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse($alerts as $alert)
                    <tr class="hover:bg-gray-50">
                        <td class="whitespace-nowrap px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $alert->product_name }}</div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $alert->current_quantity }}</div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $alert->threshold }}</div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            @if ($alert->current_quantity <= $alert->threshold * 0.5)
                                <span
                                    class="inline-flex rounded-full bg-red-100 px-2 text-xs font-semibold leading-5 text-red-800">
                                    Critical
                                </span>
                            @else
                                <span
                                    class="inline-flex rounded-full bg-yellow-100 px-2 text-xs font-semibold leading-5 text-yellow-800">
                                    Warning
                                </span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <div class="text-sm text-gray-500">{{ $alert->triggered_at->format('M d, Y H:i') }}</div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-right">
                            <flux:button href="{{ route('admin.stock_alerts.show', $alert) }}" variant="filled">
                                <flux:icon.eye class="size-4" />
                            </flux:button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-6 py-4 text-center text-sm text-gray-500" colspan="6">
                            No stock alerts found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Card View -->
    <div class="space-y-4 md:hidden">
        @forelse($alerts as $alert)
            <div class="overflow-hidden rounded-lg bg-white shadow">
                <div class="flex justify-between px-4 py-5 sm:px-6">
                    <div>
                        <h3 class="text-lg font-medium leading-6 text-gray-900">{{ $alert->product_name }}</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">{{ $alert->triggered_at->format('M d, Y H:i') }}
                        </p>
                    </div>
                    @if ($alert->current_quantity <= $alert->threshold * 0.5)
                        <span
                            class="inline-flex h-6 items-center rounded-full bg-red-100 px-2 text-xs font-semibold leading-5 text-red-800">
                            Critical
                        </span>
                    @else
                        <span
                            class="inline-flex h-6 items-center rounded-full bg-yellow-100 px-2 text-xs font-semibold leading-5 text-yellow-800">
                            Warning
                        </span>
                    @endif
                </div>
                <div class="border-t border-gray-200">
                    <dl>
                        <div class="bg-gray-50 px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Current Quantity</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $alert->current_quantity }}
                            </dd>
                        </div>
                        <div class="bg-white px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Threshold</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $alert->threshold }}</dd>
                        </div>
                    </dl>
                </div>
                <div class="bg-gray-50 px-4 py-4 text-right">
                    <flux:button href="{{ route('admin.stock_alerts.show', $alert) }}" variant="filled">
                        <flux:icon.eye class="-ml-1 mr-2 size-5" /> View Details
                    </flux:button>
                </div>
            </div>
        @empty
            <div class="rounded-lg bg-white p-6 text-center text-gray-500 shadow">
                No stock alerts found.
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $alerts->links() }}
    </div>
</div>
