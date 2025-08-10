<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div>
    <h3 class="text-lg font-semibold text-text-primary mb-2">Delete Account</h3>
    <p class="text-sm text-text-secondary mb-4">
        Permanently delete your account and all associated data. This action cannot be undone.
    </p>

    <flux:modal.trigger name="confirm-user-deletion">
        <flux:button 
            variant="outline" 
            class="border-error text-error hover:bg-error/10 hover:border-error"
            x-data="" 
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        >
            <flux:icon name="trash" class="size-4 mr-2" />
            Delete Account
        </flux:button>
    </flux:modal.trigger>

    <flux:modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable class="max-w-lg">
        <div class="bg-card rounded-lg p-6 space-y-6">
            <div class="flex items-start gap-4">
                <div class="size-12 rounded-full bg-error/10 flex items-center justify-center flex-shrink-0">
                    <flux:icon name="exclamation-triangle" class="size-6 text-error" />
                </div>
                <div class="flex-1">
                    <h2 class="text-xl font-semibold text-text-primary">Delete Account</h2>
                    <p class="text-sm text-text-secondary mt-1">
                        This action is permanent and cannot be undone.
                    </p>
                </div>
            </div>

            <div class="bg-error/5 border border-error/20 rounded-lg p-4">
                <p class="text-sm text-text-primary">
                    Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.
                </p>
            </div>

            <form wire:submit="deleteUser" class="space-y-4">
                <flux:field>
                    <flux:label class="text-sm font-medium text-text-primary">Confirm Password</flux:label>
                    <flux:input 
                        wire:model="password" 
                        id="delete_password" 
                        type="password" 
                        name="password" 
                        required
                        class="bg-background border-border focus:border-error focus:ring-error/20"
                        placeholder="Enter your password to confirm"
                    />
                    <flux:error name="password" />
                </flux:field>

                <div class="flex items-center justify-end gap-3 pt-4">
                    <flux:modal.close>
                        <flux:button variant="ghost" class="text-text-secondary hover:text-text-primary">
                            Cancel
                        </flux:button>
                    </flux:modal.close>

                    <flux:button 
                        variant="primary" 
                        type="submit"
                        class="bg-error hover:bg-error-hover text-white px-6 py-2"
                    >
                        <flux:icon name="trash" class="size-4 mr-2" />
                        Delete Account
                    </flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>
