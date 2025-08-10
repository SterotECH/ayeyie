@props([
    'title',
    'description' => '',
    'breadcrumbs' => [],
    'actions' => '',
    'stats' => [],
    'showFilters' => false,
    'filterSlot' => '',
    'searchPlaceholder' => 'Search...',
    'searchModel' => 'search',
    'hasActiveFilters' => false,
    'resetFiltersMethod' => 'resetFilters'
])

<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col items-start justify-between gap-4 md:flex-row md:items-center">
        <div>
            <h1 class="text-3xl font-bold text-text-primary">{{ $title }}</h1>
            @if($description)
                <p class="text-text-secondary">{{ $description }}</p>
            @endif
        </div>
        <div class="flex items-center space-x-4">
            <!-- Breadcrumb Navigation -->
            @if(count($breadcrumbs) > 0)
                <nav class="flex items-center space-x-2 text-sm">
                    <a href="{{ route('dashboard') }}" class="text-text-secondary hover:text-text-primary transition-colors">Dashboard</a>
                    @foreach($breadcrumbs as $breadcrumb)
                        <flux:icon.chevron-right class="w-4 h-4 text-text-secondary" />
                        @if(isset($breadcrumb['url']))
                            <a href="{{ $breadcrumb['url'] }}" class="text-text-secondary hover:text-text-primary transition-colors">{{ $breadcrumb['label'] }}</a>
                        @else
                            <span class="text-text-primary font-medium">{{ $breadcrumb['label'] }}</span>
                        @endif
                    @endforeach
                </nav>
            @endif

            <!-- Action Buttons -->
            @if($actions)
                <div>{{ $actions }}</div>
            @endif
        </div>
    </div>

    <!-- Statistics Cards -->
    @if(count($stats) > 0)
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($stats as $stat)
                <div class="bg-card rounded-xl shadow-sm p-6 border border-border">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 {{ $stat['iconBg'] ?? 'bg-primary/10' }} rounded-lg flex items-center justify-center">
                                <flux:icon.{{ $stat['icon'] }} class="{{ $stat['iconColor'] }}" />
                            </div>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-medium text-text-secondary">{{ $stat['label'] }}</dt>
                            <dd class="text-2xl font-bold text-text-primary">{{ $stat['value'] }}</dd>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Filters and Search -->
    @if($showFilters)
        <div class="bg-card rounded-xl shadow-sm border border-border">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-text-primary">Filters & Search</h3>
                    @if($hasActiveFilters)
                        <flux:button variant="ghost" wire:click="{{ $resetFiltersMethod }}" size="sm">
                            <flux:icon.x-mark class="w-4 h-4 mr-1" />
                            Clear Filters
                        </flux:button>
                    @endif
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                    <!-- Default Search Field -->
                    <div class="md:col-span-2">
                        <flux:field>
                            <flux:label>Search</flux:label>
                            <flux:input
                                wire:model.live.debounce.300ms="{{ $searchModel }}"
                                placeholder="{{ $searchPlaceholder }}"
                                icon="magnifying-glass"
                            />
                        </flux:field>
                    </div>

                    <!-- Custom Filter Slot -->
                    @if($filterSlot)
                        {{ $filterSlot }}
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Main Content Slot -->
    {{ $slot }}
</div>
