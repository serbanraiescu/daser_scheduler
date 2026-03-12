<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Voucher') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.vouchers.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <x-input-label for="code" :value="__('Voucher Code')" />
                                <x-text-input id="code" class="block mt-1 w-full" type="text" name="code" :value="old('code')" required autofocus />
                                <x-input-error :messages="$errors->get('code')" class="mt-2" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="type" :value="__('Discount Type')" />
                                    <select id="type" name="type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="percentage">Percentage (%)</option>
                                        <option value="fixed">Fixed Amount ($)</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('type')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="value" :value="__('Discount Value')" />
                                    <x-text-input id="value" class="block mt-1 w-full" type="number" step="0.01" name="value" :value="old('value')" required />
                                    <x-input-error :messages="$errors->get('value')" class="mt-2" />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="start_date" :value="__('Starts At (Optional)')" />
                                    <x-text-input id="start_date" class="block mt-1 w-full" type="date" name="start_date" :value="old('start_date')" />
                                    <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="end_date" :value="__('Expires At (Optional)')" />
                                    <x-text-input id="end_date" class="block mt-1 w-full" type="date" name="end_date" :value="old('end_date')" />
                                    <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                                </div>
                            </div>

                            <div>
                                <x-input-label for="max_uses" :value="__('Usage Limit (Optional)')" />
                                <x-text-input id="max_uses" class="block mt-1 w-full" type="number" name="max_uses" :value="old('max_uses')" placeholder="e.g. 100" />
                                <x-input-error :messages="$errors->get('max_uses')" class="mt-2" />
                            </div>

                            <div>
                                <label class="inline-flex items-center">
                                    <input type="hidden" name="active" value="0">
                                    <input type="checkbox" name="active" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" checked>
                                    <span class="ml-2 text-sm text-gray-600">{{ __('Active') }}</span>
                                </label>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <x-primary-button>
                                    {{ __('Save Voucher') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
