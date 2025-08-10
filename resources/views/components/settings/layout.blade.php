<div>
<div class="min-h-screen bg-background">
    <div class="max-w-7xl mx-auto p-6">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <div class="size-10 rounded-lg bg-primary/10 flex items-center justify-center">
                    <flux:icon name="cog-6-tooth" class="size-5 text-primary" />
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-text-primary">Settings</h1>
                    <p class="text-sm text-text-secondary">Manage your account preferences and configuration</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Navigation Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-card rounded-lg border border-border overflow-hidden sticky top-6">
                    <div class="p-4 border-b border-border">
                        <h3 class="text-sm font-semibold text-text-primary uppercase tracking-wide">Settings</h3>
                    </div>
                    <nav class="p-2 space-y-1">
                        <a href="{{ route('settings.profile') }}"
                           class="flex items-center gap-3 px-3 py-3 rounded-lg transition-all duration-200 w-full text-left border border-transparent hover:transform hover:translate-x-1 {{ request()->routeIs('settings.profile') ? 'bg-primary/10 text-primary border-primary/20 shadow-sm' : 'text-text-secondary hover:text-text-primary hover:bg-muted/40 hover:border-border/30' }}"
                           wire:navigate>
                            <div class="flex items-center justify-center size-8 rounded-lg {{ request()->routeIs('settings.profile') ? 'bg-primary/20' : 'bg-muted/30' }} flex-shrink-0">
                                <flux:icon name="user" class="size-4" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm {{ request()->routeIs('settings.profile') ? 'font-semibold' : 'font-medium' }}">Profile</div>
                                <div class="text-xs {{ request()->routeIs('settings.profile') ? 'text-primary/70' : 'text-text-muted' }}">Personal information</div>
                            </div>
                            <div class="flex-shrink-0">
                                @if(request()->routeIs('settings.profile'))
                                    <flux:icon name="chevron-right" class="size-4 text-primary" />
                                @endif
                            </div>
                        </a>
                        <a href="{{ route('settings.password') }}"
                           class="flex items-center gap-3 px-3 py-3 rounded-lg transition-all duration-200 w-full text-left border border-transparent hover:transform hover:translate-x-1 {{ request()->routeIs('settings.password') ? 'bg-primary/10 text-primary border-primary/20 shadow-sm' : 'text-text-secondary hover:text-text-primary hover:bg-muted/40 hover:border-border/30' }}"
                           wire:navigate>
                            <div class="flex items-center justify-center size-8 rounded-lg {{ request()->routeIs('settings.password') ? 'bg-primary/20' : 'bg-muted/30' }} flex-shrink-0">
                                <flux:icon name="shield-check" class="size-4" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm {{ request()->routeIs('settings.password') ? 'font-semibold' : 'font-medium' }}">Security</div>
                                <div class="text-xs {{ request()->routeIs('settings.password') ? 'text-primary/70' : 'text-text-muted' }}">Password & 2FA</div>
                            </div>
                            <div class="flex-shrink-0">
                                @if(request()->routeIs('settings.password'))
                                    <flux:icon name="chevron-right" class="size-4 text-primary" />
                                @endif
                            </div>
                        </a>
                        <a href="{{ route('settings.appearance') }}"
                           class="flex items-center gap-3 px-3 py-3 rounded-lg transition-all duration-200 w-full text-left border border-transparent hover:transform hover:translate-x-1 {{ request()->routeIs('settings.appearance') ? 'bg-primary/10 text-primary border-primary/20 shadow-sm' : 'text-text-secondary hover:text-text-primary hover:bg-muted/40 hover:border-border/30' }}"
                           wire:navigate>
                            <div class="flex items-center justify-center size-8 rounded-lg {{ request()->routeIs('settings.appearance') ? 'bg-primary/20' : 'bg-muted/30' }} flex-shrink-0">
                                <flux:icon name="swatch" class="size-4" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm {{ request()->routeIs('settings.appearance') ? 'font-semibold' : 'font-medium' }}">Appearance</div>
                                <div class="text-xs {{ request()->routeIs('settings.appearance') ? 'text-primary/70' : 'text-text-muted' }}">Theme & colors</div>
                            </div>
                            <div class="flex-shrink-0">
                                @if(request()->routeIs('settings.appearance'))
                                    <flux:icon name="chevron-right" class="size-4 text-primary" />
                                @endif
                            </div>
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-3">
                <div class="bg-card rounded-lg border border-border">
                    <div class="p-6 border-b border-border">
                        <h2 class="text-xl font-semibold text-text-primary">{{ $heading ?? '' }}</h2>
                        @if($subheading ?? false)
                            <p class="text-sm text-text-secondary mt-1">{{ $subheading }}</p>
                        @endif
                    </div>
                    <div class="p-6">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
