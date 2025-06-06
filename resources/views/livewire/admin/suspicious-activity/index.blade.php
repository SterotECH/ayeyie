<div>
    <div class="py-6">
        <div class="">
            <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
                <h2 class="text-accent text-2xl font-bold">
                    Suspicious Activities
                </h2>
                <div class="mt-4 md:mt-0">
                    <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('dashboard') }}">
                        Dashboard
                    </a>
                    <span class="mx-2 text-gray-500">/</span>
                    <span class="text-sm text-gray-900">Fraud Alert</span>
                </div>
            </div>

            <div class="overflow-hidden bg-zinc-50 shadow-sm sm:rounded-lg dark:bg-zinc-800">
                <!-- Filter Section -->
                <div class="border-b border-gray-200 p-6">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                        <div>
                            <flux:select id="severity" wire:model="severity" label="Severity">
                                <flux:select.option value="">All Severities</flux:select.option>
                                <flux:select.option value="low">Low</flux:select.option>
                                <flux:select.option value="medium">Medium</flux:select.option>
                                <flux:select.option value="high">High</flux:select.option>
                            </flux:select>
                        </div>

                        <div>
                            <flux:input id="dateFrom" type="date" label="Date From" wire:model="dateFrom" />
                        </div>

                        <div>
                            <flux:input id="dateTo" type="date" wire:model="dateTo" label="Date To" />
                        </div>

                        <div>
                            <flux:input id="search" type="text" wire:model.debounce.300ms="search"
                                placeholder="Search description or user..." label="Search" />
                        </div>
                    </div>

                    <div class="mt-4 flex justify-end">
                        <flux:button variant="filled" wire:click="resetFilters">
                            Reset Filters
                        </flux:button>
                    </div>
                </div>

                <!-- Loading Indicator -->
                <div class="flex items-center justify-center border-b border-gray-200 p-4" wire:loading>
                    <svg class="h-5 w-5 animate-spin text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    <span class="ml-2 text-sm text-gray-700">Loading...</span>
                </div>

                <!-- Table Section -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-900">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                    scope="col">
                                    <button class="group inline-flex items-center" wire:click="sortBy('activity_id')">
                                        ID
                                        @if ($sortField == 'activity_id')
                                            <svg class="ml-1 h-4 w-4 text-gray-400" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                @if ($sortDirection == 'asc')
                                                    <path fill-rule="evenodd"
                                                        d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L10 4.414l-3.293 3.293a1 1 0 01-1.414 0z"
                                                        clip-rule="evenodd"></path>
                                                @else
                                                    <path fill-rule="evenodd"
                                                        d="M14.707 12.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L10 15.586l3.293-3.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd"></path>
                                                @endif
                                            </svg>
                                        @endif
                                    </button>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                    scope="col">
                                    <button class="group inline-flex items-center" wire:click="sortBy('user_id')">
                                        User
                                        @if ($sortField == 'user_id')
                                            <svg class="ml-1 h-4 w-4 text-gray-400" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                @if ($sortDirection == 'asc')
                                                    <path fill-rule="evenodd"
                                                        d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L10 4.414l-3.293 3.293a1 1 0 01-1.414 0z"
                                                        clip-rule="evenodd"></path>
                                                @else
                                                    <path fill-rule="evenodd"
                                                        d="M14.707 12.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L10 15.586l3.293-3.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd"></path>
                                                @endif
                                            </svg>
                                        @endif
                                    </button>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                    scope="col">
                                    <button class="group inline-flex items-center" wire:click="sortBy('description')">
                                        Description
                                        @if ($sortField == 'description')
                                            <svg class="ml-1 h-4 w-4 text-gray-400" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                @if ($sortDirection == 'asc')
                                                    <path fill-rule="evenodd"
                                                        d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L10 4.414l-3.293 3.293a1 1 0 01-1.414 0z"
                                                        clip-rule="evenodd"></path>
                                                @else
                                                    <path fill-rule="evenodd"
                                                        d="M14.707 12.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L10 15.586l3.293-3.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd"></path>
                                                @endif
                                            </svg>
                                        @endif
                                    </button>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                    scope="col">
                                    <button class="group inline-flex items-center" wire:click="sortBy('severity')">
                                        Severity
                                        @if ($sortField == 'severity')
                                            <svg class="ml-1 h-4 w-4 text-gray-400" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                @if ($sortDirection == 'asc')
                                                    <path fill-rule="evenodd"
                                                        d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L10 4.414l-3.293 3.293a1 1 0 01-1.414 0z"
                                                        clip-rule="evenodd"></path>
                                                @else
                                                    <path fill-rule="evenodd"
                                                        d="M14.707 12.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L10 15.586l3.293-3.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd"></path>
                                                @endif
                                            </svg>
                                        @endif
                                    </button>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                    scope="col">
                                    <button class="group inline-flex items-center" wire:click="sortBy('detected_at')">
                                        Detected At
                                        @if ($sortField == 'detected_at')
                                            <svg class="ml-1 h-4 w-4 text-gray-400" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                @if ($sortDirection == 'asc')
                                                    <path fill-rule="evenodd"
                                                        d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L10 4.414l-3.293 3.293a1 1 0 01-1.414 0z"
                                                        clip-rule="evenodd"></path>
                                                @else
                                                    <path fill-rule="evenodd"
                                                        d="M14.707 12.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L10 15.586l3.293-3.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd"></path>
                                                @endif
                                            </svg>
                                        @endif
                                    </button>
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500"
                                    scope="col">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-zinc-50 dark:divide-gray-900 dark:bg-zinc-800">
                            @forelse($activities as $activity)
                                <tr wire:key="activity-{{ $activity->activity_id }}">
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                                        {{ $activity->activity_id }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        {{ $activity->user->name ?? 'Unknown' }}<br>
                                        <span class="text-xs text-gray-400">{{ $activity->user->email ?? '' }}</span>
                                    </td>
                                    <td class="max-w-xs truncate px-6 py-4 text-sm text-gray-500">
                                        {{ $activity->description }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        @if ($activity->severity == 'low')
                                            <span
                                                class="rounded-full bg-blue-100 px-2 py-1 text-xs font-medium text-blue-800">
                                                Low
                                            </span>
                                        @elseif($activity->severity == 'medium')
                                            <span
                                                class="rounded-full bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-800">
                                                Medium
                                            </span>
                                        @elseif($activity->severity == 'high')
                                            <span
                                                class="rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-800">
                                                High
                                            </span>
                                        @else
                                            <span
                                                class="rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-800">
                                                Unknown
                                            </span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        {{ $activity->detected_at->format('M d, Y H:i') }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                        <a class="rounded-md bg-indigo-50 px-3 py-1 text-indigo-600 hover:bg-indigo-100 hover:text-indigo-900"
                                            href="{{ route('admin.suspicious_activities.show', $activity) }}">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="px-6 py-4 text-center text-sm text-gray-500" colspan="6">
                                        No suspicious activities found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                {{ $activities->links() }}
            </div>
        </div>
    </div>
</div>
