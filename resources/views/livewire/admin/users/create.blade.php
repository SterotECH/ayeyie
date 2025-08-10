<x-ui.admin-page-layout
    title="Create New User"
    description="Add a new user to the system with appropriate role and permissions"
    :breadcrumbs="[
        ['label' => 'Users', 'url' => route('admin.users.index')],
        ['label' => 'Create User']
    ]"
    :show-filters="false"
>
    <x-slot:actions>
        <flux:button href="{{ route('admin.users.index') }}" variant="ghost" icon="arrow-left">
            Back to Users
        </flux:button>
    </x-slot:actions>

    <div>
        <div class="bg-card rounded-lg border border-border shadow-sm">
            <div class="px-6 py-4 border-b border-border">
                <h3 class="text-lg font-semibold text-text-primary">User Details</h3>
                <p class="text-sm text-text-secondary mt-1">Enter the new user's information and account settings.</p>
            </div>

            <form wire:submit="save" class="px-6 py-6">
                @if (session('message'))
                    <div class="mb-6 p-4 rounded-lg bg-success/10 border border-success/20">
                        <div class="flex items-center">
                            <flux:icon.check-circle class="w-5 h-5 text-success mr-2" />
                            <span class="text-success text-sm">{{ session('message') }}</span>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 p-4 rounded-lg bg-error/10 border border-error/20">
                        <div class="flex items-center">
                            <flux:icon.exclamation-triangle class="w-5 h-5 text-error mr-2" />
                            <span class="text-error text-sm">{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                <div class="space-y-6">
                    <!-- Name -->
                    <div>
                        <flux:field>
                            <flux:label>Full Name</flux:label>
                            <flux:input name="name" wire:model="form.name" placeholder="Enter full name" />
                            <flux:error name="form.name" />
                        </flux:field>
                    </div>

                    <!-- Email -->
                    <div>
                        <flux:field>
                            <flux:label>Email Address</flux:label>
                            <flux:input name="email" type="email" wire:model="form.email" placeholder="user@example.com" />
                            <flux:description>Optional for walk-in customers</flux:description>
                            <flux:error name="form.email" />
                        </flux:field>
                    </div>

                    <!-- Phone -->
                    <div>
                        <flux:field>
                            <flux:label>Phone Number</flux:label>
                            <flux:input name="phone" type="tel" wire:model="form.phone" placeholder="Enter phone number" />
                            <flux:error name="form.phone" />
                        </flux:field>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Password -->
                        <div>
                            <flux:field>
                                <flux:label>Password</flux:label>
                                <flux:input name="password" type="password" wire:model="form.password" placeholder="Enter password" />
                                <flux:description>Leave blank for unregistered walk-ins</flux:description>
                                <flux:error name="form.password" />
                            </flux:field>
                        </div>

                        <!-- Password Confirmation -->
                        <div>
                            <flux:field>
                                <flux:label>Confirm Password</flux:label>
                                <flux:input name="password_confirmation" type="password" wire:model="form.password_confirmation" placeholder="Confirm password" />
                                <flux:error name="form.password_confirmation" />
                            </flux:field>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Role -->
                        <div>
                            <flux:field>
                                <flux:label>User Role</flux:label>
                                <flux:select wire:model="form.role">
                                    <flux:select.option value="user">User</flux:select.option>
                                    <flux:select.option value="manager">Manager</flux:select.option>
                                    <flux:select.option value="admin">Administrator</flux:select.option>
                                </flux:select>
                                <flux:error name="form.role" />
                            </flux:field>
                        </div>

                        <!-- Language -->
                        <div>
                            <flux:field>
                                <flux:label>Preferred Language</flux:label>
                                <flux:select wire:model="form.language">
                                    <flux:select.option value="en">English</flux:select.option>
                                    <flux:select.option value="tw">Twi</flux:select.option>
                                </flux:select>
                                <flux:error name="form.language" />
                            </flux:field>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-3 pt-6 border-t border-border mt-6">
                    <flux:button href="{{ route('admin.users.index') }}" variant="ghost">
                        Cancel
                    </flux:button>
                    <flux:button type="submit" variant="primary" icon="plus">
                        Create User
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</x-ui.admin-page-layout>
