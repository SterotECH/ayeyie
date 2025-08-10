<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component {
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>
<div>
<x-settings.layout heading="Security Settings" subheading="Update your password and secure your account">
    <div class="space-y-8">
        <!-- Security Info -->
        <div class="bg-info/10 border border-info/20 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <flux:icon name="shield-check" class="size-5 text-info flex-shrink-0 mt-0.5" />
                <div>
                    <h4 class="text-sm font-medium text-info">Password Security</h4>
                    <p class="text-sm text-text-secondary mt-1">
                        Use a strong password with at least 8 characters, including uppercase, lowercase, numbers, and special characters.
                    </p>
                </div>
            </div>
        </div>

        <!-- Password Change Form -->
        <form wire:submit="updatePassword" class="space-y-6">
            <div class="space-y-4">
                <div class="space-y-2">
                    <flux:field>
                        <flux:label class="text-sm font-medium text-text-primary">Current Password</flux:label>
                        <flux:input
                            wire:model="current_password"
                            id="current_password"
                            type="password"
                            name="current_password"
                            required
                            autocomplete="current-password"
                            class="bg-background border-border focus:border-primary focus:ring-primary/20"
                        />
                        <flux:error name="current_password" />
                    </flux:field>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <flux:field>
                            <flux:label class="text-sm font-medium text-text-primary">New Password</flux:label>
                            <flux:input
                                wire:model="password"
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="new-password"
                                class="bg-background border-border focus:border-primary focus:ring-primary/20"
                            />
                            <flux:error name="password" />
                        </flux:field>
                    </div>

                    <div class="space-y-2">
                        <flux:field>
                            <flux:label class="text-sm font-medium text-text-primary">Confirm New Password</flux:label>
                            <flux:input
                                wire:model="password_confirmation"
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                                class="bg-background border-border focus:border-primary focus:ring-primary/20"
                            />
                            <flux:error name="password_confirmation" />
                        </flux:field>
                    </div>
                </div>
            </div>

            <!-- Password Requirements -->
            <div class="bg-muted/50 rounded-lg p-4">
                <h4 class="text-sm font-medium text-text-primary mb-2">Password Requirements</h4>
                <ul class="text-sm text-text-secondary space-y-1">
                    <li class="flex items-center gap-2">
                        <flux:icon name="check" class="size-3 text-success" />
                        At least 8 characters long
                    </li>
                    <li class="flex items-center gap-2">
                        <flux:icon name="check" class="size-3 text-success" />
                        Contains uppercase and lowercase letters
                    </li>
                    <li class="flex items-center gap-2">
                        <flux:icon name="check" class="size-3 text-success" />
                        Contains at least one number
                    </li>
                    <li class="flex items-center gap-2">
                        <flux:icon name="check" class="size-3 text-success" />
                        Contains at least one special character
                    </li>
                </ul>
            </div>

            <!-- Save Button -->
            <div class="flex items-center justify-between pt-4">
                <x-action-message on="password-updated" class="text-sm text-success">
                    <div class="flex items-center gap-2">
                        <flux:icon name="check-circle" class="size-4" />
                        Password updated successfully
                    </div>
                </x-action-message>

                <flux:button
                    variant="primary"
                    type="submit"
                    class="bg-primary hover:bg-primary-hover text-white px-6 py-2"
                >
                    Update Password
                </flux:button>
            </div>
        </form>

        <!-- Two-Factor Authentication -->
        <div class="border-t border-border pt-8">
            <div class="bg-card rounded-lg border border-border p-6">
                <div class="flex items-start gap-4">
                    <div class="size-10 rounded-lg bg-secondary/10 flex items-center justify-center flex-shrink-0">
                        <flux:icon name="device-phone-mobile" class="size-5 text-secondary" />
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-text-primary">Two-Factor Authentication</h3>
                        <p class="text-sm text-text-secondary mt-1">
                            Add an extra layer of security to your account by enabling two-factor authentication.
                        </p>
                        <div class="mt-4">
                            <flux:button variant="outline" class="border-secondary text-secondary hover:bg-secondary/10">
                                <flux:icon name="plus" class="size-4 mr-2" />
                                Enable 2FA
                            </flux:button>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning/10 text-warning">
                            Coming Soon
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-settings.layout>
</div>
