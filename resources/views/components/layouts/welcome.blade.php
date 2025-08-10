<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Ayeyie Poultry Feed') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @fluxAppearance
    </head>

    <body class="min-h-screen bg-background text-text-primary">
        <header class="absolute top-0 left-0 right-0 z-50 p-6 lg:p-8">
            <nav class="flex items-center justify-between max-w-7xl mx-auto">
                <!-- App Logo/Name -->
                <div class="flex items-center">
                    <div class="size-8 bg-primary rounded-lg flex items-center justify-center mr-3">
                        <flux:icon.cube class="size-6 text-background" />
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-text-primary">{{ config('app.name', 'Ayeyie') }}</h1>
                        <p class="text-xs text-text-secondary hidden sm:block">Poultry Feed Management</p>
                    </div>
                </div>

                <!-- Navigation Links -->
                @if (Route::has('login'))
                    <div class="flex items-center gap-4">
                        @auth
                            <a class="inline-flex items-center px-4 py-2 text-sm font-medium text-background bg-card hover:bg-card-hover border border-border rounded-lg transition-colors"
                                href="{{ url('/dashboard') }}">
                                Dashboard
                            </a>
                        @else
                            <a class="inline-flex items-center px-4 py-2 text-sm font-medium text-text-primary hover:text-primary border border-transparent hover:border-border rounded-lg transition-colors"
                                href="{{ route('login') }}">
                                Log in
                            </a>

                            @if (Route::has('register'))
                                <a class="inline-flex items-center px-4 py-2 text-sm font-medium text-background bg-primary hover:bg-primary-hover rounded-lg transition-colors"
                                    href="{{ route('register') }}">
                                    Register
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif
            </nav>
        </header>

        <main class="w-full">
            {{ $slot }}
        </main>

        @fluxScripts
    </body>

</html>
