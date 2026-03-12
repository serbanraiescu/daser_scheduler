<x-guest-layout>
    <div class="max-w-2xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="mb-4">
            <a href="{{ route('bookings.employee', ['service_id' => $service->id]) }}" class="text-indigo-600 hover:text-indigo-900">&larr; Back to Professionals</a>
        </div>
        
        <h1 class="text-3xl font-extrabold text-gray-900 text-center mb-8">Select Date & Time</h1>
        
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Date</label>
                <input type="date" value="{{ $date }}" onchange="window.location.href = '{{ route('bookings.slots') }}?service_id={{ $service->id }}&employee_id={{ $employeeId }}&date=' + this.value" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <h2 class="text-lg font-semibold text-gray-700 mb-4">Available Slots for {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</h2>
            
            <div class="space-y-6">
                @forelse($availableSlots as $group)
                    <div>
                        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-2">{{ $group['employee']->name }}</h3>
                        <div class="grid grid-cols-3 sm:grid-cols-4 gap-2">
                            @foreach($group['slots'] as $slot)
                                <a href="{{ route('bookings.details', ['service_id' => $service->id, 'employee_id' => $group['employee']->id, 'date' => $date, 'time' => $slot]) }}" class="py-2 px-3 text-center border border-indigo-200 rounded-md text-indigo-700 hover:bg-indigo-600 hover:text-white transition">
                                    {{ $slot }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 italic text-center py-8">No available slots for this date.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-guest-layout>
