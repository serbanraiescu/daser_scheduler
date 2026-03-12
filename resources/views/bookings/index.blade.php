<x-guest-layout>
    <div class="max-w-2xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-extrabold text-gray-900 text-center mb-8">Book an Appointment</h1>
        
        <div class="space-y-4">
            <h2 class="text-xl font-semibold text-gray-700">Select a Service</h2>
            @foreach($services as $service)
                <a href="{{ route('bookings.employee', ['service_id' => $service->id]) }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50 transition">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">{{ $service->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $service->duration_minutes }} minutes</p>
                        </div>
                        <div class="text-xl font-bold text-indigo-600">${{ number_format($service->price, 2) }}</div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</x-guest-layout>
