<x-layouts.welcome>
    <!-- Modern Hero Section -->
    <section class="relative overflow-hidden bg-background">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-gradient-to-br from-primary/5 via-accent/3 to-secondary/5"></div>
        <div class="absolute inset-0 opacity-[0.02]">
            <svg class="h-full w-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grain" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                        <circle cx="5" cy="5" r="2" fill="currentColor"/>
                        <circle cx="15" cy="15" r="1.5" fill="currentColor"/>
                        <circle cx="10" cy="12" r="1" fill="currentColor"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grain)"/>
            </svg>
        </div>

        <div class="relative mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:py-28">
            <div class="grid grid-cols-1 gap-12 lg:grid-cols-2 lg:gap-16 items-center">
                <!-- Hero Content -->
                <div class="text-center lg:text-left">
                    <div class="inline-flex items-center rounded-full bg-primary/10 px-4 py-2 text-sm font-medium text-primary mb-8">
                        <flux:icon.star variant="solid" class="mr-2 h-4 w-4" />
                        Trusted by 5,000+ Farmers
                    </div>

                    <h1 class="text-4xl font-bold tracking-tight text-text-primary sm:text-5xl lg:text-6xl">
                        <span class="block">Modern Poultry</span>
                        <span class="bg-gradient-to-r from-primary via-accent to-secondary bg-clip-text text-transparent">
                            Feed Management
                        </span>
                    </h1>

                    <p class="mt-6 text-lg leading-8 text-text-secondary max-w-2xl lg:max-w-none">
                        Transform your poultry operations with our integrated platform. Secure payments, efficient pickups, 
                        real-time inventory tracking, and fraud prevention—all in one seamless solution built for modern farmers.
                    </p>

                    <!-- Action Buttons -->
                    <div class="mt-10 flex flex-col sm:flex-row gap-4 lg:justify-start justify-center">
                        @guest
                            <a href="{{ route('register') }}" 
                               class="group relative inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-white bg-primary hover:bg-primary-hover rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl hover:scale-105">
                                Get Started Free
                                <flux:icon.arrow-right class="ml-2 h-5 w-5 transition-transform group-hover:translate-x-1" />
                            </a>
                            <a href="{{ route('login') }}" 
                               class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-text-primary bg-card hover:bg-card-hover border border-border rounded-xl shadow-sm transition-all duration-300 hover:shadow-md">
                                Sign In
                            </a>
                        @endguest

                        @auth
                            <a href="{{ route('dashboard') }}" 
                               class="group relative inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-white bg-primary hover:bg-primary-hover rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl hover:scale-105">
                                Go to Dashboard
                                <flux:icon.arrow-right class="ml-2 h-5 w-5 transition-transform group-hover:translate-x-1" />
                            </a>
                            <div class="text-lg text-text-secondary">
                                Welcome back, <span class="font-semibold text-primary">{{ auth()->user()->name }}</span>!
                            </div>
                        @endauth
                    </div>

                    <!-- Trust Indicators -->
                    <div class="mt-12 grid grid-cols-3 gap-6 text-center lg:text-left">
                        <div class="animate-pulse-slow">
                            <div class="text-2xl font-bold text-primary" x-data="{ count: 0 }" x-init="setTimeout(() => count = 98, 500)">
                                <span x-text="count + '%'">0%</span>
                            </div>
                            <div class="text-sm text-text-secondary">Faster Deliveries</div>
                        </div>
                        <div class="animate-pulse-slow" style="animation-delay: 200ms;">
                            <div class="text-2xl font-bold text-secondary" x-data="{ count: 0 }" x-init="setTimeout(() => count = 5000, 700)">
                                <span x-text="count.toLocaleString()">0</span>
                            </div>
                            <div class="text-sm text-text-secondary">Happy Customers</div>
                        </div>
                        <div class="animate-pulse-slow" style="animation-delay: 400ms;">
                            <div class="text-2xl font-bold text-accent" x-data="{ count: 0 }" x-init="setTimeout(() => count = 99.9, 900)">
                                <span x-text="count + '%'">0%</span>
                            </div>
                            <div class="text-sm text-text-secondary">Uptime</div>
                        </div>
                    </div>
                </div>

                <!-- Hero Image/Illustration -->
                <div class="relative lg:block">
                    <div class="aspect-square bg-gradient-to-br from-primary/10 via-accent/5 to-secondary/10 rounded-3xl p-8 shadow-2xl">
                        <!-- Modern Illustration -->
                        <div class="h-full flex items-center justify-center">
                            <div class="grid grid-cols-2 gap-4 w-full max-w-md">
                                <!-- Dashboard Card -->
                                <div class="bg-card rounded-2xl p-6 shadow-lg transform rotate-3 hover:rotate-0 transition-transform duration-300">
                                    <div class="h-3 w-16 bg-primary rounded mb-3"></div>
                                    <div class="h-2 w-12 bg-text-secondary/30 rounded mb-2"></div>
                                    <div class="h-2 w-20 bg-text-secondary/20 rounded"></div>
                                </div>
                                
                                <!-- Stats Card -->
                                <div class="bg-card rounded-2xl p-6 shadow-lg transform -rotate-3 hover:rotate-0 transition-transform duration-300 mt-8">
                                    <div class="h-3 w-14 bg-secondary rounded mb-3"></div>
                                    <div class="h-2 w-10 bg-text-secondary/30 rounded mb-2"></div>
                                    <div class="h-2 w-18 bg-text-secondary/20 rounded"></div>
                                </div>
                                
                                <!-- QR Code Card -->
                                <div class="bg-card rounded-2xl p-6 shadow-lg transform rotate-2 hover:rotate-0 transition-transform duration-300">
                                    <div class="h-12 w-12 bg-accent/20 rounded-lg mb-2 flex items-center justify-center">
                                        <flux:icon.qr-code class="h-8 w-8 text-accent" />
                                    </div>
                                    <div class="h-2 w-16 bg-text-secondary/30 rounded"></div>
                                </div>
                                
                                <!-- Analytics Card -->
                                <div class="bg-card rounded-2xl p-6 shadow-lg transform -rotate-2 hover:rotate-0 transition-transform duration-300">
                                    <div class="flex items-end space-x-1 mb-2">
                                        <div class="h-6 w-2 bg-primary rounded"></div>
                                        <div class="h-8 w-2 bg-secondary rounded"></div>
                                        <div class="h-4 w-2 bg-accent rounded"></div>
                                        <div class="h-7 w-2 bg-primary rounded"></div>
                                    </div>
                                    <div class="h-2 w-12 bg-text-secondary/20 rounded"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Floating Elements -->
                    <div class="absolute -top-4 -right-4 bg-success rounded-full p-4 shadow-lg animate-bounce-slow">
                        <flux:icon.check class="h-6 w-6 text-white" />
                    </div>
                    <div class="absolute -bottom-4 -left-4 bg-warning rounded-full p-4 shadow-lg animate-bounce-slow" style="animation-delay: 1s;">
                        <flux:icon.users class="h-6 w-6 text-white" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Wave Separator -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg class="w-full h-12 text-muted" viewBox="0 0 1200 120" preserveAspectRatio="none" fill="currentColor">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z"/>
            </svg>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-muted">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl font-bold text-text-primary sm:text-4xl mb-4">
                    Everything You Need for Modern Poultry Management
                </h2>
                <p class="text-lg text-text-secondary">
                    Our comprehensive platform combines cutting-edge technology with intuitive design to revolutionize your poultry feed operations.
                </p>
            </div>

            <!-- Main Features Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
                <!-- Secure Payments -->
                <div class="group bg-card rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-2 border border-border">
                    <div class="relative">
                        <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <flux:icon.shield-check class="w-8 h-8 text-primary" />
                        </div>
                        <h3 class="text-xl font-semibold text-text-primary mb-3">QR-Secured Payments</h3>
                        <p class="text-text-secondary mb-6">
                            Revolutionary QR-verified receipts prevent fraud and ensure every transaction is traceable and secure. Real-time validation gives you complete confidence.
                        </p>
                        <div class="flex items-center text-primary font-medium group-hover:text-primary-hover">
                            <span>Learn more</span>
                            <flux:icon.chevron-right class="w-4 h-4 ml-2 transition-transform group-hover:translate-x-1" />
                        </div>
                    </div>
                </div>

                <!-- Fast Pickup -->
                <div class="group bg-card rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-2 border border-border">
                    <div class="relative">
                        <div class="w-16 h-16 bg-secondary/10 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <flux:icon.bolt class="w-8 h-8 text-secondary" />
                        </div>
                        <h3 class="text-xl font-semibold text-text-primary mb-3">Lightning-Fast Pickup</h3>
                        <p class="text-text-secondary mb-6">
                            Optimized pickup process reduces wait times by 98%. Smart queue management and real-time notifications ensure you get your feed when you need it.
                        </p>
                        <div class="flex items-center text-secondary font-medium group-hover:text-secondary-hover">
                            <span>Learn more</span>
                            <flux:icon.chevron-right class="w-4 h-4 ml-2 transition-transform group-hover:translate-x-1" />
                        </div>
                    </div>
                </div>

                <!-- Smart Alerts -->
                <div class="group bg-card rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-2 border border-border">
                    <div class="relative">
                        <div class="w-16 h-16 bg-accent/10 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <flux:icon.bell class="w-8 h-8 text-accent" />
                        </div>
                        <h3 class="text-xl font-semibold text-text-primary mb-3">Intelligent Notifications</h3>
                        <p class="text-text-secondary mb-6">
                            Stay ahead with AI-powered alerts via SMS, email, or in-app notifications. From order updates to inventory alerts, never miss what matters.
                        </p>
                        <div class="flex items-center text-accent font-medium group-hover:text-accent-hover">
                            <span>Learn more</span>
                            <flux:icon.chevron-right class="w-4 h-4 ml-2 transition-transform group-hover:translate-x-1" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Secondary Features -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Inventory Management -->
                <div class="bg-card rounded-2xl p-8 border border-border">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-info/10 rounded-xl flex items-center justify-center flex-shrink-0">
                            <flux:icon.chart-bar class="w-6 h-6 text-info" />
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-text-primary mb-2">Real-time Inventory</h3>
                            <p class="text-text-secondary">Live stock tracking with automated low-stock alerts and predictive restocking recommendations.</p>
                        </div>
                    </div>
                </div>

                <!-- Analytics -->
                <div class="bg-card rounded-2xl p-8 border border-border">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-success/10 rounded-xl flex items-center justify-center flex-shrink-0">
                            <flux:icon.presentation-chart-line class="w-6 h-6 text-success" />
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-text-primary mb-2">Advanced Analytics</h3>
                            <p class="text-text-secondary">Comprehensive dashboards with sales trends, customer insights, and operational metrics.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Showcase -->
    <livewire:welcome.products.index />

    <!-- Testimonials Section -->
    <section class="py-20 bg-background">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-text-primary sm:text-4xl mb-4">
                    Trusted by Farmers Across Ghana
                </h2>
                <p class="text-lg text-text-secondary max-w-2xl mx-auto">
                    Join thousands of satisfied poultry farmers who have transformed their operations with our platform
                </p>
            </div>

            <!-- Testimonials Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach ([
                    ['name' => 'Akosua Kumi', 'location' => 'Kumasi', 'farm' => 'Golden Egg Farms', 'quote' => 'The Ayeyie system has transformed how we manage our feed supply. No more long waits or payment confusion—everything is transparent and efficient.', 'rating' => 5, 'image' => 'AK'],
                    ['name' => 'Kwame Mensah', 'location' => 'Accra', 'farm' => 'Sunrise Poultry', 'quote' => 'Fast pickups and secure payments have saved us time and money. The QR code system is brilliant—no more receipt fraud!', 'rating' => 5, 'image' => 'KM'],
                    ['name' => 'Ama Serwaa', 'location' => 'Takoradi', 'farm' => 'Coastal Farms Ltd', 'quote' => 'Real-time updates keep us in complete control. The inventory alerts have prevented stockouts multiple times. Best investment we\'ve made!', 'rating' => 5, 'image' => 'AS']
                ] as $testimonial)
                <div class="bg-card rounded-2xl p-8 shadow-lg border border-border hover:shadow-xl transition-all duration-300">
                    <!-- Rating Stars -->
                    <div class="flex items-center mb-4">
                        @for ($i = 0; $i < 5; $i++)
                            <flux:icon.star variant="solid" class="w-5 h-5 text-warning" />
                        @endfor
                    </div>

                    <!-- Quote -->
                    <blockquote class="text-text-secondary italic mb-6">
                        "{{ $testimonial['quote'] }}"
                    </blockquote>

                    <!-- Author -->
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-primary/20 rounded-full flex items-center justify-center text-primary font-bold text-sm mr-4">
                            {{ $testimonial['image'] }}
                        </div>
                        <div>
                            <div class="text-text-primary font-semibold">{{ $testimonial['name'] }}</div>
                            <div class="text-text-secondary text-sm">{{ $testimonial['farm'] }}, {{ $testimonial['location'] }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="relative py-20 bg-gradient-to-r from-primary via-accent to-secondary overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-text-primary/10"></div>
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="cta-pattern" x="0" y="0" width="10" height="10" patternUnits="userSpaceOnUse">
                        <circle cx="2" cy="2" r="1" fill="currentColor"/>
                        <circle cx="8" cy="8" r="1" fill="currentColor"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#cta-pattern)"/>
            </svg>
        </div>

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-background sm:text-4xl mb-6">
                @guest
                    Ready to Transform Your Poultry Operations?
                @endguest
                @auth
                    Welcome Back, {{ auth()->user()->name }}!
                @endauth
            </h2>
            
            <p class="text-xl text-background/90 max-w-2xl mx-auto mb-10">
                @guest
                    Join thousands of successful farmers who have revolutionized their feed management with our comprehensive platform.
                @endguest
                @auth
                    Continue managing your poultry operations with our powerful tools and insights.
                @endauth
            </p>

            <!-- CTA Button -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                @guest
                    <a href="{{ route('register') }}" 
                       class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-primary bg-background hover:bg-card-hover rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl hover:scale-105">
                        Start Your Free Trial
                        <flux:icon.arrow-right class="ml-2 h-5 w-5" />
                    </a>
                    <a href="#features" 
                       class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-background border-2 border-background/30 hover:border-background hover:bg-background/10 rounded-xl transition-all duration-300">
                        Learn More
                    </a>
                @endguest
                @auth
                    <a href="{{ route('dashboard') }}" 
                       class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-primary bg-background hover:bg-card-hover rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl hover:scale-105">
                        Continue to Dashboard
                        <flux:icon.arrow-right class="ml-2 h-5 w-5" />
                    </a>
                @endauth
            </div>

            <!-- Trust Badge -->
            <div class="mt-12 inline-flex items-center text-background/80 text-sm">
                <flux:icon.check class="w-5 h-5 mr-2" />
                <span class="font-medium">No setup fees • Cancel anytime • 24/7 support</span>
            </div>
        </div>
    </section>

    <!-- Footer Section -->
    <footer class="bg-text-primary text-background">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <!-- Main Footer Content -->
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-12">
                <!-- Company Info -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="flex items-center">
                        <div class="h-12 w-12 bg-primary rounded-xl flex items-center justify-center mr-4">
                            <flux:icon.cube class="h-7 w-7 text-background" />
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-background">Ayeyie</h3>
                            <p class="text-sm text-background/70">Poultry Feed Management</p>
                        </div>
                    </div>
                    <p class="text-background/70 leading-relaxed">
                        Transforming poultry operations across Ghana with secure payments, efficient pickups, 
                        and real-time inventory management. Built for modern farmers who demand excellence.
                    </p>
                    <!-- Social Media -->
                    <div class="flex space-x-4">
                        <a href="#" class="text-background/70 hover:text-primary transition-colors">
                            <span class="sr-only">Facebook</span>
                            <div class="h-10 w-10 bg-background/10 rounded-lg flex items-center justify-center hover:bg-primary/20 transition-colors">
                                <span class="text-sm font-bold">f</span>
                            </div>
                        </a>
                        <a href="#" class="text-background/70 hover:text-primary transition-colors">
                            <span class="sr-only">Twitter</span>
                            <div class="h-10 w-10 bg-background/10 rounded-lg flex items-center justify-center hover:bg-primary/20 transition-colors">
                                <span class="text-sm font-bold">X</span>
                            </div>
                        </a>
                        <a href="#" class="text-background/70 hover:text-primary transition-colors">
                            <span class="sr-only">WhatsApp</span>
                            <div class="h-10 w-10 bg-background/10 rounded-lg flex items-center justify-center hover:bg-primary/20 transition-colors">
                                <flux:icon.chat-bubble-left-right class="h-5 w-5" />
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Products -->
                <div>
                    <h3 class="text-lg font-semibold text-background mb-4">Products</h3>
                    <ul role="list" class="space-y-3">
                        <li><a href="#" class="text-background/70 hover:text-primary transition-colors text-sm">Broiler Feed</a></li>
                        <li><a href="#" class="text-background/70 hover:text-primary transition-colors text-sm">Layer Feed</a></li>
                        <li><a href="#" class="text-background/70 hover:text-primary transition-colors text-sm">Turkey Feed</a></li>
                        <li><a href="#" class="text-background/70 hover:text-primary transition-colors text-sm">Duck Feed</a></li>
                        <li><a href="#" class="text-background/70 hover:text-primary transition-colors text-sm">Supplements</a></li>
                    </ul>
                </div>

                <!-- Support -->
                <div>
                    <h3 class="text-lg font-semibold text-background mb-4">Support</h3>
                    <ul role="list" class="space-y-3">
                        <li><a href="#" class="text-background/70 hover:text-primary transition-colors text-sm">Help Center</a></li>
                        <li><a href="#" class="text-background/70 hover:text-primary transition-colors text-sm">Contact Us</a></li>
                        <li><a href="#" class="text-background/70 hover:text-primary transition-colors text-sm">User Guide</a></li>
                        <li><a href="#" class="text-background/70 hover:text-primary transition-colors text-sm">System Status</a></li>
                        <li><a href="#" class="text-background/70 hover:text-primary transition-colors text-sm">API Docs</a></li>
                    </ul>
                </div>

                <!-- Company -->
                <div>
                    <h3 class="text-lg font-semibold text-background mb-4">Company</h3>
                    <ul role="list" class="space-y-3">
                        <li><a href="#" class="text-background/70 hover:text-primary transition-colors text-sm">About Us</a></li>
                        <li><a href="#" class="text-background/70 hover:text-primary transition-colors text-sm">Our Story</a></li>
                        <li><a href="#" class="text-background/70 hover:text-primary transition-colors text-sm">Careers</a></li>
                        <li><a href="#" class="text-background/70 hover:text-primary transition-colors text-sm">Press</a></li>
                        <li><a href="#" class="text-background/70 hover:text-primary transition-colors text-sm">Partners</a></li>
                    </ul>
                </div>
            </div>

            <!-- Newsletter Signup -->
            <div class="mt-16 border-t border-background/10 pt-12">
                <div class="max-w-4xl mx-auto text-center">
                    <h3 class="text-2xl font-bold text-background mb-4">Stay updated with Ayeyie</h3>
                    <p class="text-background/70 mb-8 text-lg">Get the latest updates on new features, products, and farming tips delivered to your inbox.</p>
                    
                    <form class="flex flex-col sm:flex-row gap-4 max-w-lg mx-auto">
                        <div class="flex-1">
                            <label for="email-address" class="sr-only">Email address</label>
                            <input type="email" name="email-address" id="email-address" autocomplete="email" required
                                   class="w-full bg-background/10 border border-background/20 rounded-lg py-3 px-4 text-background placeholder-background/60 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                                   placeholder="Enter your email address">
                        </div>
                        <button type="submit"
                                class="bg-primary hover:bg-primary-hover px-6 py-3 rounded-lg text-background font-semibold transition-all duration-300 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-text-primary whitespace-nowrap">
                            Subscribe Now
                        </button>
                    </form>
                </div>
            </div>

            <!-- Bottom Footer -->
            <div class="mt-12 pt-8 border-t border-background/10">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <p class="text-background/70 text-sm">
                        &copy; {{ date('Y') }} Ayeyie Poultry Feed Management. All rights reserved.
                        <span class="block sm:inline mt-1 sm:mt-0">Made with 🧡 for Ghanaian farmers.</span>
                    </p>
                    <div class="flex flex-wrap gap-6">
                        <a href="#" class="text-background/70 hover:text-primary text-sm transition-colors">Privacy Policy</a>
                        <a href="#" class="text-background/70 hover:text-primary text-sm transition-colors">Terms of Service</a>
                        <a href="#" class="text-background/70 hover:text-primary text-sm transition-colors">Cookie Policy</a>
                    </div>
                </div>
            </div>

            <!-- Trust Indicators -->
            <div class="mt-16 pt-12 border-t border-background/10">
                <div class="text-center mb-8">
                    <h4 class="text-lg font-semibold text-background mb-2">Trusted by farmers across Ghana</h4>
                    <p class="text-background/60 text-sm">Join thousands who trust Ayeyie for their poultry management needs</p>
                </div>
                <div class="grid grid-cols-3 gap-6 sm:grid-cols-6">
                    <div class="text-center">
                        <div class="h-12 w-12 bg-background/10 rounded-xl mb-3 flex items-center justify-center mx-auto hover:bg-primary/20 transition-colors">
                            <flux:icon.check class="h-5 w-5 text-background/70" />
                        </div>
                        <span class="text-background/60 text-xs font-medium">SSL Secured</span>
                    </div>
                    <div class="text-center">
                        <div class="h-12 w-12 bg-background/10 rounded-xl mb-3 flex items-center justify-center mx-auto hover:bg-primary/20 transition-colors">
                            <flux:icon.check-circle class="h-5 w-5 text-background/70" />
                        </div>
                        <span class="text-background/60 text-xs font-medium">99.9% Uptime</span>
                    </div>
                    <div class="text-center">
                        <div class="h-12 w-12 bg-background/10 rounded-xl mb-3 flex items-center justify-center mx-auto hover:bg-primary/20 transition-colors">
                            <flux:icon.clock class="h-5 w-5 text-background/70" />
                        </div>
                        <span class="text-background/60 text-xs font-medium">24/7 Support</span>
                    </div>
                    <div class="text-center">
                        <div class="h-12 w-12 bg-background/10 rounded-xl mb-3 flex items-center justify-center mx-auto hover:bg-primary/20 transition-colors">
                            <flux:icon.device-phone-mobile class="h-5 w-5 text-background/70" />
                        </div>
                        <span class="text-background/60 text-xs font-medium">Mobile Ready</span>
                    </div>
                    <div class="text-center">
                        <div class="h-12 w-12 bg-background/10 rounded-xl mb-3 flex items-center justify-center mx-auto hover:bg-primary/20 transition-colors">
                            <flux:icon.shield-check class="h-5 w-5 text-background/70" />
                        </div>
                        <span class="text-background/60 text-xs font-medium">Fraud Protected</span>
                    </div>
                    <div class="text-center">
                        <div class="h-12 w-12 bg-background/10 rounded-xl mb-3 flex items-center justify-center mx-auto hover:bg-primary/20 transition-colors">
                            <flux:icon.users class="h-5 w-5 text-background/70" />
                        </div>
                        <span class="text-background/60 text-xs font-medium">5000+ Users</span>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Custom Styles for Animations -->
    <style>
        @keyframes bounce-slow {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }
        
        @keyframes pulse-slow {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.7;
            }
        }
        
        .animate-bounce-slow {
            animation: bounce-slow 3s infinite;
        }
        
        .animate-pulse-slow {
            animation: pulse-slow 2s infinite;
        }
    </style>
</x-layouts.welcome>