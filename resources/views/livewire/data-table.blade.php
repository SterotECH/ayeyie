<!-- resources/views/livewire/data-table.blade.php -->
<div>
    <div class="mb-4 flex items-center justify-between">
        <!-- Search input -->
        <div class="flex">
            <div class="relative">
                <input
                    type="text"
                    class="w-full pl-10 pr-4 py-2 rounded-lg border focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Search..."
                    wire:model.debounce.300ms="searchTerm"
                >
                <div class="absolute left-0 top-0 mt-2 ml-3 text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                @if ($searchTerm)
                    <div class="absolute right-0 top-0 mt-2 mr-3 text-gray-400 cursor-pointer" wire:click="resetSearch">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                @endif
            </div>
        </div>

        <!-- Bulk action dropdown -->
        <div class="flex items-center space-x-2" x-data="{ open: false }">
            <select
                class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 {{ empty($selected) ? 'bg-gray-100 text-gray-500' : 'bg-white text-gray-900' }}"
                wire:model="selectedBulkAction"
                {{ empty($selected) ? 'disabled' : '' }}
            >
                <option value="">Bulk Actions ({{ count($selected) }} selected)</option>
                @foreach ($bulkActions as $key => $action)
                    <option value="{{ $key }}">{{ $key }}</option>
                @endforeach
            </select>

            <button
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                wire:click="executeBulkAction"
                {{ empty($selected) || !$selectedBulkAction ? 'disabled' : '' }}
            >
                Apply
            </button>

            <div class="ml-2">
                <select
                    class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    wire:model="perPage"
                >
                    @foreach ($perPageOptions as $option)
                        <option value="{{ $option }}">{{ $option }} per page</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Desktop view -->
    <div class="bg-white rounded-lg shadow overflow-x-auto hidden md:block">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left">
                        <div class="flex items-center">
                            <input
                                type="checkbox"
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                wire:model="selectAll"
                            >
                        </div>
                    </th>
                    @foreach ($columns as $key => $column)
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('{{ $key }}')">
                            <div class="flex items-center">
                                {{ $column }}
                                @if ($sortColumn === $key)
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        @if ($sortDirection === 'asc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        @endif
                                    </svg>
                                @endif
                            </div>
                        </th>
                    @endforeach
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($rows as $row)
                    @if ($useCustomRowTemplate)
                        @include($customRowView, ['row' => $row, 'columns' => $columns, 'selected' => $selected])
                    @else
                        <tr class="{{ in_array($row->id, $selected) ? 'bg-blue-50' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input
                                    type="checkbox"
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                    value="{{ $row->id }}"
                                    wire:model="selected"
                                >
                            </td>
                            @foreach ($columns as $key => $column)
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $row->$key }}
                                </td>
                            @endforeach
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="#" class="text-blue-600 hover:text-blue-900">Edit</a>
                                <a href="#" class="ml-2 text-red-600 hover:text-red-900">Delete</a>
                            </td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="{{ count($columns) + 2 }}" class="px-6 py-4 text-center text-gray-500">
                            No data available
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile card view -->
    @if ($useCardViewMobile)
        <div class="grid grid-cols-1 gap-4 md:hidden">
            @forelse ($rows as $row)
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex justify-between items-center mb-2">
                        <div>
                            <input
                                type="checkbox"
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                value="{{ $row->id }}"
                                wire:model="selected"
                            >
                        </div>
                        <div class="flex space-x-2">
                            <a href="#" class="text-blue-600 hover:text-blue-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                </svg>
                            </a>
                            <a href="#" class="text-red-600 hover:text-red-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </a>
                        </div>
                    </div>

                    @if ($useCustomRowTemplate)
                        @include($customRowView, ['row' => $row, 'columns' => $columns, 'selected' => $selected, 'isMobile' => true])
                    @else
                        <div class="space-y-2">
                            @foreach ($columns as $key => $column)
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-500">{{ $column }}:</span>
                                    <span>{{ $row->$key }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @empty
                <div class="bg-white rounded-lg shadow p-4 text-center text-gray-500">
                    No data available
                </div>
            @endforelse
        </div>
    @endif

    <div class="mt-4">
        {{ $rows->links() }}
    </div>
</div>
