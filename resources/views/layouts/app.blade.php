<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased" x-data="{ sidebarOpen: false }">
        @if(session()->has('impersonate_id'))
            <div class="bg-indigo-600 text-white py-2 px-4 flex justify-between items-center sticky top-0 z-[100]">
                <div class="flex items-center text-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span>Acționezi în numele lui <strong>{{ auth()->user()->name }}</strong> (Vizualizare Angajat)</span>
                </div>
                <form action="{{ route('admin.employees.stop-impersonate') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-white text-indigo-600 px-3 py-1 rounded-md text-xs font-bold uppercase tracking-wider hover:bg-gray-100 transition">
                        Înapoi la Admin
                    </button>
                </form>
            </div>
        @endif
        <div class="min-h-screen bg-gray-100">
            @include('layouts.sidebar')

            <div :class="{'sm:ml-64': {{ auth()->user()->isAdmin() ? 'true' : 'false' }}}">
                @include('layouts.navigation')
                @if(auth()->user()->isAdmin())
                    <x-license-alerts />
                @endif

                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
