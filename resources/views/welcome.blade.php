<x-layouts.welcome>
    <!-- Hero Section with Background Pattern -->
    <div class="relative overflow-hidden">
        <div
            class="absolute inset-0 bg-gradient-to-br from-amber-50 to-transparent opacity-50 dark:from-gray-900 dark:to-gray-800">
        </div>
        <div class="absolute inset-0" x-data="{}" x-init="(() => {
            const svgNS = 'http://www.w3.org/2000/svg';
            const svg = document.createElementNS(svgNS, 'svg');
            svg.setAttribute('width', '100%');
            svg.setAttribute('height', '100%');
            svg.style.opacity = '0.03';
        
            for (let i = 0; i < 20; i++) {
                const circle = document.createElementNS(svgNS, 'circle');
                circle.setAttribute('cx', Math.random() * 100 + '%');
                circle.setAttribute('cy', Math.random() * 100 + '%');
                circle.setAttribute('r', Math.random() * 50 + 10);
                circle.setAttribute('fill', 'currentColor');
                svg.appendChild(circle);
            }
        
            $el.appendChild(svg);
        })()"></div>

        <div class="relative w-full py-24 md:py-32">
            <section class="mx-auto max-w-4xl text-center">
                <h1
                    class="mb-6 text-4xl font-bold leading-tight tracking-tight text-[#1b1b18] md:text-5xl lg:text-6xl dark:text-[#EDEDEC]">
                    <span class="bg-gradient-to-r from-amber-600 to-yellow-500 bg-clip-text text-transparent">
                        Effortless Poultry Feed
                    </span>
                    <span class="mt-2 block">Management System</span>
                </h1>

                <p
                    class="mx-auto mb-10 max-w-2xl text-lg font-normal leading-relaxed text-[#4a4a47] md:text-xl dark:text-[#A2A29D]">
                    Streamline your poultry operations with secure payments, swift pickups, and real-time insights—built
                    exclusively for Ayeyie Poultry Feed.
                </p>

                @guest
                    <div class="flex flex-col justify-center gap-4 sm:flex-row">
                        <a class="inline-flex items-center justify-center rounded-lg bg-amber-500 px-8 py-3.5 text-lg font-medium text-white shadow-lg transition duration-300 hover:bg-amber-600 focus:ring-4 focus:ring-amber-300 dark:focus:ring-amber-800"
                            href="{{ route('register') }}" x-data="{ hover: false }" @mouseenter="hover = true"
                            @mouseleave="hover = false">
                            <span>Get Started</span>
                            <svg class="ml-2 h-5 w-5 transition-transform duration-300" xmlns="http://www.w3.org/2000/svg"
                                :class="{ 'translate-x-1': hover }" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                        <a class="inline-flex items-center justify-center rounded-lg border border-[#19140035] bg-[#FDFDFC] px-8 py-3.5 text-lg font-medium text-[#1b1b18] transition duration-300 hover:bg-gray-50 focus:ring-4 focus:ring-gray-200 dark:border-[#3E3E3A] dark:bg-[#1b1b18] dark:bg-gray-700 dark:text-[#EDEDEC] dark:hover:bg-[#33332d] dark:focus:ring-[#62605b]"
                            href="{{ route('login') }}">
                            Sign In
                        </a>
                    </div>
                @endguest

                @auth
                    <div
                        class="rounded-xl border border-[#19140035] bg-[#FDFDFC] p-6 shadow-md dark:border-[#3E3E3A] dark:bg-[#1b1b18]">
                        <p class="mb-4 text-lg font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                            Welcome back, <span
                                class="font-semibold text-amber-600 dark:text-amber-400">{{ auth()->user()->name }}</span>
                        </p>
                        <div class="flex flex-col justify-center gap-4 sm:flex-row">
                            <flux:button href="{{ route('dashboard') }}" wire:navigate icon:trailing="chevron-down"
                                variant="primary">
                                <span>Go to Dashboard</span>
                            </flux:button>
                            <flux:button href="{{ route('customers.orders.create') }}" icon="plus" wire:navigate>
                                <span>New Order</span>
                            </flux:button>
                        </div>
                    </div>
                @endauth

                <!-- Stats Counter -->
                <div class="mx-auto mt-16 grid max-w-2xl grid-cols-1 gap-6 sm:grid-cols-3">
                    <div class="text-center" x-data="{ count: 0 }" x-init="(() => {
                        const target = 98;
                        const duration = 2000;
                        const start = performance.now();
                        const step = (timestamp) => {
                            const progress = Math.min((timestamp - start) / duration, 1);
                            count = Math.floor(progress * target);
                            if (progress < 1) requestAnimationFrame(step);
                        };
                        requestAnimationFrame(step);
                    })()">
                        <span class="block text-3xl font-bold text-amber-600 dark:text-amber-400"
                            x-text="count + '%'"></span>
                        <span class="text-sm text-[#4a4a47] dark:text-[#A2A29D]">Faster Deliveries</span>
                    </div>
                    <div class="text-center" x-data="{ count: 0 }" x-init="(() => {
                        const target = 5000;
                        const duration = 3000;
                        const start = performance.now();
                        const step = (timestamp) => {
                            const progress = Math.min((timestamp - start) / duration, 1);
                            count = Math.floor(progress * target);
                            if (progress < 1) requestAnimationFrame(step);
                        };
                        requestAnimationFrame(step);
                    })()">
                        <span class="block text-3xl font-bold text-amber-600 dark:text-amber-400"
                            x-text="count.toLocaleString()"></span>
                        <span class="text-sm text-[#4a4a47] dark:text-[#A2A29D]">Happy Customers</span>
                    </div>
                    <div class="text-center" x-data="{ count: 0 }" x-init="(() => {
                        const target = 99;
                        const duration = 2500;
                        const start = performance.now();
                        const step = (timestamp) => {
                            const progress = Math.min((timestamp - start) / duration, 1);
                            count = Math.floor(progress * target);
                            if (progress < 1) requestAnimationFrame(step);
                        };
                        requestAnimationFrame(step);
                    })()">
                        <span class="block text-3xl font-bold text-amber-600 dark:text-amber-400"
                            x-text="count + '%'"></span>
                        <span class="text-sm text-[#4a4a47] dark:text-[#A2A29D]">Order Accuracy</span>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- Features Section -->
    <section class="bg-[#FDFDFC] py-16 dark:bg-[#0a0a0a]">
        <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-12 text-center">
                <h2 class="mb-4 text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Powerful Features</h2>
                <p class="mx-auto max-w-3xl text-xl text-[#4a4a47] dark:text-[#A2A29D]">
                    Everything you need to manage your poultry feed supply chain efficiently
                </p>
            </div>

            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                <!-- Feature Card 1 -->
                <div
                    class="group relative overflow-hidden rounded-xl border border-[#19140035] bg-[#FDFDFC] p-8 shadow-lg transition-all duration-300 hover:translate-y-[-5px] dark:border-[#3E3E3A] dark:bg-[#1b1b18]">
                    <div
                        class="absolute right-0 top-0 h-32 w-32 rounded-bl-full bg-amber-100 opacity-30 transition-opacity group-hover:opacity-50 dark:bg-amber-900/30">
                    </div>
                    <div class="relative z-10">
                        <div
                            class="mb-6 flex h-14 w-14 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900/50">
                            <svg class="h-8 w-8 text-amber-600 dark:text-amber-400" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h3 class="mb-3 text-xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Trusted Payments</h3>
                        <p class="mb-6 leading-relaxed text-[#4a4a47] dark:text-[#A2A29D]">
                            QR-verified receipts keep every transaction secure. Real-time validation ensures confidence.
                        </p>
                        <a class="inline-flex items-center font-medium text-amber-600 hover:text-amber-700 dark:text-amber-400 dark:hover:text-amber-300"
                            href="#">
                            <span>Learn more</span>
                            <svg class="ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Feature Card 2 -->
                <div
                    class="group relative overflow-hidden rounded-xl border border-[#19140035] bg-[#FDFDFC] p-8 shadow-lg transition-all duration-300 hover:translate-y-[-5px] dark:border-[#3E3E3A] dark:bg-[#1b1b18]">
                    <div
                        class="absolute right-0 top-0 h-32 w-32 rounded-bl-full bg-amber-100 opacity-30 transition-opacity group-hover:opacity-50 dark:bg-amber-900/30">
                    </div>
                    <div class="relative z-10">
                        <div
                            class="mb-6 flex h-14 w-14 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900/50">
                            <svg class="h-8 w-8 text-amber-600 dark:text-amber-400" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h3 class="mb-3 text-xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Fast Track Pickup</h3>
                        <p class="mb-6 leading-relaxed text-[#4a4a47] dark:text-[#A2A29D]">
                            Skip the wait with our optimized pickup process. Get your feed when you need it.
                        </p>
                        <a class="inline-flex items-center font-medium text-amber-600 hover:text-amber-700 dark:text-amber-400 dark:hover:text-amber-300"
                            href="#">
                            <span>Learn more</span>
                            <svg class="ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Feature Card 3 -->
                <div
                    class="group relative overflow-hidden rounded-xl border border-[#19140035] bg-[#FDFDFC] p-8 shadow-lg transition-all duration-300 hover:translate-y-[-5px] dark:border-[#3E3E3A] dark:bg-[#1b1b18]">
                    <div
                        class="absolute right-0 top-0 h-32 w-32 rounded-bl-full bg-amber-100 opacity-30 transition-opacity group-hover:opacity-50 dark:bg-amber-900/30">
                    </div>
                    <div class="relative z-10">
                        <div
                            class="mb-6 flex h-14 w-14 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900/50">
                            <svg class="h-8 w-8 text-amber-600 dark:text-amber-400" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </div>
                        <h3 class="mb-3 text-xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Smart Updates</h3>
                        <p class="mb-6 leading-relaxed text-[#4a4a47] dark:text-[#A2A29D]">
                            Stay informed with real-time notifications via SMS, email, or in-app alerts.
                        </p>
                        <a class="inline-flex items-center font-medium text-amber-600 hover:text-amber-700 dark:text-amber-400 dark:hover:text-amber-300"
                            href="#">
                            <span>Learn more</span>
                            <svg class="ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <livewire:welcome.products.index />

    <!-- Testimonials Section with Alpine.js Carousel -->
    <section class="bg-[#FDFDFC] py-16 dark:bg-[#1b1b18]">
        <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-12 text-center">
                <h2 class="mb-4 text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">What Our Customers Say</h2>
                <p class="mx-auto max-w-3xl text-xl text-[#4a4a47] dark:text-[#A2A29D]">
                    Trusted by poultry farmers across the region
                </p>
            </div>

            <div class="relative" x-data="{
                activeSlide: 0,
                slides: [
                    { name: 'Akosua Kumi', farm: 'Kumasi Poultry Farm', quote: 'The Ayeyie system has transformed how we manage our feed supply. No more long waits or payment confusion—everything is transparent and efficient.' },
                    { name: 'Kwame Mensah', farm: 'Accra Feed Co.', quote: 'Fast pickups and secure payments have saved us time and money. A game-changer for our operations.' },
                    { name: 'Ama Serwaa', farm: 'Takoradi Farms', quote: 'Real-time updates keep us in control. The best tool for poultry feed management!' }
                ],
                interval: 5000,
                autoplay: true,
                init() {
                    if (this.autoplay) {
                        this.autoplayInterval = setInterval(() => {
                            this.activeSlide = (this.activeSlide + 1) % this.slides.length;
                        }, this.interval);
                    }
                },
                stopAutoplay() {
                    clearInterval(this.autoplayInterval);
                },
                restartAutoplay() {
                    if (this.autoplay) {
                        this.stopAutoplay();
                        this.init();
                    }
                }
            }"
                @keydown.arrow-right="activeSlide = (activeSlide + 1) % slides.length; restartAutoplay()"
                @keydown.arrow-left="activeSlide = (activeSlide - 1 + slides.length) % slides.length; restartAutoplay()">
                <!-- Testimonial Slider -->
                <div class="relative overflow-hidden">
                    <div class="flex transition-transform duration-500 ease-out"
                        :style="`transform: translateX(-${activeSlide * 100}%)`">
                        @foreach ([['Akosua Kumi', 'Kumasi Poultry Farm', 'The Ayeyie system has transformed how we manage our feed supply. No more long waits or payment confusion—everything is transparent and efficient.'], ['Kwame Mensah', 'Accra Feed Co.', 'Fast pickups and secure payments have saved us time and money. A game-changer for our operations.'], ['Ama Serwaa', 'Takoradi Farms', 'Real-time updates keep us in control. The best tool for poultry feed management!']] as $testimonial)
                            <div class="w-full flex-shrink-0 px-4">
                                <div
                                    class="rounded-xl border border-[#19140035] bg-[#FDFDFC] p-8 shadow-md dark:border-[#3E3E3A] dark:bg-[#1b1b18]">
                                    <div class="mb-6 flex items-center">
                                        <div class="mr-4">
                                            <div
                                                class="flex h-16 w-16 items-center justify-center rounded-full bg-amber-200 text-xl font-bold text-amber-800 dark:bg-amber-700 dark:text-amber-200">
                                                {{ substr($testimonial[0], 0, 1) . substr(explode(' ', $testimonial[0])[1], 0, 1) }}
                                            </div>
                                        </div>
                                        <div>
                                            <h4 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">
                                                {{ $testimonial[0] }}</h4>
                                            <p class="text-[#4a4a47] dark:text-[#A2A29D]">{{ $testimonial[1] }}</p>
                                        </div>
                                        <div class="ml-auto flex">
                                            @for ($i = 0; $i < 5; $i++)
                                                <svg class="h-5 w-5 text-yellow-400"
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3 .921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784 .57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81 .588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="italic text-[#4a4a47] dark:text-[#A2A29D]">“{{ $testimonial[2] }}”</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Carousel Controls -->
                <button
                    class="absolute left-0 top-1/2 -translate-y-1/2 rounded-full bg-amber-500 p-2 text-white transition hover:bg-amber-600 focus:ring-4 focus:ring-amber-300 dark:focus:ring-amber-800"
                    @click="activeSlide = (activeSlide - 1 + slides.length) % slides.length; restartAutoplay()">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button
                    class="absolute right-0 top-1/2 -translate-y-1/2 rounded-full bg-amber-500 p-2 text-white transition hover:bg-amber-600 focus:ring-4 focus:ring-amber-300 dark:focus:ring-amber-800"
                    @click="activeSlide = (activeSlide + 1) % slides.length; restartAutoplay()">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>
    </section>

    <!-- Trust Badges Section -->
    <section class="bg-[#FDFDFC] py-16 dark:bg-[#0a0a0a]">
        <div class="mx-auto w-full max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="mb-12 text-center">
                <h2 class="mb-4 text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Trusted by Farmers, Secured for
                    You</h2>
            </div>
            <div class="grid grid-cols-1 gap-8 sm:grid-cols-3">
                <div class="flex flex-col items-center">
                    <svg class="mb-4 h-12 w-12 text-amber-600 dark:text-amber-400" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 11c1.104 0 2-.896 2-2s-.896-2-2-2-2 .896-2 2 .896 2 2 2zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                    </svg>
                    <p class="font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Farmer Approved</p>
                    <p class="text-sm text-[#4a4a47] dark:text-[#A2A29D]">Serving poultry farmers region-wide</p>
                </div>
                <div class="flex flex-col items-center">
                    <svg class="mb-4 h-12 w-12 text-amber-600 dark:text-amber-400" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm0-6C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z" />
                    </svg>
                    <p class="font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Secure Payments</p>
                    <p class="text-sm text-[#4a4a47] dark:text-[#A2A29D]">Encrypted and QR-verified</p>
                </div>
                <div class="flex flex-col items-center">
                    <svg class="mb-4 h-12 w-12 text-amber-600 dark:text-amber-400" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    <p class="font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Fast Service</p>
                    <p class="text-sm text-[#4a4a47] dark:text-[#A2A29D]">Swift pickups guaranteed</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call-to-Action Banner -->
    <section class="bg-amber-500 py-12 dark:bg-amber-600">
        <div class="mx-auto w-full max-w-7xl px-4 text-center sm:px-6 lg:px-8">
            @guest
                <h3 class="mb-4 text-2xl font-bold text-white">Ready to Simplify Your Feed Management?</h3>
                <a class="inline-flex items-center justify-center rounded-lg bg-zinc-50 px-8 py-3 text-lg font-medium text-amber-600 shadow-lg transition duration-300 hover:bg-gray-100 focus:ring-4 focus:ring-amber-300 dark:bg-zinc-800"
                    href="{{ route('register') }}">
                    Join Now
                    <svg class="ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            @endguest
            @auth
                <h3 class="mb-4 text-2xl font-bold text-white">Welcome, {{ auth()->user()->name }}!</h3>
                <a class="inline-flex items-center justify-center rounded-lg bg-zinc-50 px-8 py-3 text-lg font-medium text-amber-600 shadow-lg transition duration-300 hover:bg-gray-100 focus:ring-4 focus:ring-amber-300 dark:bg-zinc-800"
                    href="{{ route('dashboard') }}">
                    Start Managing
                    <svg class="ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            @endauth
        </div>
    </section>
</x-layouts.welcome>
