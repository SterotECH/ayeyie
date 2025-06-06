<div class="rounded-lg bg-white p-6 shadow-md">
    @if (session()->has('message'))
        <div class="relative mb-4 rounded border border-green-400 bg-green-100 px-4 py-3 text-green-700" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-800">Order #{{ $transaction->transaction_id }}</h2>
        <div class="flex space-x-2">
            <span
                class="@if ($transaction->payment_status === 'completed') bg-green-100 text-green-800
                @elseif($transaction->payment_status === 'pending') bg-yellow-100 text-yellow-800
                @else bg-red-100 text-red-800 @endif rounded-full px-3 py-1 text-sm font-semibold">
                {{ ucfirst($transaction->payment_status) }}
            </span>
            @if ($pickup)
                <span
                    class="@if ($pickup->pickup_status === 'completed') bg-green-100 text-green-800
                    @else bg-yellow-100 text-yellow-800 @endif rounded-full px-3 py-1 text-sm font-semibold">
                    {{ $pickup->pickup_status === 'completed' ? 'Picked Up' : 'Awaiting Pickup' }}
                </span>
            @endif
        </div>
    </div>

    <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-2">
        <div>
            <h3 class="mb-2 text-lg font-semibold text-gray-700">Transaction Details</h3>
            <div class="rounded bg-gray-50 p-4">
                <p class="mb-2"><span class="font-medium">Date:</span>
                    {{ $transaction->transaction_date->format('M d, Y h:i A') }}</p>
                <p class="mb-2"><span class="font-medium">Payment Method:</span>
                    {{ ucfirst($transaction->payment_method) }}</p>
                <p class="mb-2"><span class="font-medium">Total Amount:</span>
                    ${{ number_format($transaction->total_amount, 2) }}</p>
                <p class="mb-2"><span class="font-medium">Processed by:</span> {{ $staff->name ?? 'Unknown' }}</p>
            </div>
        </div>

        <div>
            <h3 class="mb-2 text-lg font-semibold text-gray-700">Customer Information</h3>
            <div class="rounded bg-gray-50 p-4">
                @if ($customer)
                    <p class="mb-2"><span class="font-medium">Name:</span> {{ $customer->name }}</p>
                    <p class="mb-2"><span class="font-medium">Email:</span> {{ $customer->email }}</p>
                    <p class="mb-2"><span class="font-medium">Phone:</span> {{ $customer->phone ?? 'N/A' }}</p>
                @else
                    <p class="italic text-gray-500">Walk-in customer (no account)</p>
                @endif
            </div>
        </div>
    </div>

    <div class="mb-6">
        <h3 class="mb-2 text-lg font-semibold text-gray-700">Order Items</h3>
        <div class="overflow-hidden rounded bg-gray-50">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                            Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                            Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Unit
                            Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                            Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach ($transactionItems as $item)
                        <tr>
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="flex items-center">
                                    @if ($item->product->image)
                                        <div class="h-10 w-10 flex-shrink-0">
                                            <img class="h-10 w-10 rounded-full"
                                                src="{{ asset('storage/' . $item->product->image) }}"
                                                alt="{{ $item->product->name }}">
                                        </div>
                                    @endif
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->product->name }}</div>
                                        <div class="text-sm text-gray-500">ID: {{ $item->product->product_id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $item->quantity }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                ${{ number_format($item->unit_price, 2) }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                ${{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium" colspan="3">Total:
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm font-bold">
                            ${{ number_format($transaction->total_amount, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    @if ($receipt)
        <div class="mb-6">
            <h3 class="mb-2 text-lg font-semibold text-gray-700">Receipt Information</h3>
            <div class="rounded bg-gray-50 p-4">
                <p class="mb-2"><span class="font-medium">Receipt Code:</span> {{ $receipt->receipt_code }}</p>
                <p class="mb-2"><span class="font-medium">Issued At:</span>
                    {{ $receipt->issued_at->format('M d, Y h:i A') }}</p>

                <div class="mt-4">
                    <button
                        class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        wire:click="toggleQrCode">
                        {{ $showQrCode ? 'Hide QR Code' : 'Show QR Code' }}
                    </button>
                </div>

                @if ($showQrCode)
                    <div class="mt-4 rounded bg-white p-4 shadow-sm">
                        <div class="text-center">
                            <img class="mx-auto" src="data:image/png;base64,{{ $receipt->qr_code }}" alt="QR Code">
                            <p class="mt-2 text-sm text-gray-500">Scan this QR code for pickup verification</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    @if ($pickup)
        <div class="mb-6">
            <h3 class="mb-2 text-lg font-semibold text-gray-700">Pickup Information</h3>
            <div class="rounded bg-gray-50 p-4">
                <p class="mb-2"><span class="font-medium">Status:</span> {{ ucfirst($pickup->pickup_status) }}</p>
                @if ($pickup->pickup_date)
                    <p class="mb-2"><span class="font-medium">Pickup Date:</span>
                        {{ $pickup->pickup_date->format('M d, Y h:i A') }}</p>
                @endif
                <p class="mb-2"><span class="font-medium">Processed by:</span> {{ $pickup->user->name ?? 'Unknown' }}
                </p>
            </div>
        </div>
    @endif

    @if ($canBePickedUp)
        <div class="mt-6 flex justify-end">
            <button
                class="inline-flex items-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                wire:click="markAsPickedUp">
                Mark as Picked Up
            </button>
        </div>
    @endif
</div>
