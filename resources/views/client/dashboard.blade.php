<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-gray-900 leading-tight">
            {{ __('Bună,') }} {{ auth()->user()->name }} 👋
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Fidelity Card Section -->
            <div class="relative overflow-hidden bg-indigo-600 rounded-[2rem] p-8 text-white shadow-2xl shadow-indigo-200">
                <div class="absolute top-0 right-0 -m-8 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 -m-8 w-48 h-48 bg-white/10 rounded-full blur-3xl"></div>
                
                <div class="relative flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    <div class="space-y-4">
                        <div class="inline-flex px-3 py-1 bg-white/20 rounded-full text-xs font-black uppercase tracking-widest backdrop-blur-md">
                            Card de Fidelitate
                        </div>
                        <div>
                            <p class="text-indigo-100 text-sm font-medium">Număr Card</p>
                            <h3 class="text-2xl font-mono tracking-[0.3em] font-black">
                                {{ $client->fidelity_card_number ?? '---- ---- ----' }}
                            </h3>
                        </div>
                        <div class="flex gap-8">
                            <div>
                                <p class="text-indigo-100 text-sm font-medium">Puncte</p>
                                <p class="text-3xl font-black">{{ $client->loyalty_points }}</p>
                            </div>
                            <div>
                                <p class="text-indigo-100 text-sm font-medium">Discount Special</p>
                                <p class="text-3xl font-black">{{ number_format($client->special_discount, 0) }}%</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="w-full md:w-auto bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl p-6 flex items-center gap-4">
                        <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-indigo-600 shadow-xl">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-indigo-100">Vouchere</p>
                            <p class="text-2xl font-black">{{ $voucherCount }} Active</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Upcoming Booking -->
                <div class="lg:col-span-1 space-y-4">
                    <h3 class="text-xl font-black text-gray-900 px-2">Următoarea Programare</h3>
                    @if($upcomingBooking)
                        <div class="bg-white rounded-3xl p-6 border-2 border-indigo-50 shadow-sm space-y-6">
                            <div class="flex justify-between items-start">
                                <div class="bg-indigo-50 text-indigo-600 px-4 py-2 rounded-2xl font-black text-center">
                                    <span class="block text-xs uppercase">{{ $upcomingBooking->date->translatedFormat('M') }}</span>
                                    <span class="text-2xl">{{ $upcomingBooking->date->format('d') }}</span>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-3 py-1 bg-green-50 text-green-600 rounded-full text-[10px] font-black uppercase tracking-widest">Confirmată</span>
                                    <p class="text-lg font-black text-gray-900 mt-1">{{ $upcomingBooking->start_time->format('H:i') }}</p>
                                </div>
                            </div>
                            
                            <div>
                                <h4 class="text-sm font-bold text-gray-500 uppercase tracking-widest">Serviciu</h4>
                                <p class="text-xl font-black text-gray-900">{{ $upcomingBooking->service->name }}</p>
                            </div>
                            
                            <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-2xl">
                                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-400 shadow-sm">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Specialist</p>
                                    <p class="text-sm font-bold text-gray-900">{{ $upcomingBooking->employee->name }}</p>
                                </div>
                            </div>

                            <a href="{{ route('bookings.show', $upcomingBooking->manage_token) }}" class="block w-full text-center py-4 bg-gray-900 text-white rounded-2xl font-bold hover:bg-black transition shadow-lg">
                                Detalii Rezervare
                            </a>
                        </div>
                    @else
                        <div class="bg-gray-50 rounded-3xl p-12 text-center border-2 border-dashed border-gray-200">
                            <p class="text-gray-400 font-medium">Momentan nu ai nicio programare viitoare.</p>
                            <a href="{{ route('bookings.index') }}" class="inline-block mt-4 text-indigo-600 font-black text-sm uppercase tracking-widest hover:text-indigo-700">Programază-te acum →</a>
                        </div>
                    @endif
                </div>

                <!-- Recent History -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex justify-between items-center px-2">
                        <h3 class="text-xl font-black text-gray-900">Activitate Recentă</h3>
                        <a href="{{ route('client.history') }}" class="text-xs font-bold text-indigo-600 uppercase tracking-widest hover:underline">Vezi tot istoricul</a>
                    </div>
                    
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/50">
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Data</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Serviciu</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Specialist</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Preț</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($recentHistory as $booking)
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-bold text-gray-900">{{ $booking->date->format('d.m.Y') }}</p>
                                            <p class="text-[10px] text-gray-400 font-medium">{{ $booking->start_time->format('H:i') }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-bold text-gray-900">{{ $booking->service->name }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-medium text-gray-600">{{ $booking->employee->name }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <p class="text-sm font-black text-gray-900">{{ number_format($booking->service->price, 0) }} RON</p>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-gray-400 font-medium italic">Încă nu ai nicio programare încheiată.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
