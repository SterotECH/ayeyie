<x-ui.admin-page-layout
    title="Audit Logs"
    description="Monitor and track all system activities and user actions"
    :breadcrumbs="[['label' => 'Audit Logs']]"
    :stats="[
        ['label' => 'Total Logs', 'value' => number_format($stats['total']), 'icon' => 'document-text', 'iconBg' => 'bg-primary/10', 'iconColor' => 'text-primary'],
        ['label' => 'Today\'s Activity', 'value' => number_format($stats['today']), 'icon' => 'calendar', 'iconBg' => 'bg-success/10', 'iconColor' => 'text-success'],
        ['label' => 'Critical Events', 'value' => number_format($stats['critical']), 'icon' => 'exclamation-triangle', 'iconBg' => 'bg-error/10', 'iconColor' => 'text-error'],
        ['label' => 'Error Events', 'value' => number_format($stats['errors']), 'icon' => 'x-circle', 'iconBg' => 'bg-warning/10', 'iconColor' => 'text-warning']
    ]"
    :show-filters="true"
    search-placeholder="Search actions, entities, or details..."
    :has-active-filters="$search || array_filter($filters)"
>
    <x-slot:filterSlot>
        <!-- Date Filter -->
        <div>
            <flux:field>
                <flux:label>Date Range</flux:label>
                <flux:select wire:model.live="filters.dateFilter" placeholder="All Dates">
                    <flux:select.option value="today">Today</flux:select.option>
                    <flux:select.option value="week">This Week</flux:select.option>
                    <flux:select.option value="month">This Month</flux:select.option>
                </flux:select>
            </flux:field>
        </div>

        <!-- Log Level Filter -->
        <div>
            <flux:field>
                <flux:label>Log Level</flux:label>
                <flux:select wire:model.live="filters.logLevelFilter" placeholder="All Levels">
                    <flux:select.option value="info">Info</flux:select.option>
                    <flux:select.option value="warning">Warning</flux:select.option>
                    <flux:select.option value="error">Error</flux:select.option>
                    <flux:select.option value="critical">Critical</flux:select.option>
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
                    <flux:select.option value="100">100</flux:select.option>
                </flux:select>
            </flux:field>
        </div>
    </x-slot:filterSlot>

    <!-- Main Table -->
    <x-ui.admin-table
        :headers="[
            ['label' => 'User', 'field' => 'user_id', 'sortable' => true],
            ['label' => 'Action', 'field' => 'action', 'sortable' => true],
            ['label' => 'Entity', 'field' => 'entity_type', 'sortable' => true],
            ['label' => 'Log Level', 'field' => 'log_level', 'sortable' => true],
            ['label' => 'Logged At', 'field' => 'logged_at', 'sortable' => true],
            ['label' => 'Actions']
        ]"
        :items="$logs"
        empty-title="No Audit Logs Found"
        empty-description="No audit logs match your current filters"
        :has-active-filters="$search || array_filter($filters)"
        :sort-by="$sortBy"
        :sort-direction="$sortDirection"
    >
        @foreach($logs as $item)
            <tr class="hover:bg-muted transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-8 w-8">
                            <div class="h-8 w-8 rounded-full bg-primary/10 flex items-center justify-center">
                                @if($item->user)
                                    <span class="text-xs font-medium text-primary">
                                        {{ strtoupper(substr($item->user->name, 0, 2)) }}
                                    </span>
                                @else
                                    <flux:icon.cog class="w-4 h-4 text-primary" />
                                @endif
                            </div>
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-medium text-text-primary">
                                {{ $item->user->name ?? 'System' }}
                            </div>
                            @if($item->user?->email)
                                <div class="text-sm text-text-secondary">{{ $item->user->email }}</div>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="text-sm font-medium text-text-primary mb-1">{{ $item->action }}</div>
                    @if($item->details)
                        <x-audit.details-summary :details="$item->details" :max-length="80" />
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-text-primary">
                        {{ class_basename($item->entity_type) }}
                    </div>
                    <div class="text-sm text-text-secondary">ID: {{ $item->entity_id }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($item->log_level === 'critical')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-error/10 text-error">
                            <flux:icon.exclamation-triangle class="w-3 h-3 mr-1" />
                            Critical
                        </span>
                    @elseif($item->log_level === 'error')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning/10 text-warning">
                            <flux:icon.x-circle class="w-3 h-3 mr-1" />
                            Error
                        </span>
                    @elseif($item->log_level === 'warning')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning/10 text-warning">
                            <flux:icon.exclamation-circle class="w-3 h-3 mr-1" />
                            Warning
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success/10 text-success">
                            <flux:icon.information-circle class="w-3 h-3 mr-1" />
                            Info
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary">
                    <div>{{ $item->logged_at->format('M j, Y') }}</div>
                    <div class="text-xs">{{ $item->logged_at->format('g:i A') }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex items-center justify-end space-x-2">
                        <flux:button href="{{ route('admin.audit_logs.show', $item->log_id) }}" variant="ghost" size="sm" icon="eye" title="View Log Details" />
                    </div>
                </td>
            </tr>
        @endforeach

        {{-- Empty Action Slot --}}
        <x-slot:emptyAction>
            <div class="text-center">
                <p class="text-text-secondary text-sm mb-4">No audit logs recorded yet.</p>
                <p class="text-text-secondary text-xs">System activities and user actions will be logged here.</p>
            </div>
        </x-slot:emptyAction>
    </x-ui.admin-table>

    <!-- Pagination -->
    <x-ui.admin-pagination
        :items="$logs"
        item-name="logs"
        :has-active-filters="$search || array_filter($filters)"
    />
</x-ui.admin-page-layout>
