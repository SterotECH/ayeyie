{{-- <div class="relative" x-data="{
    productHover: null,
    animateCart: false
}">
    <!-- Product Listing Section -->
    <div class="mb-6">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="hover:text-accent text-2xl font-bold transition-all duration-300">Available Products</h2>

            <!-- Search Bar with animation -->
            <div class="relative transition-all duration-300 hover:scale-105">
                <flux:input class="focus:ring-accent transition-all duration-300 focus:ring-2" type="text"
                    wire:model.live.debounce.300ms="searchQuery" placeholder="Search products..."
                    icon="magnifying-glass" />
            </div>

            <!-- Cart Toggle Button with notification badge -->
            <flux:modal.trigger name="cart">
                <flux:button class="relative transition-all duration-300 hover:scale-110" icon="shopping-bag"
                    size="sm" x-bind:class="{ 'animate-bounce': animateCart }">
                    <span
                        class="bg-accent absolute -right-2 -top-2 flex h-5 w-5 items-center justify-center rounded-full text-xs font-bold text-white shadow-lg transition-all duration-300 hover:scale-110">
                        {{ count($cartItems) }}
                    </span>
                </flux:button>
            </flux:modal.trigger>
        </div>

        <!-- Product Grid with hover effects -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4">
            @forelse ($products as $product)
                <div class="group overflow-hidden rounded-lg bg-zinc-50 shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-xl dark:bg-zinc-900"
                    x-on:mouseenter="productHover = {{ $product->product_id }}" x-on:mouseleave="productHover = null">
                    <div class="relative overflow-hidden">
                        @if ($product->image)
                            <img class="h-48 w-full object-cover transition-all duration-500 group-hover:scale-110"
                                src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                        @else
                            <div
                                class="flex h-48 w-full items-center justify-center bg-gray-200 transition-all duration-300 group-hover:bg-gray-300 dark:bg-zinc-800 dark:group-hover:bg-zinc-700">
                                <flux:icon.server-stack
                                    class="text-accent size-14 transition-all duration-300 group-hover:rotate-12 group-hover:scale-110" />
                            </div>
                        @endif

                        <div
                            class="absolute inset-0 flex items-center justify-center bg-black/30 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                            <flux:button class="transform transition-all duration-300 hover:scale-110"
                                wire:click="addToCart({{ $product->product_id }})" icon="shopping-cart"
                                variant="primary">
                                Quick Add
                            </flux:button>
                        </div>
                    </div>

                    <div class="p-4">
                        <h3 class="group-hover:text-accent mb-1 text-lg font-bold transition-all duration-300">
                            {{ $product->name }}</h3>
                        <flux:text class="mb-2 text-sm text-gray-600 dark:text-gray-300">
                            {{ Str::limit($product->description, 60) }}
                        </flux:text>
                        <div class="flex items-center justify-between">
                            <span
                                class="text-accent text-lg font-semibold transition-all duration-300 group-hover:scale-110">₦{{ number_format($product->price, 2) }}</span>
                            <span
                                class="group-hover:bg-accent rounded-full bg-gray-100 px-2 py-1 text-xs font-medium transition-all duration-300 group-hover:text-white dark:bg-zinc-700">
                                Stock: {{ $product->stock_quantity }}
                            </span>
                        </div>

                        <flux:button class="mt-3 w-full transform transition-all duration-300 hover:scale-105"
                            wire:click="addToCart({{ $product->product_id }})" icon="plus" variant="primary">
                            Add to Cart
                        </flux:button>
                    </div>
                </div>
            @empty
                <div class="col-span-full space-y-4 py-12 text-center">
                    <flux:icon.face-frown class="text-accent mx-auto size-24 animate-pulse" />
                    <flux:text class="text-lg font-medium">No products found. Try a different search.</flux:text>
                    <flux:button class="mt-4 animate-bounce" wire:click="resetSearch" icon="arrow-path"
                        variant="outline">
                        Reset Search
                    </flux:button>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Cart Slideover with improved animations -->
    <flux:modal name="cart" variant="flyout">
        <div class="space-y-6">
            <div class="border-b border-gray-200 pb-4 dark:border-zinc-700">
                <flux:heading class="flex items-center gap-2" size="lg">
                    <flux:icon.shopping-bag class="text-accent size-6" />
                    Your Cart
                </flux:heading>
            </div>

            @if (!$checkoutMode)
                <!-- Cart Items with animations -->
                <div>
                    @if (count($cartItems) > 0)
                        <div class="max-h-[60vh] space-y-4 overflow-y-auto pr-2">
                            @foreach ($cartItems as $index => $item)
                                <div
                                    class="flex items-center justify-between rounded-lg bg-gray-50 p-3 shadow-sm transition-all duration-300 hover:bg-gray-100 dark:bg-zinc-800 dark:hover:bg-zinc-700">
                                    <div>
                                        <h3 class="text-accent font-medium">{{ $item['name'] }}</h3>
                                        <div class="text-gray-500 dark:text-gray-300">
                                            ₦{{ number_format($item['unit_price'], 2) }} each
                                        </div>
                                    </div>

                                    <div class="flex items-center">
                                        <flux:button.group class="shadow-sm">
                                            <flux:button
                                                class="transition-all duration-300 hover:bg-red-100 dark:hover:bg-red-900"
                                                wire:click="updateQuantity({{ $index }}, -1)" size="sm"
                                                icon="minus">
                                            </flux:button>

                                            <div
                                                class="flex h-8 w-10 items-center justify-center bg-white font-medium dark:bg-zinc-700">
                                                {{ $item['quantity'] }}
                                            </div>

                                            <flux:button
                                                class="transition-all duration-300 hover:bg-green-100 dark:hover:bg-green-900"
                                                wire:click="updateQuantity({{ $index }}, 1)" size="sm"
                                                icon="plus">
                                            </flux:button>
                                        </flux:button.group>

                                        <flux:button
                                            class="ml-2 text-red-500 transition-all duration-300 hover:bg-red-100 hover:text-red-600 dark:hover:bg-red-900"
                                            size="sm" variant="ghost" wire:click="removeItem({{ $index }})"
                                            icon="trash">
                                        </flux:button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6 border-t pt-4 dark:border-zinc-700">
                            <div class="mb-4 flex justify-between text-lg font-bold">
                                <span>Total:</span>
                                <span class="text-accent">₦{{ number_format($totalAmount, 2) }}</span>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <flux:button
                                    class="transition-all duration-300 hover:bg-red-50 hover:text-red-500 dark:hover:bg-red-900"
                                    icon="trash" wire:click="clearCart" variant="outline">
                                    Clear Cart
                                </flux:button>

                                <flux:button class="transition-all duration-300 hover:scale-105" icon="banknotes"
                                    wire:click="startCheckout" variant="primary">
                                    Checkout
                                </flux:button>
                            </div>
                        </div>
                    @else
                        <div class="space-y-6 py-16 text-center">
                            <flux:icon.shopping-cart class="text-accent mx-auto size-24 animate-bounce" />
                            <div class="space-y-2">
                                <flux:text class="text-xl font-medium">Your cart is empty</flux:text>
                                <flux:text class="text-gray-500 dark:text-gray-400">Add some products to get started
                                </flux:text>
                            </div>
                            <flux:button class="transition-all duration-300 hover:scale-105" wire:click="toggleCart"
                                variant="outline" icon="arrow-left">
                                Continue Shopping
                            </flux:button>
                        </div>
                    @endif
                </div>
            @else
                <!-- Checkout Mode with animations -->
                <div>
                    <h3 class="mb-4 flex items-center gap-2 text-lg font-bold">
                        <flux:icon.credit-card class="text-accent size-5" />
                        Checkout
                    </h3>

                    <!-- Order Summary -->
                    <div class="mb-6 rounded-lg bg-gray-50 p-4 shadow-sm dark:bg-zinc-800">
                        <h4 class="mb-3 flex items-center gap-2 font-medium">
                            <flux:icon.clipboard-document-check class="text-accent size-5" />
                            Order Summary
                        </h4>
                        <div class="max-h-[30vh] space-y-2 overflow-y-auto pr-2">
                            @foreach ($cartItems as $item)
                                <div class="flex justify-between rounded border-b border-gray-100 pb-2 text-sm last:border-0 dark:border-zinc-700"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 transform translate-y-2"
                                    x-transition:enter-end="opacity-100 transform translate-y-0">
                                    <span>{{ $item['name'] }} <span
                                            class="text-gray-500">(x{{ $item['quantity'] }})</span></span>
                                    <span class="font-medium">₦{{ number_format($item['subtotal'], 2) }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4 flex justify-between border-t pt-3 font-bold dark:border-zinc-700">
                            <span>Total:</span>
                            <span class="text-accent text-lg">₦{{ number_format($totalAmount, 2) }}</span>
                        </div>
                    </div>

                    <!-- Payment Method with animations -->
                    <div class="mb-6">
                        <label class="mb-3 block flex items-center gap-2 font-medium text-gray-700 dark:text-gray-300">
                            <flux:icon.banknotes class="text-accent size-5" />
                            Payment Method
                        </label>
                        <div class="grid grid-cols-3 gap-3">
                            <label
                                class="hover:border-accent flex cursor-pointer items-center rounded-lg border bg-white p-3 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md dark:bg-zinc-800"
                                :class="{ 'border-accent ring-2 ring-accent': $wire.paymentMethod === 'cash' }">
                                <input class="form-radio text-accent h-5 w-5" type="radio" value="cash"
                                    wire:model.live="paymentMethod">
                                <span class="ml-2 font-medium">Cash</span>
                            </label>
                            <label
                                class="hover:border-accent flex cursor-pointer items-center rounded-lg border bg-white p-3 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md dark:bg-zinc-800"
                                :class="{ 'border-accent ring-2 ring-accent': $wire.paymentMethod === 'card' }">
                                <input class="form-radio text-accent h-5 w-5" type="radio" value="card"
                                    wire:model.live="paymentMethod">
                                <span class="ml-2 font-medium">Card</span>
                            </label>
                            <label
                                class="hover:border-accent flex cursor-pointer items-center rounded-lg border bg-white p-3 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md dark:bg-zinc-800"
                                :class="{ 'border-accent ring-2 ring-accent': $wire.paymentMethod === 'transfer' }">
                                <input class="form-radio text-accent h-5 w-5" type="radio" value="transfer"
                                    wire:model.live="paymentMethod">
                                <span class="ml-2 font-medium">Transfer</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex space-x-3">
                        <flux:button
                            class="flex-1 transition-all duration-300 hover:bg-gray-100 dark:hover:bg-zinc-700"
                            wire:click="cancelCheckout" icon="arrow-left" variant="outline">
                            Back to Cart
                        </flux:button>
                        <flux:button class="flex-1 transition-all duration-300 hover:scale-105" variant="primary"
                            wire:click="processTransaction" icon="check-circle">
                            Complete Order
                        </flux:button>
                    </div>
                </div>
            @endif
        </div>
    </flux:modal>

    <!-- Enhanced Notification System -->
    <div class="fixed right-0 top-0 z-50 space-y-3 p-4" style="width: 320px; max-height: 100vh; overflow-y: auto;"
        x-data="{
            notifications: [],
            add(message, type) {
                const audio = new Audio('{{ asset('/notification-sound.mp3') }}');
                audio.volume = 0.5;
                audio.play().catch(e => console.log('Audio play prevented: ' + e));

                this.notifications.push({
                    id: Date.now(),
                    type: type,
                    message: message,
                    isNew: true
                });

                const id = this.notifications[this.notifications.length - 1].id;

                setTimeout(() => {
                    const index = this.notifications.findIndex(n => n.id === id);
                    if (index !== -1) {
                        this.notifications[index].isNew = false;
                    }
                }, 300);

                setTimeout(() => {
                    this.remove(id);
                }, 4000);
            },
            remove(id) {
                this.notifications = this.notifications.filter(notification => notification.id !== id);
            }
        }"
        x-on:notify.window="console.log($event.detail[0].message); add($event.detail[0].message, $event.detail[0].type)">
        <template x-for="notification in notifications" :key="notification.id">
            <div class="rounded-lg p-4 shadow-lg" x-show="true"
                x-transition:enter="transform transition ease-out duration-300"
                x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transform transition ease-in-out duration-300"
                x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                x-bind:class="{
                    'bg-green-100 border-l-4 border-green-500 text-green-800 dark:bg-green-900 dark:text-green-100': notification
                        .type === 'success',
                    'bg-red-100 border-l-4 border-red-500 text-red-800 dark:bg-red-900 dark:text-red-100': notification
                        .type === 'error',
                    'animate-pulse': notification.isNew
                }">
                <div class="flex items-start justify-between">
                    <div class="flex items-start">
                        <div class="mr-3 flex-shrink-0" x-show="notification.type === 'success'">
                            <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="mr-3 flex-shrink-0" x-show="notification.type === 'error'">
                            <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="text-sm font-medium" x-text="notification.message"></div>
                    </div>
                    <button
                        class="ml-4 inline-flex rounded-full p-1.5 text-gray-500 transition-colors duration-300 hover:bg-gray-200 hover:text-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white"
                        x-on:click="remove(notification.id)">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <!-- Progress bar indicator for auto-dismiss -->
                <div class="mt-2 overflow-hidden rounded-full bg-gray-200 dark:bg-gray-600">
                    <div class="h-1 rounded-full" style="width: 100%; animation-duration: 4s;"
                        x-bind:class="{
                            'bg-green-500': notification.type === 'success',
                            'bg-red-500': notification.type === 'error',
                            'animate-progress-shrink': true
                        }">
                    </div>
                </div>
            </div>
        </template>
    </div>


    <!-- Add these styles to your app.css or create a new CSS file -->
    <style>
        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            50% {
                transform: translateX(5px);
            }

            75% {
                transform: translateX(-5px);
            }
        }

        @keyframes progress-shrink {
            from {
                width: 100%;
            }

            to {
                width: 0%;
            }
        }

        .animate-shake {
            animation: shake 0.5s cubic-bezier(0.36, 0.07, 0.19, 0.97) both;
        }

        .animate-progress-shrink {
            animation: progress-shrink linear forwards;
        }
    </style>
