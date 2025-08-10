<x-ui.admin-page-layout
    title="Suspicious Activities"
    description="Monitor and investigate suspicious activities across the system"
    :breadcrumbs="[['label' => 'Suspicious Activities']]"
    :stats="[
        ['label' => 'Total Activities', 'value' => number_format($stats['total']), 'icon' => 'shield-exclamation', 'iconBg' => 'bg-primary/10', 'iconColor' => 'text-primary'],
        ['label' => 'High Severity', 'value' => number_format($stats['high']), 'icon' => 'exclamation-triangle', 'iconBg' => 'bg-error/10', 'iconColor' => 'text-error'],
        ['label' => 'Medium Severity', 'value' => number_format($stats['medium']), 'icon' => 'exclamation-circle', 'iconBg' => 'bg-warning/10', 'iconColor' => 'text-warning'],
        ['label' => 'Low Severity', 'value' => number_format($stats['low']), 'icon' => 'information-circle', 'iconBg' => 'bg-success/10', 'iconColor' => 'text-success']
    ]"
    :show-filters="true"
    search-placeholder="Search activities, users, or descriptions..."
    :has-active-filters="$search || array_filter($filters)"
>
    <x-slot:filterSlot>
        <!-- Severity Filter -->
        <div>
            <flux:field>
                <flux:label>Severity Level</flux:label>
                <flux:select wire:model.live="filters.severity" placeholder="All Severities">
                    <flux:select.option value="low">Low Risk</flux:select.option>
                    <flux:select.option value="medium">Medium Risk</flux:select.option>
                    <flux:select.option value="high">High Risk</flux:select.option>
                </flux:select>
            </flux:field>
        </div>

        <!-- Date From -->
        <div>
            <flux:field>
                <flux:label>Date From</flux:label>
                <flux:input type="date" wire:model.live="filters.dateFrom" />
            </flux:field>
        </div>

        <!-- Date To -->
        <div>
            <flux:field>
                <flux:label>Date To</flux:label>
                <flux:input type="date" wire:model.live="filters.dateTo" />
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
                    <flux:select.option value="100">100</flux:select.option>
                </flux:select>
            </flux:field>
        </div>
    </x-slot:filterSlot>

    <!-- Main Table -->
    <x-ui.admin-table 
        :headers="[
            ['label' => 'ID', 'field' => 'activity_id', 'sortable' => true],
            ['label' => 'User', 'field' => 'user_id', 'sortable' => true],
            ['label' => 'Description', 'field' => 'description', 'sortable' => true],
            ['label' => 'Severity', 'field' => 'severity', 'sortable' => true],
            ['label' => 'Detected At', 'field' => 'detected_at', 'sortable' => true],
            ['label' => 'Actions']
        ]"
        :items="$activities"
        empty-title="No Suspicious Activities Found"
        empty-description="No suspicious activities match your current filters"
        :has-active-filters="$search || array_filter($filters)"
        :sort-by="$sortBy"
        :sort-direction="$sortDirection"
    >
        @foreach($activities as $item)
            <tr class="hover:bg-muted transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-text-primary">#{{ $item->activity_id }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-8 w-8">
                            <div class="h-8 w-8 rounded-full bg-primary/10 flex items-center justify-center">
                                <span class="text-xs font-medium text-primary">
                                    {{ $item->user ? strtoupper(substr($item->user->name, 0, 2)) : '?' }}
                                </span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-medium text-text-primary">
                                {{ $item->user->name ?? 'Unknown User' }}
                            </div>
                            @if($item->user?->email)
                                <div class="text-sm text-text-secondary">{{ $item->user->email }}</div>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="text-sm text-text-primary max-w-xs truncate" title="{{ $item->description }}">
                        {{ $item->description }}
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($item->severity === 'high')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-error/10 text-error">
                            <flux:icon.exclamation-triangle class="w-3 h-3 mr-1" />
                            High Risk
                        </span>
                    @elseif($item->severity === 'medium')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning/10 text-warning">
                            <flux:icon.exclamation-circle class="w-3 h-3 mr-1" />
                            Medium Risk
                        </span>
                    @elseif($item->severity === 'low')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success/10 text-success">
                            <flux:icon.information-circle class="w-3 h-3 mr-1" />
                            Low Risk
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-muted text-text-secondary">
                            <flux:icon.question-mark-circle class="w-3 h-3 mr-1" />
                            Unknown
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary">
                    <div>{{ $item->detected_at->format('M j, Y') }}</div>
                    <div class="text-xs">{{ $item->detected_at->format('g:i A') }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex items-center justify-end space-x-2">
                        <flux:button href="{{ route('admin.suspicious_activities.show', $item) }}" variant="ghost" size="sm" icon="eye" title="View Details" />
                    </div>
                </td>
            </tr>
        @endforeach

        {{-- Empty Action Slot --}}
        <x-slot:emptyAction>
            <div class="text-center">
                <p class="text-text-secondary text-sm mb-4">No suspicious activities detected yet.</p>
            </div>
        </x-slot:emptyAction>
    </x-ui.admin-table>

    <!-- Pagination -->
    <x-ui.admin-pagination 
        :items="$activities" 
        item-name="activities"
        :has-active-filters="$search || array_filter($filters)"
    />
</x-ui.admin-page-layout>
