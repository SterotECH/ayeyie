<div>
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-accent text-lg font-medium">Edit User</h3>
                    <p class="text-accent-content/50 mt-1 text-sm">
                        Update user information. Password changes are handled separately.
                    </p>
                </div>
            </div>

            <div class="mt-5 md:col-span-2 md:mt-0">
                <form wire:submit="save">
                    <div class="overflow-hidden shadow sm:rounded-md">
                        <div class="bg-zinc-50 px-4 py-5 sm:p-6 dark:bg-gray-900 dark:bg-zinc-800">
                            @if (session('message'))
                                <div class="mb-4 rounded border border-green-400 bg-green-100 px-4 py-2 text-green-700">
                                    {{ session('message') }}
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="mb-4 rounded border border-red-400 bg-red-100 px-4 py-2 text-red-700">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <div class="grid grid-cols-6 gap-6">
                                <!-- Name -->
                                <div class="col-span-6 sm:col-span-4">
                                    <flux:input name="name" wire:model="form.name" label="Full Name" />
                                </div>

                                <!-- Phone -->
                                <div class="col-span-6 sm:col-span-4">
                                    <flux:input name="phone" type="tel" wire:model="form.phone"
                                        label="Phone Number" />
                                </div>

                                <!-- Email -->
                                <div class="col-span-6 sm:col-span-4">
                                    <flux:input name="email" type="email" wire:model="form.email"
                                        label="Email Address" />
                                    <p class="text-xs text-gray-500">Optional for walk-in customers</p>
                                </div>

                                <!-- Role -->
                                <div class="col-span-6 sm:col-span-3">
                                    <flux:select id="role" wire:model="form.role" label="Role">
                                        <flux:select.option value="customer">Customer</flux:select.option>
                                        <flux:select.option value="staff">Staff</flux:select.option>
                                        <flux:select.option value="admin">Admin</flux:select.option>
                                    </flux:select>
                                </div>

                                <!-- Language -->
                                <div class="col-span-6 sm:col-span-3">
                                    <flux:select id="language" label="Preferred Language" wire:model="form.language">
                                        <flux:select.option value="en">English</flux:select.option>
                                        <flux:select.option value="tw">Twi</flux:select.option>
                                    </flux:select>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-4 py-3 text-right sm:px-6 dark:bg-gray-700 dark:bg-gray-900">
                            <flux:button type="submit" variant="primary">
                                Update User
                            </flux:button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
