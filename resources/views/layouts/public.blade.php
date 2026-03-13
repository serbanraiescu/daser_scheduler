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
                    @foreach($pagesHeader as $headerPage)
                        <a href="{{ url('/page/' . $headerPage->slug) }}" class="text-sm font-medium text-gray-600 hover:text-[var(--primary-color)] transition">{{ $headerPage->title }}</a>
                    @endforeach
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
                        @foreach($pagesHeader as $headerPage)
                            <a href="{{ url('/page/' . $headerPage->slug) }}" class="block text-base font-semibold text-gray-900">{{ $headerPage->title }}</a>
                        @endforeach
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
    <footer class="bg-gray-50 border-t border-gray-100 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="md:col-span-2">
                    <div class="flex items-center mb-6">
                        @if($settings->logo_url)
                            <img src="{{ $settings->logo_url }}" alt="{{ $settings->business_name }}" class="h-8 w-auto">
                        @else
                            <span class="text-xl font-bold text-gray-900">{{ $settings->business_name ?? 'Daser Scheduler' }}</span>
                        @endif
                    </div>
                    <p class="text-gray-500 max-w-sm">
                        {{ Str::limit($settings->about_text, 150) ?? 'Expertiza noastră redefinește standardele frumuseții și îngrijirii personale.' }}
                    </p>
                    <div class="flex space-x-4 mt-6">
                        @if($settings->instagram_url)
                            <a href="{{ $settings->instagram_url }}" target="_blank" class="text-gray-400 hover:text-pink-600 transition">Instagram</a>
                        @endif
                        @if($settings->facebook_url)
                            <a href="{{ $settings->facebook_url }}" target="_blank" class="text-gray-400 hover:text-blue-600 transition">Facebook</a>
                        @endif
                        @if($settings->tiktok_url)
                            <a href="{{ $settings->tiktok_url }}" target="_blank" class="text-gray-400 hover:text-black transition">TikTok</a>
                        @endif
                    </div>
                </div>

                <div>
                    <h5 class="text-sm font-bold text-gray-900 uppercase tracking-widest mb-6">Informații</h5>
                    <ul class="space-y-4">
                        @foreach($pagesFooter as $footerPage)
                            <li><a href="{{ url('/page/' . $footerPage->slug) }}" class="text-gray-500 hover:text-[var(--primary-color)] transition">{{ $footerPage->title }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <div>
                    <h5 class="text-sm font-bold text-gray-900 uppercase tracking-widest mb-6">Contact</h5>
                    <ul class="space-y-4 text-gray-500">
                        @if($settings->phone) <li>{{ $settings->phone }}</li> @endif
                        @if($settings->email) <li>{{ $settings->email }}</li> @endif
                        @if($settings->address) <li>{{ $settings->address }}</li> @endif
                    </ul>
                </div>
            </div>
            <div class="pt-8 border-t border-gray-200 text-center text-xs text-gray-400">
                &copy; {{ date('Y') }} {{ $settings->business_name ?? 'Daser Scheduler' }}. Toate drepturile rezervate.
            </div>
        </div>
    </footer>
</body>
</html>
