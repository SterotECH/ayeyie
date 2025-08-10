<div class="space-y-6">
    {{-- Staff Overview Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {{-- Inventory Overview --}}
        <div class="bg-card rounded-lg border border-border p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-secondary">Products</p>
                    <p class="text-3xl font-bold text-primary">{{ number_format($stats['total_products']) }}</p>
                </div>
                <div class="h-12 w-12 rounded-lg bg-primary/10 flex items-center justify-center">
                    <flux:icon name="cube" class="h-6 w-6 text-primary" />
                </div>
            </div>
            <div class="mt-4 text-xs text-muted">
                {{ $stats['out_of_stock_products'] }} Out of Stock • {{ $stats['low_stock_products'] }} Low Stock
            </div>
        </div>

        {{-- Pickups Overview --}}
        <div class="bg-card rounded-lg border border-border p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-secondary">Pending Pickups</p>
                    <p class="text-3xl font-bold text-warning">{{ number_format($stats['pending_pickups']) }}</p>
                </div>
                <div class="h-12 w-12 rounded-lg bg-warning/10 flex items-center justify-center">
                    <flux:icon name="truck" class="h-6 w-6 text-warning" />
                </div>
            </div>
            <div class="mt-4 text-xs text-muted">
                Requires immediate attention
            </div>
        </div>

        {{-- Stock Alerts --}}
        <div class="bg-card rounded-lg border border-border p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-secondary">Urgent Alerts</p>
                    <p class="text-3xl font-bold text-error">{{ number_format($stats['urgent_alerts']) }}</p>
                </div>
                <div class="h-12 w-12 rounded-lg bg-error/10 flex items-center justify-center">
                    <flux:icon name="exclamation-triangle" class="h-6 w-6 text-error" />
                </div>
            </div>
            <div class="mt-4 text-xs text-muted">
                Critical stock levels
            </div>
        </div>
    </div>

    {{-- Today's Overview --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Today's Activity --}}
        <div class="bg-card rounded-lg border border-border p-6">
            <h3 class="text-lg font-semibold text-primary mb-4">Today's Overview</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-secondary">Transactions</span>
                    <span class="font-medium text-primary">{{ number_format($todaysOverview['transactions']) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-secondary">Revenue</span>
                    <span class="font-medium text-success">₵{{ number_format($todaysOverview['revenue'], 2) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-secondary">Completed Pickups</span>
                    <span class="font-medium text-success">{{ number_format($todaysOverview['completed_pickups']) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-secondary">My Activities</span>
                    <span class="font-medium text-primary">{{ number_format($todaysOverview['my_activities']) }}</span>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="bg-card rounded-lg border border-border p-6">
            <h3 class="text-lg font-semibold text-primary mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <flux:button 
                    href="{{ route('staff.products.index') }}" 
                    variant="outline" 
                    size="sm" 
                    class="w-full justify-start"
                    wire:navigate
                >
                    <flux:icon name="cube" class="size-4 mr-2" />
                    Manage Products
                </flux:button>
                <flux:button 
                    href="{{ route('staff.pickups.index') }}" 
                    variant="outline" 
                    size="sm" 
                    class="w-full justify-start"
                    wire:navigate
                >
                    <flux:icon name="truck" class="size-4 mr-2" />
                    Process Pickups
                </flux:button>
                <flux:button 
                    href="{{ route('staff.stock-alerts.index') }}" 
                    variant="outline" 
                    size="sm" 
                    class="w-full justify-start"
                    wire:navigate
                >
                    <flux:icon name="exclamation-triangle" class="size-4 mr-2" />
                    View Stock Alerts
                </flux:button>
                <flux:button 
                    href="{{ route('staff.transactions.index') }}" 
                    variant="outline" 
                    size="sm" 
                    class="w-full justify-start"
                    wire:navigate
                >
                    <flux:icon name="currency-dollar" class="size-4 mr-2" />
                    View Transactions
                </flux:button>
            </div>
        </div>
    </div>

    {{-- Urgent Stock Alerts --}}
    @if($urgentAlerts->count() > 0)
        <div class="bg-card rounded-lg border border-border p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-primary">Urgent Stock Alerts</h3>
                <flux:button 
                    href="{{ route('staff.stock-alerts.index') }}" 
                    variant="outline" 
                    size="sm"
                    wire:navigate
                >
                    View All
                </flux:button>
            </div>
            <div class="space-y-3">
                @foreach($urgentAlerts as $alert)
                    <div class="flex items-center justify-between p-3 bg-error/5 rounded-lg border-l-4 border-error">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <flux:icon name="{{ $alert->getAlertLevelEnum()->getIcon() }}" class="size-4 text-error" />
                                <span class="font-medium text-primary">{{ $alert->product->name }}</span>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $alert->getAlertLevelEnum()->getBadgeClass() }}">
                                    {{ $alert->getAlertLevelEnum()->getLabel() }}
                                </span>
                            </div>
                            <div class="text-sm text-secondary">
                                Current Stock: {{ $alert->current_quantity }} / Threshold: {{ $alert->threshold }}
                                @if($alert->getShortageAmount() > 0)
                                    <span class="text-error ml-2">(Shortage: {{ $alert->getShortageAmount() }})</span>
                                @endif
                            </div>
                        </div>
                        <div class="text-xs text-muted">
                            {{ $alert->triggered_at->diffForHumans() }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Recent Activity --}}
    <div class="bg-card rounded-lg border border-border p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-primary">Recent Activity</h3>
        </div>
        
        @if ($recentActivity->isEmpty())
            <p class="text-secondary">No recent activity logged.</p>
        @else
            <div class="space-y-3">
                @foreach ($recentActivity as $activity)
                    <div class="flex items-center justify-between p-3 bg-muted/20 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded-full bg-primary/10 flex items-center justify-center">
                                <span class="text-xs font-medium text-primary">
                                    {{ substr($activity->user->name ?? 'U', 0, 1) }}
                                </span>
                            </div>
                            <div>
                                <div class="font-medium text-primary">{{ $activity->user->name ?? 'Unknown User' }}</div>
                                <div class="text-sm text-secondary">
                                    {{ str_replace('_', ' ', ucfirst($activity->action)) }}
                                    @if(is_array($activity->details) && isset($activity->details['message']))
                                        - {{ $activity->details['message'] }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="text-xs text-muted">
                            {{ $activity->logged_at->diffForHumans() }}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>