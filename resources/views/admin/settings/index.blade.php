<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- CRITICAL: Database Migration Tool at the top for emergency access -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-2 border-red-200">
                <div class="p-6 bg-red-50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h4 class="font-bold text-red-900 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            Actualizare Bază de Date (Eroare 500 Fix)
                        </h4>
                        <p class="text-sm text-red-700 mt-1">Dacă ai primit erori sau pagina nu se încărca complet, rulează acest utilitar pentru a crea tabelele noi.</p>
                    </div>
                    <form action="{{ route('admin.settings.migrate') }}" method="POST" onsubmit="return confirm('Ești sigur că vrei să actualizezi baza de date?')">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-red-600 border border-transparent rounded-lg font-bold text-sm text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 transition">
                            Actualizează Acum
                        </button>
                    </form>
                </div>
            </div>

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

                            <h3 class="text-lg font-bold border-b pb-2 mt-6">Vouchere Aniversare</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <x-input-label for="birthday_voucher_enabled" :value="__('Activat')" />
                                    <select name="birthday_voucher_enabled" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="1" {{ ($settings['birthday_voucher_enabled'] ?? '0') == '1' ? 'selected' : '' }}>DA</option>
                                        <option value="0" {{ ($settings['birthday_voucher_enabled'] ?? '0') == '0' ? 'selected' : '' }}>NU</option>
                                    </select>
                                </div>
                                <div>
                                    <x-input-label for="birthday_voucher_percent" :value="__('Reducere (%)')" />
                                    <x-text-input id="birthday_voucher_percent" class="block mt-1 w-full" type="number" name="birthday_voucher_percent" :value="$settings['birthday_voucher_percent'] ?? '20'" />
                                </div>
                                <div>
                                    <x-input-label for="birthday_voucher_valid_days" :value="__('Valabilitate (Zile)')" />
                                    <x-text-input id="birthday_voucher_valid_days" class="block mt-1 w-full" type="number" name="birthday_voucher_valid_days" :value="$settings['birthday_voucher_valid_days'] ?? '14'" />
                                </div>
                            </div>

                            <h3 class="text-lg font-bold border-b pb-2 mt-6">Reactivare Clienți Inactivi</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="reactivation_enabled" :value="__('Activat')" />
                                    <select name="reactivation_enabled" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="1" {{ ($settings['reactivation_enabled'] ?? '0') == '1' ? 'selected' : '' }}>DA</option>
                                        <option value="0" {{ ($settings['reactivation_enabled'] ?? '0') == '0' ? 'selected' : '' }}>NU</option>
                                    </select>
                                </div>
                                <div>
                                    <x-input-label for="reactivation_days_inactive" :value="__('Zile de inactivitate')" />
                                    <x-text-input id="reactivation_days_inactive" class="block mt-1 w-full" type="number" name="reactivation_days_inactive" :value="$settings['reactivation_days_inactive'] ?? '60'" />
                                </div>
                                <div>
                                    <x-input-label for="reactivation_discount_percent" :value="__('Reducere (%)')" />
                                    <x-text-input id="reactivation_discount_percent" class="block mt-1 w-full" type="number" name="reactivation_discount_percent" :value="$settings['reactivation_discount_percent'] ?? '15'" />
                                </div>
                                <div>
                                    <x-input-label for="reactivation_voucher_valid_days" :value="__('Valabilitate Voucher (Zile)')" />
                                    <x-text-input id="reactivation_voucher_valid_days" class="block mt-1 w-full" type="number" name="reactivation_voucher_valid_days" :value="$settings['reactivation_voucher_valid_days'] ?? '10'" />
                                </div>
                            </div>

                            <h3 class="text-lg font-bold border-b pb-2 mt-6">Setări Email (SMTP)</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="mail_host" :value="__('SMTP Host')" />
                                    <x-text-input id="mail_host" class="block mt-1 w-full" type="text" name="mail_host" :value="$settings['mail_host'] ?? ''" placeholder="mail.exemplu.ro" />
                                </div>
                                <div>
                                    <x-input-label for="mail_port" :value="__('SMTP Port')" />
                                    <x-text-input id="mail_port" class="block mt-1 w-full" type="text" name="mail_port" :value="$settings['mail_port'] ?? '465'" />
                                </div>
                                <div>
                                    <x-input-label for="mail_username" :value="__('Email Username')" />
                                    <x-text-input id="mail_username" class="block mt-1 w-full" type="text" name="mail_username" :value="$settings['mail_username'] ?? ''" />
                                </div>
                                <div>
                                    <x-input-label for="mail_password" :value="__('Email Password')" />
                                    <x-text-input id="mail_password" class="block mt-1 w-full" type="password" name="mail_password" :value="$settings['mail_password'] ?? ''" />
                                </div>
                                <div>
                                    <x-input-label for="mail_encryption" :value="__('Encryption')" />
                                    <select name="mail_encryption" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="ssl" {{ ($settings['mail_encryption'] ?? 'ssl') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                        <option value="tls" {{ ($settings['mail_encryption'] ?? 'ssl') == 'tls' ? 'selected' : '' }}>TLS</option>
                                        <option value="none" {{ ($settings['mail_encryption'] ?? 'ssl') == 'none' ? 'selected' : '' }}>None</option>
                                    </select>
                                </div>
                                <div>
                                    <x-input-label for="mail_from_address" :value="__('Email From Address')" />
                                    <x-text-input id="mail_from_address" class="block mt-1 w-full" type="email" name="mail_from_address" :value="$settings['mail_from_address'] ?? ''" />
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
                                            $statusColor = ($licenseData->status ?? 'unknown') === 'active' ? 'text-green-600' : 'text-red-600';
                                        @endphp
                                        <span class="text-xl font-bold {{ $statusColor }}">{{ ucfirst($licenseData->status ?? 'Unknown') }}</span>
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
                                                   <div class="mt-12 pt-8 border-t text-center text-xs text-gray-400">
                                Versiune Sistem: 2.1.0-retention | Build: 2026.03.15
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</x-app-layout>
