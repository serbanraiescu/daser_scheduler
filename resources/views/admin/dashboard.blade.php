<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500 uppercase">Bookings Today</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">{{ $bookingsToday }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500 uppercase">Revenue Today</div>
                    <div class="mt-2 text-3xl font-bold text-green-600">${{ number_format($revenueToday, 2) }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500 uppercase">Active Employees</div>
                    <div class="mt-2 text-3xl font-bold text-blue-600">{{ $employees->count() }}</div>
                </div>
            </div>

            <!-- Calendar -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Calendar</h3>
                    <div id="calendar" class="min-h-[600px]"></div>
                </div>
            </div>

            <!-- Upcoming Bookings -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Upcoming Appointments</h3>
                    <div class="space-y-4">
                        @forelse($upcomingBookings as $booking)
                            <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                                <div>
                                    <div class="font-bold">{{ $booking->client->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $booking->service->name }} with {{ $booking->employee->name }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="font-medium">{{ $booking->start_time->format('H:i') }}</div>
                                    <div class="text-xs text-gray-400">{{ $booking->start_time->format('M d, Y') }}</div>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 italic">No upcoming appointments.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridDay',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'timeGridWeek,timeGridDay'
                },
                editable: true,
                selectable: true,
                events: '/admin/api/bookings',
                eventDrop: function(info) {
                    let start = info.event.start.toISOString();
                    let end = info.event.end.toISOString();
                    
                    fetch(`/admin/api/bookings/${info.event.id}`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            start: start,
                            end: end
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            alert(data.error);
                            info.revert();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        info.revert();
                    });
                },
                select: function(info) {
                    // Logic for manual booking
                }
            });
            calendar.render();
        });
    </script>
</x-app-layout>
