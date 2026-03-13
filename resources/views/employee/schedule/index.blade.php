<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Programul Meu de Lucru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('employee.schedule.update') }}" method="POST">
                @csrf
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="mb-6">
                            <h3 class="text-lg font-bold mb-2">Configurează orarul pentru următoarele 14 zile</h3>
                            <p class="text-sm text-gray-600">Setează orele de început, sfârșit și pauzele pentru fiecare zi. Dacă ești liber, bifează "Liber".</p>
                        </div>

                        <div class="space-y-4">
                            @foreach($days as $date => $schedule)
                                @php 
                                    $carbonDate = \Carbon\Carbon::parse($date);
                                    $isToday = $carbonDate->isToday();
                                @endphp
                                <div class="p-4 rounded-xl border {{ $isToday ? 'border-indigo-200 bg-indigo-50' : 'border-gray-100 bg-gray-50' }} flex flex-col md:flex-row md:items-center justify-between gap-4">
                                    <div class="w-full md:w-48">
                                        <div class="font-bold text-gray-900 capitalize">{{ $carbonDate->translatedFormat('l') }}</div>
                                        <div class="text-sm text-gray-500">{{ $carbonDate->format('d M Y') }}</div>
                                        @if($isToday)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800 mt-1">
                                                Azi
                                            </span>
                                        @endif
                                    </div>

                                    <div class="flex-1 grid grid-cols-2 md:grid-cols-4 gap-4 items-end">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Start</label>
                                            <input type="time" name="schedule[{{ $date }}][start_time]" 
                                                value="{{ $schedule ? \Carbon\Carbon::parse($schedule->start_time)->format('H:i') : '09:00' }}" 
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Sfârșit</label>
                                            <input type="time" name="schedule[{{ $date }}][end_time]" 
                                                value="{{ $schedule ? \Carbon\Carbon::parse($schedule->end_time)->format('H:i') : '18:00' }}" 
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Pauză (de la)</label>
                                            <input type="time" name="schedule[{{ $date }}][break_start]" 
                                                value="{{ ($schedule && $schedule->break_start) ? \Carbon\Carbon::parse($schedule->break_start)->format('H:i') : '' }}" 
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Pauză (până la)</label>
                                            <input type="time" name="schedule[{{ $date }}][break_end]" 
                                                value="{{ ($schedule && $schedule->break_end) ? \Carbon\Carbon::parse($schedule->break_end)->format('H:i') : '' }}" 
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <input type="checkbox" id="off_{{ $date }}" name="schedule[{{ $date }}][is_off]" value="1" {{ !$schedule ? 'checked' : '' }}
                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        <label for="off_{{ $date }}" class="text-sm font-medium text-gray-700">Liber</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-8 flex justify-end">
                            <x-primary-button>
                                {{ __('Salvează Programul') }}
                            </x-primary-button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
