<x-ui.admin-page-layout 
    title="Stock Alert Details"
    description="Monitor and manage low stock inventory alerts"
    :breadcrumbs="[
        ['label' => 'Stock Alerts', 'url' => route('admin.stock_alerts.index')],
        ['label' => 'Alert #' . $alert->alert_id]
    ]"
    :show-filters="false"
>
    <x-slot:actions>
        <flux:button href="{{ route('admin.stock_alerts.index') }}" variant="ghost" icon="arrow-left">
            Back to Alerts
        </flux:button>
        <flux:button href="#" variant="primary" icon="check">
            Mark as Reviewed
        </flux:button>
        <flux:button href="#" variant="success" icon="shopping-cart">
            Create Purchase Order
        </flux:button>
    </x-slot:actions>

    @if (session()->has('message'))
        <div class="mb-6 p-4 rounded-lg bg-success/10 border border-success/20">
            <div class="flex items-center">
                <flux:icon.check-circle class="w-5 h-5 text-success mr-2" />
                <span class="text-success text-sm">{{ session('message') ?? 'Alert marked as reviewed' }}</span>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Alert Details Card -->
        <div class="lg:col-span-2">
            <div class="bg-card rounded-lg border border-border shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-border">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-text-primary">Alert Information</h3>
                            <p class="text-sm text-text-secondary mt-1">
                                Triggered on {{ $alert->triggered_at->format('M j, Y g:i A') }}
                            </p>
                        </div>
                        <div>
                            @if($alert->current_quantity <= $alert->threshold * 0.5)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-error/10 text-error">
                                    <flux:icon.exclamation-triangle class="w-4 h-4 mr-1" />
                                    Critical Level
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-warning/10 text-warning">
                                    <flux:icon.exclamation-circle class="w-4 h-4 mr-1" />
                                    Warning Level
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="px-6 py-6">
                    <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Product Information -->
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-text-secondary">Product</dt>
                            <dd class="mt-2">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12">
                                        <div class="h-12 w-12 rounded-lg bg-warning/10 flex items-center justify-center">
                                            <flux:icon.cube class="w-6 h-6 text-warning" />
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-text-primary">{{ $product->name }}</div>
                                        <div class="text-sm text-text-secondary">Product ID: {{ $product->product_id }}</div>
                                        @if($product->sku)
                                            <div class="text-sm text-text-secondary">SKU: {{ $product->sku }}</div>
                                        @endif
                                    </div>
                                </div>
                            </dd>
                        </div>

                        <!-- Current Quantity -->
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-text-secondary">Current Quantity</dt>
                            <dd class="mt-1">
                                <div class="text-2xl font-bold text-text-primary">{{ number_format($alert->current_quantity) }}</div>
                                <div class="text-sm text-text-secondary">units available</div>
                            </dd>
                        </div>

                        <!-- Threshold -->
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-text-secondary">Alert Threshold</dt>
                            <dd class="mt-1">
                                <div class="text-2xl font-bold text-text-primary">{{ number_format($alert->threshold) }}</div>
                                <div class="text-sm text-text-secondary">minimum required</div>
                            </dd>
                        </div>

                        <!-- Alert Message -->
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-text-secondary">Alert Message</dt>
                            <dd class="mt-1 text-sm text-text-primary">{{ $alert->alert_message ?? 'Stock quantity has fallen below the minimum threshold.' }}</dd>
                        </div>

                        <!-- Additional Details -->
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-text-secondary">Shortage</dt>
                            <dd class="mt-1 text-sm text-text-primary">
                                @php
                                    $shortage = $alert->threshold - $alert->current_quantity;
                                @endphp
                                @if($shortage > 0)
                                    <span class="text-error font-semibold">{{ number_format($shortage) }} units short</span>
                                @else
                                    <span class="text-success">No shortage</span>
                                @endif
                            </dd>
                        </div>

                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-text-secondary">Percentage Below Threshold</dt>
                            <dd class="mt-1 text-sm text-text-primary">
                                @php
                                    $percentage = ($alert->current_quantity / $alert->threshold) * 100;
                                @endphp
                                <span class="@if($percentage <= 50) text-error @else text-warning @endif font-semibold">
                                    {{ number_format($percentage, 1) }}%
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Action Panel -->
        <div class="lg:col-span-1">
            <div class="bg-card rounded-lg border border-border shadow-sm">
                <div class="px-6 py-4 border-b border-border">
                    <h3 class="text-lg font-semibold text-text-primary">Quick Actions</h3>
                    <p class="text-sm text-text-secondary mt-1">Resolve this stock alert</p>
                </div>
                <div class="px-6 py-6 space-y-4">
                    <flux:button variant="primary" size="sm" icon="check" class="w-full">
                        Mark as Reviewed
                    </flux:button>
                    <flux:button variant="success" size="sm" icon="shopping-cart" class="w-full">
                        Create Purchase Order
                    </flux:button>
                    <flux:button variant="ghost" size="sm" icon="pencil" class="w-full">
                        Update Threshold
                    </flux:button>
                </div>
            </div>

            <!-- Related Information -->
            <div class="mt-6 bg-card rounded-lg border border-border shadow-sm">
                <div class="px-6 py-4 border-b border-border">
                    <h3 class="text-lg font-semibold text-text-primary">Related Information</h3>
                </div>
                <div class="px-6 py-4">
                    <div class="text-center py-4">
                        <flux:icon.information-circle class="w-8 h-8 text-text-secondary mx-auto mb-2" />
                        <p class="text-text-secondary text-sm">Additional product analytics and history can be displayed here</p>
                        @if($product)
                            <flux:button href="{{ route('admin.products.show', $product) }}" variant="ghost" class="mt-3" icon="cube">
                                View Product Details
                            </flux:button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-ui.admin-page-layout>