</div> --}}
<div class="p-6">
    <h1 class="text-accent text-2xl font-bold">Place Your Order</h1>
    <p class="text-accent/50 text-sm">Browse and add products to your cart</p>

    <!-- Search Products -->
    <div class="my-4">
        <flux:input wire:model.live="searchQuery" placeholder="Search products..." icon="magnifying-glass" />
    </div>

    <!-- Products List -->
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
        @foreach ($products as $product)
            <div class="rounded-lg bg-zinc-50 p-4 shadow dark:bg-zinc-800">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $product->name }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">GHS {{ number_format($product->price, 2) }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Stock: {{ $product->stock_quantity }}</p>
                <flux:button class="mt-2" wire:click="addToCart({{ $product->product_id }})" variant="filled">
                    Add to Cart
                </flux:button>
            </div>
        @endforeach
    </div>

    <!-- Cart -->
    @if ($showCart)
        <div class="mt-6 rounded-lg bg-zinc-50 p-4 shadow dark:bg-zinc-800">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Your Cart</h2>
            @forelse ($cartItems as $index => $item)
                <div class="flex items-center justify-between border-b py-2 dark:border-gray-700">
                    <div>
                        <p class="text-sm text-gray-900 dark:text-gray-100">{{ $item['name'] }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">GHS
                            {{ number_format($item['unit_price'], 2) }} x {{ $item['quantity'] }}</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <flux:button wire:click="updateQuantity({{ $index }}, -1)" size="sm">-</flux:button>
                        <span>{{ $item['quantity'] }}</span>
                        <flux:button wire:click="updateQuantity({{ $index }}, 1)" size="sm">+</flux:button>
                        <flux:button wire:click="removeItem({{ $index }})" variant="ghost" icon="trash"
                            size="sm" />
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500 dark:text-gray-400">Cart is empty</p>
            @endforelse
            <p class="mt-2 text-lg font-bold text-gray-900 dark:text-gray-100">Total: GHS
                {{ number_format($totalAmount, 2) }}</p>
            <flux:button class="mt-4" wire:click="startCheckout" variant="primary">Proceed to Checkout</flux:button>
        </div>
    @endif

    <!-- Checkout Modal -->
    @if ($checkoutMode)
        <flux:modal name="checkout" wire:model="checkoutMode">
            <flux:heading>Checkout</flux:heading>
            <div class="p-6">
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Amount: GHS
                    {{ number_format($totalAmount, 2) }}</p>

                <!-- Payment Fields -->
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone Number (Mobile
                        Money)</label>
                    <flux:input class="mt-1" wire:model="phoneNumber" placeholder="e.g., 233241234567" />
                </div>

                @if ($reference)
                    <div class="mt-4">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Payment Reference: {{ $reference }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">For demo: Approve in <a
                                class="text-amber-600" href="https://dashboard.paystack.com/#/test-transactions"
                                target="_blank">Paystack Dashboard</a>, then simulate.</p>
                        <flux:button class="mt-2" wire:click="simulatePayment" variant="success">Simulate Payment
                            Success</flux:button>
                    </div>
                @else
                    <flux:button class="mt-4" wire:click="processTransaction" variant="primary">Pay Now</flux:button>
                @endif

                <flux:button class="mt-2" wire:click="cancelCheckout" variant="ghost">Cancel</flux:button>
            </div>
        </flux:modal>
    @endif

    <!-- Paystack Inline JS -->
    <script src="https://js.paystack.co/v1/inline.js"></script>
    @script
        <script>
            Livewire.on('payment-initialized', (event) => {
                alert('{{ $amount }}')
                const handler = PaystackPop.setup({
                    key: '{{ config('services.paystack.public_key') }}',
                    email: '{{ Auth::user()->email ?? 'customer@ayeyie.com' }}',
                    amount: 100,
                    currency: 'GHS',
                    ref: '{{ $reference }}',
                    callback: function(response) {
                        alert(
                            'Payment initiated. Click "Simulate Payment Success" to complete.'
                        );
                    },
                    onClose: function() {
                        alert('Payment window closed.');
                    }
                });
                handler.openIframe();
            });
        </script>
    @endscript
</div>
