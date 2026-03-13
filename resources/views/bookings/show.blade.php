<x-booking-layout :settings="$settings">
    <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
        
        <!-- Header / Status -->
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-emerald-50 text-emerald-500 mb-6 shadow-xl shadow-emerald-100/50">
                @if($booking->status === 'cancelled')
                    <div class="bg-red-50 text-red-500 w-full h-full rounded-full flex items-center justify-center">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                    </div>
                @else
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                @endif
            </div>
            
            <h1 class="text-3xl sm:text-4xl font-black text-gray-900 mb-2">
                {{ $booking->status === 'cancelled' ? 'Programare Anulată' : 'Programare Confirmată!' }}
            </h1>
            <p class="text-gray-500 font-medium px-4">
                {{ $booking->status === 'cancelled' ? 'Programarea a fost eliminată din calendar.' : 'Te așteptăm cu drag la data și ora stabilită.' }}
            </p>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border-2 border-emerald-100 text-emerald-700 px-6 py-4 rounded-2xl font-bold text-center mx-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Card Detalii -->
        <div class="bg-white rounded-[2.5rem] border-2 border-gray-50 shadow-2xl shadow-gray-200/50 overflow-hidden mx-2 sm:mx-0">
            <!-- Top Section: Client & Service -->
            <div class="p-8 sm:p-10 border-b border-gray-50 bg-gray-50/30">
                <div class="flex flex-col sm:flex-row justify-between items-start gap-6">
                    <div>
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] block mb-3">Serviciu ales</span>
                        <h2 class="text-2xl font-black text-gray-900">{{ $booking->service->name }}</h2>
                        <div class="flex items-center gap-3 mt-2">
                            <span class="inline-flex items-center text-sm font-bold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">
                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $booking->service->duration_minutes }} minute
                            </span>
                            <span class="text-lg font-black text-gray-900 border-l pl-3 border-gray-200">{{ $booking->service->price }} LEI</span>
                        </div>
                    </div>
                    <div class="w-full sm:w-auto p-4 bg-white rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                        <div class="w-12 h-12 bg-primary/10 text-primary rounded-xl flex items-center justify-center font-black text-xl">
                            {{ substr($booking->employee->name, 0, 1) }}
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Specialist</span>
                            <span class="text-base font-black text-gray-900">{{ $booking->employee->name }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Details Grid -->
            <div class="p-8 sm:p-10 grid grid-cols-1 sm:grid-cols-2 gap-8">
                <!-- Date & Time -->
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center shrink-0">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-0.5">Data și Ora</span>
                        <span class="text-base font-black text-gray-900 block truncate">{{ \Carbon\Carbon::parse($booking->date)->translatedFormat('l, d F Y') }}</span>
                        <span class="text-base font-black text-indigo-600 block">Ora {{ $booking->start_time->format('H:i') }}</span>
                    </div>
                </div>

                <!-- Client Info -->
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center shrink-0">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div>
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-0.5">Identitate Client</span>
                        <span class="text-base font-black text-gray-900 block">{{ $booking->client->name }}</span>
                        <span class="text-sm font-bold text-gray-500 block">{{ $booking->client->phone }}</span>
                    </div>
                </div>
            </div>

            <!-- Manage Actions -->
            <div class="p-8 sm:p-10 bg-gray-50/50 border-t border-gray-50 space-y-4">
                @if($booking->status !== 'cancelled' && $booking->start_time->gt(now()))
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-2">
                            <a href="{{ route('bookings.ics', $booking->manage_token) }}" 
                               class="flex items-center justify-center gap-3 h-14 bg-gray-900 text-white rounded-2xl font-black text-sm hover:scale-[1.02] transition shadow-xl shadow-gray-900/20 active:scale-95">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                ADĂUGA ÎN CALENDAR
                            </a>
                            <span class="text-[10px] text-gray-400 font-bold text-center uppercase tracking-wider">
                                * Descarcă și deschide fișierul pentru salvare
                            </span>
                        </div>
                        
                        <form action="{{ route('bookings.cancel', $booking->manage_token) }}" method="POST" onsubmit="return confirm('Ești sigur că vrei să anulezi programarea?')">
                            @csrf
                            <button type="submit" 
                                    class="w-full h-14 bg-white border-2 border-red-50 text-red-500 rounded-2xl font-black text-sm hover:bg-red-50 transition active:scale-95">
                                ANULEAZĂ PROGRAMAREA
                            </button>
                        </form>
                    </div>
                @endif

                <div class="text-center pt-4">
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mb-4">Link pentru gestionare ulterioară</p>
                    <div class="p-4 bg-white border border-gray-100 rounded-xl flex items-center gap-3 select-all cursor-pointer group">
                        <svg class="w-4 h-4 text-gray-300 group-hover:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.828a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                        <span class="text-xs font-bold text-gray-600 break-all overflow-hidden text-ellipsis">{{ request()->url() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center pt-8 pb-12">
            <a href="{{ route('bookings.index') }}" class="inline-flex items-center gap-2 text-sm font-black text-gray-400 hover:text-primary transition-colors group">
                <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7 7-7"/></svg>
                PROGRAMEAZĂ O ALTĂ VIZITĂ
            </a>
        </div>
    </div>
</x-booking-layout>
