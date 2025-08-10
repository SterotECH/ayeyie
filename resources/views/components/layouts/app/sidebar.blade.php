<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        @include('partials.head')
    </head>

    <body class="min-h-screen bg-background text-text-primary">
        <!-- Mobile Overlay -->
        <div id="mobile-overlay" class="hidden fixed inset-0 bg-black/50 z-30 lg:hidden"></div>

        <div class="flex h-screen">
            <!-- Custom Sidebar -->
            <aside id="sidebar" class="w-64 lg:w-14 bg-card border-r border-border flex flex-col relative z-40
                                   transition-all duration-300 ease-in-out
                                   fixed lg:static inset-y-0 left-0 -translate-x-full lg:translate-x-0 group">

                <!-- Toggle Button -->
                <button id="toggle-btn"
                        class="absolute -right-2.5 top-3 bg-card border border-border rounded-full p-1 text-text-secondary hover:text-text-primary transition-colors z-20 shadow-sm">
                    <flux:icon id="toggle-icon" name="chevron-right" class="size-3.5" />
                </button>

                <!-- Logo Section -->
                <div class="p-3 border-b border-border">
                    <a class="flex items-center space-x-2" href="{{ route('dashboard') }}" wire:navigate>
                        <x-app-logo class="size-6 flex-shrink-0" />
                    </a>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 p-3 space-y-4">
                    <!-- Platform Section -->
                    <div>
                        <h3 class="sidebar-text text-[8px] font-medium text-text-secondary uppercase tracking-wide mb-2 px-2">
                            Platform
                        </h3>
                        <ul class="space-y-0.5">
                            <li>
                                <a href="{{ route('dashboard') }}"
                                   class="flex items-center p-2 rounded-md transition-all duration-200 relative text-sm text-text-secondary hover:bg-muted hover:text-text-primary group {{ request()->routeIs('dashboard') ? 'bg-primary/10 text-primary font-medium' : '' }}"
                                   wire:navigate>
                                    <flux:icon name="home" class="w-5 h-5 flex-shrink-0" />
                                    <span class="ml-2 sidebar-text">Dashboard</span>
                                    <div class="absolute left-full ml-1.5 bg-card border border-border rounded-md px-2 py-1 text-xs whitespace-nowrap opacity-0 pointer-events-none transition-opacity z-50 shadow-md sidebar-tooltip">Dashboard</div>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- App Section -->
                    <div>
                        <h3 class="sidebar-text text-[8px] font-medium text-text-secondary uppercase tracking-wide mb-2 px-2">
                            App
                        </h3>
                        <ul class="space-y-0.5">
                            @if (Auth::user()->role === 'customer')
                                <li>
                                    <a href="{{ route('customers.orders.index') }}"
                                       class="flex items-center p-2 rounded-md transition-all duration-200 relative text-sm text-text-secondary hover:bg-muted hover:text-text-primary group {{ request()->routeIs('customers.orders*') ? 'bg-primary/10 text-primary font-medium' : '' }}"
                                       wire:navigate>
                                        <flux:icon name="shopping-cart" class="w-5 h-5 flex-shrink-0" />
                                        <span class="ml-2 sidebar-text">My Orders</span>
                                        <div class="absolute left-full ml-1.5 bg-card border border-border rounded-md px-2 py-1 text-xs whitespace-nowrap opacity-0 pointer-events-none transition-opacity z-50 shadow-md sidebar-tooltip">My Orders</div>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('customers.pickups.index') }}"
                                       class="flex items-center p-2 rounded-md transition-all duration-200 relative text-sm text-text-secondary hover:bg-muted hover:text-text-primary group {{ request()->routeIs('customers.pickups*') ? 'bg-primary/10 text-primary font-medium' : '' }}"
                                       wire:navigate>
                                        <flux:icon name="truck" class="w-5 h-5 flex-shrink-0" />
                                        <span class="ml-2 sidebar-text">Pickups</span>
                                        <div class="absolute left-full ml-1.5 bg-card border border-border rounded-md px-2 py-1 text-xs whitespace-nowrap opacity-0 pointer-events-none transition-opacity z-50 shadow-md sidebar-tooltip">Pickups</div>
                                    </a>
                                </li>
                            @elseif(Auth::user()->role === 'staff')
                                <li>
                                    <a href="{{ route('staff.transactions.process-payment') }}"
                                       class="flex items-center p-2 rounded-md transition-all duration-200 relative text-sm text-text-secondary hover:bg-muted hover:text-text-primary group {{ request()->routeIs('staff.transactions.*') ? 'bg-primary/10 text-primary font-medium' : '' }}"
                                       wire:navigate>
                                        <flux:icon name="currency-dollar" class="w-5 h-5 flex-shrink-0" />
                                        <span class="ml-2 sidebar-text">Process Payment</span>
                                        <div class="absolute left-full ml-1.5 bg-card border border-border rounded-md px-2 py-1 text-xs whitespace-nowrap opacity-0 pointer-events-none transition-opacity z-50 shadow-md sidebar-tooltip">Process Payment</div>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('staff.orders.verify') }}"
                                       class="flex items-center p-2 rounded-md transition-all duration-200 relative text-sm text-text-secondary hover:bg-muted hover:text-text-primary group {{ request()->routeIs('staff.orders.*') ? 'bg-primary/10 text-primary font-medium' : '' }}"
                                       wire:navigate>
                                        <flux:icon name="truck" class="w-5 h-5 flex-shrink-0" />
                                        <span class="ml-2 sidebar-text">Verify Pickup</span>
                                        <div class="absolute left-full ml-1.5 bg-card border border-border rounded-md px-2 py-1 text-xs whitespace-nowrap opacity-0 pointer-events-none transition-opacity z-50 shadow-md sidebar-tooltip">Verify Pickup</div>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('staff.stock-alerts.index') }}"
                                       class="flex items-center p-2 rounded-md transition-all duration-200 relative text-sm text-text-secondary hover:bg-muted hover:text-text-primary group {{ request()->routeIs('staff.stock-alerts.*') ? 'bg-primary/10 text-primary font-medium' : '' }}"
                                       wire:navigate>
                                        <flux:icon name="archive-box" class="w-5 h-5 flex-shrink-0" />
                                        <span class="ml-2 sidebar-text">Stock Alerts</span>
                                        <div class="absolute left-full ml-1.5 bg-card border border-border rounded-md px-2 py-1 text-xs whitespace-nowrap opacity-0 pointer-events-none transition-opacity z-50 shadow-md sidebar-tooltip">Stock Alerts</div>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('staff.pickups.index') }}"
                                       class="flex items-center p-2 rounded-md transition-all duration-200 relative text-sm text-text-secondary hover:bg-muted hover:text-text-primary group {{ request()->routeIs('staff.pickups.*') ? 'bg-primary/10 text-primary font-medium' : '' }}"
                                       wire:navigate>
                                        <flux:icon name="clipboard-document-list" class="w-5 h-5 flex-shrink-0" />
                                        <span class="ml-2 sidebar-text">Manage Pickups</span>
                                        <div class="absolute left-full ml-1.5 bg-card border border-border rounded-md px-2 py-1 text-xs whitespace-nowrap opacity-0 pointer-events-none transition-opacity z-50 shadow-md sidebar-tooltip">Manage Pickups</div>
                                    </a>
                                </li>
                            @elseif(Auth::user()->role === 'admin')
                                <li>
                                    <a href="{{ route('admin.products.index') }}"
                                       class="flex items-center p-2 rounded-md transition-all duration-200 relative text-sm text-text-secondary hover:bg-muted hover:text-text-primary group {{ request()->routeIs('admin.products*') ? 'bg-primary/10 text-primary font-medium' : '' }}"
                                       wire:navigate>
                                        <flux:icon name="shopping-cart" class="w-5 h-5 flex-shrink-0" />
                                        <span class="ml-2 sidebar-text">Products</span>
                                        <div class="absolute left-full ml-1.5 bg-card border border-border rounded-md px-2 py-1 text-xs whitespace-nowrap opacity-0 pointer-events-none transition-opacity z-50 shadow-md sidebar-tooltip">Products</div>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.suspicious_activities.index') }}"
                                       class="flex items-center p-2 rounded-md transition-all duration-200 relative text-sm text-text-secondary hover:bg-muted hover:text-text-primary group {{ request()->routeIs('admin.suspicious_activities*') ? 'bg-primary/10 text-primary font-medium' : '' }}"
                                       wire:navigate>
                                        <flux:icon name="exclamation-triangle" class="w-5 h-5 flex-shrink-0" />
                                        <span class="ml-2 sidebar-text">Fraud Alerts</span>
                                        <div class="absolute left-full ml-1.5 bg-card border border-border rounded-md px-2 py-1 text-xs whitespace-nowrap opacity-0 pointer-events-none transition-opacity z-50 shadow-md sidebar-tooltip">Fraud Alerts</div>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.stock_alerts.index') }}"
                                       class="flex items-center p-2 rounded-md transition-all duration-200 relative text-sm text-text-secondary hover:bg-muted hover:text-text-primary group {{ request()->routeIs('admin.stock_alerts*') ? 'bg-primary/10 text-primary font-medium' : '' }}"
                                       wire:navigate>
                                        <flux:icon name="bell" class="w-5 h-5 flex-shrink-0" />
                                        <span class="ml-2 sidebar-text">Stock Alerts</span>
                                        <div class="absolute left-full ml-1.5 bg-card border border-border rounded-md px-2 py-1 text-xs whitespace-nowrap opacity-0 pointer-events-none transition-opacity z-50 shadow-md sidebar-tooltip">Stock Alerts</div>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.audit_logs.index') }}"
                                       class="flex items-center p-2 rounded-md transition-all duration-200 relative text-sm text-text-secondary hover:bg-muted hover:text-text-primary group {{ request()->routeIs('admin.audit_logs*') ? 'bg-primary/10 text-primary font-medium' : '' }}"
                                       wire:navigate>
                                        <flux:icon name="document-text" class="w-5 h-5 flex-shrink-0" />
                                        <span class="ml-2 sidebar-text">Audit Logs</span>
                                        <div class="absolute left-full ml-1.5 bg-card border border-border rounded-md px-2 py-1 text-xs whitespace-nowrap opacity-0 pointer-events-none transition-opacity z-50 shadow-md sidebar-tooltip">Audit Logs</div>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.users.index') }}"
                                       class="flex items-center p-2 rounded-md transition-all duration-200 relative text-sm text-text-secondary hover:bg-muted hover:text-text-primary group {{ request()->routeIs('admin.users*') ? 'bg-primary/10 text-primary font-medium' : '' }}"
                                       wire:navigate>
                                        <flux:icon name="users" class="w-5 h-5 flex-shrink-0" />
                                        <span class="ml-2 sidebar-text">User Management</span>
                                        <div class="absolute left-full ml-1.5 bg-card border border-border rounded-md px-2 py-1 text-xs whitespace-nowrap opacity-0 pointer-events-none transition-opacity z-50 shadow-md sidebar-tooltip">User Management</div>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </nav>

                <!-- User Profile -->
                <div class="p-3 border-t border-border mt-auto">
                    <div class="relative">
                        <button id="profile-btn" class="flex items-center w-full p-2 rounded-lg hover:bg-muted transition-colors">
                            <div class="size-7 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <span class="text-xs font-semibold text-primary">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </div>
                            <div class="ml-2 flex-1 text-left sidebar-text">
                                <div class="text-sm font-medium text-primary">{{ auth()->user()->name }}</div>
                                <div class="text-xs text-text-secondary">{{ auth()->user()->email }}</div>
                            </div>
                            <flux:icon name="chevrons-up-down" class="size-3.5 text-text-secondary ml-2 sidebar-text" />
                        </button>

                        <!-- Profile Menu -->
                        <div id="profile-menu" class="hidden absolute bottom-full mb-2 left-0 right-0 bg-card border border-border rounded-lg shadow-lg py-1 z-[100] min-w-48">
                            <a href="{{ route('settings.profile') }}" class="flex items-center px-3 py-2 text-sm hover:bg-muted transition-colors text-text-primary" wire:navigate>
                                <flux:icon name="user" class="size-4 mr-2" />
                                Profile
                            </a>
                            <a href="{{ route('settings.password') }}" class="flex items-center px-3 py-2 text-sm hover:bg-muted transition-colors text-text-primary" wire:navigate>
                                <flux:icon name="shield-check" class="size-4 mr-2" />
                                Security
                            </a>
                            <a href="{{ route('settings.appearance') }}" class="flex items-center px-3 py-2 text-sm hover:bg-muted transition-colors text-text-primary" wire:navigate>
                                <flux:icon name="swatch" class="size-4 mr-2" />
                                Appearance
                            </a>
                            <hr class="border-border my-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center w-full px-3 py-2 text-sm hover:bg-muted transition-colors text-left text-error">
                                    <flux:icon name="arrow-right-start-on-rectangle" class="size-4 mr-2" />
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 overflow-auto lg:ml-0">
                <!-- Mobile Header -->
                <div class="lg:hidden bg-card border-b border-border p-4 flex items-center justify-between">
                    <button id="mobile-menu-btn" class="p-2 rounded-lg hover:bg-muted transition-colors">
                        <flux:icon name="bars-3" class="size-5 text-text-primary" />
                    </button>
                    <div class="flex items-center gap-2">
                        <x-app-logo class="size-6" />
                        <span class="font-semibold text-text-primary">{{ config('app.name') }}</span>
                    </div>
                    <div class="w-9"></div> <!-- Spacer for centering -->
                </div>

                <div class="p-4 lg:p-0">
                    {{ $slot }}
                </div>
            </main>
        </div>

        @fluxScripts

        <script>
            // Initialize immediately to prevent flash
            function initializeSidebar() {
                const sidebar = document.getElementById('sidebar');
                const toggleBtn = document.getElementById('toggle-btn');
                const toggleIcon = document.getElementById('toggle-icon');
                const profileBtn = document.getElementById('profile-btn');
                const profileMenu = document.getElementById('profile-menu');
                const mobileMenuBtn = document.getElementById('mobile-menu-btn');
                const mobileOverlay = document.getElementById('mobile-overlay');

                if (!sidebar || !toggleBtn || !toggleIcon) return;

                // Check if mobile
                let isMobile = window.innerWidth < 1024;
                let isCollapsed = localStorage.getItem('sidebarCollapsed') !== 'false';
                let isMobileOpen = false;

                // Apply initial state
                if (isMobile) {
                    // Mobile: hide sidebar initially, show all text when open
                    sidebar.classList.add('-translate-x-full');
                    sidebar.classList.remove('translate-x-0');
                    sidebar.classList.remove('lg:w-14', 'lg:w-56', 'sidebar-collapsed', 'sidebar-expanded');
                    sidebar.classList.add('w-64'); // Ensure full width on mobile
                    if (mobileOverlay) mobileOverlay.classList.add('hidden');
                } else {
                    // Desktop: remove mobile classes and apply desktop state
                    sidebar.classList.remove('-translate-x-full', 'translate-x-0', 'w-64');
                    if (isCollapsed) {
                        collapseSidebar();
                    } else {
                        expandSidebar();
                    }
                }

                // Desktop toggle sidebar
                toggleBtn.addEventListener('click', function() {
                    if (window.innerWidth >= 1024) {
                        if (isCollapsed) {
                            expandSidebar();
                        } else {
                            collapseSidebar();
                        }
                        isCollapsed = !isCollapsed;
                        localStorage.setItem('sidebarCollapsed', isCollapsed);
                    }
                });

                // Mobile menu button
                if (mobileMenuBtn) {
                    mobileMenuBtn.addEventListener('click', function() {
                        isMobileOpen = !isMobileOpen;
                        if (isMobileOpen) {
                            openMobileSidebar();
                        } else {
                            closeMobileSidebar();
                        }
                    });
                }

                // Mobile overlay click
                if (mobileOverlay) {
                    mobileOverlay.addEventListener('click', function() {
                        isMobileOpen = false;
                        closeMobileSidebar();
                    });
                }

                // Handle window resize
                window.addEventListener('resize', function() {
                    const nowMobile = window.innerWidth < 1024;
                    if (nowMobile !== isMobile) {
                        isMobile = nowMobile;
                        if (nowMobile) {
                            // Switched to mobile
                            closeMobileSidebar();
                            sidebar.classList.remove('lg:w-14', 'lg:w-56', 'sidebar-collapsed', 'sidebar-expanded');
                            sidebar.classList.add('w-64');
                        } else {
                            // Switched to desktop
                            sidebar.classList.remove('translate-x-0', '-translate-x-full', 'w-64');
                            if (mobileOverlay) mobileOverlay.classList.add('hidden');
                            if (isCollapsed) {
                                collapseSidebar();
                            } else {
                                expandSidebar();
                            }
                        }
                    }
                });

                // Profile menu - simplified approach
                function setupProfileMenu() {
                    const btn = document.getElementById('profile-btn');
                    const menu = document.getElementById('profile-menu');

                    if (!btn || !menu) return;

                    btn.onclick = function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        if (menu.classList.contains('hidden')) {
                            menu.classList.remove('hidden');
                        } else {
                            menu.classList.add('hidden');
                        }
                    };

                    // Close menu when clicking outside
                    document.onclick = function(e) {
                        if (!btn.contains(e.target) && !menu.contains(e.target)) {
                            menu.classList.add('hidden');
                        }
                    };
                }

                setupProfileMenu();

                function collapseSidebar() {
                    sidebar.classList.remove('lg:w-56');
                    sidebar.classList.add('lg:w-14');
                    sidebar.classList.add('sidebar-collapsed');
                    sidebar.classList.remove('sidebar-expanded');
                    toggleIcon.setAttribute('name', 'chevron-right');
                }

                function expandSidebar() {
                    sidebar.classList.remove('lg:w-14');
                    sidebar.classList.add('lg:w-56');
                    sidebar.classList.add('sidebar-expanded');
                    sidebar.classList.remove('sidebar-collapsed');
                    toggleIcon.setAttribute('name', 'chevron-left');
                }

                function openMobileSidebar() {
                    sidebar.classList.remove('-translate-x-full');
                    sidebar.classList.add('translate-x-0');
                    sidebar.classList.add('w-64'); // Ensure full width when opening
                    if (mobileOverlay) mobileOverlay.classList.remove('hidden');
                }

                function closeMobileSidebar() {
                    sidebar.classList.remove('translate-x-0');
                    sidebar.classList.add('-translate-x-full');
                    if (mobileOverlay) mobileOverlay.classList.add('hidden');
                }
            }

            // Run immediately and on DOMContentLoaded
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initializeSidebar);
            } else {
                initializeSidebar();
            }

            // Also run on Livewire navigation
            document.addEventListener('livewire:navigated', initializeSidebar);
        </script>

        <style>
            /* Sidebar text visibility */
            .sidebar-collapsed .sidebar-text {
                display: none;
            }

            .sidebar-expanded .sidebar-text {
                display: block;
            }

            /* Mobile always shows text */
            @media (max-width: 1023px) {
                .sidebar-text {
                    display: block !important;
                }
                .sidebar-tooltip {
                    display: none !important;
                }
            }

            /* Tooltip behavior - only show on hover when collapsed */
            .sidebar-collapsed .group:hover .sidebar-tooltip {
                opacity: 1;
            }

            .sidebar-expanded .sidebar-tooltip {
                display: none;
            }
        </style>

    </body>
</html>
