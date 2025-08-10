<div class="bg-card rounded-lg border border-border p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-primary">Critical Stock Alerts</h3>
        <div class="flex items-center gap-2">
            @if($autoRefresh)
                <flux:button wire:click="$set('autoRefresh', false)" variant="ghost" size="sm">
                    <flux:icon name="pause" class="size-4" />
                </flux:button>
            @else
                <flux:button wire:click="$set('autoRefresh', true)" variant="ghost" size="sm">
                    <flux:icon name="play" class="size-4" />
                </flux:button>
            @endif
            <flux:button wire:click="refresh" variant="ghost" size="sm">
                <flux:icon name="arrow-path" class="size-4" />
            </flux:button>
        </div>
    </div>

    {{-- Summary Stats --}}
    <div class="grid grid-cols-2 gap-4 mb-4">
        <div class="p-4 bg-error/10 rounded-lg">
            <div class="text-2xl font-bold text-error">{{ $totalCritical }}</div>
            <div class="text-sm text-secondary">Critical Alerts</div>
        </div>
        <div class="p-4 bg-warning/10 rounded-lg">
            <div class="text-2xl font-bold text-warning">{{ $totalUnresolved }}</div>
            <div class="text-sm text-secondary">Total Unresolved</div>
        </div>
    </div>

    {{-- Critical Alerts List --}}
    @if($criticalAlerts->count() > 0)
        <div class="space-y-3">
            @foreach($criticalAlerts as $alert)
                <div class="flex items-center justify-between p-3 bg-muted/30 rounded-lg border-l-4 {{ $alert->alert_level === 'out_of_stock' ? 'border-error' : 'border-warning' }}">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <flux:icon name="{{ $alert->getAlertLevelEnum()->getIcon() }}" class="size-4 {{ $alert->getAlertLevelEnum()->getColorClass() }}" />
                            <span class="font-medium text-primary">{{ $alert->product->name }}</span>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $alert->getAlertLevelEnum()->getBadgeClass() }}">
                                {{ $alert->getAlertLevelEnum()->getLabel() }}
                            </span>
                        </div>
                        <div class="text-sm text-secondary">
                            Stock: {{ $alert->current_quantity }} / {{ $alert->threshold }}
                            @if($alert->getShortageAmount() > 0)
                                <span class="text-error ml-2">(Need {{ $alert->getShortageAmount() }} more)</span>
                            @endif
                        </div>
                        <div class="text-xs text-muted mt-1">
                            {{ $alert->triggered_at->diffForHumans() }}
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <flux:button 
                            wire:click="acknowledgeAlert({{ $alert->alert_id }})" 
                            variant="ghost" 
                            size="sm"
                            title="Acknowledge alert"
                        >
                            <flux:icon name="check" class="size-4" />
                        </flux:button>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- View All Link --}}
        @if($hasMore)
            <div class="mt-4 text-center">
                <flux:button 
                    wire:navigate
                    href="{{ route('admin.stock-alerts.index') }}"
                    variant="outline" 
                    size="sm"
                >
                    View All {{ $totalUnresolved }} Alerts
                    <flux:icon name="arrow-right" class="size-4 ml-1" />
                </flux:button>
            </div>
        @endif
    @else
        <div class="text-center py-8">
            <flux:icon name="check-circle" class="size-12 text-success mx-auto mb-2" />
            <div class="font-medium text-success">No Critical Alerts</div>
            <div class="text-sm text-secondary">All products are adequately stocked</div>
        </div>
    @endif

    {{-- Auto-refresh functionality --}}
    @if($autoRefresh)
        <div wire:poll.30s="refresh" class="hidden"></div>
    @endif
</div>