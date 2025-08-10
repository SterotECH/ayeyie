<div>
    <x-ui.admin-page-layout
        title="Order Verification"
        description="Verify and confirm customer orders for pickup"
        :breadcrumbs="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Order Verification']
        ]"
    >
        <div class="max-w-4xl mx-auto space-y-6">
            @if(!$orderFound)
                {{-- QR Code Scanner/Manual Entry --}}
                <div class="bg-card rounded-lg border border-border p-6">
                    <h3 class="text-lg font-semibold text-primary mb-4 flex items-center">
                        <flux:icon name="qr-code" class="size-5 text-primary mr-2"/>
                        Scan QR Code or Enter Details
                    </h3>
                    
                    <form wire:submit="verifyOrder">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <flux:field>
                                <flux:label>Receipt Code</flux:label>
                                <flux:input wire:model="receiptCode" placeholder="e.g., AYE2508101234" />
                                <flux:error name="receiptCode"/>
                            </flux:field>

                            <flux:field>
                                <flux:label>Transaction ID</flux:label>
                                <flux:input type="number" wire:model="transactionId" placeholder="e.g., 1" />
                                <flux:error name="transactionId"/>
                            </flux:field>
                        </div>

                        <div class="mt-4">
                            <flux:button type="submit" variant="primary" icon="search">
                                Verify Order
                            </flux:button>
                        </div>
                    </form>
                </div>
            @endif

            @if($orderFound && !$orderVerified)
                {{-- Order Details --}}
                <div class="bg-card rounded-lg border border-border p-6">
                    <h3 class="text-lg font-semibold text-primary mb-6 flex items-center">
                        <flux:icon name="check-circle" class="size-5 text-success mr-2"/>
                        Order Found - Ready for Pickup
                    </h3>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                        {{-- Customer Info --}}
                        <div class="space-y-4">
                            <h4 class="font-medium text-primary">Customer Information</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-secondary">Name:</span>
                                    <span class="text-primary">{{ $customerName }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-secondary">Phone:</span>
                                    <span class="text-primary">{{ $customerPhone }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Order Info --}}
                        <div class="space-y-4">
                            <h4 class="font-medium text-primary">Order Information</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-secondary">Order ID:</span>
                                    <span class="text-primary">#{{ $transaction->transaction_id }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-secondary">Receipt Code:</span>
                                    <span class="text-primary font-mono">{{ $receiptCode }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-secondary">Order Date:</span>
                                    <span class="text-primary">{{ $transaction->transaction_date->format('M j, Y g:i A') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-secondary">Payment Method:</span>
                                    <span class="text-primary">{{ ucfirst($transaction->payment_method) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-secondary">Total Amount:</span>
                                    <span class="text-primary font-bold">₵{{ number_format($transaction->total_amount, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Order Items --}}
                    <div class="border-t border-border pt-6">
                        <h4 class="font-medium text-primary mb-4">Order Items</h4>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead class="text-secondary border-b border-border">
                                    <tr>
                                        <th class="py-2 px-3 font-medium">Product</th>
                                        <th class="py-2 px-3 font-medium text-center">Qty</th>
                                        <th class="py-2 px-3 font-medium text-right">Price</th>
                                        <th class="py-2 px-3 font-medium text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transaction->items as $item)
                                        <tr class="border-b border-border">
                                            <td class="py-2 px-3">
                                                <div class="font-medium text-primary">{{ $item->product->name }}</div>
                                            </td>
                                            <td class="py-2 px-3 text-center">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-primary/10 text-primary">
                                                    {{ $item->quantity }}
                                                </span>
                                            </td>
                                            <td class="py-2 px-3 text-right text-primary">₵{{ number_format($item->unit_price, 2) }}</td>
                                            <td class="py-2 px-3 text-right font-medium text-primary">₵{{ number_format($item->subtotal, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="border-t border-border">
                                    <tr>
                                        <td colspan="3" class="py-3 px-3 text-right font-semibold text-secondary">Total Amount:</td>
                                        <td class="py-3 px-3 text-right text-lg font-bold text-primary">₵{{ number_format($transaction->total_amount, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    {{-- Confirmation Actions --}}
                    <div class="border-t border-border pt-6 mt-6">
                        <div class="flex justify-between items-center">
                            <div>
                                <h4 class="font-medium text-primary">Ready to Complete Pickup?</h4>
                                <p class="text-sm text-secondary mt-1">This will mark the order as picked up and payment as completed.</p>
                            </div>
                            <div class="flex gap-3">
                                <flux:button wire:click="$refresh" variant="outline">
                                    Cancel
                                </flux:button>
                                <flux:button wire:click="confirmPickup" variant="primary" icon="check-circle">
                                    Confirm Pickup
                                </flux:button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($orderVerified)
                {{-- Success Message --}}
                <div class="bg-success/5 border border-success/20 rounded-lg p-6 text-center">
                    <flux:icon name="check-circle" class="size-16 text-success mx-auto mb-4"/>
                    <h3 class="text-xl font-semibold text-success mb-2">Order Pickup Completed!</h3>
                    <p class="text-success">Receipt #{{ $receiptCode }} has been successfully processed.</p>
                    <div class="mt-4">
                        <flux:button href="{{ route('staff.orders.verify') }}" variant="primary" wire:navigate>
                            Verify Another Order
                        </flux:button>
                    </div>
                </div>
            @endif
        </div>
    </x-ui.admin-page-layout>
</div>