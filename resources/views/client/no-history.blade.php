<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-3xl text-gray-900 leading-tight">
            {{ __('Informații Cont') }}
        </h2>
    </x-slot>

    <div class="py-24">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col items-center justify-center text-center">
            <div class="bg-white rounded-[3rem] p-16 shadow-2xl shadow-gray-200 border-2 border-indigo-50 max-w-2xl mx-auto space-y-10 relative overflow-hidden group">
                <!-- Decorative element -->
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-indigo-50 rounded-full blur-3xl group-hover:bg-indigo-100 transition-colors"></div>
                
                <div class="relative w-32 h-32 bg-indigo-600 rounded-[2rem] flex items-center justify-center text-white mx-auto shadow-xl shadow-indigo-100 rotate-3 group-hover:rotate-0 transition-transform duration-500">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                
                <div class="relative space-y-4">
                    <h3 class="text-3xl font-black text-gray-900 leading-tight">Momentan nu avem date<br/>în istoricul nostru</h3>
                    <p class="text-gray-500 font-bold text-lg leading-relaxed px-6">Contul dumneavoastră nu este încă asociat cu o fișă de client. Acest lucru se întâmplă dacă v-ați programat anterior folosind alt număr de telefon sau altă adresă de email.</p>
                </div>

                <div class="pt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('bookings.index') }}" class="px-12 py-5 bg-indigo-600 text-white rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-indigo-700 transition shadow-xl shadow-indigo-100 active:scale-95">
                        Efectuează Prima Programare
                    </a>
                    <a href="tel:0745000000" class="px-8 py-5 text-gray-400 font-black text-sm uppercase tracking-widest hover:text-indigo-600 transition">
                        Contactați-ne
                    </a>
                </div>
            </div>
            
            <p class="mt-12 text-gray-400 font-bold italic text-sm">Vă rugăm să folosiți aceleași date de contact la următoarea programare pentru sincronizare automată.</p>
        </div>
    </div>
</x-app-layout>
