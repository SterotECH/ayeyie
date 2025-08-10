<div>
    <x-ui.admin-page-layout
        title="Shopping Cart & Checkout"
        description="Review your items and complete your order"
        :breadcrumbs="[
        ['label' => 'Orders', 'url' => route('customers.orders.index')],
        ['label' => 'Checkout']
    ]"
        :stats="[
        ['label' => 'Cart Items', 'value' => $stats['cart_items'], 'icon' => 'shopping-bag', 'iconBg' => 'bg-primary/10', 'iconColor' => 'text-primary'],
        ['label' => 'Subtotal', 'value' => '₵' . number_format($stats['subtotal'], 2), 'icon' => 'calculator', 'iconBg' => 'bg-success/10', 'iconColor' => 'text-success'],
        ['label' => 'Tax', 'value' => '₵' . number_format($stats['tax'], 2), 'icon' => 'document-text', 'iconBg' => 'bg-warning/10', 'iconColor' => 'text-warning'],
        ['label' => 'Total', 'value' => '₵' . number_format($stats['total'], 2), 'icon' => 'currency-dollar', 'iconBg' => 'bg-info/10', 'iconColor' => 'text-info']
    ]"
    >
        <x-slot:actions>
            <div class="flex items-center gap-3">
                <flux:button wire:click="continueShopping" variant="ghost" icon="arrow-left">
                    Continue Shopping
                </flux:button>
                <flux:button wire:click="clearCart" variant="outline" icon="trash"
                             class="text-error border-error hover:bg-error hover:text-white">
                    Clear Cart
                </flux:button>
            </div>
        </x-slot:actions>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Cart Items --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Cart Items List --}}
                <div class="bg-card rounded-lg border border-border p-6">
                    <h3 class="text-lg font-semibold text-primary mb-6">Cart Items ({{ count($cartItems) }})</h3>

                    <div class="space-y-4">
                        @foreach($cartItems as $item)
                            <div
                                class="flex items-center justify-between p-4 bg-muted/20 rounded-lg border border-border">
                                <div class="flex items-center">
                                    <div
                                        class="flex-shrink-0 h-12 w-12 rounded-lg bg-primary/10 flex items-center justify-center mr-4">
                                        <flux:icon name="cube" class="size-6 text-primary"/>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-medium text-primary">{{ $item['name'] }}</h4>
                                        <p class="text-sm text-secondary">{{ Str::limit($item['description'] ?? '', 50) }}</p>
                                        <div class="flex items-center gap-4 mt-2">
                                            <span class="text-sm text-secondary">₵{{ number_format($item['price'], 2) }} each</span>
                                            <span
                                                class="text-sm text-secondary">Stock: {{ $item['stock_quantity'] }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-4">
                                    {{-- Quantity Controls --}}
                                    <div class="flex items-center gap-2">
                                        <flux:button
                                            wire:click="updateQuantity({{ $item['product_id'] }}, {{ $item['quantity'] - 1 }})"
                                            variant="ghost"
                                            size="sm"
                                            icon="minus"
                                            class="h-8 w-8"
                                            :disabled="$item['quantity'] <= 1"
                                        />
                                        <span
                                            class="w-12 text-center font-medium text-primary">{{ $item['quantity'] }}</span>
                                        <flux:button
                                            wire:click="updateQuantity({{ $item['product_id'] }}, {{ $item['quantity'] + 1 }})"
                                            variant="ghost"
                                            size="sm"
                                            icon="plus"
                                            class="h-8 w-8"
                                            :disabled="$item['quantity'] >= $item['stock_quantity']"
                                        />
                                    </div>

                                    {{-- Item Total --}}
                                    <div class="text-right min-w-[80px]">
                                        <div class="font-bold text-primary">
                                            ₵{{ number_format($item['price'] * $item['quantity'], 2) }}</div>
                                    </div>

                                    {{-- Remove Button --}}
                                    <flux:button
                                        wire:click="removeItem({{ $item['product_id'] }})"
                                        variant="ghost"
                                        size="sm"
                                        icon="trash"
                                        class="text-error hover:bg-error/10"
                                        title="Remove item"
                                    />
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Order Summary & Payment --}}
            <div class="space-y-6">
                {{-- Order Summary --}}
                <div class="bg-card rounded-lg border border-border p-6">
                    <h3 class="text-lg font-semibold text-primary mb-4">Order Summary</h3>

                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-secondary">Subtotal</span>
                            <span class="text-sm text-primary font-medium">₵{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-secondary">Tax</span>
                            <span class="text-sm text-primary font-medium">₵{{ number_format($tax, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-secondary">Shipping</span>
                            <span class="text-sm text-success font-medium">Free</span>
                        </div>
                        <hr class="border-border">
                        <div class="flex justify-between">
                            <span class="text-base font-semibold text-primary">Total</span>
                            <span class="text-lg font-bold text-primary">₵{{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                </div>

                {{-- Customer Information --}}
                <div class="bg-card rounded-lg border border-border p-6">
                    <h3 class="text-lg font-semibold text-primary mb-4">Customer Information</h3>

                    <div class="space-y-4">
                        <flux:field>
                            <flux:label>Full Name</flux:label>
                            <flux:input wire:model="customerName" placeholder="Enter your full name"/>
                            <flux:error name="customerName"/>
                        </flux:field>

                        <flux:field>
                            <flux:label>Email Address</flux:label>
                            <flux:input type="email" wire:model="customerEmail" placeholder="Enter your email"/>
                            <flux:error name="customerEmail"/>
                        </flux:field>

                        <flux:field>
                            <flux:label>Phone Number</flux:label>
                            <flux:input wire:model="customerPhone" placeholder="Enter your phone number"/>
                            <flux:error name="customerPhone"/>
                        </flux:field>
                    </div>
                </div>

                {{-- Pickup Date --}}
                <div class="bg-card rounded-lg border border-border p-6">
                    <h3 class="text-lg font-semibold text-primary mb-4">Preferred Pickup Date</h3>
                    
                    <flux:field>
                        <flux:label>Select your preferred pickup date</flux:label>
                        <flux:input 
                            type="date" 
                            wire:model="preferredPickupDate" 
                            min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                            placeholder="Choose pickup date"/>
                        <flux:error name="preferredPickupDate"/>
                        <flux:description>Choose when you'd like to pick up your order. Pickup must be at least 1 day from now.</flux:description>
                    </flux:field>
                </div>

                {{-- Payment Method --}}
                <div class="bg-card rounded-lg border border-border p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-primary">Payment Method</h3>
                        <flux:badge variant="outline" class="text-xs">
                            Cash on Delivery
                        </flux:badge>
                    </div>

                    <div class="space-y-3">
                        <div class="p-4 bg-success/5 border border-success/20 rounded-lg">
                            <div class="flex items-center">
                                <flux:icon name="banknotes" class="size-5 text-success mr-2"/>
                                <div>
                                    <h4 class="font-medium text-primary">Pay on Pickup</h4>
                                    <p class="text-sm text-secondary">Pay cash when you collect your order at our store</p>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" wire:model="paymentMethod" value="cash">
                    </div>
                </div>

                {{-- Terms and Checkout --}}
                <div class="bg-card rounded-lg border border-border p-6">
                    <div class="space-y-4">
                        <div class="text-secondary">
                            <flux:checkbox wire:model.live="agreeToTerms" label="I agree to the terms and conditions"
                                           description="By selecting this you agree to our terms and condition"/>
                            <flux:error name="agreeToTerms"/>
                        </div>

                        <flux:button
                            wire:click="proceedToPayment"
                            variant="primary"
                            class="w-full"
                            icon="shopping-bag"
                            :disabled="!$agreeToTerms || $processingPayment"
                            wire:loading.attr="disabled"
                            wire:target="proceedToPayment"
                        >
                            <span wire:loading.remove wire:target="proceedToPayment">
                                Place Order (Pay on Pickup)
                            </span>
                            <span wire:loading wire:target="proceedToPayment">
                                Processing...
                            </span>
                        </flux:button>

                        @if($processingPayment)
                            <div class="bg-info/5 border border-info/20 rounded-lg p-4">
                                <div class="flex items-center">
                                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-info mr-2"></div>
                                    <span class="text-sm text-secondary">Processing your order... Please wait.</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Pickup Notice --}}
                <div class="bg-info/5 border border-info/20 rounded-lg p-4">
                    <div class="flex items-center">
                        <flux:icon name="information-circle" class="size-5 text-info mr-2"/>
                        <span class="text-sm text-secondary">Default: Store pickup. Request paid delivery after order placement.</span>
                    </div>
                </div>
            </div>
        </div>

    </x-ui.admin-page-layout>
</div>
