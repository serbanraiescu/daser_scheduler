@props(['settings'])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Rezervare - {{ $settings->business_name ?? config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Styles -->
    <style>
        :root {
            --primary-color: {{ $settings->primary_color ?? '#1a1a1a' }};
            --secondary-color: {{ $settings->secondary_color ?? '#d4af37' }};
        }
        body { font-family: 'Inter', sans-serif; -webkit-tap-highlight-color: transparent; }
        .bg-primary { background-color: var(--primary-color); }
        .text-primary { color: var(--primary-color); }
        .border-primary { border-color: var(--primary-color); }
        .ring-primary { --tw-ring-color: var(--primary-color); }
        
        /* Custom scrollbar for horizontal calendar */
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="h-full antialiased text-gray-900 overflow-x-hidden">
    <div class="min-h-full flex flex-col">
        <!-- Minimal Header -->
        <header class="bg-white border-b border-gray-100 sticky top-0 z-40">
            <div class="max-w-3xl mx-auto px-4 h-16 flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="p-2 -ml-2 text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </a>
                    <span class="ml-2 font-bold text-gray-900">{{ $settings->business_name ?? 'Booking' }}</span>
                </div>
                <div class="flex items-center gap-4">
                    <!-- Progress could go here or inside the main content -->
                </div>
            </div>
        </header>

        <main class="flex-grow">
            <div class="max-w-3xl mx-auto py-8 px-4 pb-32">
                {{ $slot }}
            </div>
        </main>
    </div>
</body>
</html>
