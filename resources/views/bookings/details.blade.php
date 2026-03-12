<x-guest-layout>
    <div class="max-w-2xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="mb-4">
            <a href="{{ url()->previous() }}" class="text-indigo-600 hover:text-indigo-900">&larr; Back to Slots</a>
        </div>
        
        <h1 class="text-3xl font-extrabold text-gray-900 text-center mb-8">Confirm Details</h1>
        
        <div class="bg-white p-8 rounded-lg shadow-md">
            <div class="mb-8 p-4 bg-indigo-50 rounded-lg border border-indigo-100">
                <h3 class="font-bold text-indigo-900">{{ $service->name }}</h3>
                <p class="text-sm text-indigo-700">With {{ $employee->name }}</p>
                <p class="text-sm font-medium mt-2 text-indigo-900">{{ \Carbon\Carbon::parse($date)->format('M d, Y') }} at {{ $time }}</p>
                <p class="text-lg font-bold mt-2 text-indigo-900">${{ number_format($service->price, 2) }}</p>
            </div>

            <form action="{{ route('bookings.confirm') }}" method="POST">
                @csrf
                <input type="hidden" name="service_id" value="{{ $service->id }}">
                <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                <input type="hidden" name="date" value="{{ $date }}">
                <input type="hidden" name="time" value="{{ $time }}">

                <div class="space-y-4">
                    <div>
                        <x-input-label for="name" :value="__('Your Name')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="phone" :value="__('Phone Number')" />
                        <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" required />
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="email" :value="__('Email (optional)')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="voucher_code" :value="__('Voucher Code (Optional)')" />
                        <x-text-input id="voucher_code" class="block mt-1 w-full" type="text" name="voucher_code" />
                        <x-input-error :messages="$errors->get('voucher_code')" class="mt-2" />
                    </div>

                    <div class="pt-4">
                        <x-primary-button class="w-full justify-center py-3 text-lg">
                            {{ __('Book Appointment') }}
                        </x-primary-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
