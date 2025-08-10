<?php

use App\Enums\AuditAction;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'phone' => ['required', 'string', 'max:20', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'customer';

        event(new Registered(($user = User::create($validated))));
        
        $auditLogService = app(AuditLogService::class);
        
        // Log customer registration
        $auditLogService->logUserManagement(
            AuditAction::CUSTOMER_REGISTERED,
            $user,
            null,
            [
                'registered_name' => $validated['name'],
                'registered_email' => $validated['email'],
                'registered_phone' => $validated['phone'],
                'role' => 'customer',
            ]
        );

        Auth::login($user);
        
        // Log the login after registration
        $auditLogService->logAuth(AuditAction::USER_LOGIN, $user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header title="Create an account" description="Enter your details below to create your account" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="register" class="flex flex-col gap-6">
        <!-- Name -->
        <div class="grid gap-2">
            <flux:input wire:model="name" id="name" label="{{ __('Name') }}" type="text" name="name" required
                autofocus autocomplete="name" placeholder="Full name" />
        </div>

        <!-- Email Address -->
        <div class="grid gap-2">
            <flux:input wire:model="email" id="email" label="{{ __('Email address') }}" type="email"
                name="email" required autocomplete="email" placeholder="email@example.com" />
        </div>

        <!-- Phone -->
        <div class="grid gap-2">
            <flux:input wire:model="phone" id="phone" label="{{ __('Phone') }}" type="tel" name="phone"
                required autocomplete="phone" placeholder="johndoe" />
        </div>

        <!-- Password -->
        <div class="grid gap-2">
            <flux:input wire:model="password" id="password" label="{{ __('Password') }}" type="password"
                name="password" required autocomplete="new-password" placeholder="Password" />
        </div>

        <!-- Confirm Password -->
        <div class="grid gap-2">
            <flux:input wire:model="password_confirmation" id="password_confirmation"
                label="{{ __('Confirm password') }}" type="password" name="password_confirmation" required
                autocomplete="new-password" placeholder="Confirm password" />
        </div>

        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full">
                {{ __('Create account') }}
            </flux:button>
        </div>
    </form>

    <div class="space-x-1 text-center text-sm text-zinc-600 dark:text-zinc-400">
        Already have an account?
        <x-text-link href="{{ route('login') }}">Log in</x-text-link>
    </div>
</div>
