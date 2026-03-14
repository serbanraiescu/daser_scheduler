<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl p-6 lg:p-8">
                
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                    <div>
                        <h2 class="text-3xl font-black text-gray-900 leading-tight">Agenda Programări</h2>
                        <p class="text-sm font-medium text-gray-500 mt-2">Vizualizează orele ocupate și disponibilitatea ta pe zile și săptămâni.</p>
                    </div>
                    <a href="{{ route('employee.bookings.create') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg shadow-indigo-500/30">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Adaugă Programare
                    </a>
                </div>

                <div id="calendar" class="fc-theme-standard"></div>
            </div>
        </div>
    </div>

    <!-- FullCalendar Integration -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@6.1.11/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.11/locales/ro.global.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'ro',
                initialView: 'timeGridWeek',
                dayMinWidth: 120, // Forces horizontal scroll if width is too small (e.g., mobile)
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                slotMinTime: '07:00:00',
                slotMaxTime: '22:00:00',
                allDaySlot: true,
                nowIndicator: true,
                navLinks: true,
                events: '{{ route('employee.calendar.events') }}',
                eventClick: function(info) {
                    if (info.event.id && info.event.id.startsWith('booking_')) {
                        let phone = info.event.extendedProps.phone || 'Nu este specificat';
                        let timeStr = info.event.start.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) + ' - ' + info.event.end.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                        alert('Programare: ' + info.event.title + '\nInterval: ' + timeStr + '\nTelefon: ' + phone);
                    }
                },
                dateClick: function(info) {
                    // Pre-fill date when adding booking functionality could be here
                    // e.g., window.location.href = "/employee/bookings/create?date=" + info.dateStr.split('T')[0];
                },
                height: 'auto',
                stickyHeaderDates: true,
                firstDay: 1, // Monday
                slotLabelFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    meridiem: false,
                    hour12: false
                },
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    meridiem: false,
                    hour12: false
                }
            });
            calendar.render();
        });
    </script>
    
    <style>
        .fc .fc-toolbar-title {
            font-size: 1.5rem;
            font-weight: 900;
            color: #111827;
            text-transform: capitalize;
        }
        .fc .fc-button-primary {
            background-color: #4f46e5 !important;
            border-color: #4f46e5 !important;
            border-radius: 0.75rem;
            text-transform: capitalize;
            font-weight: 600;
            padding: 0.5rem 1rem;
        }
        .fc .fc-button-primary:hover {
            background-color: #4338ca !important;
            border-color: #4338ca !important;
        }
        .fc .fc-button-primary:not(:disabled):active, .fc .fc-button-primary:not(:disabled).fc-button-active {
            background-color: #3730a3 !important;
            border-color: #3730a3 !important;
        }
        .fc-theme-standard th {
            border: none !important;
            padding: 12px 0;
            text-transform: uppercase;
            font-size: 0.75rem;
            font-weight: 800;
            color: #6b7280;
            background-color: #f9fafb;
        }
        .fc-event {
            border: none !important;
            border-radius: 8px;
            padding: 3px 6px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            transition: transform 0.15s ease;
        }
        .fc-event:hover {
            transform: scale(1.02);
            z-index: 10 !important;
        }
        .fc-v-event .fc-event-main {
            color: #fff;
            padding: 2px;
        }
        .fc-timegrid-slot {
            height: 3.5em !important; 
        }
        .fc .fc-timegrid-axis-cushion {
            font-size: 0.75rem;
            font-weight: 700;
            color: #9ca3af;
        }
        .fc .fc-bg-event {
            opacity: 0.4;
            border-radius: 8px;
        }
        .fc-theme-standard .fc-scrollgrid {
            border: 1px solid #f3f4f6;
            border-radius: 1rem;
            overflow: hidden;
        }
        .fc-theme-standard td, .fc-theme-standard th {
            border-color: #f3f4f6;
        }
        /* Mobile specific styling */
        @media (max-width: 640px) {
            .fc .fc-toolbar.fc-header-toolbar {
                flex-direction: column;
                gap: 1rem;
            }
            .fc .fc-toolbar-title {
                font-size: 1.25rem;
            }
        }
    </style>
</x-app-layout>
