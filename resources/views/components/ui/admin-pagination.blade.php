@props([
    'items',
    'itemName' => 'items',
    'hasActiveFilters' => false
])

<!-- Results Info and Pagination -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div class="flex items-center space-x-2 text-sm text-text-secondary">
        <span>
            Showing {{ $items->firstItem() ?? 0 }} to {{ $items->lastItem() ?? 0 }} 
            of {{ $items->total() }} {{ $itemName }}
        </span>
        @if($hasActiveFilters)
            <span class="text-text-secondary/50">•</span>
            <span class="text-primary">Filtered results</span>
        @endif
    </div>
    
    <div class="mt-3 sm:mt-0">
        {{ $items->links() }}
    </div>
</div>