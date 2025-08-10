<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        @include('partials.head')
    </head>

    <body class="min-h-screen bg-background text-text-primary">
        <flux:sidebar class="border-r border-card bg-card" sticky stashable collapsible>
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a class="mr-5 flex items-center space-x-2" href="{{ route('dashboard') }}" wire:navigate>
                <x-app-logo class="size-8" href="#"></x-app-logo>
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group class="grid" heading="Platform">
                    <flux:navlist.item icon="home" :href="route('dashboard')"
                        :current="request()->routeIs('dashboard')" wire:navigate>
                        Dashboard
                    </flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>
            <flux:navlist variant="outline">
                <flux:navlist.group class="grid space-y-2" heading="App">
                    @if (Auth::user()->role === 'customer')
                        <flux:navlist.item href="{{ route('customers.orders.index') }}" icon="shopping-cart"
                            :current="request()-> routeIs('customers.orders*')" class="text-accent-content">
                            My Orders
                        </flux:navlist.item>
                        <flux:navlist.item href="{{ route('customers.pickups.index')}}" icon="truck" :current="request()->routeIs('customers.pickups*')">
                            Pickups
                        </flux:navlist.item>
                    @elseif(Auth::User()->role === 'staff')
                        <flux:navlist.item href="#" icon="currency-dollar"
                            :current="request()->is('staff/transactions')">
                            Process Payment
                        </flux:navlist.item>
                        <flux:navlist.item href="#" icon="truck" :current="request()->is('staff/pickups')">
                            Verify Pickup
                        </flux:navlist.item>
                        <flux:navlist.item href="#" icon="archive-box" :current="request()->is('staff/stock')">
                            Stock Status
                        </flux:navlist.item>
                    @elseif(Auth::user()->role === 'admin')
                        <flux:navlist.item href="{{ route('admin.products.index') }}" icon="shopping-cart"
                            :current="request()->routeIs('admin.products*')" wire:navigate>
                            Products
                        </flux:navlist.item>
                        <flux:navlist.item href="{{ route('admin.suspicious_activities.index') }}"
                            icon="exclamation-triangle" :current="request()->routeIs('admin.suspicious_activities*')">
                            Fraud Alerts
                        </flux:navlist.item>
                        <flux:navlist.item href="{{ route('admin.stock_alerts.index') }}" icon="bell"
                            :current="request()->routeIs('admin.stock_alerts*')">
                            Stock Alerts
                        </flux:navlist.item>
                        <flux:navlist.item href="{{ route('admin.audit_logs.index') }}" icon="document-text"
                            :current="request()->routeIs('admin.audit_logs* ')">
                            Audit Logs
                        </flux:navlist.item>
                        <flux:navlist.item href="{{ route('admin.users.index') }}" icon="users"
                            :current="request()->routeIs('admin.users*')">
                            User Management
                        </flux:navlist.item>
                    @endif
                </flux:navlist.group>
            </flux:navlist>

            <flux:spacer />

            <!-- Desktop User Menu -->
            <flux:dropdown position="bottom" align="start">
                <flux:profile name="{{ auth()->user()->name }}" initials="{{ auth()->user()->initials() }}"
                    icon-trailing="chevrons-up-down" />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-left text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item href="/settings/profile" icon="cog">Settings</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form class="w-full" method="POST" action="{{ route('logout') }}">
                        @csrf
                        <flux:menu.item class="w-full" type="submit" as="button"
                            icon="arrow-right-start-on-rectangle">
                            Log Out
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile initials="{{ auth()->user()->initials() }}" icon-trailing="chevron-down" />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-left text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item href="/settings/profile" icon="cog">Settings</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form class="w-full" method="POST" action="{{ route('logout') }}">
                        @csrf
                        <flux:menu.item class="w-full" type="submit" as="button"
                            icon="arrow-right-start-on-rectangle">
                            Log Out
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>

</html>
