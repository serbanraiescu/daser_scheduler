<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Outfit', sans-serif; }
            .glass {
                background: rgba(255, 255, 255, 0.7);
                backdrop-filter: blur(15px);
                -webkit-backdrop-filter: blur(15px);
                border: 1px solid rgba(255, 255, 255, 0.3);
            }
            .bg-animate {
                background: linear-gradient(-45deg, #4f46e5, #7c3aed, #2563eb, #4338ca);
                background-size: 400% 400%;
                animation: gradient 15s ease infinite;
            }
            @keyframes gradient {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }
        </style>
    </head>
    <body class="antialiased bg-animate">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="mb-4 transform hover:scale-105 transition-transform duration-300">
                <a href="/">
                    <div class="p-3 glass rounded-2xl shadow-xl">
                        @php $settings = \App\Models\WebsiteSetting::first(); @endphp
                        @if($settings && $settings->logo_alt_url)
                            <img src="{{ $settings->logo_alt_url }}" alt="{{ $settings->business_name }}" class="h-12 w-auto">
                        @elseif($settings && $settings->logo_url)
                            <img src="{{ $settings->logo_url }}" alt="{{ $settings->business_name }}" class="h-12 w-auto">
                        @else
                            <x-application-logo class="w-16 h-16 fill-current text-indigo-600" />
                        @endif
                    </div>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-8 py-10 glass shadow-2xl overflow-hidden sm:rounded-3xl">
                {{ $slot }}
            </div>

            <div class="mt-8 text-white/60 text-sm">
                &copy; {{ date('Y') }} {{ config('app.name') }}. Toate drepturile rezervate.
            </div>
        </div>
    </body>
</html>
