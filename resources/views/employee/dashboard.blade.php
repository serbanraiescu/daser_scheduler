<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard Angajat') }}
            </h2>
            <a href="{{ route('employee.bookings.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-indigo-700 transition shadow-md shadow-indigo-200">
                + Adaugă Programare
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl border shadow-sm flex items-center gap-4">
                    <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
                        <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <div>
                        <div class="text-3xl font-black text-gray-900">{{ $stats['today'] }}</div>
                        <div class="text-sm font-bold text-gray-500 uppercase">Programări Azi</div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl border shadow-sm flex items-center gap-4">
                    <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                        <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/><path d="m9 16 2 2 4-4"/></svg>
                    </div>
                    <div>
                        <div class="text-3xl font-black text-gray-900">{{ $stats['upcoming'] }}</div>
                        <div class="text-sm font-bold text-gray-500 uppercase">Viitoare</div>
                    </div>
                </div>
            </div>

            <!-- Appointments List -->
            <div class="bg-white rounded-3xl border shadow-sm overflow-hidden">
                <div class="p-6 border-b flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <h3 class="text-lg font-bold">Programările din {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</h3>
                    
                    <form action="{{ route('employee.dashboard') }}" method="GET" class="flex items-center gap-2">
                        <input type="date" name="date" value="{{ $date }}" 
                            onchange="this.form.submit()"
                            class="rounded-xl border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </form>
                </div>

                <div class="divide-y">
                    @forelse($bookings as $booking)
                        <div class="p-6 hover:bg-gray-50 transition flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div class="w-20 text-center">
                                    <div class="text-lg font-black text-indigo-600">{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}</div>
                                    <div class="text-xs font-bold text-gray-400 uppercase">Start</div>
                                </div>
                                <div class="h-10 w-px bg-gray-200 hidden md:block"></div>
                                <div>
                                    <div class="text-lg font-bold text-gray-900">{{ $booking->client->name }}</div>
                                    <div class="text-sm text-gray-500 flex items-center gap-2">
                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                        {{ $booking->client->phone }}
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col md:items-end">
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-700 uppercase mb-1">
                                    {{ $booking->service->name }}
                                </span>
                                <div class="text-xs text-gray-400 font-bold">
                                    Durată: {{ $booking->service->duration_minutes }} min
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center">
                            <div class="w-16 h-16 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="m9 16 2 2 4-4"/></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">Nicio programare</h3>
                            <p class="text-gray-500">Nu ai nicio programare confirmată pentru această dată.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
