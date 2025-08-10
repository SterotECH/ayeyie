<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        @include('partials.head')
    </head>

    <body class="min-h-screen bg-background text-text-primary">
        <div class="flex h-screen">
            <!-- Sidebar -->
            <aside id="sidebar" class="w-64 bg-card border-r border-border flex flex-col transition-all duration-300 ease-in-out">
                
                <!-- Toggle Button -->
                <button id="sidebar-toggle" 
                        class="absolute -right-3 top-6 bg-card border border-border rounded-full p-1.5 text-text-secondary hover:text-text-primary hover:bg-muted transition-colors z-10 shadow-sm">
                    <flux:icon id="toggle-icon" name="chevron-left" class="size-4" />
                </button>

                <!-- Logo -->
                <div class="p-4 border-b border-border">
                    <a class="flex items-center space-x-2" href="{{ route('dashboard') }}" wire:navigate>
                        <x-app-logo class="size-8 flex-shrink-0" />
                        <span id="logo-text" class="font-semibold text-lg text-primary">
                            {{ config('app.name') }}
                        </span>
                    </a>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 p-4 space-y-6">
                    <!-- Platform Section -->
                    <div>
                        <h3 class="section-heading text-xs font-medium text-text-secondary uppercase tracking-wide mb-3">
                            Platform
                        </h3>
                        <ul class="space-y-1">
                            <li>
                                <a href="{{ route('dashboard') }}" 
                                   class="nav-link {{ request()->routeIs('dashboard') ? 'nav-link-active' : 'nav-link-inactive' }}"
                                   wire:navigate>
                                    <flux:icon name="home" class="size-5" />
                                    <span class="nav-text">Dashboard</span>
                                    <div class="tooltip">Dashboard</div>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- App Section -->
                    <div>
                        <h3 class="section-heading text-xs font-medium text-text-secondary uppercase tracking-wide mb-3">
                            Application
                        </h3>
                        <ul class="space-y-1">
                            @if (Auth::user()->role === 'customer')
                                <li>
                                    <a href="{{ route('customers.orders.index') }}" 
                                       class="nav-link {{ request()->routeIs('customers.orders*') ? 'nav-link-active' : 'nav-link-inactive' }}"
                                       wire:navigate>
                                        <flux:icon name="shopping-cart" class="size-5" />
                                        <span class="nav-text">My Orders</span>
                                        <div class="tooltip">My Orders</div>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('customers.pickups.index') }}" 
                                       class="nav-link {{ request()->routeIs('customers.pickups*') ? 'nav-link-active' : 'nav-link-inactive' }}"
                                       wire:navigate>
                                        <flux:icon name="truck" class="size-5" />
                                        <span class="nav-text">Pickups</span>
                                        <div class="tooltip">Pickups</div>
                                    </a>
                                </li>
                            @elseif(Auth::user()->role === 'staff')
                                <li>
                                    <a href="#" 
                                       class="nav-link {{ request()->is('staff/transactions') ? 'nav-link-active' : 'nav-link-inactive' }}">
                                        <flux:icon name="currency-dollar" class="size-5" />
                                        <span class="nav-text">Process Payment</span>
                                        <div class="tooltip">Process Payment</div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" 
                                       class="nav-link {{ request()->is('staff/pickups') ? 'nav-link-active' : 'nav-link-inactive' }}">
                                        <flux:icon name="truck" class="size-5" />
                                        <span class="nav-text">Verify Pickup</span>
                                        <div class="tooltip">Verify Pickup</div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" 
                                       class="nav-link {{ request()->is('staff/stock') ? 'nav-link-active' : 'nav-link-inactive' }}">
                                        <flux:icon name="archive-box" class="size-5" />
                                        <span class="nav-text">Stock Status</span>
                                        <div class="tooltip">Stock Status</div>
                                    </a>
                                </li>
                            @elseif(Auth::user()->role === 'admin')
                                <li>
                                    <a href="{{ route('admin.products.index') }}" 
                                       class="nav-link {{ request()->routeIs('admin.products*') ? 'nav-link-active' : 'nav-link-inactive' }}"
                                       wire:navigate>
                                        <flux:icon name="shopping-cart" class="size-5" />
                                        <span class="nav-text">Products</span>
                                        <div class="tooltip">Products</div>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.suspicious_activities.index') }}" 
                                       class="nav-link {{ request()->routeIs('admin.suspicious_activities*') ? 'nav-link-active' : 'nav-link-inactive' }}"
                                       wire:navigate>
                                        <flux:icon name="exclamation-triangle" class="size-5" />
                                        <span class="nav-text">Fraud Alerts</span>
                                        <div class="tooltip">Fraud Alerts</div>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.stock_alerts.index') }}" 
                                       class="nav-link {{ request()->routeIs('admin.stock_alerts*') ? 'nav-link-active' : 'nav-link-inactive' }}"
                                       wire:navigate>
                                        <flux:icon name="bell" class="size-5" />
                                        <span class="nav-text">Stock Alerts</span>
                                        <div class="tooltip">Stock Alerts</div>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.audit_logs.index') }}" 
                                       class="nav-link {{ request()->routeIs('admin.audit_logs*') ? 'nav-link-active' : 'nav-link-inactive' }}"
                                       wire:navigate>
                                        <flux:icon name="document-text" class="size-5" />
                                        <span class="nav-text">Audit Logs</span>
                                        <div class="tooltip">Audit Logs</div>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.users.index') }}" 
                                       class="nav-link {{ request()->routeIs('admin.users*') ? 'nav-link-active' : 'nav-link-inactive' }}"
                                       wire:navigate>
                                        <flux:icon name="users" class="size-5" />
                                        <span class="nav-text">User Management</span>
                                        <div class="tooltip">User Management</div>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </nav>

                <!-- User Profile -->
                <div class="p-4 border-t border-border">
                    <div class="relative">
                        <button id="profile-toggle" class="flex items-center w-full p-2 rounded-lg hover:bg-muted transition-colors">
                            <div class="size-8 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <span class="text-sm font-semibold text-primary">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </div>
                            <div class="profile-info ml-3 flex-1 text-left">
                                <div class="text-sm font-medium text-primary">{{ auth()->user()->name }}</div>
                                <div class="text-xs text-text-secondary">{{ auth()->user()->email }}</div>
                            </div>
                            <flux:icon name="chevrons-up-down" class="chevron-icon size-4 text-text-secondary" />
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="profile-menu" class="hidden absolute bottom-full mb-2 left-0 right-0 bg-card border border-border rounded-lg shadow-lg py-1 z-50">
                            <a href="/settings/profile" class="flex items-center px-3 py-2 text-sm hover:bg-muted transition-colors">
                                <flux:icon name="cog" class="size-4 mr-2" />
                                Settings
                            </a>
                            <hr class="border-border my-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center w-full px-3 py-2 text-sm hover:bg-muted transition-colors text-left">
                                    <flux:icon name="arrow-right-start-on-rectangle" class="size-4 mr-2" />
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 overflow-auto">
                <flux:main>
                    {{ $slot }}
                </flux:main>
            </main>
        </div>

        @fluxScripts
        
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const sidebar = document.getElementById('sidebar');
                const toggleBtn = document.getElementById('sidebar-toggle');
                const toggleIcon = document.getElementById('toggle-icon');
                const logoText = document.getElementById('logo-text');
                const navTexts = document.querySelectorAll('.nav-text');
                const sectionHeadings = document.querySelectorAll('.section-heading');
                const profileInfo = document.querySelector('.profile-info');
                const chevronIcon = document.querySelector('.chevron-icon');
                const profileToggle = document.getElementById('profile-toggle');
                const profileMenu = document.getElementById('profile-menu');
                
                // Get saved state from localStorage
                let isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                
                // Apply initial state
                if (isCollapsed) {
                    sidebar.classList.remove('w-64');
                    sidebar.classList.add('w-16');
                    toggleIcon.setAttribute('name', 'chevron-right');
                    hideText();
                }
                
                // Toggle sidebar
                toggleBtn.addEventListener('click', function() {
                    if (isCollapsed) {
                        // Expand
                        sidebar.classList.remove('w-16');
                        sidebar.classList.add('w-64');
                        toggleIcon.setAttribute('name', 'chevron-left');
                        setTimeout(showText, 200);
                    } else {
                        // Collapse
                        sidebar.classList.remove('w-64');
                        sidebar.classList.add('w-16');
                        toggleIcon.setAttribute('name', 'chevron-right');
                        hideText();
                    }
                    isCollapsed = !isCollapsed;
                    localStorage.setItem('sidebarCollapsed', isCollapsed);
                });
                
                // Profile menu
                profileToggle.addEventListener('click', function() {
                    profileMenu.classList.toggle('hidden');
                });
                
                document.addEventListener('click', function(e) {
                    if (!profileToggle.contains(e.target) && !profileMenu.contains(e.target)) {
                        profileMenu.classList.add('hidden');
                    }
                });
                
                function hideText() {
                    logoText.style.display = 'none';
                    navTexts.forEach(text => text.style.display = 'none');
                    sectionHeadings.forEach(heading => heading.style.display = 'none');
                    profileInfo.style.display = 'none';
                    chevronIcon.style.display = 'none';
                }
                
                function showText() {
                    logoText.style.display = 'block';
                    navTexts.forEach(text => text.style.display = 'block');
                    sectionHeadings.forEach(heading => heading.style.display = 'block');
                    profileInfo.style.display = 'block';
                    chevronIcon.style.display = 'block';
                }
            });
        </script>

        <style>
            .nav-link {
                @apply flex items-center px-3 py-2 rounded-lg transition-colors relative;
            }
            
            .nav-link-active {
                @apply bg-primary/10 text-primary;
            }
            
            .nav-link-inactive {
                @apply text-text-secondary hover:text-text-primary hover:bg-muted;
            }
            
            .nav-text {
                @apply ml-3;
            }
            
            .tooltip {
                @apply absolute left-full ml-2 bg-card border border-border rounded px-2 py-1 text-sm whitespace-nowrap opacity-0 pointer-events-none transition-opacity;
                z-index: 50;
            }
            
            .w-16 .nav-link:hover .tooltip {
                @apply opacity-100;
            }
            
            .w-64 .tooltip {
                @apply hidden;
            }
        </style>
    </body>
</html>