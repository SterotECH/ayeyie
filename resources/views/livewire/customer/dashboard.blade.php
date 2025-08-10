<div class="space-y-6">
    {{-- Customer Overview Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Total Orders --}}
        <div class="bg-card rounded-lg border border-border p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-secondary">Total Orders</p>
                    <p class="text-3xl font-bold text-primary">{{ number_format($stats['total_orders']) }}</p>
                </div>
                <div class="h-12 w-12 rounded-lg bg-primary/10 flex items-center justify-center">
                    <flux:icon name="shopping-bag" class="h-6 w-6 text-primary" />
                </div>
            </div>
            <div class="mt-4 text-xs text-muted">
                {{ $stats['completed_orders'] }} Completed • {{ $stats['pending_orders'] }} Pending
            </div>
        </div>

        {{-- Total Spent --}}
        <div class="bg-card rounded-lg border border-border p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-secondary">Total Spent</p>
                    <p class="text-3xl font-bold text-success">₵{{ number_format($stats['total_spent'], 2) }}</p>
                </div>
                <div class="h-12 w-12 rounded-lg bg-success/10 flex items-center justify-center">
                    <flux:icon name="currency-dollar" class="h-6 w-6 text-success" />
                </div>
            </div>
            <div class="mt-4 text-xs text-muted">
                Lifetime purchases
            </div>
        </div>

        {{-- Pending Pickups --}}
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
                {{ $stats['completed_pickups'] }} Already Collected
            </div>
        </div>

        {{-- This Month --}}
        <div class="bg-card rounded-lg border border-border p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-secondary">This Month</p>
                    <p class="text-3xl font-bold text-primary">{{ number_format($orderSummary['this_month_orders']) }}</p>
                </div>
                <div class="h-12 w-12 rounded-lg bg-primary/10 flex items-center justify-center">
                    <flux:icon name="chart-bar" class="h-6 w-6 text-primary" />
                </div>
            </div>
            <div class="mt-4 text-xs text-muted">
                ₵{{ number_format($orderSummary['this_month_spent'], 2) }} Spent
            </div>
        </div>
    </div>

    {{-- Main Content Area --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Recent Orders --}}
        <div class="lg:col-span-2">
            <div class="bg-card rounded-lg border border-border p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-primary">Recent Orders</h3>
                    <flux:button
                        href="{{ route('customers.orders.index') }}"
                        variant="outline"
                        size="sm"
                        wire:navigate
                    >
                        View All
                    </flux:button>
                </div>

                @if ($recentOrders->isEmpty())
                    <div class="text-center py-8">
                        <flux:icon name="shopping-bag" class="size-12 text-muted mx-auto mb-2" />
                        <p class="text-secondary">No orders yet</p>
                        <p class="text-sm text-muted">Your recent orders will appear here</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach ($recentOrders as $order)
                            <div class="flex items-center justify-between p-4 bg-muted/20 rounded-lg">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="font-medium text-primary">Order #{{ $order->transaction_id }}</span>
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            {{ $order->transaction_status === 'completed' ? 'bg-success/10 text-success' : 'bg-warning/10 text-warning' }}">
                                            {{ ucfirst($order->transaction_status) }}
                                        </span>
                                    </div>
                                    <div class="text-sm text-secondary">
                                        {{ $order->transactionItems->count() }} item(s) •
                                        ₵{{ number_format($order->total_amount, 2) }}
                                    </div>
                                    <div class="text-xs text-muted">
                                        {{ $order->transaction_date ? \Carbon\CarbonImmutable::parse($order->transaction_date)->format('M j, Y') : 'N/A' }}
                                    </div>
                                </div>
                                <div class="text-right">
                                    <flux:button
                                        href="{{ route('customers.orders.show', $order->transaction_id) }}"
                                        variant="ghost"
                                        size="sm"
                                        wire:navigate
                                    >
                                        <flux:icon name="eye" class="size-4" />
                                    </flux:button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Upcoming Pickups --}}
            <div class="bg-card rounded-lg border border-border p-6">
                <h3 class="text-lg font-semibold text-primary mb-4">Upcoming Pickups</h3>

                @if ($upcomingPickups->isEmpty())
                    <div class="text-center py-6">
                        <flux:icon name="truck" class="size-8 text-muted mx-auto mb-2" />
                        <p class="text-sm text-secondary">No pending pickups</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach ($upcomingPickups as $pickup)
                            <div class="p-3 bg-warning/5 rounded-lg border-l-4 border-warning">
                                <div class="font-medium text-primary mb-1">
                                    Order #{{ $pickup->transaction->transaction_id }}
                                </div>
                                <div class="text-sm text-secondary">
                                    Pickup Date: {{ $pickup->pickup_date ? \Carbon\CarbonImmutable::parse($pickup->pickup_date)->format('M j, Y') : 'TBD' }}
                                </div>
                                <div class="text-xs text-muted">
                                    {{ $pickup->transaction->transactionItems->count() }} item(s)
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        <flux:button
                            href="{{ route('customers.pickups.index') }}"
                            variant="outline"
                            size="sm"
                            class="w-full"
                            wire:navigate
                        >
                            View All Pickups
                        </flux:button>
                    </div>
                @endif
            </div>

            {{-- Quick Actions --}}
            <div class="bg-card rounded-lg border border-border p-6">
                <h3 class="text-lg font-semibold text-primary mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <flux:button
                        href="{{ route('welcome.products.index') }}"
                        variant="outline"
                        size="sm"
                        class="w-full justify-start"
                        wire:navigate
                    >
                        <flux:icon name="shopping-cart" class="size-4 mr-2" />
                        Browse Products
                    </flux:button>
                    <flux:button
                        href="{{ route('customers.orders.index') }}"
                        variant="outline"
                        size="sm"
                        class="w-full justify-start"
                        wire:navigate
                    >
                        <flux:icon name="clipboard-document-list" class="size-4 mr-2" />
                        My Orders
                    </flux:button>
                    <flux:button
                        href="{{ route('customers.pickups.index') }}"
                        variant="outline"
                        size="sm"
                        class="w-full justify-start"
                        wire:navigate
                    >
                        <flux:icon name="truck" class="size-4 mr-2" />
                        My Pickups
                    </flux:button>
                    <flux:button
                        href="{{ route('settings.profile') }}"
                        variant="outline"
                        size="sm"
                        class="w-full justify-start"
                        wire:navigate
                    >
                        <flux:icon name="user" class="size-4 mr-2" />
                        Update Profile
                    </flux:button>
                </div>
            </div>

            {{-- Spending Summary --}}
            <div class="bg-card rounded-lg border border-border p-6">
                <h3 class="text-lg font-semibold text-primary mb-4">Spending Summary</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-secondary">This Month</span>
                        <span class="font-medium text-success">₵{{ number_format($orderSummary['this_month_spent'], 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-secondary">Last Month</span>
                        <span class="font-medium text-muted">₵{{ number_format($orderSummary['last_month_spent'], 2) }}</span>
                    </div>
                    @if($orderSummary['spending_trend'] != 0)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-secondary">Trend</span>
                            <span class="font-medium {{ $orderSummary['spending_trend'] > 0 ? 'text-warning' : 'text-success' }}">
                                {{ $orderSummary['spending_trend'] > 0 ? '+' : '' }}{{ number_format($orderSummary['spending_trend'], 1) }}%
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
