<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Adaugă Programare Manuală') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-3xl border">
                <div class="p-8">
                    <form action="{{ route('employee.bookings.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-data="{ ...clientSearch(), timeValue: '{{ old('time', $time) }}' }">

                            


                            <!-- Fast Search Bar -->
                            <div class="md:col-span-2 relative mb-2">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Caută Client Existent (Opțional)</label>
                                <div class="relative">
                                    <input type="text" x-model="searchQuery" @input.debounce.300ms="search()" placeholder="Caută rapid după nume sau telefon..."
                                        class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 pl-11 bg-gray-50 text-gray-900 font-medium">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-indigo-500">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                    </div>
                                    <button type="button" x-show="searchQuery.length > 0" @click="searchQuery = ''; results = []; queryTyped = false" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                                
                                <!-- Search Results Dropdown -->
                                <div x-show="results.length > 0 && searchQuery.length >= 2" @click.away="results = []" class="absolute z-10 w-full mt-2 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden" style="display: none;">
                                    <template x-for="client in results" :key="client.phone">
                                        <div @click="selectClient(client)" class="p-3.5 hover:bg-indigo-50 cursor-pointer border-b border-gray-50 last:border-0 flex justify-between items-center transition group">
                                            <div class="font-bold text-gray-900 group-hover:text-indigo-700" x-text="client.name"></div>
                                            <div class="text-sm text-gray-500 flex items-center group-hover:text-indigo-600">
                                                <svg class="w-4 h-4 mr-1.5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                                <span x-text="client.phone"></span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                                <div x-show="isSearching" class="absolute z-10 w-full mt-2 bg-white rounded-xl shadow-lg border border-gray-100 p-4 flex justify-center text-sm text-indigo-500 font-medium" style="display: none;">
                                    <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    Se caută...
                                </div>
                                <div x-show="!isSearching && queryTyped && results.length === 0 && searchQuery.length >= 2" class="absolute z-10 w-full mt-2 bg-white rounded-xl shadow-lg border border-gray-100 p-4 text-center text-sm font-medium text-gray-500" style="display: none;">
                                    Niciun client găsit. Poți introduce datele manual mai jos.
                                </div>
                            </div>

                            <!-- Service -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Serviciu</label>
                                <select name="service_id" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Selectează un serviciu...</option>
                                    @foreach($services as $service)
                                        <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                            {{ $service->name }} ({{ $service->price }} Lei, {{ $service->duration_minutes }} min)
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('service_id')" class="mt-2" />
                            </div>

                            <!-- Date -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Data</label>
                                <input type="date" name="date" value="{{ old('date', $date) }}" required min="{{ date('Y-m-d') }}"
                                    class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <x-input-error :messages="$errors->get('date')" class="mt-2" />
                            </div>

                            <!-- Time Picker (Radial) -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-4 text-center">Selectează Ora</label>
                                <x-radial-time-picker name="ignore_time_radial" x-model="timeValue" :min-hour="$minHour" :max-hour="$maxHour" />
                                <input type="hidden" name="time" :value="timeValue">
                                <x-input-error :messages="$errors->get('time')" class="mt-2" />
                            </div>



                            <!-- Client Name -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nume Client</label>
                                <input type="text" name="client_name" x-model="clientName" required placeholder="Ex: Popescu Ion"
                                    class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                    :class="{'bg-indigo-50 border-indigo-200': highlighted}">
                                <x-input-error :messages="$errors->get('client_name')" class="mt-2" />
                            </div>

                            <!-- Client Phone -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Telefon Client</label>
                                <input type="text" name="client_phone" x-model="clientPhone" required placeholder="Ex: 0722123456"
                                    class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                    :class="{'bg-indigo-50 border-indigo-200': highlighted}">
                                <x-input-error :messages="$errors->get('client_phone')" class="mt-2" />
                            </div>

                            <!-- Client Email -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Email Client (Opțional)</label>
                                <input type="email" name="client_email" x-model="clientEmail" placeholder="Ex: client@email.com"
                                    class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                    :class="{'bg-indigo-50 border-indigo-200': highlighted}">
                                <x-input-error :messages="$errors->get('client_email')" class="mt-2" />
                            </div>
                        </div>

                        <div class="pt-6 border-t flex justify-between items-center">
                            <a href="{{ route('employee.dashboard') }}" class="text-sm font-bold text-gray-500 hover:text-gray-700">
                                Anulează
                            </a>
                            <x-primary-button>
                                Salvează Programarea
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="mt-6 p-4 bg-indigo-50 rounded-2xl border border-indigo-100 flex gap-4">
                <div class="text-indigo-600">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="16" y2="12"/><line x1="12" x2="12.01" y1="8" y2="8"/></svg>
                </div>
                <div class="text-sm text-indigo-700">
                    Programările adăugate manual sunt <strong>confirmate automat</strong> și vor apărea în listă imediat. Sistemul verifică automat dacă există suprapuneri.
                </div>
            </div>
        </div>
    </div>

    <script>
        function clientSearch() {
            return {
                searchQuery: '',
                clientName: '{{ old("client_name") }}',
                clientPhone: '{{ old("client_phone") }}',
                clientEmail: '{{ old("client_email") }}',
                results: [],
                isSearching: false,
                queryTyped: false,
                highlighted: false,
                search() {
                    this.queryTyped = true;
                    if (this.searchQuery.length < 2) {
                        this.results = [];
                        return;
                    }
                    this.isSearching = true;
                    fetch(`/employee/api/clients/search?q=${encodeURIComponent(this.searchQuery)}`)
                        .then(res => res.json())
                        .then(data => {
                            this.results = data;
                            this.isSearching = false;
                        })
                        .catch(() => {
                            this.results = [];
                            this.isSearching = false;
                        });
                },
                selectClient(client) {
                    this.clientName = client.name;
                    this.clientPhone = client.phone;
                    this.clientEmail = client.email || '';
                    this.searchQuery = '';
                    this.results = [];
                    this.queryTyped = false;
                    
                    // Flash effect
                    this.highlighted = true;
                    setTimeout(() => { this.highlighted = false; }, 1000);
                }
            }
        }
    </script>
</x-app-layout>
