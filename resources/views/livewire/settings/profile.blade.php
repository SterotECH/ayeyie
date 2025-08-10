<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {
    public string $name = '';
    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id)
            ],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<div>
<x-settings.layout heading="Profile Information" subheading="Update your account's profile information and email address">
    <div class="space-y-8">
        <!-- Profile Form -->
        <form wire:submit="updateProfileInformation" class="space-y-6">
            <!-- Profile Avatar Section -->
            <div class="flex items-center gap-4">
                <div class="size-16 rounded-full bg-primary/10 flex items-center justify-center">
                    <span class="text-xl font-bold text-primary">
                        {{ auth()->user()->initials() }}
                    </span>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-text-primary">Profile Photo</h3>
                    <p class="text-xs text-text-secondary">This is your avatar displayed across the application</p>
                </div>
            </div>

            <!-- Form Fields -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <flux:field>
                        <flux:label class="text-sm font-medium text-text-primary">Full Name</flux:label>
                        <flux:input
                            wire:model="name"
                            type="text"
                            name="name"
                            required
                            autofocus
                            autocomplete="name"
                            class="bg-background border-border focus:border-primary focus:ring-primary/20"
                        />
                        <flux:error name="name" />
                    </flux:field>
                </div>

                <div class="space-y-2">
                    <flux:field>
                        <flux:label class="text-sm font-medium text-text-primary">Email Address</flux:label>
                        <flux:input
                            wire:model="email"
                            type="email"
                            name="email"
                            required
                            autocomplete="email"
                            class="bg-background border-border focus:border-primary focus:ring-primary/20"
                        />
                        <flux:error name="email" />
                    </flux:field>
                </div>
            </div>

            <!-- Email Verification Notice -->
            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail &&! auth()->user()->hasVerifiedEmail())
                <div class="bg-warning/10 border border-warning/20 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <flux:icon name="exclamation-triangle" class="size-5 text-warning flex-shrink-0 mt-0.5" />
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-warning">Email Verification Required</h4>
                            <p class="text-sm text-text-secondary mt-1">
                                Your email address is unverified. Please verify your email to access all features.
                            </p>
                            <button
                                wire:click.prevent="resendVerificationNotification"
                                class="mt-2 text-sm text-warning hover:text-warning-hover underline focus:outline-none focus:ring-2 focus:ring-warning/20 rounded"
                            >
                                Resend verification email
                            </button>
                        </div>
                    </div>

                    @if (session('status') === 'verification-link-sent')
                        <div class="mt-3 p-3 bg-success/10 border border-success/20 rounded-lg">
                            <div class="flex items-center gap-2">
                                <flux:icon name="check-circle" class="size-4 text-success" />
                                <p class="text-sm text-success">A new verification link has been sent to your email address.</p>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Save Button -->
            <div class="flex items-center justify-between pt-4">
                <x-action-message on="profile-updated" class="text-sm text-success">
                    <div class="flex items-center gap-2">
                        <flux:icon name="check-circle" class="size-4" />
                        Profile updated successfully
                    </div>
                </x-action-message>

                <flux:button
                    variant="primary"
                    type="submit"
                    class="bg-primary hover:bg-primary-hover text-white px-6 py-2"
                >
                    Save Changes
                </flux:button>
            </div>
        </form>

        <!-- Danger Zone -->
        <div class="border-t border-border pt-8">
            <div class="bg-error/5 border border-error/20 rounded-lg p-6">
                <div class="flex items-start gap-4">
                    <div class="size-10 rounded-lg bg-error/10 flex items-center justify-center flex-shrink-0">
                        <flux:icon name="exclamation-triangle" class="size-5 text-error" />
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-text-primary">Danger Zone</h3>
                        <p class="text-sm text-text-secondary mt-1">
                            Irreversible and destructive actions
                        </p>
                        <div class="mt-4">
                            <livewire:settings.delete-user-form />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-settings.layout>
</div>
