<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Carduri Cadou') }}
            </h2>
            <a href="{{ route('admin.gift-vouchers.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                Adaugă Card Cadou
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500 uppercase">Total Vândute</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['total_sold'] }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500 uppercase">Active</div>
                    <div class="mt-2 text-3xl font-bold text-green-600">{{ $stats['active'] }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500 uppercase">Utilizate Integral</div>
                    <div class="mt-2 text-3xl font-bold text-blue-600">{{ $stats['redeemed'] }}</div>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cod</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client / Cumpărător</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tip / Valoare</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rămas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valabilitate</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acțiuni</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($giftVouchers as $voucher)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap font-mono font-bold">{{ $voucher->code }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($voucher->client)
                                        <div class="font-medium text-gray-900">{{ $voucher->client->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $voucher->client->phone }}</div>
                                    @else
                                        <div class="font-medium text-gray-900">{{ $voucher->buyer_name }}</div>
                                        <div class="text-xs text-gray-500">{{ $voucher->buyer_phone }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($voucher->service_id)
                                        <span class="text-sm font-medium">Pachet {{ $voucher->service->name }}</span>
                                    @else
                                        <span class="text-sm font-medium">{{ number_format($voucher->value_amount, 2) }} {{ config('app.currency', 'RON') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($voucher->service_id)
                                        <span class="font-bold text-blue-600">{{ $voucher->remaining_uses }}</span> ședințe
                                    @else
                                        <span class="font-bold text-green-600">{{ number_format($voucher->remaining_value, 2) }}</span> {{ config('app.currency', 'RON') }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $voucher->expires_at ? $voucher->expires_at->format('d.m.Y') : 'Nedeterminată' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusClasses = [
                                            'active' => 'bg-green-100 text-green-800',
                                            'redeemed' => 'bg-blue-100 text-blue-800',
                                            'expired' => 'bg-red-100 text-red-800',
                                        ];
                                    @endphp
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses[$voucher->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($voucher->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <form action="{{ route('admin.gift-vouchers.destroy', $voucher) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Sigur dorești să ștergi acest card cadou?')">Șterge</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-500 italic">
                                    Nu există carduri cadou înregistrate.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $giftVouchers->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
