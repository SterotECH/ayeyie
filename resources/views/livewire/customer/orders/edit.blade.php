<x-ui.admin-page-layout
    title="Edit Order"
    description="Modify your pending order details"
    :breadcrumbs="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Orders', 'url' => route('customers.orders.index')],
        ['label' => 'Order #' . $transaction->transaction_id, 'url' => route('customers.orders.show', $transaction)],
        ['label' => 'Edit']
    ]"
    :stats="[
        ['label' => 'Items', 'value' => $stats['total_items'], 'icon' => 'shopping-bag', 'iconBg' => 'bg-primary/10', 'iconColor' => 'text-primary'],
        ['label' => 'Quantity', 'value' => $stats['total_quantity'], 'icon' => 'cube', 'iconBg' => 'bg-info/10', 'iconColor' => 'text-info'],
        ['label' => 'Subtotal', 'value' => '₵' . number_format($stats['subtotal'], 2), 'icon' => 'calculator', 'iconBg' => 'bg-success/10', 'iconColor' => 'text-success'],
        ['label' => 'Total', 'value' => '₵' . number_format($stats['total'], 2), 'icon' => 'currency-dollar', 'iconBg' => 'bg-accent/10', 'iconColor' => 'text-accent']
    ]"
>
    <x-slot:actions>
        <div class="flex items-center gap-3">
            <flux:button 
                wire:click="$parent.redirect('{{ route('customers.orders.show', $transaction) }}')" 
                variant="ghost" 
                icon="arrow-left"
            >
                Back to Order
            </flux:button>
            <flux:button 
                wire:click="cancelOrder" 
                variant="outline" 
                icon="x-mark" 
                class="text-error border-error hover:bg-error hover:text-white"
                wire:confirm="Are you sure you want to cancel this order? This action cannot be undone."
            >
                Cancel Order
            </flux:button>
        </div>
    </x-slot:actions>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Order Items --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Order Information --}}
            <div class="bg-card rounded-lg border border-border p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-primary">Order Information</h3>
                    <div class="flex items-center space-x-2">
                        <flux:badge variant="warning" icon="clock">{{ $transaction->payment_status }}</flux:badge>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <span class="text-sm font-medium text-secondary">Order ID:</span>
                        <p class="text-primary font-semibold">#{{ $transaction->transaction_id }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-secondary">Order Date:</span>
                        <p class="text-primary">{{ $transaction->transaction_date->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>

            {{-- Order Items List --}}
            <div class="bg-card rounded-lg border border-border p-6">
                <h3 class="text-lg font-semibold text-primary mb-6">Order Items</h3>
                
                <div class="space-y-4">
                    @foreach($transactionItems as $index => $item)
                        <div class="flex items-center justify-between p-4 bg-muted/20 rounded-lg border border-border">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12 rounded-lg bg-primary/10 flex items-center justify-center mr-4">
                                    <flux:icon name="cube" class="size-6 text-primary" />
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-medium text-primary">{{ $item['product_name'] }}</h4>
                                    <div class="flex items-center gap-4 mt-2">
                                        <span class="text-sm text-secondary">₵{{ number_format($item['unit_price'], 2) }} each</span>
                                        <span class="text-sm text-secondary">Stock: {{ $item['stock_quantity'] + $item['original_quantity'] }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-4">
                                {{-- Quantity Controls --}}
                                <div class="flex items-center gap-2">
                                    <flux:button
                                        wire:click="updateQuantity({{ $index }}, {{ $item['quantity'] - 1 }})"
                                        variant="ghost"
                                        size="sm"
                                        icon="minus"
                                        class="h-8 w-8"
                                        :disabled="$item['quantity'] <= 1"
                                    />
                                    <span class="w-12 text-center font-medium text-primary">{{ $item['quantity'] }}</span>
                                    <flux:button
                                        wire:click="updateQuantity({{ $index }}, {{ $item['quantity'] + 1 }})"
                                        variant="ghost"
                                        size="sm"
                                        icon="plus"
                                        class="h-8 w-8"
                                        :disabled="$item['quantity'] >= ($item['stock_quantity'] + $item['original_quantity'])"
                                    />
                                </div>
                                
                                {{-- Item Total --}}
                                <div class="text-right min-w-[80px]">
                                    <div class="font-bold text-primary">₵{{ number_format($item['unit_price'] * $item['quantity'], 2) }}</div>
                                </div>
                                
                                {{-- Remove Button --}}
                                <flux:button
                                    wire:click="removeItem({{ $index }})"
                                    variant="ghost"
                                    size="sm"
                                    icon="trash"
                                    class="text-error hover:bg-error/10"
                                    title="Remove item"
                                    :disabled="count($transactionItems) <= 1"
                                />
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Order Summary & Settings --}}
        <div class="space-y-6">
            {{-- Order Summary --}}
            <div class="bg-card rounded-lg border border-border p-6">
                <h3 class="text-lg font-semibold text-primary mb-4">Order Summary</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-secondary">Subtotal</span>
                        <span class="text-sm text-primary font-medium">₵{{ number_format($stats['subtotal'], 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-secondary">Tax</span>
                        <span class="text-sm text-primary font-medium">₵0.00</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-secondary">Shipping</span>
                        <span class="text-sm text-success font-medium">Free</span>
                    </div>
                    <hr class="border-border">
                    <div class="flex justify-between">
                        <span class="text-base font-semibold text-primary">Total</span>
                        <span class="text-lg font-bold text-primary">₵{{ number_format($stats['total'], 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Payment Method --}}
            <div class="bg-card rounded-lg border border-border p-6">
                <h3 class="text-lg font-semibold text-primary mb-4">Payment Method</h3>
                
                <div class="space-y-3">
                    <flux:radio.group wire:model.live="paymentMethod">
                        <flux:radio value="paystack" class="flex items-center p-3 rounded-lg border border-border hover:bg-muted/30">
                            <div class="flex items-center">
                                <flux:icon name="device-phone-mobile" class="size-5 text-primary mr-3" />
                                <div>
                                    <div class="font-medium text-primary">Mobile Money (Paystack)</div>
                                    <div class="text-sm text-secondary">Pay with your mobile money account</div>
                                </div>
                            </div>
                        </flux:radio>
                        
                        <flux:radio value="cash" class="flex items-center p-3 rounded-lg border border-border hover:bg-muted/30">
                            <div class="flex items-center">
                                <flux:icon name="banknotes" class="size-5 text-success mr-3" />
                                <div>
                                    <div class="font-medium text-primary">Cash on Delivery</div>
                                    <div class="text-sm text-secondary">Pay when you receive your order</div>
                                </div>
                            </div>
                        </flux:radio>
                        
                        <flux:radio value="bank_transfer" class="flex items-center p-3 rounded-lg border border-border hover:bg-muted/30">
                            <div class="flex items-center">
                                <flux:icon name="building-library" class="size-5 text-info mr-3" />
                                <div>
                                    <div class="font-medium text-primary">Bank Transfer</div>
                                    <div class="text-sm text-secondary">Transfer to our bank account</div>
                                </div>
                            </div>
                        </flux:radio>
                    </flux:radio.group>
                </div>
            </div>

            {{-- Customer Information --}}
            <div class="bg-card rounded-lg border border-border p-6">
                <h3 class="text-lg font-semibold text-primary mb-4">Customer Information</h3>
                
                <div class="space-y-3">
                    <div>
                        <span class="text-sm font-medium text-secondary">Name:</span>
                        <p class="text-primary">{{ $transaction->customer->name ?? 'Walk-in Customer' }}</p>
                    </div>
                    @if($transaction->customer)
                        <div>
                            <span class="text-sm font-medium text-secondary">Email:</span>
                            <p class="text-primary">{{ $transaction->customer->email }}</p>
                        </div>
                        @if($transaction->customer->phone)
                            <div>
                                <span class="text-sm font-medium text-secondary">Phone:</span>
                                <p class="text-primary">{{ $transaction->customer->phone }}</p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            {{-- Save Changes --}}
            <div class="bg-card rounded-lg border border-border p-6">
                <div class="space-y-4">
                    <flux:button
                        wire:click="saveChanges"
                        variant="primary"
                        size="lg"
                        class="w-full"
                        icon="check"
                        wire:loading.attr="disabled"
                        wire:target="saveChanges"
                    >
                        <span wire:loading.remove wire:target="saveChanges">
                            Save Changes
                        </span>
                        <span wire:loading wire:target="saveChanges">
                            Saving...
                        </span>
                    </flux:button>
                    
                    <p class="text-xs text-secondary text-center">
                        Changes will be saved to your pending order. You can still modify until payment is processed.
                    </p>
                </div>
            </div>

            {{-- Order Status Notice --}}
            <div class="bg-warning/5 border border-warning/20 rounded-lg p-4">
                <div class="flex items-center">
                    <flux:icon name="information-circle" class="size-5 text-warning mr-2" />
                    <span class="text-sm text-secondary">
                        This order is pending and can be modified until payment is completed.
                    </span>
                </div>
            </div>
        </div>
    </div>
</x-ui.admin-page-layout>