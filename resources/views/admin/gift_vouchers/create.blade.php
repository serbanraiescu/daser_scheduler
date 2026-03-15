<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Adaugă Card Cadou') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.gift-vouchers.store') }}" method="POST" x-data="{ type: 'value' }">
                        @csrf
                        
                        <div class="grid grid-cols-1 gap-6">
                            <!-- Buyer Info -->
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                                <h3 class="text-sm font-bold text-gray-700 uppercase mb-4">Informații Cumpărător / Destinatar</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="client_id" :value="__('Client existent (Opțional)')" />
                                        <select id="client_id" name="client_id" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                            <option value="">-- Selectează Client --</option>
                                            @foreach($clients as $client)
                                                <option value="{{ $client->id }}">{{ $client->name }} ({{ $client->phone }})</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <x-input-label for="buyer_name" :value="__('Nume Cumpărător (Dacă nu e client)')" />
                                        <x-text-input id="buyer_name" class="block mt-1 w-full" type="text" name="buyer_name" :value="old('buyer_name')" />
                                    </div>

                                    <div>
                                        <x-input-label for="buyer_email" :value="__('Email Cumpărător')" />
                                        <x-text-input id="buyer_email" class="block mt-1 w-full" type="email" name="buyer_email" :value="old('buyer_email')" />
                                    </div>

                                    <div>
                                        <x-input-label for="buyer_phone" :value="__('Telefon Cumpărător')" />
                                        <x-text-input id="buyer_phone" class="block mt-1 w-full" type="text" name="buyer_phone" :value="old('buyer_phone')" />
                                    </div>
                                </div>
                            </div>

                            <!-- Voucher Details -->
                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                                <h3 class="text-sm font-bold text-blue-800 uppercase mb-4">Detalii Card Cadou</h3>
                                
                                <div class="mb-4">
                                    <x-input-label :value="__('Tip Card Cadou')" />
                                    <div class="flex mt-2 space-x-4">
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="type" value="value" x-model="type" class="text-blue-600 focus:ring-blue-500">
                                            <span class="ml-2">Valoare Bronz (RON)</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="type" value="package" x-model="type" class="text-blue-600 focus:ring-blue-500">
                                            <span class="ml-2">Pachet Servicii (Ședințe)</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Value based -->
                                    <div x-show="type === 'value'">
                                        <x-input-label for="value_amount" :value="__('Suma (RON)')" />
                                        <x-text-input id="value_amount" class="block mt-1 w-full" type="number" step="0.01" name="value_amount" :value="old('value_amount')" />
                                    </div>

                                    <!-- Package based -->
                                    <div x-show="type === 'package'" class="col-span-1">
                                        <x-input-label for="service_id" :value="__('Serviciu')" />
                                        <select id="service_id" name="service_id" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                            @foreach($services as $service)
                                                <option value="{{ $service->id }}">{{ $service->name }} ({{ $service->price }} RON)</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div x-show="type === 'package'">
                                        <x-input-label for="remaining_uses" :value="__('Număr Ședințe')" />
                                        <x-text-input id="remaining_uses" class="block mt-1 w-full" type="number" name="remaining_uses" :value="old('remaining_uses', 3)" />
                                    </div>

                                    <div>
                                        <x-input-label for="expires_at" :value="__('Valabil până la (Opțional)')" />
                                        <x-text-input id="expires_at" class="block mt-1 w-full" type="date" name="expires_at" :value="old('expires_at')" />
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <x-primary-button>
                                    {{ __('Creează Card Cadou') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
