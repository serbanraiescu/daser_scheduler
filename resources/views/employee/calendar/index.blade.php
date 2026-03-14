<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-3xl p-6 lg:p-8">
                
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                    <div>
                        <h2 class="text-3xl font-black text-gray-900 leading-tight">Agenda Programări</h2>
                        <p class="text-sm font-medium text-gray-500 mt-2">Vizualizează orele ocupate și disponibilitatea ta pe zile și săptămâni.</p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <button type="button" @click="$dispatch('start-voice')" class="inline-flex items-center px-6 py-3 bg-indigo-50 border-2 border-indigo-100 rounded-xl font-bold text-xs text-indigo-600 uppercase tracking-widest hover:bg-indigo-100 active:bg-indigo-200 transition shadow-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                            Comandă Vocală
                        </button>
                        <a href="{{ route('employee.bookings.create') }}" class="hidden sm:inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg shadow-indigo-500/30">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Adaugă Programare
                        </a>
                    </div>
                </div>

                <div id="calendar" class="fc-theme-standard overflow-visible"></div>
            </div>
        </div>
    </div>

    <!-- Mobile Floating Action Button -->
    <div class="fixed bottom-6 right-6 flex flex-col gap-4 z-50 sm:hidden">
        <button type="button" @click="$dispatch('start-voice')" 
                class="w-14 h-14 bg-white border-2 border-indigo-100 rounded-full shadow-xl flex items-center justify-center text-indigo-600 hover:bg-indigo-50 transition-transform active:scale-95">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
        </button>
        <a href="{{ route('employee.bookings.create') }}" 
           class="w-14 h-14 bg-indigo-600 rounded-full shadow-2xl flex items-center justify-center text-white hover:bg-indigo-700 transition-transform active:scale-95">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
        </a>
    </div>

    <!-- Voice Recognition Overlay -->
    <div x-data="voiceBooking()" 
         x-show="isListening" 
         x-cloak
         @start-voice.window="startListening()"
         class="fixed inset-0 bg-indigo-900/90 backdrop-blur-sm flex items-center justify-center z-[100] transition-all">
        <div class="bg-white rounded-3xl p-8 max-w-sm w-full mx-4 shadow-2xl text-center space-y-6 transform animate-in fade-in zoom-in duration-300">
            <div class="relative">
                <div class="absolute inset-0 bg-indigo-100 rounded-full animate-ping opacity-25"></div>
                <div class="relative w-20 h-20 bg-indigo-600 rounded-full flex items-center justify-center text-white mx-auto shadow-lg">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                </div>
            </div>
            
            <div class="space-y-2">
                <h3 class="text-xl font-black text-gray-900">Te ascult...</h3>
                <p class="text-xs font-bold text-indigo-500 uppercase tracking-widest">Spune o comandă</p>
                <div class="mt-4 p-4 bg-gray-50 rounded-2xl min-h-[80px] flex items-center justify-center">
                    <p class="text-gray-800 font-medium italic" x-text="transcript || 'Ex: Programare Popescu Ion luni la ora 14'"></p>
                </div>
            </div>

            <div class="pt-4 flex gap-3">
                <button @click="stopListening()" class="flex-1 px-6 py-3 bg-gray-100 text-gray-600 rounded-xl font-bold text-sm hover:bg-gray-200 transition">
                    Anulează
                </button>
            </div>
        </div>
    </div>

    <!-- FullCalendar Integration -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@6.1.11/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.11/locales/ro.global.min.js"></script>

    <script>
        function voiceBooking() {
            return {
                isListening: false,
                transcript: '',
                recognition: null,
                
                init() {
                    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
                    if (SpeechRecognition) {
                        this.recognition = new SpeechRecognition();
                        this.recognition.lang = 'ro-RO';
                        this.recognition.continuous = false;
                        this.recognition.interimResults = true;
                        
                        this.recognition.onresult = (event) => {
                            this.transcript = Array.from(event.results)
                                .map(result => result[0])
                                .map(result => result.transcript)
                                .join('');
                                
                            if (event.results[0].isFinal) {
                                this.processCommand(this.transcript.toLowerCase());
                            }
                        };
                        
                        this.recognition.onerror = () => {
                            this.stopListening();
                            alert('Eroare la recunoașterea vocală. Verifică permisiunile microfonului.');
                        };
                        
                        this.recognition.onend = () => {
                            if (this.isListening && !this.transcript) {
                                this.stopListening();
                            }
                        };
                    }
                },
                
                startListening() {
                    if (!this.recognition) {
                        alert('Browser-ul tău nu suportă recunoașterea vocală.');
                        return;
                    }
                    this.transcript = '';
                    this.isListening = true;
                    this.recognition.start();
                },
                
                stopListening() {
                    this.isListening = false;
                    if (this.recognition) this.recognition.stop();
                },
                
                processCommand(text) {
                    console.log('Processing:', text);
                    
                    // Simple Romanian Parser
                    // Pattern: Programare [Nume] [Zi] [Ora]
                    const days = {
                        'azi': 0, 'astăzi': 0, 'mâine': 1, 'maine': 1,
                        'luni': 'Monday', 'marți': 'Tuesday', 'marti': 'Tuesday', 'miercuri': 'Wednesday',
                        'joi': 'Thursday', 'vineri': 'Friday', 'sâmbătă': 'Saturday', 'sambata': 'Saturday', 'duminică': 'Sunday', 'duminica': 'Sunday'
                    };

                    let date = new Date();
                    let foundDay = false;
                    
                    // 1. Extract Date
                    for (let word in days) {
                        if (text.includes(word)) {
                            if (typeof days[word] === 'number') {
                                date.setDate(date.getDate() + days[word]);
                            } else {
                                // Find next occurrence of that weekday
                                const targetDay = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'].indexOf(days[word]);
                                const currentDay = date.getDay();
                                let diff = targetDay - currentDay;
                                if (diff < 0) diff += 7;
                                date.setDate(date.getDate() + diff);
                            }
                            foundDay = true;
                            break;
                        }
                    }
                    
                    const dateStr = date.toISOString().split('T')[0];

                    // 2. Extract Time
                    let timeStr = '09:00';
                    const timeMatch = text.match(/ora\s+(\d{1,2})(?::(\d{2}))?/);
                    if (timeMatch) {
                        let h = timeMatch[1].padStart(2, '0');
                        let m = (timeMatch[2] || '00').padStart(2, '0');
                        timeStr = `${h}:${m}`;
                    }

                    // 3. Extract Name (anything between "programare" and the day/time keywords)
                    let name = '';
                    const nameMatch = text.match(/programare\s+(.*?)\s+(?:azi|maine|mâine|luni|marti|marți|miercuri|joi|vineri|sambata|sâmbătă|duminica|duminică|la|ora)/);
                    if (nameMatch) {
                        name = nameMatch[1].trim();
                    } else {
                        // Fallback: take everything after "programare" and before "ora"
                        const fallbackMatch = text.match(/programare\s+(.*?)\s*(?:la\s+)?ora/);
                        if (fallbackMatch) name = fallbackMatch[1].trim();
                    }

                    if (name) {
                        setTimeout(() => {
                            window.location.href = `/employee/bookings/create?search=${encodeURIComponent(name)}&date=${dateStr}&time=${timeStr}`;
                        }, 500);
                    } else {
                        alert('Nu am înțeles numele clientului. Încearcă: "Programare [Nume] azi la ora 10"');
                        this.stopListening();
                    }
                }
            };
        }

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
                    let datePart = info.dateStr.split('T')[0];
                    let timePart = info.dateStr.includes('T') ? info.dateStr.split('T')[1].substring(0, 5) : '09:00';
                    window.location.href = "{{ route('employee.bookings.create') }}?date=" + datePart + "&time=" + timePart;
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
        
        /* Aggressive Sticky Header Fix */
        .fc-scrollgrid-section-header.fc-scrollgrid-section-sticky > td,
        .fc-scrollgrid-section-header.fc-scrollgrid-section-sticky > th {
            top: 64px !important; /* Mobile Header Height */
            background: #fff !important;
            z-index: 100 !important;
            box-shadow: 0 2px 4px -1px rgba(0,0,0,0.06);
        }

        /* Ensure parent visibility for sticky to work */
        .fc-scrollgrid, .fc-scrollgrid-section, .fc-scrollgrid-section-header {
            overflow: visible !important;
        }

        @media (min-width: 640px) {
            .fc-scrollgrid-section-header.fc-scrollgrid-section-sticky > td,
            .fc-scrollgrid-section-header.fc-scrollgrid-section-sticky > th {
                top: 0 !important;
            }
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
