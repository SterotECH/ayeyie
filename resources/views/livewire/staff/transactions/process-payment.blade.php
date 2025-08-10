<div>
    <x-ui.admin-page-layout
        title="Process Payment"
        description="Process cash on delivery payments for customer orders"
        :breadcrumbs="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Process Payment']
        ]"
    >
        <div class="max-w-4xl mx-auto space-y-6">
            @if(!$orderFound && !$paymentProcessed)
                {{-- Receipt Code Entry --}}
                <div class="bg-card rounded-lg border border-border p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="size-10 rounded-lg bg-primary/10 flex items-center justify-center">
                            <flux:icon name="currency-dollar" class="size-5 text-primary" />
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-text-primary">Find Order for Payment</h3>
                            <p class="text-sm text-text-secondary">Manually enter receipt code to process cash on delivery payment</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="max-w-md">
                            <flux:field>
                                <flux:label>Receipt Code</flux:label>
                                <flux:input
                                    wire:model.live="receiptCode"
                                    placeholder="e.g., AYE2508101234"
                                    class="font-mono"
                                    autofocus
                                    wire:keydown.enter="findOrder"
                                />
                                <flux:error name="receiptCode"/>
                            </flux:field>
                        </div>

                        <div class="flex gap-3">
                            <flux:button wire:click="findOrder" variant="primary" icon="magnifying-glass" :disabled="$isSearching">
                                @if($isSearching)
                                    Searching...
                                @else
                                    Find Order
                                @endif
                            </flux:button>
                            <flux:button
                                href="{{ route('staff.qr-scanner', ['mode' => 'payment']) }}"
                                variant="outline"
                                icon="qr-code"
                                wire:navigate
                            >
                                Use QR Scanner
                            </flux:button>
                            @if($receiptCode)
                                <flux:button type="button" wire:click="resetForm" variant="ghost">
                                    Clear
                                </flux:button>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            @if($orderFound && !$paymentProcessed)
                {{-- Order Details & Payment Form --}}
                <div class="bg-card rounded-lg border border-border overflow-hidden">
                    {{-- Header --}}
                    <div class="px-6 py-4 border-b border-border bg-success/5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <flux:icon name="check-circle" class="size-6 text-success" />
                                <div>
                                    <h3 class="text-lg font-semibold text-text-primary">Order Ready for Payment</h3>
                                    <p class="text-sm text-text-secondary">Cash on Delivery - {{ $receiptCode }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-text-primary">₵{{ number_format($transaction->total_amount, 2) }}</div>
                                <div class="text-sm text-text-secondary">Amount Due</div>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            {{-- Customer & Order Info --}}
                            <div class="space-y-6">
                                {{-- Customer Information --}}
                                <div>
                                    <h4 class="text-sm font-semibold text-text-primary mb-3 flex items-center gap-2">
                                        <flux:icon name="user" class="size-4" />
                                        Customer Information
                                    </h4>
                                    <div class="bg-muted/30 rounded-lg p-4 space-y-2">
                                        <div class="flex justify-between text-sm">
                                            <span class="text-text-secondary">Name:</span>
                                            <span class="font-medium text-text-primary">{{ $customerName }}</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-text-secondary">Phone:</span>
                                            <span class="font-medium text-text-primary">{{ $customerPhone }}</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Order Information --}}
                                <div>
                                    <h4 class="text-sm font-semibold text-text-primary mb-3 flex items-center gap-2">
                                        <flux:icon name="document-text" class="size-4" />
                                        Order Details
                                    </h4>
                                    <div class="bg-muted/30 rounded-lg p-4 space-y-2">
                                        <div class="flex justify-between text-sm">
                                            <span class="text-text-secondary">Order ID:</span>
                                            <span class="font-medium text-text-primary">#{{ $transaction->transaction_id }}</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-text-secondary">Order Date:</span>
                                            <span class="font-medium text-text-primary">{{ $transaction->transaction_date->format('M j, Y g:i A') }}</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-text-secondary">Items:</span>
                                            <span class="font-medium text-text-primary">{{ $transaction->items->count() }} item(s)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Payment Form --}}
                            <div class="space-y-6">
                                <div>
                                    <h4 class="text-sm font-semibold text-text-primary mb-3 flex items-center gap-2">
                                        <flux:icon name="currency-dollar" class="size-4" />
                                        Payment Processing
                                    </h4>

                                    <div class="space-y-4">
                                        {{-- Amount Due --}}
                                        <div class="bg-primary/5 border border-primary/20 rounded-lg p-4">
                                            <div class="text-center">
                                                <div class="text-sm text-text-secondary mb-1">Total Amount Due</div>
                                                <div class="text-3xl font-bold text-primary">₵{{ number_format($transaction->total_amount, 2) }}</div>
                                            </div>
                                        </div>

                                        {{-- Amount Received Input --}}
                                        <flux:field>
                                            <flux:label>Amount Received (₵)</flux:label>
                                            <flux:input
                                                type="number"
                                                step="0.01"
                                                min="0"
                                                wire:model.live="amountReceived"
                                                wire:keyup="calculateChange"
                                                class="text-lg font-mono"
                                                placeholder="0.00"
                                            />
                                            <flux:error name="amountReceived"/>
                                        </flux:field>

                                        {{-- Change Calculation --}}
                                        @if($amountReceived > 0)
                                            <div class="bg-muted/30 rounded-lg p-4">
                                                <div class="flex justify-between items-center">
                                                    <span class="text-sm font-medium text-text-secondary">Change to Give:</span>
                                                    <span class="text-lg font-bold {{ $changeAmount > 0 ? 'text-warning' : 'text-success' }}">
                                                        ₵{{ number_format($changeAmount, 2) }}
                                                    </span>
                                                </div>
                                                @if($amountReceived < $transaction->total_amount)
                                                    <div class="mt-2 text-sm text-error">
                                                        Insufficient amount - ₵{{ number_format($transaction->total_amount - $amountReceived, 2) }} short
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Order Items --}}
                        <div class="mt-6 pt-6 border-t border-border">
                            <h4 class="text-sm font-semibold text-text-primary mb-4 flex items-center gap-2">
                                <flux:icon name="shopping-bag" class="size-4" />
                                Order Items
                            </h4>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-sm">
                                    <thead class="text-text-secondary border-b border-border">
                                        <tr>
                                            <th class="py-2 px-3 font-medium">Product</th>
                                            <th class="py-2 px-3 font-medium text-center">Qty</th>
                                            <th class="py-2 px-3 font-medium text-right">Unit Price</th>
                                            <th class="py-2 px-3 font-medium text-right">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($transaction->items as $item)
                                            <tr class="border-b border-border">
                                                <td class="py-3 px-3">
                                                    <div class="font-medium text-text-primary">{{ $item->product->name }}</div>
                                                    @if($item->product->description)
                                                        <div class="text-xs text-text-secondary mt-1">{{ Str::limit($item->product->description, 50) }}</div>
                                                    @endif
                                                </td>
                                                <td class="py-3 px-3 text-center">
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-primary/10 text-primary">
                                                        {{ $item->quantity }}
                                                    </span>
                                                </td>
                                                <td class="py-3 px-3 text-right text-text-primary">₵{{ number_format($item->unit_price, 2) }}</td>
                                                <td class="py-3 px-3 text-right font-medium text-text-primary">₵{{ number_format($item->subtotal, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="border-t border-border bg-muted/20">
                                        <tr>
                                            <td colspan="3" class="py-3 px-3 text-right font-semibold text-text-secondary">Total Amount:</td>
                                            <td class="py-3 px-3 text-right text-xl font-bold text-primary">₵{{ number_format($transaction->total_amount, 2) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        {{-- Payment Actions --}}
                        <div class="mt-6 pt-6 border-t border-border">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="font-medium text-text-primary">Complete Payment Processing</h4>
                                    <p class="text-sm text-text-secondary mt-1">Confirm cash payment has been received from customer.</p>
                                </div>
                                <div class="flex gap-3">
                                    <flux:button wire:click="resetForm" variant="outline">
                                        Cancel
                                    </flux:button>
                                    <flux:button
                                        wire:click="processPayment"
                                        variant="primary"
                                        icon="check-circle"
                                        :disabled="$amountReceived < $transaction->total_amount"
                                    >
                                        Process Payment
                                    </flux:button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($paymentProcessed)
                {{-- Success Message --}}
                <div class="bg-success/5 border border-success/20 rounded-lg p-6 text-center">
                    <flux:icon name="check-circle" class="size-16 text-success mx-auto mb-4"/>
                    <h3 class="text-xl font-semibold text-success mb-2">Payment Processed Successfully!</h3>
                    <p class="text-success mb-4">Receipt #{{ $receiptCode }} - Payment of ₵{{ number_format($transaction->total_amount, 2) }} received.</p>

                    @if($changeAmount > 0)
                        <div class="bg-warning/10 border border-warning/20 rounded-lg p-4 mb-4">
                            <div class="flex items-center justify-center gap-2">
                                <flux:icon name="exclamation-triangle" class="size-5 text-warning" />
                                <span class="font-semibold text-warning">Change to Give: ₵{{ number_format($changeAmount, 2) }}</span>
                            </div>
                        </div>
                    @endif

                    <div class="flex justify-center gap-3">
                        <flux:button wire:click="resetForm" variant="primary">
                            Process Another Payment
                        </flux:button>
                        <flux:button href="{{ route('staff.pickups.index') }}" variant="outline" wire:navigate>
                            View All Pickups
                        </flux:button>
                    </div>
                </div>
            @endif
        </div>
    </x-ui.admin-page-layout>
</div>
