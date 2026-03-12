<x-guest-layout>
    <div class="max-w-2xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                {{ session('success') }}
            </div>
        @endif

        <h1 class="text-3xl font-extrabold text-gray-900 text-center mb-8">Appointment Details</h1>
        
        <div class="bg-white p-8 rounded-lg shadow-md text-center">
            <div class="mb-6 inline-flex items-center justify-center p-4 bg-green-100 rounded-full">
                <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Booking {{ ucfirst($booking->status) }}</h2>
            <p class="text-gray-500 mb-8">Keep this link to manage your appointment.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-left border-t border-b py-6 mb-8">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase">Service</h3>
                    <p class="text-lg font-bold">{{ $booking->service->name }}</p>
                    <p class="text-sm text-gray-500">{{ $booking->service->duration_minutes }} min</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase">Professional</h3>
                    <p class="text-lg font-bold">{{ $booking->employee->name }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase">Date & Time</h3>
                    <p class="text-lg font-bold">{{ $booking->date->format('M d, Y') }}</p>
                    <p class="text-lg font-bold">{{ $booking->start_time->format('H:i') }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase">Client</h3>
                    <p class="text-lg font-bold">{{ $booking->client->name }}</p>
                    <p class="text-sm text-gray-500">{{ $booking->client->phone }}</p>
                </div>
            </div>

            <div class="flex flex-col space-y-4">
                @if($booking->status !== 'cancelled' && $booking->start_time->gt(now()))
                    <a href="{{ route('bookings.ics', $booking->manage_token) }}" class="w-full py-3 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 transition flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Add to Calendar (ICS)
                    </a>

                    <form action="{{ route('bookings.cancel', $booking->manage_token) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel?')">
                        @csrf
                        <button type="submit" class="w-full py-3 bg-red-50 text-red-600 font-bold rounded-lg hover:bg-red-100 transition">
                            Cancel Appointment
                        </button>
                    </form>
                @endif
                
                <a href="{{ route('bookings.index') }}" class="text-indigo-600 font-medium hover:text-indigo-800">
                    Book another appointment
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
