<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestiune Program & Concedii') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ activeTab: 'standard' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Tabs Navigation -->
            <div class="flex space-x-4 mb-6 bg-white p-1 rounded-xl shadow-sm border">
                <button @click="activeTab = 'standard'" :class="activeTab === 'standard' ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-50'" class="flex-1 py-2 rounded-lg font-bold transition-all">
                    Program Standard
                </button>
                <button @click="activeTab = 'exceptions'" :class="activeTab === 'exceptions' ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-50'" class="flex-1 py-2 rounded-lg font-bold transition-all">
                    Excepții (Următoarele 14 zile)
                </button>
                <button @click="activeTab = 'vacation'" :class="activeTab === 'vacation' ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-50'" class="flex-1 py-2 rounded-lg font-bold transition-all">
                    Concedii / Zile Blocate
                </button>
            </div>

            <!-- Tab 1: Standard Schedule -->
            <div x-show="activeTab === 'standard'" x-transition>
                <form action="{{ route('employee.schedule.standard.update') }}" method="POST">
                    @csrf
                    <div class="bg-white shadow-sm sm:rounded-xl overflow-hidden border">
                        <div class="p-6">
                            <h3 class="text-lg font-bold mb-4">Program Săptămânal Recurent</h3>
                            <p class="text-sm text-gray-600 mb-6">Acesta este programul tău de bază care se repetă în fiecare săptămână.</p>
                            
                            <div class="space-y-4">
                                @foreach($weekDays as $num => $name)
                                    @php $s = $standardSchedules->get($num); @endphp
                                    <div class="p-4 rounded-xl border bg-gray-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                                        <div class="w-24 font-bold text-gray-900">{{ $name }}</div>
                                        
                                        <div class="flex-1 grid grid-cols-2 lg:grid-cols-4 gap-4">
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Inceput</label>
                                                <input type="time" name="standard[{{ $num }}][start_time]" 
                                                    value="{{ $s ? \Carbon\Carbon::parse($s->start_time)->format('H:i') : '09:00' }}"
                                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 sm:text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Sfârșit</label>
                                                <input type="time" name="standard[{{ $num }}][end_time]" 
                                                    value="{{ $s ? \Carbon\Carbon::parse($s->end_time)->format('H:i') : '18:00' }}"
                                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 sm:text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Pauză (de la)</label>
                                                <input type="time" name="standard[{{ $num }}][break_start]" 
                                                    value="{{ ($s && $s->break_start) ? \Carbon\Carbon::parse($s->break_start)->format('H:i') : '' }}"
                                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 sm:text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Pauză (până la)</label>
                                                <input type="time" name="standard[{{ $num }}][break_end]" 
                                                    value="{{ ($s && $s->break_end) ? \Carbon\Carbon::parse($s->break_end)->format('H:i') : '' }}"
                                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 sm:text-sm">
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <input type="checkbox" name="standard[{{ $num }}][is_off]" {{ ($s && $s->is_off) ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                            <label class="text-sm font-medium text-gray-700">Liber</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-8 flex justify-end">
                                <x-primary-button>Salvează Program Standard</x-primary-button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tab 2: Exceptions -->
            <div x-show="activeTab === 'exceptions'" x-transition style="display:none;">
                <form action="{{ route('employee.schedule.update') }}" method="POST">
                    @csrf
                    <div class="bg-white shadow-sm sm:rounded-xl overflow-hidden border">
                        <div class="p-6">
                            <h3 class="text-lg font-bold mb-4">Excepții de la Program (Următoarele 14 zile)</h3>
                            <p class="text-sm text-gray-600 mb-6">Folosește această secțiune pentru a schimba orarul doar pentru anumite zile specifice (ex: o zi în care ai treabă).</p>
                            
                            <div class="space-y-4">
                                @foreach($days as $date => $exception)
                                    @php $cd = \Carbon\Carbon::parse($date); @endphp
                                    <div class="p-4 rounded-xl border {{ $cd->isToday() ? 'border-indigo-200 bg-indigo-50' : 'bg-gray-50 border-gray-200' }} flex flex-col md:flex-row md:items-center justify-between gap-4">
                                        <div class="w-48">
                                            <div class="font-bold text-gray-900 capitalize">{{ $cd->translatedFormat('l') }}</div>
                                            <div class="text-sm text-gray-500">{{ $cd->format('d M Y') }}</div>
                                        </div>

                                        <div class="flex-1 grid grid-cols-2 lg:grid-cols-4 gap-4">
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Start</label>
                                                <input type="time" name="schedule[{{ $date }}][start_time]" 
                                                    value="{{ $exception ? \Carbon\Carbon::parse($exception->start_time)->format('H:i') : '' }}"
                                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 sm:text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Sfârșit</label>
                                                <input type="time" name="schedule[{{ $date }}][end_time]" 
                                                    value="{{ $exception ? \Carbon\Carbon::parse($exception->end_time)->format('H:i') : '' }}"
                                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 sm:text-sm">
                                            </div>
                                            <div class="col-span-2 grid grid-cols-2 gap-2">
                                                <div>
                                                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Pauză (de la)</label>
                                                    <input type="time" name="schedule[{{ $date }}][break_start]" 
                                                        value="{{ ($exception && $exception->break_start) ? \Carbon\Carbon::parse($exception->break_start)->format('H:i') : '' }}"
                                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 sm:text-sm">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Pauză (până la)</label>
                                                    <input type="time" name="schedule[{{ $date }}][break_end]" 
                                                        value="{{ ($exception && $exception->break_end) ? \Carbon\Carbon::parse($exception->break_end)->format('H:i') : '' }}"
                                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 sm:text-sm">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <input type="checkbox" name="schedule[{{ $date }}][is_off]" value="1" {{ ($exception && $exception->is_off) ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                            <label class="text-sm font-medium text-gray-700">Liber</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-8 flex justify-end">
                                <x-primary-button>Salvează Excepțiile</x-primary-button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tab 3: Vacation -->
            <div x-show="activeTab === 'vacation'" x-transition style="display:none;">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Form Block -->
                    <div class="md:col-span-1">
                        <form action="{{ route('employee.schedule.block') }}" method="POST" class="bg-white p-6 rounded-xl border shadow-sm">
                            @csrf
                            <h3 class="text-lg font-bold mb-4">Blochează o zi / Concediu</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Data</label>
                                    <input type="date" name="date" required min="{{ date('Y-m-d') }}"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Motiv (Ex: Concediu, Medical)</label>
                                    <input type="text" name="reason" placeholder="Ex: Concediu de odihnă"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500">
                                </div>
                                <button type="submit" class="w-full bg-red-600 text-white font-bold py-2 rounded-lg hover:bg-red-700 transition">
                                    Blochează Ziua
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- List Block -->
                    <div class="md:col-span-2 bg-white rounded-xl border shadow-sm overflow-hidden">
                        <div class="p-6 border-b">
                            <h3 class="text-lg font-bold">Zile Blocate Active</h3>
                        </div>
                        <div class="divide-y">
                            @forelse($blockedDates as $blocked)
                                <div class="p-4 flex justify-between items-center hover:bg-gray-50 transition">
                                    <div>
                                        <div class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($blocked->date)->format('d M Y') }}</div>
                                        <div class="text-sm text-gray-500">{{ $blocked->reason }}</div>
                                    </div>
                                    <form action="{{ route('employee.schedule.unblock', $blocked->id) }}" method="POST" onsubmit="return confirm('Deblochezi această zi?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-bold">
                                            Șterge
                                        </button>
                                    </form>
                                </div>
                            @empty
                                <div class="p-8 text-center text-gray-500">
                                    Nu ai nicio zi de concediu sau zi blocată în viitor.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
