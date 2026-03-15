<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-black text-3xl text-gray-900 leading-tight">
                    {{ __('Bună,') }} {{ auth()->user()->name }} 👋
                </h2>
                <p class="text-gray-500 font-medium">Gestionați-vă programările și beneficiile de fidelitate.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('bookings.index') }}" class="px-6 py-3 bg-indigo-600 text-white rounded-2xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200 active:scale-95">
                    + Programare Nouă
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-12">
            
            <!-- Top Cards Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Fidelity Card -->
                <div class="lg:col-span-2 relative overflow-hidden bg-indigo-600 rounded-[2.5rem] p-8 text-white shadow-2xl shadow-indigo-200">
                    <div class="absolute top-0 right-0 -m-8 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 -m-8 w-48 h-48 bg-white/10 rounded-full blur-3xl"></div>
                    
                    <div class="relative flex flex-col sm:flex-row justify-between items-start sm:items-center gap-8">
                        <div class="space-y-6">
                            <div class="inline-flex px-4 py-1.5 bg-white/20 rounded-full text-[10px] font-black uppercase tracking-[0.2em] backdrop-blur-md">
                                Card de Fidelitate
                            </div>
                            <div>
                                <p class="text-indigo-100 text-xs font-bold uppercase tracking-widest mb-1">Număr Card</p>
                                <h3 class="text-2xl font-mono tracking-[0.3em] font-black text-white/90">
                                    {{ $client->fidelity_card_number ?? '---- ---- ----' }}
                                </h3>
                            </div>
                            <div class="flex gap-12">
                                <div>
                                    <p class="text-indigo-100 text-xs font-bold uppercase tracking-widest mb-1">Puncte</p>
                                    <p class="text-4xl font-black">{{ $client->loyalty_points }}</p>
                                </div>
                                <div>
                                    <p class="text-indigo-100 text-xs font-bold uppercase tracking-widest mb-1">Discount Client</p>
                                    <p class="text-4xl font-black">{{ number_format($client->special_discount, 0) }}%</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="w-full sm:w-auto bg-white/10 backdrop-blur-xl border border-white/20 rounded-[2rem] p-6 flex flex-col items-center gap-2 group">
                            <div class="w-20 h-20 bg-white rounded-2xl flex items-center justify-center text-indigo-600 shadow-xl group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5h14a2 2 0 0 1 2 2v3a2 2 0 0 0 0 4v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3a2 2 0 0 0 0-4V7a2 2 0 0 1 2-2Z"/></svg>
                            </div>
                            <div class="text-center">
                                <p class="text-[10px] font-black uppercase tracking-widest text-indigo-100 mb-1">Vouchere</p>
                                <p class="text-2xl font-black">{{ $voucherCount }} Active</p>
                                <a href="{{ route('client.vouchers') }}" class="text-[10px] font-black text-white/60 hover:text-white transition-colors uppercase mt-2 block">Vezi tot →</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Booking -->
                <div class="lg:col-span-1 space-y-4">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] px-4">Proxima vizită</h3>
                    @if($upcomingBooking)
                        <div class="bg-white rounded-[2.5rem] p-8 border-2 border-indigo-50 shadow-xl shadow-gray-100 space-y-6 relative overflow-hidden group">
                            <div class="absolute top-0 right-0 p-4">
                                <span class="inline-flex items-center px-3 py-1 bg-green-50 text-green-600 rounded-full text-[10px] font-black uppercase tracking-widest">Confirmată</span>
                            </div>

                            <div class="flex items-center gap-6">
                                <div class="bg-indigo-600 text-white w-16 h-20 rounded-2xl flex flex-col items-center justify-center shadow-lg shadow-indigo-100">
                                    <span class="text-[10px] font-black uppercase">{{ $upcomingBooking->date->translatedFormat('M') }}</span>
                                    <span class="text-2xl font-black tracking-tighter">{{ $upcomingBooking->date->format('d') }}</span>
                                </div>
                                <div>
                                    <p class="text-2xl font-black text-gray-900">{{ $upcomingBooking->start_time->format('H:i') }}</p>
                                    <p class="text-gray-500 font-bold">{{ $upcomingBooking->date->translatedFormat('l') }}</p>
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Serviciu ales</p>
                                    <p class="text-xl font-black text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $upcomingBooking->service->name }}</p>
                                </div>
                                
                                <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-2xl group-hover:bg-indigo-50/50 transition-colors">
                                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-indigo-500 shadow-sm border border-indigo-100 font-black">
                                        {{ substr($upcomingBooking->employee->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Specialist</p>
                                        <p class="text-sm font-bold text-gray-900">{{ $upcomingBooking->employee->name }}</p>
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('bookings.show', $upcomingBooking->manage_token) }}" class="flex items-center justify-center gap-2 w-full py-4 bg-gray-900 text-white rounded-2xl font-bold hover:bg-black transition shadow-lg active:scale-95">
                                <span>Administrare Rezervare</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </div>
                    @else
                        <div class="bg-gray-50/50 rounded-[2.5rem] p-12 text-center border-2 border-dashed border-gray-200 flex flex-col items-center justify-center h-full min-h-[300px]">
                            <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-gray-300 shadow-sm mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <p class="text-gray-400 font-medium max-w-[180px] mx-auto mb-6">Momentan nu ai nicio programare viitoare.</p>
                            <a href="{{ route('bookings.index') }}" class="px-6 py-2 border-2 border-indigo-100 text-indigo-600 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition shadow-sm">Programază-te acum</a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Full History Table -->
            <div class="space-y-4 pt-4">
                <div class="flex items-center justify-between px-4">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em]">Istoric Complet</h3>
                    <span class="text-[10px] font-bold text-indigo-500 bg-indigo-50 px-3 py-1 rounded-full uppercase">{{ $bookings->total() }} Rezervări</span>
                </div>
                
                <div class="bg-white rounded-[2.5rem] shadow-xl shadow-gray-100 overflow-hidden border border-gray-50 transition-all duration-300 hover:shadow-2xl">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/80 backdrop-blur-sm">
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Data & Oră</th>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Serviciu</th>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Specialist</th>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Valoare</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($bookings as $booking)
                                <tr class="group hover:bg-indigo-50/30 transition-all">
                                    <td class="px-8 py-6">
                                        <p class="text-sm font-black text-gray-800">{{ $booking->date->format('d.m.Y') }}</p>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase">{{ $booking->start_time->format('H:i') }}</p>
                                    </td>
                                    <td class="px-8 py-6">
                                        <p class="text-sm font-black text-indigo-600 group-hover:scale-105 origin-left transition-transform">{{ $booking->service->name }}</p>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase transition-colors">{{ $booking->service->duration }} min</p>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-white border border-gray-100 rounded-full flex items-center justify-center text-[10px] text-indigo-500 font-black shadow-sm group-hover:border-indigo-200 group-hover:bg-indigo-50 transition-all">
                                                {{ substr($booking->employee->name, 0, 1) }}
                                            </div>
                                            <p class="text-sm font-bold text-gray-600 transition-colors">{{ $booking->employee->name }}</p>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        @if($booking->status === 'cancelled')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[9px] font-black bg-red-100 text-red-600 uppercase tracking-widest">Anulată</span>
                                        @elseif($booking->date->isPast())
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[9px] font-black bg-gray-100 text-gray-600 uppercase tracking-widest">Finalizată</span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[9px] font-black bg-green-100 text-green-600 uppercase tracking-widest">Viitoare</span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <p class="text-sm font-black text-gray-900 group-hover:text-indigo-600 transition-colors">{{ number_format($booking->service->price, 0) }} RON</p>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-24 text-center">
                                        <div class="max-w-xs mx-auto space-y-6">
                                            <div class="w-20 h-20 bg-gray-50 rounded-[2rem] flex items-center justify-center text-gray-200 mx-auto">
                                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            </div>
                                            <div>
                                                <p class="text-gray-900 font-black uppercase text-xs tracking-widest mb-2">Pustiulici...</p>
                                                <p class="text-gray-400 font-medium italic text-sm">Nu am găsit încă nicio programare în istoricul tău.</p>
                                            </div>
                                            <a href="{{ route('bookings.index') }}" class="inline-block px-8 py-3 bg-indigo-600 text-white rounded-xl font-black text-[10px] uppercase tracking-[0.2em] shadow-lg shadow-indigo-100">Creează Prima Rezervare</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-8 px-4">
                    {{ $bookings->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
