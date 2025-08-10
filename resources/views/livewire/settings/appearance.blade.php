<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>
<div>
<x-settings.layout heading="Appearance Settings" subheading="Customize how the application looks and feels">
    <div class="space-y-8">
        <!-- Theme Selection -->
        <div class="space-y-4">
            <div>
                <h3 class="text-lg font-semibold text-text-primary">Theme</h3>
                <p class="text-sm text-text-secondary">Choose your preferred theme for the application</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Light Theme -->
                <div x-data="{ selected: $flux.appearance === 'light' }"
                     class="relative p-4 bg-card border-2 rounded-lg cursor-pointer transition-all duration-200 hover:border-primary/50"
                     :class="{ 'border-primary bg-primary/5': selected, 'border-border': !selected }"
                     @click="$flux.appearance = 'light'">
                    <div class="flex items-center gap-3">
                        <div class="size-10 rounded-lg bg-white border-2 border-gray-200 flex items-center justify-center">
                            <flux:icon name="sun" class="size-5 text-yellow-500" />
                        </div>
                        <div>
                            <h4 class="font-medium text-text-primary">Light</h4>
                            <p class="text-xs text-text-secondary">Clean and bright interface</p>
                        </div>
                    </div>
                    <div x-show="selected" class="absolute top-2 right-2">
                        <flux:icon name="check-circle" class="size-5 text-primary" />
                    </div>
                </div>

                <!-- Dark Theme -->
                <div x-data="{ selected: $flux.appearance === 'dark' }"
                     class="relative p-4 bg-card border-2 rounded-lg cursor-pointer transition-all duration-200 hover:border-primary/50"
                     :class="{ 'border-primary bg-primary/5': selected, 'border-border': !selected }"
                     @click="$flux.appearance = 'dark'">
                    <div class="flex items-center gap-3">
                        <div class="size-10 rounded-lg bg-gray-900 border-2 border-gray-700 flex items-center justify-center">
                            <flux:icon name="moon" class="size-5 text-blue-400" />
                        </div>
                        <div>
                            <h4 class="font-medium text-text-primary">Dark</h4>
                            <p class="text-xs text-text-secondary">Easy on the eyes</p>
                        </div>
                    </div>
                    <div x-show="selected" class="absolute top-2 right-2">
                        <flux:icon name="check-circle" class="size-5 text-primary" />
                    </div>
                </div>

                <!-- System Theme -->
                <div x-data="{ selected: $flux.appearance === 'system' }"
                     class="relative p-4 bg-card border-2 rounded-lg cursor-pointer transition-all duration-200 hover:border-primary/50"
                     :class="{ 'border-primary bg-primary/5': selected, 'border-border': !selected }"
                     @click="$flux.appearance = 'system'">
                    <div class="flex items-center gap-3">
                        <div class="size-10 rounded-lg bg-gradient-to-br from-white to-gray-900 border-2 border-gray-400 flex items-center justify-center">
                            <flux:icon name="computer-desktop" class="size-5 text-gray-600" />
                        </div>
                        <div>
                            <h4 class="font-medium text-text-primary">System</h4>
                            <p class="text-xs text-text-secondary">Match your device</p>
                        </div>
                    </div>
                    <div x-show="selected" class="absolute top-2 right-2">
                        <flux:icon name="check-circle" class="size-5 text-primary" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Theme Preview -->
        <div class="border-t border-border pt-8">
            <div>
                <h3 class="text-lg font-semibold text-text-primary mb-4">Preview</h3>
                <div class="bg-muted/50 rounded-lg p-6">
                    <div class="bg-card border border-border rounded-lg p-4 space-y-4">
                        <div class="flex items-center justify-between">
                            <h4 class="font-semibold text-text-primary">Sample Dashboard</h4>
                            <span class="px-2 py-1 bg-primary/10 text-primary text-xs rounded-full">Active</span>
                        </div>
                        <div class="grid grid-cols-3 gap-3">
                            <div class="bg-primary/10 text-primary p-3 rounded-lg text-center">
                                <div class="text-lg font-bold">124</div>
                                <div class="text-xs">Orders</div>
                            </div>
                            <div class="bg-success/10 text-success p-3 rounded-lg text-center">
                                <div class="text-lg font-bold">₵4,272</div>
                                <div class="text-xs">Revenue</div>
                            </div>
                            <div class="bg-warning/10 text-warning p-3 rounded-lg text-center">
                                <div class="text-lg font-bold">8</div>
                                <div class="text-xs">Pending</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Color Scheme Info -->
        <div class="border-t border-border pt-8">
            <div class="bg-info/10 border border-info/20 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <flux:icon name="information-circle" class="size-5 text-info flex-shrink-0 mt-0.5" />
                    <div>
                        <h4 class="text-sm font-medium text-info">Color Scheme</h4>
                        <p class="text-sm text-text-secondary mt-1">
                            Our design system uses warm orange primary colors with green accents, inspired by poultry and agricultural themes. The color scheme automatically adapts to your selected theme.
                        </p>
                        <div class="mt-3 flex items-center gap-2">
                            <div class="size-4 rounded-full bg-primary"></div>
                            <span class="text-xs text-text-secondary">Primary (Warm Orange)</span>
                            <div class="size-4 rounded-full bg-secondary ml-2"></div>
                            <span class="text-xs text-text-secondary">Secondary (Forest Green)</span>
                            <div class="size-4 rounded-full bg-accent ml-2"></div>
                            <span class="text-xs text-text-secondary">Accent (Golden Corn)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-settings.layout>
</div>
