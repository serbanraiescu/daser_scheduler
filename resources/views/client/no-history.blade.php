<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-gray-900 leading-tight">
            {{ __('Informații Cont') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-[2rem] p-12 text-center border-2 border-indigo-50 shadow-sm max-w-2xl mx-auto space-y-8">
                <div class="w-24 h-24 bg-indigo-50 rounded-full flex items-center justify-center text-indigo-600 mx-auto shadow-inner">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                
                <div class="space-y-4">
                    <h3 class="text-2xl font-black text-gray-900">Istoric Indisponibil</h3>
                    <p class="text-gray-500 font-medium">Contul dumneavoastră nu este încă asociat cu o fișă de client în studioul nostru. Acest lucru se întâmplă de obicei dacă v-ați programat anterior folosind un alt număr de telefon sau o altă adresă de email.</p>
                    <p class="text-indigo-600 font-bold italic pt-4">Vă rugăm să ne contactați sau să efectuați o primă programare folosind noile date!</p>
                </div>

                <div class="pt-8">
                    <a href="{{ route('bookings.index') }}" class="inline-flex items-center px-8 py-4 bg-indigo-600 text-white rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-indigo-700 transition shadow-xl shadow-indigo-100">
                        Efectuează o Programare
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
