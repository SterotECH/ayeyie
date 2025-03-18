<div>
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <!-- User Information Section -->
        <div class="overflow-hidden bg-white shadow sm:rounded-lg dark:bg-slate-800">
            <div class="px-4 py-5 sm:px-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-accent text-lg font-medium leading-6">User Information</h3>
                        <p class="text-accent-content/50 mt-1 max-w-2xl text-sm">Personal details and account
                            information.</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.users.edit', $user->user_id) }}">
                            <flux:button>
                                Edit User
                            </flux:button>
                        </a>
                        <flux:button variant="danger" wire:click="confirmDelete">
                            Delete User
                        </flux:button>
                    </div>
                </div>
            </div>
            <div class="dark:border-accent border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 dark:bg-slate-900">
                        <dt class="text-sm font-medium text-gray-500 dark:text-white">Full name</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0 dark:text-gray-50">
                            {{ $user->name }}</dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 dark:bg-slate-800">
                        <dt class="text-sm font-medium text-gray-500 dark:text-white">Role</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0 dark:text-gray-50">
                            <span
                                class="{{ $user->role === 'admin'
                                    ? 'bg-purple-100 text-purple-800'
                                    : ($user->role === 'staff'
                                        ? 'bg-blue-100 text-blue-800'
                                        : 'bg-green-100 text-green-800') }} inline-flex rounded-full px-3 py-1 text-xs font-semibold">
                                {{ ucfirst($user->role) }}
                            </span>
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 dark:bg-slate-900">
                        <dt class="text-sm font-medium text-gray-500 dark:text-white">Email address</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0 dark:text-gray-50">
                            {{ $user->email ?? 'Not provided' }}</dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 dark:bg-slate-800">
                        <dt class="text-sm font-medium text-gray-500 dark:text-white">Phone number</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0 dark:text-gray-50">
                            {{ $user->phone }}</dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 dark:bg-slate-900">
                        <dt class="text-sm font-medium text-gray-500 dark:text-white">Language</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0 dark:text-gray-50">
                            {{ $user->language === 'en' ? 'English' : 'Twi' }}
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 dark:bg-slate-800">
                        <dt class="text-sm font-medium text-gray-500 dark:text-white">Account created</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0 dark:text-gray-50">
                            {{ $user->created_at->format('F j, Y g:i A') }}</dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 dark:bg-slate-900">
                        <dt class="text-sm font-medium text-gray-500 dark:text-white">Last updated</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0 dark:text-gray-50">
                            {{ $user->updated_at->format('F j, Y g:i A') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Tabs Section -->
        <div class="mt-6">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button
                        class="{{ $activeTab === 'transactions' ? 'border-accent text-accent' : 'border-transparent text-gray-500 dark:text-white hover:border-gray-300 hover:text-gray-700' }} whitespace-nowrap border-b-2 px-1 py-4 text-sm font-medium"
                        wire:click="$set('activeTab', 'transactions')">
                        Transactions
                    </button>
                    <button
                        class="{{ $activeTab === 'pickups' ? 'border-accent text-accent' : 'border-transparent text-gray-500 dark:text-white hover:border-gray-300 hover:text-gray-700' }} whitespace-nowrap border-b-2 px-1 py-4 text-sm font-medium"
                        wire:click="$set('activeTab', 'pickups')">
                        Pickups
                    </button>
                    <button
                        class="{{ $activeTab === 'suspicious' ? 'border-accent text-accent' : 'border-transparent text-gray-500 dark:text-white hover:border-gray-300 hover:text-gray-700' }} whitespace-nowrap border-b-2 px-1 py-4 text-sm font-medium"
                        wire:click="$set('activeTab', 'suspicious')">
                        Suspicious Activities
                    </button>
                    <button
                        class="{{ $activeTab === 'audit' ? 'border-accent text-accent' : 'border-transparent text-gray-500 dark:text-white hover:border-gray-300 hover:text-gray-700' }} whitespace-nowrap border-b-2 px-1 py-4 text-sm font-medium"
                        wire:click="$set('activeTab', 'audit')">
                        Audit Logs
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="mt-6">
                @if ($activeTab === 'transactions')
                    <div id="transactions-tab">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-50">Transactions</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-white">
                            {{ $user->role === 'customer' ? 'Transactions made by this customer' : 'Transactions processed by this staff member' }}
                        </p>

                        <div class="mt-4 rounded-md border">
                            <!-- Transaction data will be loaded here -->
                            <p class="p-4 text-sm text-gray-500 dark:text-white">Loading transactions...</p>
                        </div>
                    </div>
                @elseif ($activeTab === 'pickups')
                    <div id="pickups-tab">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-50">Pickups</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-white">
                            {{ $user->role === 'customer' ? 'Pickups made by this customer' : 'Pickups processed by this staff member' }}
                        </p>

                        <div class="mt-4 rounded-md border">
                            <!-- Pickup data will be loaded here -->
                            <p class="p-4 text-sm text-gray-500 dark:text-white">Loading pickups...</p>
                        </div>
                    </div>
                @elseif ($activeTab === 'suspicious')
                    <div id="suspicious-tab">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-50">Suspicious Activities</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-white">
                            Suspicious activities linked to this account
                        </p>

                        <div class="mt-4 rounded-md border">
                            <!-- Suspicious activities data will be loaded here -->
                            <p class="p-4 text-sm text-gray-500 dark:text-white">Loading suspicious activities...</p>
                        </div>
                    </div>
                @elseif ($activeTab === 'audit')
                    <div id="audit-tab">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-50">Audit Logs</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-white">
                            System actions performed by or related to this user
                        </p>

                        <div class="mt-4 rounded-md border">
                            <!-- Audit logs data will be loaded here -->
                            <p class="p-4 text-sm text-gray-500 dark:text-white">Loading audit logs...</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-data="{ show: @entangle('showDeleteModal') }">
        <div class="fixed inset-0 z-10 overflow-y-auto" role="dialog" aria-labelledby="modal-title" aria-modal="true"
            style="display: none;" x-show="show">
            <div class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-50 backdrop-blur-sm transition-opacity dark:bg-black/75"
                    aria-hidden="true" x-show="show"></div>
                <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>
                <div class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle dark:bg-slate-800"
                    x-show="show">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 dark:bg-slate-800">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <!-- Heroicon name: outline/exclamation -->
                                <svg class="h-6 w-6 text-red-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-50"
                                    id="modal-title">
                                    Delete User
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-white">
                                        Are you sure you want to delete this user? All of their data will be permanently
                                        removed.
                                        This action cannot be undone.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 dark:bg-slate-900">
                        <flux:button class="sm:ml-3" variant="danger" wire:click="deleteUser">
                            Delete
                        </flux:button>
                        <flux:button class="mt-3 sm:mt-0" wire:click="$set('showDeleteModal', false)">
                            Cancel
                        </flux:button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
