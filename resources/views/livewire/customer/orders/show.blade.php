<x-ui.admin-page-layout
    :title="'Order Details #' . $transaction->transaction_id"
    description="View comprehensive order information and payment details"
    :breadcrumbs="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Orders', 'url' => route('customers.orders.index')],
        ['label' => 'Order #' . $transaction->transaction_id]
    ]"
    :stats="[
        ['label' => 'Order Items', 'value' => $stats['total_items'], 'icon' => 'shopping-bag', 'iconBg' => 'bg-primary/10', 'iconColor' => 'text-primary'],
        ['label' => 'Order Value', 'value' => '₵' . number_format($stats['total_value'], 2), 'icon' => 'currency-dollar', 'iconBg' => 'bg-success/10', 'iconColor' => 'text-success'],
        ['label' => 'Payment Status', 'value' => ucfirst($stats['payment_status']), 'icon' => 'credit-card', 'iconBg' => 'bg-info/10', 'iconColor' => 'text-info'],
        ['label' => 'Order Date', 'value' => $stats['order_date']->format('M j, Y'), 'icon' => 'calendar', 'iconBg' => 'bg-warning/10', 'iconColor' => 'text-warning']
    ]"
>
    <x-slot:actions>
        <div class="flex items-center gap-3">
            @if($transaction->payment_status === 'pending')
                <flux:button wire:click="contactStore" variant="outline" icon="phone">
                    Contact Store
                </flux:button>
            @endif
            @if($transaction->receipt && $transaction->receipt->pickup)
                <flux:button 
                    href="{{ route('customers.pickups.show', $transaction->receipt->pickup) }}" 
                    variant="outline" 
                    icon="truck"
                    wire:navigate
                >
                    View Pickup
                </flux:button>
            @endif
            <flux:button href="{{ route('customers.orders.index') }}" variant="ghost" icon="arrow-left">
                Back to Orders
            </flux:button>
        </div>
    </x-slot:actions>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Order Information --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Order Status Card --}}
            <div class="bg-card rounded-lg border border-border p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-primary">Order Status</h3>
                    <div class="flex items-center gap-2">
                        @if($transaction->payment_status === 'pending')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-warning/10 text-warning">
                                <flux:icon name="clock" class="w-4 h-4 mr-1" />
                                Payment Pending
                            </span>
                        @elseif($transaction->payment_status === 'completed')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-success/10 text-success">
                                <flux:icon name="check-circle" class="w-4 h-4 mr-1" />
                                Payment Completed
                            </span>
                        @elseif($transaction->payment_status === 'failed')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-error/10 text-error">
                                <flux:icon name="x-circle" class="w-4 h-4 mr-1" />
                                Payment Failed
                            </span>
                        @endif

                        @if($stats['has_pickup'])
                            @if($stats['pickup_status'] === 'completed')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-success/10 text-success">
                                    <flux:icon name="check-circle" class="w-4 h-4 mr-1" />
                                    Picked Up
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-warning/10 text-warning">
                                    <flux:icon name="truck" class="w-4 h-4 mr-1" />
                                    Awaiting Pickup
                                </span>
                            @endif
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-secondary">Order ID</dt>
                            <dd class="mt-1 text-lg font-semibold text-primary">#{{ $transaction->transaction_id }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-secondary">Order Date</dt>
                            <dd class="mt-1 text-sm text-primary">{{ $transaction->transaction_date->format('M j, Y g:i A') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-secondary">Payment Method</dt>
                            <dd class="mt-1 text-sm text-primary">{{ ucfirst($transaction->payment_method) }}</dd>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-secondary">Processed By</dt>
                            <dd class="mt-1">
                                @if($transaction->staff)
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-6 w-6 rounded-full bg-primary/10 flex items-center justify-center mr-2">
                                            <span class="text-xs font-medium text-primary">
                                                {{ substr($transaction->staff->name, 0, 1) }}
                                            </span>
                                        </div>
                                        <span class="text-sm text-primary">{{ $transaction->staff->name }}</span>
                                    </div>
                                @else
                                    <span class="text-sm text-muted">Not yet assigned</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-secondary">Total Amount</dt>
                            <dd class="mt-1 text-lg font-bold text-success">₵{{ number_format($transaction->total_amount, 2) }}</dd>
                        </div>
                        @if($transaction->receipt)
                            <div>
                                <dt class="text-sm font-medium text-secondary">Receipt Code</dt>
                                <dd class="mt-1 text-sm text-primary font-mono">{{ $transaction->receipt->receipt_code }}</dd>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Order Items --}}
            <div class="bg-card rounded-lg border border-border p-6">
                <h3 class="text-lg font-semibold text-primary mb-6">Order Items</h3>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="text-secondary border-b border-border">
                            <tr>
                                <th class="py-3 px-4 font-medium">Product</th>
                                <th class="py-3 px-4 font-medium text-center">Quantity</th>
                                <th class="py-3 px-4 font-medium text-right">Unit Price</th>
                                <th class="py-3 px-4 font-medium text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaction->items as $item)
                                <tr class="border-b border-border hover:bg-muted/30 transition-colors">
                                    <td class="py-3 px-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 rounded-lg bg-primary/10 flex items-center justify-center mr-3">
                                                <flux:icon name="cube" class="size-5 text-primary" />
                                            </div>
                                            <div>
                                                <div class="font-medium text-primary">{{ $item->product->name }}</div>
                                                @if($item->product->description)
                                                    <div class="text-xs text-secondary mt-1">{{ Str::limit($item->product->description, 50) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary/10 text-primary">
                                            {{ $item->quantity }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-right text-primary">₵{{ number_format($item->unit_price, 2) }}</td>
                                    <td class="py-3 px-4 text-right font-medium text-primary">₵{{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="border-t border-border">
                            <tr>
                                <td colspan="3" class="py-4 px-4 text-right font-semibold text-secondary">Total Amount:</td>
                                <td class="py-4 px-4 text-right text-lg font-bold text-primary">₵{{ number_format($transaction->total_amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- Sidebar Information --}}
        <div class="space-y-6">
            {{-- Order Summary --}}
            <div class="bg-card rounded-lg border border-border p-6">
                <h3 class="text-lg font-semibold text-primary mb-4">Order Summary</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-secondary">Order Date</span>
                        <span class="text-sm text-primary">{{ $transaction->transaction_date->format('M j, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-secondary">Total Items</span>
                        <span class="text-sm text-primary">{{ $transaction->items->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-secondary">Payment Method</span>
                        <span class="text-sm text-primary">{{ ucfirst($transaction->payment_method) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-secondary">Payment Status</span>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                            @if($transaction->payment_status === 'completed') bg-success/10 text-success 
                            @elseif($transaction->payment_status === 'pending') bg-warning/10 text-warning 
                            @else bg-error/10 text-error @endif">
                            {{ ucfirst($transaction->payment_status) }}
                        </span>
                    </div>
                    <hr class="border-border">
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-primary">Total Amount</span>
                        <span class="text-sm font-bold text-success">₵{{ number_format($transaction->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Pickup Information --}}
            @if($transaction->receipt && $transaction->receipt->pickup)
                <div class="bg-card rounded-lg border border-border p-6">
                    <h3 class="text-lg font-semibold text-primary mb-4">Pickup Information</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-secondary">Pickup Status</span>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $transaction->receipt->pickup->pickup_status === 'completed' ? 'bg-success/10 text-success' : 'bg-warning/10 text-warning' }}">
                                {{ ucfirst($transaction->receipt->pickup->pickup_status) }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-secondary">
                                {{ $transaction->receipt->pickup->pickup_status === 'completed' ? 'Pickup Completed' : 'Preferred Pickup Date' }}
                            </span>
                            <span class="text-sm text-primary">
                                @if($transaction->receipt->pickup->pickup_status === 'completed')
                                    {{ $transaction->receipt->pickup->pickup_date->format('M j, Y g:i A') }}
                                @else
                                    {{ $transaction->receipt->pickup->pickup_date ? $transaction->receipt->pickup->pickup_date->format('M j, Y') : 'Not set' }}
                                @endif
                            </span>
                        </div>
                        @if($transaction->receipt->pickup->user)
                            <div class="flex justify-between">
                                <span class="text-sm text-secondary">Processed By</span>
                                <span class="text-sm text-primary">{{ $transaction->receipt->pickup->user->name }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="mt-4">
                        <flux:button 
                            href="{{ route('customers.pickups.show', $transaction->receipt->pickup) }}" 
                            variant="primary" 
                            size="sm" 
                            class="w-full"
                            wire:navigate
                        >
                            <flux:icon name="truck" class="size-4 mr-2" />
                            View Pickup Details
                        </flux:button>
                    </div>
                </div>
            @endif

            {{-- Auto-Cleanup Notice --}}
            @if($transaction->payment_status === 'pending')
                <div class="bg-info/5 rounded-lg border border-info/20 p-4">
                    <div class="flex items-start">
                        <flux:icon name="clock" class="size-5 text-info mt-0.5 mr-3 flex-shrink-0" />
                        <div class="text-sm">
                            <p class="font-medium text-primary mb-1">Auto-Cleanup Notice</p>
                            <p class="text-secondary">
                                Orders that remain unpaid for 7 days are automatically cancelled and items returned to inventory. 
                                Customers with multiple unpaid orders may be flagged for review.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Payment Instructions (for pending payments) --}}
            @if($transaction->payment_status === 'pending')
                <div class="bg-warning/5 rounded-lg border border-warning/20 p-6">
                    <h3 class="text-lg font-semibold text-primary mb-4 flex items-center">
                        <flux:icon name="information-circle" class="size-5 text-warning mr-2" />
                        Payment Instructions
                    </h3>
                    
                    <div class="space-y-3">
                        <div class="flex items-center mb-3">
                            <flux:icon name="shopping-bag" class="size-5 text-success mr-2" />
                            <span class="text-base font-medium text-primary">Cash Payment & Pickup</span>
                        </div>
                        <div class="space-y-2 text-sm text-secondary">
                            <p>✓ Your order has been confirmed</p>
                            <p>✓ Please visit our store and <strong class="text-primary">pay ₵{{ number_format($transaction->total_amount, 2) }} on pickup</strong></p>
                            <p>✓ Store pickup is the default option</p>
                            <p>✓ Delivery is available for an additional fee</p>
                        </div>
                        <div class="mt-4 p-3 bg-success/5 border border-success/20 rounded-lg">
                            <p class="text-sm text-success font-medium">Ready for pickup - Pay on pickup</p>
                        </div>
                        <div class="mt-4">
                            <flux:button 
                                wire:click="requestDelivery" 
                                variant="outline" 
                                size="sm" 
                                class="w-full"
                                icon="truck"
                            >
                                Request Paid Delivery
                            </flux:button>
                        </div>
                    </div>
                </div>
            @endif

            {{-- QR Code for Pickup --}}
            @if($transaction->receipt && $transaction->receipt->qr_code)
                <div class="bg-card rounded-lg border border-border p-6">
                    <h3 class="text-lg font-semibold text-primary mb-4 flex items-center">
                        <flux:icon name="qr-code" class="size-5 text-primary mr-2"/>
                        Pickup QR Code
                    </h3>
                    
                    <div class="text-center space-y-4">
                        <div class="flex justify-center">
                            <img src="{{ $transaction->receipt->qr_code }}" 
                                 alt="Order QR Code" 
                                 class="w-48 h-48 border border-border rounded-lg"/>
                        </div>
                        <div class="space-y-2">
                            <p class="text-sm font-medium text-primary">Receipt Code: {{ $transaction->receipt->receipt_code }}</p>
                            <p class="text-xs text-secondary">Show this QR code to staff for order verification and pickup</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Contact Information --}}
            <div class="bg-card rounded-lg border border-border p-6">
                <h3 class="text-lg font-semibold text-primary mb-4">Store Contact</h3>
                <div class="space-y-3">
                    <div class="flex items-center">
                        <flux:icon name="phone" class="size-4 text-secondary mr-3" />
                        <span class="text-sm text-primary">+233 123 456 789</span>
                    </div>
                    <div class="flex items-center">
                        <flux:icon name="envelope" class="size-4 text-secondary mr-3" />
                        <span class="text-sm text-primary">support@ayeyie.com</span>
                    </div>
                    <div class="flex items-start">
                        <flux:icon name="map-pin" class="size-4 text-secondary mr-3 mt-0.5" />
                        <span class="text-sm text-primary">123 Poultry Road<br>Accra, Ghana</span>
                    </div>
                </div>
                
                <div class="mt-4">
                    <flux:button wire:click="contactStore" variant="primary" size="sm" class="w-full">
                        <flux:icon name="phone" class="size-4 mr-2" />
                        Contact Store
                    </flux:button>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-card rounded-lg border border-border p-6">
                <h3 class="text-lg font-semibold text-primary mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    @if($transaction->payment_status === 'pending')
                        <flux:button href="{{ route('customers.orders.edit', $transaction) }}" variant="outline" size="sm" class="w-full justify-start" wire:navigate>
                            <flux:icon name="pencil" class="size-4 mr-2" />
                            Edit Order
                        </flux:button>
                    @endif
                    <flux:button href="{{ route('customers.orders.index') }}" variant="outline" size="sm" class="w-full justify-start" wire:navigate>
                        <flux:icon name="clipboard-document-list" class="size-4 mr-2" />
                        My Orders
                    </flux:button>
                    <flux:button href="{{ route('customers.pickups.index') }}" variant="outline" size="sm" class="w-full justify-start" wire:navigate>
                        <flux:icon name="truck" class="size-4 mr-2" />
                        My Pickups
                    </flux:button>
                    <flux:button href="{{ route('welcome.products.index') }}" variant="outline" size="sm" class="w-full justify-start" wire:navigate>
                        <flux:icon name="shopping-cart" class="size-4 mr-2" />
                        Shop More Products
                    </flux:button>
                </div>
            </div>
        </div>
    </div>
</x-ui.admin-page-layout>