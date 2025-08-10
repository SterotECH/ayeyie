@props([
    'headers' => [],
    'items',
    'emptyTitle' => 'No Items Found',
    'emptyDescription' => 'No items match your current criteria.',
    'emptyAction' => '',
    'hasActiveFilters' => false,
    'resetFiltersMethod' => 'resetFilters',
    'sortBy' => null,
    'sortDirection' => null
])

<!-- Main Table -->
<div class="bg-card rounded-xl shadow-sm border border-border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-border">
            @if(count($headers) > 0)
                <thead class="bg-muted">
                <tr>
                    @foreach($headers as $header)
                        @if(isset($header['sortable']) && $header['sortable'])
                            <th wire:click="sortBy('{{ $header['field'] }}')"
                                class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase tracking-wider cursor-pointer hover:bg-muted-hover transition-colors">
                                <div class="flex items-center space-x-1">
                                    <span>{{ $header['label'] }}</span>
                                    @if($sortBy === $header['field'])
                                        @if($sortDirection === 'asc')
                                            <flux:icon.chevron-up class="w-4 h-4 text-primary"/>
                                        @else
                                            <flux:icon.chevron-down class="w-4 h-4 text-primary"/>
                                        @endif
                                    @else
                                        <flux:icon.chevron-up-down class="w-4 h-4 text-text-secondary/50"/>
                                    @endif
                                </div>
                            </th>
                        @else
                            <th class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase tracking-wider">
                                {{ $header['label'] }}
                            </th>
                        @endif
                    @endforeach
                </tr>
                </thead>
            @endif

            <tbody class="bg-card divide-y divide-border">
            @if(count($items) > 0)
                {{ $slot }}
            @else
                <tr>
                    <td colspan="{{ count($headers) }}" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <flux:icon.squares-2x2 class="w-12 h-12 text-text-secondary/50 mb-4"/>
                            <h3 class="text-lg font-medium text-text-primary mb-2">{{ $emptyTitle }}</h3>
                            <p class="text-text-secondary mb-6">
                                @if($hasActiveFilters)
                                    {{ $emptyDescription }} Try adjusting your search criteria.
                                @else
                                    {{ $emptyDescription }}
                                @endif
                            </p>
                            @if($hasActiveFilters)
                                <flux:button variant="outline" wire:click="{{ $resetFiltersMethod }}">
                                    Clear All Filters
                                </flux:button>
                            @elseif($emptyAction)
                                {{ $emptyAction }}
                            @endif
                        </div>
                    </td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>
