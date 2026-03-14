<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.clients.index') }}" class="p-2 hover:bg-gray-100 rounded-full transition">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalii Client') }}: {{ $client->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Detalii de baza -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-black text-gray-900">{{ $client->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $client->phone }} | {{ $client->email ?? 'Fără email' }}</p>
                        @if($client->tags)
                            <div class="mt-2">
                                @foreach($client->tags as $tag)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 mr-1">
                                        {{ $tag->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="text-right">
                        <div class="bg-indigo-50 px-4 py-2 rounded-xl border border-indigo-100 mb-2">
                            <p class="text-xs font-bold text-indigo-400 uppercase tracking-widest">Fidelitate</p>
                            <p class="text-lg font-black text-indigo-600">{{ $client->loyalty_points }} puncte</p>
                            <p class="text-sm font-bold text-indigo-600">{{ number_format($client->special_discount, 0) }}% discount</p>
                        </div>
                        <a href="{{ route('admin.clients.edit', $client) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Editează Client
                        </a>
                    </div>
                </div>
            </div>

            <!-- Istoric Programari -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-black text-gray-900">Istoric Programări</h3>
                </div>
                <div class="p-0">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data & Oră</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Serviciu</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Specialist</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Preț</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($bookings as $booking)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $booking->date->format('d.m.Y') }}</div>
                                        <div class="text-sm text-gray-500">{{ $booking->start_time->format('H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $booking->service->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $booking->service->duration }} min</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $booking->employee->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($booking->status === 'cancelled')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800">Anulată</span>
                                        @elseif($booking->date->isPast())
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-800">Finalizată</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800">Viitoare</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        {{ number_format($booking->service->price, 0) }} RON
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        Nicio programare înregistrată pentru acest client.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($bookings->hasPages())
                    <div class="p-6 border-t border-gray-100">
                        {{ $bookings->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
