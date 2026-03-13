@props(['settings', 'pagesHeader', 'pagesFooter'])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('meta_title', $settings->seo_title ?? $settings->business_name ?? config('app.name', 'Laravel'))</title>
    <meta name="description" content="@yield('meta_description', $settings->seo_description ?? '')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Dynamic Styles -->
    <style>
        :root {
            --primary-color: {{ $settings->primary_color ?? '#6366f1' }};
            --secondary-color: {{ $settings->secondary_color ?? '#a855f7' }};
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 bg-white">
    <!-- Header -->
    <header class="fixed w-full z-50 bg-white/80 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center">
                        @if($settings->logo_url)
                            <img src="{{ $settings->logo_url }}" alt="{{ $settings->business_name }}" class="h-10 w-auto">
                        @else
                            <x-application-logo class="h-10 w-auto fill-current text-[var(--primary-color)]" />
                            <span class="ml-3 text-2xl font-extrabold tracking-tight text-gray-900">{{ $settings->business_name ?? 'Daser Scheduler' }}</span>
                        @endif
                    </a>
                </div>

                <!-- Desktop Menu -->
                <nav class="hidden md:flex items-center space-x-8">
                    @if(isset($pagesHeader))
                        @foreach($pagesHeader as $headerPage)
                            <a href="{{ url('/page/' . $headerPage->slug) }}" class="text-sm font-medium text-gray-600 hover:text-[var(--primary-color)] transition">{{ $headerPage->title }}</a>
                        @endforeach
                    @endif
                    <a href="{{ route('bookings.index') }}" class="inline-flex items-center px-6 py-2.5 border border-transparent text-sm font-bold rounded-full text-white bg-[var(--primary-color)] hover:opacity-90 transition shadow-lg shadow-[var(--primary-color)]/25">
                        Programează-te
                    </a>
                </nav>

                <!-- Mobile menu button -->
                <div class="flex items-center md:hidden" x-data="{ open: false }">
                    <button @click="open = !open" class="text-gray-500 hover:text-gray-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <!-- Mobile Menu Overlay -->
                    <div x-show="open" @click.away="open = false" class="absolute top-20 left-0 w-full bg-white border-b border-gray-100 shadow-xl p-4 space-y-4">
                        @if(isset($pagesHeader))
                            @foreach($pagesHeader as $headerPage)
                                <a href="{{ url('/page/' . $headerPage->slug) }}" class="block text-base font-semibold text-gray-900">{{ $headerPage->title }}</a>
                            @endforeach
                        @endif
                        <a href="{{ route('bookings.index') }}" class="block text-center px-6 py-3 border border-transparent text-base font-bold rounded-xl text-white bg-[var(--primary-color)]">
                            Programează-te
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="pt-20">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-gray-950 text-white pt-20 pb-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-12 mb-16">
                <!-- Brand Column -->
                <div class="md:col-span-5">
                    <a href="{{ url('/') }}" class="inline-block mb-8">
                        @if($settings->logo_url)
                            <img src="{{ $settings->logo_url }}" alt="{{ $settings->business_name }}" class="h-12 w-auto brightness-0 invert">
                        @else
                            <span class="text-3xl font-black tracking-tighter text-white">
                                {{ strtoupper($settings->business_name ?? 'Daser') }}<span class="text-[var(--secondary-color)]">.</span>
                            </span>
                        @endif
                    </a>
                    <p class="text-gray-400 text-lg leading-relaxed max-w-md mb-8">
                        {{ isset($settings->about_text) ? Str::limit($settings->about_text, 180) : 'Eforturile noastre sunt dedicate excelenței și stilului tău personal.' }}
                    </p>
                    <div class="flex items-center gap-4">
                        @if($settings->instagram_url)
                            <a href="{{ $settings->instagram_url }}" target="_blank" class="w-12 h-12 flex items-center justify-center rounded-full bg-white/5 border border-white/10 hover:bg-[var(--primary-color)] transition-all duration-300">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            </a>
                        @endif
                        @if($settings->facebook_url)
                            <a href="{{ $settings->facebook_url }}" target="_blank" class="w-12 h-12 flex items-center justify-center rounded-full bg-white/5 border border-white/10 hover:bg-[var(--primary-color)] transition-all duration-300">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Links Column -->
                <div class="md:col-span-3">
                    <h5 class="text-sm font-bold text-white uppercase tracking-[0.2em] mb-8">Link-uri Utile</h5>
                    <ul class="space-y-4">
                        <li><a href="{{ url('/') }}" class="text-gray-400 hover:text-white transition-colors duration-200">Acasă</a></li>
                        @if(isset($pagesFooter))
                            @foreach($pagesFooter as $footerPage)
                                <li><a href="{{ url('/page/' . $footerPage->slug) }}" class="text-gray-400 hover:text-white transition-colors duration-200">{{ $footerPage->title }}</a></li>
                            @endforeach
                        @endif
                    </ul>
                </div>

                <!-- Contact Column -->
                <div class="md:col-span-4">
                    <h5 class="text-sm font-bold text-white uppercase tracking-[0.2em] mb-8">Contact</h5>
                    <ul class="space-y-6">
                        @if($settings->phone)
                            <li class="flex items-start">
                                <span class="text-[var(--secondary-color)] mr-4">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                </span>
                                <div class="text-lg text-gray-300 font-medium">{{ $settings->phone }}</div>
                            </li>
                        @endif
                        @if($settings->address)
                            <li class="flex items-start">
                                <span class="text-[var(--secondary-color)] mr-4">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </span>
                                <div class="text-lg text-gray-300 leading-relaxed">{{ $settings->address }}</div>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

            <div class="pt-10 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-6 text-sm text-gray-500">
                <div>&copy; {{ date('Y') }} {{ $settings->business_name ?? 'Daser Scheduler' }}. Toate drepturile rezervate.</div>
                <div class="flex items-center gap-2">
                    <span>Power by</span>
                    <span class="font-bold text-white tracking-widest text-[10px] uppercase">Daser Scheduler</span>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>