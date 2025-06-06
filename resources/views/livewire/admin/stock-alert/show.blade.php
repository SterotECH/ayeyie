<div>
    <!-- Header with Title and Breadcrumb -->
    <div class="mb-6 flex flex-col items-start justify-between md:flex-row md:items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Stock Alert Details</h1>
            <p class="text-sm text-gray-500">
                {{ $alert->product_name }} -
                {{-- {{ optional($alert->triggered_at->format('M d, Y H:i')) }} --}}
            </p>
        </div>

        <nav class="breadcrumbs text-sm">
            <ul>
                <li><a class="text-gray-500" href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><a class="text-gray-500" href="{{ route('admin.stock_alerts.index') }}">Inventory</a></li>
                <li class="font-medium text-gray-900">Details</li>
            </ul>
        </nav>
    </div>

    <!-- Alert Banner (shown after actions) -->
    @if (session()->has('message'))
        <div class="mb-6 border-l-4 border-green-400 bg-green-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">
                        {{ session('message') ?? 'Alert marked as reviewed' }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Alert Details Card -->
        <div class="lg:col-span-1">
            <div class="overflow-hidden rounded-lg bg-zinc-50 shadow dark:bg-zinc-800">
                <div class="border-b border-gray-200 px-4 py-5 sm:px-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Alert Information</h3>
                </div>

                {{-- <div class="px-4 py-5 sm:p-0">
                    <dl class="sm:divide-y sm:divide-gray-200 dark:divide-gray-900">
                        <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
                            <dt class="text-sm font-medium text-gray-500">Product</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $product->name }}</dd>
                        </div>

                        <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
                            <dt class="text-sm font-medium text-gray-500">SKU</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $product->sku ?? 'N/A' }}
                            </dd>
                        </div>

                        <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
                            <dt class="text-sm font-medium text-gray-500">Current Quantity</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $alert->current_quantity }}
                            </dd>
                        </div>

                        <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
                            <dt class="text-sm font-medium text-gray-500">Threshold</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $alert->threshold }}</dd>
                        </div>

                        <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1 sm:col-span-2 sm:mt-0">
                                @if ($alert->current_quantity <= $alert->threshold * 0.5)
                                    <span
                                        class="inline-flex rounded-full bg-red-100 px-2 text-xs font-semibold leading-5 text-red-800">
                                        Critical
                                    </span>
                                @else
                                    <span
                                        class="inline-flex rounded-full bg-yellow-100 px-2 text-xs font-semibold leading-5 text-yellow-800">
                                        Warning
                                    </span>
                                @endif
                            </dd>
                        </div>

                        <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
                            <dt class="text-sm font-medium text-gray-500">Triggered On</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                {{ $alert->triggered_at->format('F d, Y \a\t h:i A') }}</dd>
                        </div>

                        <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
                            <dt class="text-sm font-medium text-gray-500">Message</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $alert->alert_message }}
                            </dd>
                        </div>
                    </dl>
                </div> --}}
            </div>
            {{--
            <!-- Action Buttons -->
            <div class="mt-6 flex flex-col sm:flex-row sm:space-x-4">
                <button
                    class="inline-flex w-full items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50"
                    wire:click="takeAction('review')" @if ($markAsReviewed) disabled @endif>
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ $markAsReviewed ? 'Reviewed' : 'Mark as Reviewed' }}
                </button>

                <button
                    class="mt-3 inline-flex w-full items-center justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:mt-0"
                    wire:click="takeAction('reorder')">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Create Purchase Order
                </button>
            </div> --}}
        </div>
    </div>
</div>
