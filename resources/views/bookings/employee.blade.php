<x-guest-layout>
    <div class="max-w-2xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="mb-4">
            <a href="{{ route('bookings.index') }}" class="text-indigo-600 hover:text-indigo-900">&larr; Back to Services</a>
        </div>
        
        <h1 class="text-3xl font-extrabold text-gray-900 text-center mb-8">Select Professional</h1>
        
        <div class="space-y-4">
            <h2 class="text-xl font-semibold text-gray-700">Choose an Employee</h2>
            
            <a href="{{ route('bookings.slots', ['service_id' => $service->id, 'employee_id' => 0]) }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50 transition border-l-4 border-l-indigo-500">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">First Available</h3>
                        <p class="text-sm text-gray-500">Pick any professional that is free at your preferred time</p>
                    </div>
                </div>
            </a>

            @foreach($employees as $employee)
                <a href="{{ route('bookings.slots', ['service_id' => $service->id, 'employee_id' => $employee->id]) }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50 transition">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">{{ $employee->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $employee->email }}</p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</x-guest-layout>
