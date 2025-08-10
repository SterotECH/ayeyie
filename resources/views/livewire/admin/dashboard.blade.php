<div class="space-y-6">
    {{-- Admin Overview Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Users Overview --}}
        <div class="bg-card rounded-lg border border-border p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-secondary">Total Users</p>
                    <p class="text-3xl font-bold text-primary">{{ number_format($stats['total_users']) }}</p>
                </div>
                <div class="h-12 w-12 rounded-lg bg-primary/10 flex items-center justify-center">
                    <flux:icon name="users" class="h-6 w-6 text-primary" />
                </div>
            </div>
            <div class="mt-4 text-xs text-muted">
                {{ $stats['admin_users'] }} Admin • {{ $stats['staff_users'] }} Staff • {{ $stats['customer_users'] }} Customer
            </div>
        </div>

        {{-- Products Overview --}}
        <div class="bg-card rounded-lg border border-border p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-secondary">Products</p>
                    <p class="text-3xl font-bold text-primary">{{ number_format($stats['total_products']) }}</p>
                </div>
                <div class="h-12 w-12 rounded-lg bg-success/10 flex items-center justify-center">
                    <flux:icon name="cube" class="h-6 w-6 text-success" />
                </div>
            </div>
            <div class="mt-4 text-xs text-muted">
                {{ $stats['out_of_stock_products'] }} Out of Stock • {{ $stats['low_stock_products'] }} Low Stock
            </div>
        </div>

        {{-- Critical Alerts --}}
        <div class="bg-card rounded-lg border border-border p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-secondary">Critical Alerts</p>
                    <p class="text-3xl font-bold text-error">{{ number_format($stats['critical_alerts']) }}</p>
                </div>
                <div class="h-12 w-12 rounded-lg bg-error/10 flex items-center justify-center">
                    <flux:icon name="exclamation-triangle" class="h-6 w-6 text-error" />
                </div>
            </div>
            <div class="mt-4 text-xs text-muted">
                {{ $stats['unresolved_alerts'] }} Total Unresolved
            </div>
        </div>

        {{-- Security Overview --}}
        <div class="bg-card rounded-lg border border-border p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-secondary">Security Alerts</p>
                    <p class="text-3xl font-bold text-warning">{{ number_format($stats['suspicious_activities']) }}</p>
                </div>
                <div class="h-12 w-12 rounded-lg bg-warning/10 flex items-center justify-center">
                    <flux:icon name="shield-exclamation" class="h-6 w-6 text-warning" />
                </div>
            </div>
            <div class="mt-4 text-xs text-muted">
                {{ $stats['high_severity_fraud'] }} High Severity
            </div>
        </div>
    </div>

    {{-- System Overview Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Today's Activity --}}
        <div class="bg-card rounded-lg border border-border p-6">
            <h3 class="text-lg font-semibold text-primary mb-4">Today's Activity</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-secondary">Transactions</span>
                    <span class="font-medium text-primary">{{ number_format($systemOverview['todays_transactions']) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-secondary">Revenue</span>
                    <span class="font-medium text-success">₵{{ number_format($systemOverview['todays_revenue'], 2) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-secondary">Active Users</span>
                    <span class="font-medium text-primary">{{ number_format($systemOverview['active_users_today']) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-secondary">Pending Pickups</span>
                    <span class="font-medium text-warning">{{ number_format($stats['pending_pickups']) }}</span>
                </div>
            </div>
        </div>

        {{-- Monthly Overview --}}
        <div class="bg-card rounded-lg border border-border p-6">
            <h3 class="text-lg font-semibold text-primary mb-4">This Month</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-secondary">Total Transactions</span>
                    <span class="font-medium text-primary">{{ number_format($systemOverview['monthly_transactions']) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-secondary">Total Revenue</span>
                    <span class="font-medium text-success">₵{{ number_format($systemOverview['monthly_revenue'], 2) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-secondary">Avg Daily Revenue</span>
                    <span class="font-medium text-success">
                        ₵{{ number_format($systemOverview['monthly_revenue'] / max(1, now()->day), 2) }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-secondary">System Status</span>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-success/10 text-success">
                        {{ ucfirst($systemOverview['system_health']) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="bg-card rounded-lg border border-border p-6">
            <h3 class="text-lg font-semibold text-primary mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <flux:button
                    href="{{ route('admin.users.create') }}"
                    variant="outline"
                    size="sm"
                    class="w-full justify-start"
                    wire:navigate
                >
                    <flux:icon name="user-plus" class="size-4 mr-2" />
                    Add New User
                </flux:button>
                <flux:button
                    href="{{ route('admin.products.create') }}"
                    variant="outline"
                    size="sm"
                    class="w-full justify-start"
                    wire:navigate
                >
                    <flux:icon name="plus" class="size-4 mr-2" />
                    Add New Product
                </flux:button>
                <flux:button
                    href="{{ route('admin.stock_alerts.index') }}"
                    variant="outline"
                    size="sm"
                    class="w-full justify-start"
                    wire:navigate
                >
                    <flux:icon name="exclamation-triangle" class="size-4 mr-2" />
                    Manage Stock Alerts
                </flux:button>
                <flux:button
                    href="{{ route('admin.audit_logs.index') }}"
                    variant="outline"
                    size="sm"
                    class="w-full justify-start"
                    wire:navigate
                >
                    <flux:icon name="clipboard-document-list" class="size-4 mr-2" />
                    View Audit Logs
                </flux:button>
            </div>
        </div>
    </div>

    {{-- Critical Stock Alerts Widget --}}
    @if($criticalAlerts->count() > 0)
        <div class="bg-card rounded-lg border border-border p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-primary">Critical Stock Alerts</h3>
                <flux:button
                    href="{{ route('admin.stock_alerts.index') }}"
                    variant="outline"
                    size="sm"
                    wire:navigate
                >
                    View All
                </flux:button>
            </div>
            <div class="space-y-3">
                @foreach($criticalAlerts as $alert)
                    <div class="flex items-center justify-between p-3 bg-muted/30 rounded-lg border-l-4 border-error">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <flux:icon name="{{ $alert->getAlertLevelEnum()->getIcon() }}" class="size-4 text-error" />
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
                        </div>
                        <div class="text-xs text-muted">
                            {{ $alert->triggered_at->diffForHumans() }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Recent Suspicious Activities --}}
    @if($recentSuspiciousActivity->count() > 0)
        <div class="bg-card rounded-lg border border-border p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-primary">Recent Security Alerts</h3>
                <flux:button
                    href="{{ route('admin.suspicious-activities.index') }}"
                    variant="outline"
                    size="sm"
                    wire:navigate
                >
                    View All
                </flux:button>
            </div>
            <div class="space-y-3">
                @foreach($recentSuspiciousActivity as $activity)
                    <div class="flex items-center justify-between p-3 bg-warning/5 rounded-lg border-l-4 border-warning">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <flux:icon name="shield-exclamation" class="size-4 text-warning" />
                                <span class="font-medium text-primary">{{ $activity->user->name ?? 'Unknown User' }}</span>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-warning/10 text-warning">
                                    {{ ucfirst($activity->severity) }} Risk
                                </span>
                            </div>
                            <div class="text-sm text-secondary">
                                {{ $activity->description }}
                            </div>
                        </div>
                        <div class="text-xs text-muted">
                            {{ $activity->detected_at->diffForHumans() }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Recent Audit Logs --}}
    <div class="bg-card rounded-lg border border-border p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-primary">Recent Audit Logs</h3>
            <flux:button
                href="{{ route('admin.audit_logs.index') }}"
                variant="outline"
                size="sm"
                wire:navigate
            >
                View All
            </flux:button>
        </div>

        @if ($recentAuditLogs->isEmpty())
            <p class="text-secondary">No recent activity logged.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="text-primary border-b border-border">
                        <tr>
                            <th class="py-2 px-4 font-medium">User</th>
                            <th class="py-2 px-4 font-medium">Action</th>
                            <th class="py-2 px-4 font-medium">Details</th>
                            <th class="py-2 px-4 font-medium">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentAuditLogs as $log)
                            <tr class="border-b border-border hover:bg-muted/30 transition-colors">
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-2">
                                        <div class="h-8 w-8 rounded-full bg-primary/10 flex items-center justify-center">
                                            <span class="text-xs font-medium text-primary">
                                                {{ substr($log->user->name ?? 'Unknown', 0, 1) }}
                                            </span>
                                        </div>
                                        <span class="font-medium text-primary">{{ $log->user->name ?? 'Unknown User' }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-primary/10 text-primary">
                                        {{ str_replace('_', ' ', ucfirst($log->action)) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="text-secondary max-w-xs">
                                        @if(is_array($log->details))
                                            @if(isset($log->details['message']))
                                                {{ $log->details['message'] }}
                                            @elseif(isset($log->details['ip_address']))
                                                From {{ $log->details['ip_address'] }}
                                            @else
                                                {{ count($log->details) }} data points
                                            @endif
                                        @else
                                            {{ $log->details ?? 'N/A' }}
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-muted">
                                    <div>{{ $log->logged_at->format('M j, Y') }}</div>
                                    <div class="text-xs">{{ $log->logged_at->format('g:i A') }}</div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
