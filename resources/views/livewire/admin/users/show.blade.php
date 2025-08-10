<x-ui.admin-page-layout 
    title="{{ $user->name }}"
    description="View and manage user details"
    :breadcrumbs="[
        ['label' => 'Users', 'url' => route('admin.users.index')],
        ['label' => $user->name]
    ]"
    :show-filters="false"
>
    <x-slot:actions>
        <flux:button href="{{ route('admin.users.edit', $user->user_id) }}" icon="pencil">
            Edit User
        </flux:button>
        <flux:button variant="danger" wire:click="confirmDelete" icon="trash">
            Delete User
        </flux:button>
    </x-slot:actions>

    <!-- User Information Card -->
    <div class="bg-card rounded-lg border border-border shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-border">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-16 w-16">
                    <div class="h-16 w-16 rounded-full bg-primary/10 flex items-center justify-center">
                        <span class="text-xl font-semibold text-primary">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </span>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-text-primary">{{ $user->name }}</h3>
                    <p class="text-text-secondary">ID: {{ $user->user_id }}</p>
                </div>
            </div>
        </div>
        <div class="px-6 py-4">
            <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-text-secondary">Full name</dt>
                    <dd class="mt-1 text-sm text-text-primary font-medium">{{ $user->name }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-text-secondary">Role</dt>
                    <dd class="mt-1">
                        @if($user->role === 'admin')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-error/10 text-error">
                                <flux:icon.shield-check class="w-3 h-3 mr-1" />
                                Administrator
                            </span>
                        @elseif($user->role === 'manager')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning/10 text-warning">
                                <flux:icon.user-group class="w-3 h-3 mr-1" />
                                Manager
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success/10 text-success">
                                <flux:icon.user class="w-3 h-3 mr-1" />
                                User
                            </span>
                        @endif
                    </dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-text-secondary">Email address</dt>
                    <dd class="mt-1 text-sm text-text-primary">{{ $user->email ?? 'Not provided' }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-text-secondary">Phone number</dt>
                    <dd class="mt-1 text-sm text-text-primary">{{ $user->phone ?? 'Not provided' }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-text-secondary">Language</dt>
                    <dd class="mt-1 text-sm text-text-primary">{{ $user->language === 'en' ? 'English' : 'Twi' }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-text-secondary">Account status</dt>
                    <dd class="mt-1">
                        @if($user->email_verified_at)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success/10 text-success">
                                <flux:icon.check-circle class="w-3 h-3 mr-1" />
                                Verified
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning/10 text-warning">
                                <flux:icon.clock class="w-3 h-3 mr-1" />
                                Pending Verification
                            </span>
                        @endif
                    </dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-text-secondary">Account created</dt>
                    <dd class="mt-1 text-sm text-text-primary">{{ $user->created_at->format('M j, Y g:i A') }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-text-secondary">Last updated</dt>
                    <dd class="mt-1 text-sm text-text-primary">{{ $user->updated_at->format('M j, Y g:i A') }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Tabs Section -->
    <div class="mt-6">
        <div class="bg-card rounded-lg border border-border shadow-sm overflow-hidden">
            <div class="border-b border-border">
                <nav class="flex space-x-8 px-6" aria-label="Tabs">
                    <button
                        class="{{ $activeTab === 'transactions' ? 'border-primary text-primary' : 'border-transparent text-text-secondary hover:border-muted hover:text-text-primary' }} whitespace-nowrap border-b-2 px-1 py-4 text-sm font-medium"
                        wire:click="$set('activeTab', 'transactions')">
                        Transactions
                    </button>
                    <button
                        class="{{ $activeTab === 'pickups' ? 'border-primary text-primary' : 'border-transparent text-text-secondary hover:border-muted hover:text-text-primary' }} whitespace-nowrap border-b-2 px-1 py-4 text-sm font-medium"
                        wire:click="$set('activeTab', 'pickups')">
                        Pickups
                    </button>
                    <button
                        class="{{ $activeTab === 'suspicious' ? 'border-primary text-primary' : 'border-transparent text-text-secondary hover:border-muted hover:text-text-primary' }} whitespace-nowrap border-b-2 px-1 py-4 text-sm font-medium"
                        wire:click="$set('activeTab', 'suspicious')">
                        Suspicious Activities
                    </button>
                    <button
                        class="{{ $activeTab === 'audit' ? 'border-primary text-primary' : 'border-transparent text-text-secondary hover:border-muted hover:text-text-primary' }} whitespace-nowrap border-b-2 px-1 py-4 text-sm font-medium"
                        wire:click="$set('activeTab', 'audit')">
                        Audit Logs
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                @if ($activeTab === 'transactions')
                    <div id="transactions-tab">
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold text-text-primary">Transactions</h3>
                            <p class="text-sm text-text-secondary">
                                {{ $user->role === 'customer' ? 'Transactions made by this customer' : 'Transactions processed by this staff member' }}
                            </p>
                        </div>

                        <div class="bg-muted/50 rounded-lg p-8 text-center">
                            <flux:icon.clock class="w-8 h-8 text-text-secondary mx-auto mb-2" />
                            <p class="text-text-secondary text-sm">Loading transactions...</p>
                        </div>
                    </div>
                @elseif ($activeTab === 'pickups')
                    <div id="pickups-tab">
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold text-text-primary">Pickups</h3>
                            <p class="text-sm text-text-secondary">
                                {{ $user->role === 'customer' ? 'Pickups made by this customer' : 'Pickups processed by this staff member' }}
                            </p>
                        </div>

                        <div class="bg-muted/50 rounded-lg p-8 text-center">
                            <flux:icon.clock class="w-8 h-8 text-text-secondary mx-auto mb-2" />
                            <p class="text-text-secondary text-sm">Loading pickups...</p>
                        </div>
                    </div>
                @elseif ($activeTab === 'suspicious')
                    <div id="suspicious-tab">
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold text-text-primary">Suspicious Activities</h3>
                            <p class="text-sm text-text-secondary">
                                Suspicious activities linked to this account
                            </p>
                        </div>

                        <div class="bg-muted/50 rounded-lg p-8 text-center">
                            <flux:icon.clock class="w-8 h-8 text-text-secondary mx-auto mb-2" />
                            <p class="text-text-secondary text-sm">Loading suspicious activities...</p>
                        </div>
                    </div>
                @elseif ($activeTab === 'audit')
                    <div id="audit-tab">
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold text-text-primary">Audit Logs</h3>
                            <p class="text-sm text-text-secondary">
                                System actions performed by or related to this user
                            </p>
                        </div>

                        <div class="bg-muted/50 rounded-lg p-8 text-center">
                            <flux:icon.clock class="w-8 h-8 text-text-secondary mx-auto mb-2" />
                            <p class="text-text-secondary text-sm">Loading audit logs...</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <flux:modal name="delete-user" class="md:w-96" :show="$showDeleteModal">
        <div class="space-y-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-error/10 rounded-lg flex items-center justify-center">
                        <flux:icon.exclamation-triangle class="w-5 h-5 text-error" />
                    </div>
                </div>
                <div class="ml-3">
                    <flux:heading size="lg">Delete User</flux:heading>
                </div>
            </div>
            
            <p class="text-sm text-text-secondary">
                Are you sure you want to delete <strong>{{ $user->name }}</strong>? This action cannot be undone and will permanently remove all user data.
            </p>
            
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-border">
                <flux:button wire:click="$set('showDeleteModal', false)" variant="ghost">
                    Cancel
                </flux:button>
                <flux:button wire:click="deleteUser" variant="danger" icon="trash">
                    Delete User
                </flux:button>
            </div>
        </div>
    </flux:modal>
</x-ui.admin-page-layout>
