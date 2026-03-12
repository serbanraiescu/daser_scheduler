<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Employee') }}: {{ $employee->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.employees.update', $employee) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <x-input-label for="name" :value="__('Name')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $employee->name)" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $employee->email)" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="phone" :value="__('Phone')" />
                                <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone', $employee->phone)" />
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>

                            <div class="bg-gray-50 p-4 rounded-md">
                                <p class="text-sm text-gray-600 mb-2">Leave password blank to keep current password.</p>
                                <div>
                                    <x-input-label for="password" :value="__('New Password')" />
                                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" />
                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                </div>

                                <div class="mt-4">
                                    <x-input-label for="password_confirmation" :value="__('Confirm New Password')" />
                                    <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" />
                                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                </div>
                            </div>

                            <div>
                                <label class="inline-flex items-center">
                                    <input type="hidden" name="active" value="0">
                                    <input type="checkbox" name="active" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ ($employee->employee && $employee->employee->active) ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-600">{{ __('Active') }}</span>
                                </label>
                            </div>

                            <div>
                                <x-input-label :value="__('Assigned Services')" />
                                <div class="mt-2 grid grid-cols-2 md:grid-cols-4 gap-4">
                                    @foreach($services as $service)
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="services[]" value="{{ $service->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ in_array($service->id, $employeeServices) ? 'checked' : '' }}>
                                            <span class="ml-2 text-sm text-gray-600">{{ $service->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <x-input-error :messages="$errors->get('services')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <x-primary-button>
                                    {{ __('Update Employee') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
