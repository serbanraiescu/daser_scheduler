<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="grid grid-cols-1 gap-6">
                            <h3 class="text-lg font-bold border-b pb-2">Business Information</h3>
                            
                            <div>
                                <x-input-label for="business_name" :value="__('Business Name')" />
                                <x-text-input id="business_name" class="block mt-1 w-full" type="text" name="business_name" :value="$settings['business_name'] ?? ''" />
                                <x-input-error :messages="$errors->get('business_name')" class="mt-2" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="business_email" :value="__('Business Email')" />
                                    <x-text-input id="business_email" class="block mt-1 w-full" type="email" name="business_email" :value="$settings['business_email'] ?? ''" />
                                    <x-input-error :messages="$errors->get('business_email')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="business_phone" :value="__('Business Phone')" />
                                    <x-text-input id="business_phone" class="block mt-1 w-full" type="text" name="business_phone" :value="$settings['business_phone'] ?? ''" />
                                    <x-input-error :messages="$errors->get('business_phone')" class="mt-2" />
                                </div>
                            </div>

                            <h3 class="text-lg font-bold border-b pb-2 mt-6">Booking Rules</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="booking_window_start" :value="__('Minimum hours before booking (Start Window)')" />
                                    <x-text-input id="booking_window_start" class="block mt-1 w-full" type="number" name="booking_window_start" :value="$settings['booking_window_start'] ?? '2'" />
                                    <p class="text-xs text-gray-500 mt-1">Clients can't book earlier than this many hours before the appointment.</p>
                                </div>
                                <div>
                                    <x-input-label for="booking_window_end" :value="__('Maximum days in advance (End Window)')" />
                                    <x-text-input id="booking_window_end" class="block mt-1 w-full" type="number" name="booking_window_end" :value="$settings['booking_window_end'] ?? '30'" />
                                    <p class="text-xs text-gray-500 mt-1">Clients can book up to this many days in advance.</p>
                                </div>
                            </div>
                            <h3 class="text-lg font-bold border-b pb-2 mt-6">Sistem de Fidelitate</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <x-input-label for="fidelity_points_required" :value="__('Puncte necesare pentru o programare gratuită')" />
                                    <x-text-input id="fidelity_points_required" class="block mt-1 w-full" type="number" name="fidelity_points_required" :value="$settings['fidelity_points_required'] ?? '7'" />
                                    <p class="text-xs text-gray-500 mt-1">Când un client acumulează acest număr de puncte, va primi automat un voucher de 100% reducere.</p>
                                </div>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <x-primary-button>
                                    {{ __('Save Settings') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>

                        <div class="mt-12 pt-8 border-t">
                            <h3 class="text-lg font-bold border-b pb-2 mb-6">Licențiere</h3>
                            <form action="{{ route('admin.settings.update') }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-end">
                                    <div>
                                        <x-input-label for="license_key" :value="__('License Key')" />
                                        <x-text-input id="license_key" class="block mt-1 w-full" type="text" name="license_key" :value="$settings['license_key'] ?? ''" />
                                    </div>
                                    <div>
                                        <x-input-label for="license_kill_token" :value="__('Kill Token (Remote Revocation)')" />
                                        <x-text-input id="license_kill_token" class="block mt-1 w-full" type="password" name="license_kill_token" :value="$settings['license_kill_token'] ?? ''" />
                                    </div>
                                </div>
                                <div class="mt-4 flex justify-end">
                                    <x-primary-button>
                                        {{ __('Update Key') }}
                                    </x-primary-button>
                                </div>
                            </form>

                            <div class="mt-6 p-4 bg-gray-50 rounded-lg border flex justify-between items-center">
                                <div>
                                    <div class="text-sm font-medium text-gray-500 uppercase">Status Licență</div>
                                    <div class="mt-1 flex items-center">
                                        @php
                                            $licenseData = app(\App\Services\LicenseService::class)->getStatus();
                                            $statusColor = $licenseData->status === 'active' ? 'text-green-600' : 'text-red-600';
                                        @endphp
                                        <span class="text-xl font-bold {{ $statusColor }}">{{ ucfirst($licenseData->status) }}</span>
                                        @if($licenseData->days_left)
                                            <span class="ml-2 text-sm text-gray-500">({{ $licenseData->days_left }} zile rămase)</span>
                                        @endif
                                    </div>
                                    <div class="mt-1 text-xs text-gray-400 font-mono">
                                        Ultima verificare: {{ $licenseData->last_check ?: 'Niciodată' }}
                                    </div>
                                </div>
                                <form action="{{ route('admin.license.reverify') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-50 border border-indigo-200 rounded-md font-semibold text-xs text-indigo-700 uppercase tracking-widest hover:bg-indigo-100 transition ease-in-out duration-150">
                                        Re-verifică Licența
                                    </button>
                                </form>
                            </div>

                            <div class="mt-12 pt-8 border-t">
                                <h3 class="text-lg font-bold border-b pb-2 mb-6 text-red-600">Sistem & Mentenanță</h3>
                                <div class="p-6 bg-red-50 rounded-xl border border-red-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                    <div>
                                        <h4 class="font-bold text-red-900">Actualizare Bază de Date</h4>
                                        <p class="text-sm text-red-700 mt-1">Dacă ai făcut update la aplicație și lipsesc tabele sau coloane, rulează acest utilitar.</p>
                                    </div>
                                    <form action="{{ route('admin.settings.migrate') }}" method="POST" onsubmit="return confirm('Ești sigur că vrei să actualizezi baza de date?')">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-red-600 border border-transparent rounded-lg font-bold text-sm text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                            Actualizează Tabelele
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</x-app-layout>
