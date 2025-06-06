<div>
    <div class="mb-6 flex flex-col items-start justify-between md:flex-row md:items-center">
        <div>
            <h1 class="text-accent text-2xl font-bold">Audit Logs</h1>
            <p class="text-accent/50 text-sm">Monitor system activities</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('dashboard') }}">
                Dashboard
            </a>
            <span class="mx-2 text-gray-500">/</span>
            <span class="text-sm text-gray-900">Audit Logs</span>
        </div>
    </div>

    <div class="mb-6 rounded-lg bg-zinc-50 p-4 shadow dark:bg-zinc-800">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
            <!-- Search -->
            <div class="col-span-1 md:col-span-2">
                <label class="sr-only" for="search">Search</label>
                <div class="relative">
                    <flux:input id="search" icon="magnifying-glass" wire:model.debounce.300ms="search"
                        placeholder="Search audit logs..." />
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

            <!-- Log Level Filter -->
            <div>
                <label class="sr-only" for="logLevelFilter">Log Level Filter</label>
                <flux:select id="logLevelFilter" wire:model="logLevelFilter">
                    <flux:select.option value="">All Levels</flux:select.option>
                    <flux:select.option value="info">Info</flux:select.option>
                    <flux:select.option value="warning">Warning</flux:select.option>
                    <flux:select.option value="error">Error</flux:select.option>
                    <flux:select.option value="critical">Critical</flux:select.option>
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
                        User
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                        scope="col">
                        Action
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                        scope="col">
                        Entity
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                        scope="col">
                        Level
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
            <tbody class="divide-y divide-gray-200 bg-zinc-50 dark:divide-gray-900 dark:bg-zinc-800">
                @forelse($logs as $log)
                    <tr class="hover:bg-gray-50 dark:bg-gray-700">
                        <td class="whitespace-nowrap px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $log->user->name ?? 'System' }}</div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $log->action }}</div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <div class="text-sm text-gray-900">
                                {{ class_basename($log->entity_type) }} #{{ $log->entity_id }}
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            @if ($log->log_level === 'critical')
                                <span
                                    class="inline-flex rounded-full bg-red-100 px-2 text-xs font-semibold leading-5 text-red-800">
                                    Critical
                                </span>
                            @elseif ($log->log_level === 'error')
                                <span
                                    class="inline-flex rounded-full bg-orange-100 px-2 text-xs font-semibold leading-5 text-orange-800">
                                    Error
                                </span>
                            @elseif ($log->log_level === 'warning')
                                <span
                                    class="inline-flex rounded-full bg-yellow-100 px-2 text-xs font-semibold leading-5 text-yellow-800">
                                    Warning
                                </span>
                            @else
                                <span
                                    class="inline-flex rounded-full bg-blue-100 px-2 text-xs font-semibold leading-5 text-blue-800">
                                    Info
                                </span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <div class="text-sm text-gray-500">{{ $log->logged_at->format('M d, Y H:i') }}</div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-right">
                            <flux:button href="{{ route('admin.audit-logs.show', $log->log_id) }}" variant="filled">
                                <flux:icon.eye class="size-4" />
                            </flux:button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-6 py-4 text-center text-sm text-gray-500" colspan="6">
                            No audit logs found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Card View -->
    <div class="space-y-4 md:hidden">
        @forelse($logs as $log)
            <div class="overflow-hidden rounded-lg bg-zinc-50 shadow dark:bg-zinc-800">
                <div class="flex justify-between px-4 py-5 sm:px-6">
                    <div>
                        <h3 class="text-lg font-medium leading-6 text-gray-900">{{ $log->action }}</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">{{ $log->logged_at->format('M d, Y H:i') }}
                        </p>
                    </div>
                    @if ($log->log_level === 'critical')
                        <span
                            class="inline-flex h-6 items-center rounded-full bg-red-100 px-2 text-xs font-semibold leading-5 text-red-800">
                            Critical
                        </span>
                    @elseif ($log->log_level === 'error')
                        <span
                            class="inline-flex h-6 items-center rounded-full bg-orange-100 px-2 text-xs font-semibold leading-5 text-orange-800">
                            Error
                        </span>
                    @elseif ($log->log_level === 'warning')
                        <span
                            class="inline-flex h-6 items-center rounded-full bg-yellow-100 px-2 text-xs font-semibold leading-5 text-yellow-800">
                            Warning
                        </span>
                    @else
                        <span
                            class="inline-flex h-6 items-center rounded-full bg-blue-100 px-2 text-xs font-semibold leading-5 text-blue-800">
                            Info
                        </span>
                    @endif
                </div>
                <div class="border-t border-gray-200">
                    <dl>
                        <div class="bg-gray-50 px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 dark:bg-gray-700">
                            <dt class="text-sm font-medium text-gray-500">User</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                {{ $log->user->name ?? 'System' }}
                            </dd>
                        </div>
                        <div class="bg-zinc-50 px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 dark:bg-zinc-800">
                            <dt class="text-sm font-medium text-gray-500">Entity</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                {{ class_basename($log->entity_type) }} #{{ $log->entity_id }}
                            </dd>
                        </div>
                    </dl>
                </div>
                <div class="bg-gray-50 px-4 py-4 text-right dark:bg-gray-700">
                    <flux:button href="{{ route('admin.audit-logs.show', $log->log_id) }}" variant="filled">
                        <flux:icon.eye class="-ml-1 mr-2 size-5" /> View Details
                    </flux:button>
                </div>
            </div>
        @empty
            <div class="rounded-lg bg-zinc-50 p-6 text-center text-gray-500 shadow dark:bg-zinc-800">
                No audit logs found.
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $logs->links() }}
    </div>
</div>
