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

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                                <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required min="{{ date('Y-m-d') }}"
                                    class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <x-input-error :messages="$errors->get('date')" class="mt-2" />
                            </div>

                            <!-- Time -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Ora</label>
                                <input type="time" name="time" value="{{ old('time') }}" required
                                    class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <x-input-error :messages="$errors->get('time')" class="mt-2" />
                            </div>

                            <!-- Client Name -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nume Client</label>
                                <input type="text" name="client_name" value="{{ old('client_name') }}" required placeholder="Ex: Popescu Ion"
                                    class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <x-input-error :messages="$errors->get('client_name')" class="mt-2" />
                            </div>

                            <!-- Client Phone -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Telefon Client</label>
                                <input type="text" name="client_phone" value="{{ old('client_phone') }}" required placeholder="Ex: 0722123456"
                                    class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <x-input-error :messages="$errors->get('client_phone')" class="mt-2" />
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
</x-app-layout>
