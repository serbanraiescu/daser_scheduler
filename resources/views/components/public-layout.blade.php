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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Dynamic Styles -->
    <style>
        :root {
            --primary-color: {{ $settings->primary_color ?? '#1a1a1a' }};
            --secondary-color: {{ $settings->secondary_color ?? '#d4af37' }};
        }
        body { font-family: 'Inter', sans-serif; }
        .glass-nav {
            @apply bg-white/80 backdrop-blur-lg border-b border-gray-100/50;
        }
        .card-hover {
            @apply transition-all duration-300 hover:-translate-y-1 hover:shadow-xl;
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased text-gray-900 bg-white">
    <!-- Header -->
    <header 
        x-data="{ scrolled: false }" 
        @scroll.window="scrolled = (window.pageYOffset > 20)"
        :class="scrolled ? 'glass-nav py-3' : 'bg-transparent py-5'"
        class="fixed w-full z-50 transition-all duration-300"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center transition-all duration-300">
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center group">
                        @if($settings->logo_url)
                            <img src="{{ $settings->logo_url }}" alt="{{ $settings->business_name }}" class="h-10 w-auto">
                        @else
                            <div class="w-10 h-10 bg-[var(--primary-color)] rounded-xl flex items-center justify-center mr-3 group-hover:rotate-6 transition-transform">
                                <span class="text-white font-black text-xl">{{ substr($settings->business_name ?? 'D', 0, 1) }}</span>
                            </div>
                            <span class="text-xl font-black tracking-tighter text-gray-900 uppercase">{{ $settings->business_name ?? 'Daser' }}<span class="text-[var(--secondary-color)]">.</span></span>
                        @endif
                    </a>
                </div>

                <!-- Desktop Menu -->
                <nav class="hidden lg:flex items-center gap-8">
                    <a href="#services" class="text-sm font-semibold text-gray-600 hover:text-[var(--primary-color)] transition-colors">Servicii</a>
                    <a href="#team" class="text-sm font-semibold text-gray-600 hover:text-[var(--primary-color)] transition-colors">Echipa</a>
                    <a href="#contact" class="text-sm font-semibold text-gray-600 hover:text-[var(--primary-color)] transition-colors">Contact</a>
                    @if(isset($pagesHeader))
                        @foreach($pagesHeader as $headerPage)
                            <a href="{{ url('/page/' . $headerPage->slug) }}" class="text-sm font-semibold text-gray-600 hover:text-[var(--primary-color)] transition-colors">{{ $headerPage->title }}</a>
                        @endforeach
                    @endif
                    
                    <div class="h-6 w-px bg-gray-200 ml-4 mr-2"></div>
                    
                    <a href="{{ route('login') }}" class="text-sm font-bold text-gray-900 hover:opacity-70 transition-opacity px-4 py-2 border-2 border-gray-900 rounded-full">
                        Autentificare
                    </a>
                    <a href="{{ route('bookings.index') }}" class="inline-flex items-center px-7 py-3 border border-transparent text-sm font-black rounded-full text-white bg-[var(--primary-color)] hover:shadow-2xl hover:shadow-[var(--primary-color)]/30 transition shadow-xl shadow-[var(--primary-color)]/20">
                        RESERVĂ ACUM
                    </a>
                </nav>

                <!-- Mobile menu button -->
                <div class="flex items-center lg:hidden" x-data="{ open: false }">
                    <button @click="open = !open" class="text-gray-900 p-2">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16" />
                            <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <!-- Mobile Menu Overlay -->
                    <div 
                        x-show="open" 
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-4"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        @click.away="open = false" 
                        class="fixed top-24 left-4 right-4 bg-white rounded-3xl border border-gray-100 shadow-2xl p-6 space-y-4 flex flex-col items-center text-center z-[100]"
                    >
                        <a href="#services" @click="open = false" class="block text-xl font-bold text-gray-900 py-2">Servicii</a>
                        <a href="#team" @click="open = false" class="block text-xl font-bold text-gray-900 py-2">Echipa</a>
                        <a href="#contact" @click="open = false" class="block text-xl font-bold text-gray-900 py-2">Contact</a>
                        @if(isset($pagesHeader))
                            @foreach($pagesHeader as $headerPage)
                                <a href="{{ url('/page/' . $headerPage->slug) }}" @click="open = false" class="block text-xl font-bold text-gray-900 py-2">{{ $headerPage->title }}</a>
                            @endforeach
                        @endif
                        <div class="w-full h-px bg-gray-100 my-4"></div>
                        <a href="{{ route('bookings.index') }}" class="w-full text-center px-8 py-4 border border-transparent text-lg font-black rounded-2xl text-white bg-[var(--primary-color)]">
                            PROGRAMEAZĂ-TE
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main>
        {{ $slot }}
    </main>

    <!-- Mobile Sticky CTA -->
    <div class="lg:hidden fixed bottom-6 left-4 right-4 z-[40]">
        <a href="{{ route('bookings.index') }}" class="flex items-center justify-center w-full h-16 bg-[var(--primary-color)] text-white text-lg font-black rounded-2xl shadow-2xl shadow-[var(--primary-color)]/30 active:scale-95 transition-all">
            <span>PROGRAMEAZĂ-TE ACUM</span>
            <svg class="w-6 h-6 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </a>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-950 text-white pt-24 pb-12 overflow-hidden relative">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-px bg-gradient-to-r from-transparent via-white/10 to-transparent"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-16 mb-20">
                <!-- Brand Column -->
                <div class="md:col-span-12 lg:col-span-5">
                    <a href="{{ url('/') }}" class="inline-block mb-10 group">
                        @if($settings->logo_url)
                            <img src="{{ $settings->logo_url }}" alt="{{ $settings->business_name }}" class="h-16 w-auto brightness-0 invert">
                        @else
                            <span class="text-4xl font-black tracking-tighter text-white uppercase">
                                {{ $settings->business_name ?? 'Daser' }}<span class="text-[var(--secondary-color)]">.</span>
                            </span>
                        @endif
                    </a>
                    <p class="text-gray-400 text-xl leading-relaxed max-w-lg mb-12 font-medium">
                        {{ isset($settings->about_text) ? Str::limit($settings->about_text, 220) : 'Dedicat stilului mat și experiențelor premium de îngrijire personală.' }}
                    </p>
                    <div class="flex items-center gap-5">
                        @if($settings->instagram_url)
                            <a href="{{ $settings->instagram_url }}" target="_blank" class="w-14 h-14 flex items-center justify-center rounded-2xl bg-white/5 border border-white/10 hover:bg-[var(--secondary-color)] hover:text-black transition-all duration-500 hover:-translate-y-2">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            </a>
                        @endif
                        @if($settings->facebook_url)
                            <a href="{{ $settings->facebook_url }}" target="_blank" class="w-14 h-14 flex items-center justify-center rounded-2xl bg-white/5 border border-white/10 hover:bg-[#1877F2] hover:border-[#1877F2] transition-all duration-500 hover:-translate-y-2">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Links Column -->
                <div class="md:col-span-6 lg:col-span-3">
                    <h5 class="text-xs font-black text-white uppercase tracking-[0.3em] mb-10">Meniu Rapid</h5>
                    <ul class="space-y-6">
                        <li><a href="{{ url('/') }}" class="text-lg text-gray-400 hover:text-white transition-colors duration-200 font-medium">Acasă</a></li>
                        @if(isset($pagesFooter))
                            @foreach($pagesFooter as $footerPage)
                                <li><a href="{{ url('/page/' . $footerPage->slug) }}" class="text-lg text-gray-400 hover:text-white transition-colors duration-200 font-medium">{{ $footerPage->title }}</a></li>
                            @endforeach
                        @endif
                    </ul>
                </div>

                <!-- Contact Column -->
                <div class="md:col-span-6 lg:col-span-4">
                    <h5 class="text-xs font-black text-white uppercase tracking-[0.3em] mb-10">Contact direct</h5>
                    <ul class="space-y-8">
                        @if($settings->phone)
                            <li class="flex items-start">
                                <div class="w-12 h-12 rounded-xl bg-white/5 flex items-center justify-center mr-5 shrink-0 border border-white/10">
                                    <svg class="w-6 h-6 text-[var(--secondary-color)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Telefon</div>
                                    <div class="text-xl text-white font-bold leading-none">{{ $settings->phone }}</div>
                                </div>
                            </li>
                        @endif
                        @if($settings->address)
                            <li class="flex items-start">
                                <div class="w-12 h-12 rounded-xl bg-white/5 flex items-center justify-center mr-5 shrink-0 border border-white/10">
                                    <svg class="w-6 h-6 text-[var(--secondary-color)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Locație</div>
                                    <div class="text-xl text-white font-bold leading-tight">{{ $settings->address }}</div>
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

            <div class="pt-12 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-10">
                <div class="text-gray-500 font-medium">
                    &copy; {{ date('Y') }} {{ $settings->business_name ?? 'Daser Scheduler' }}. All rights reserved.
                </div>
                <div class="flex items-center group">
                    <span class="text-gray-500 mr-3 font-semibold uppercase text-[10px] tracking-[0.2em]">Crafted by</span>
                    <span class="font-black text-white text-lg tracking-tighter uppercase group-hover:text-[var(--secondary-color)] transition-colors">DASER<span class="text-[var(--secondary-color)]">.</span></span>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
/html>