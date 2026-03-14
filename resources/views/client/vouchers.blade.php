<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('client.dashboard') }}" class="p-2 hover:bg-gray-100 rounded-full transition">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h2 class="font-black text-2xl text-gray-900 leading-tight">
                {{ __('Vouchere & Oferte') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <div class="bg-indigo-50 border-2 border-indigo-100 rounded-[2rem] p-8 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="space-y-2 text-center md:text-left">
                    <h3 class="text-xl font-black text-indigo-900">Discount-ul tău permanent</h3>
                    <p class="text-indigo-600 font-medium">Bucură-te de prețuri speciale la fiecare programare.</p>
                </div>
                <div class="bg-white px-8 py-4 rounded-3xl shadow-xl shadow-indigo-100 border-2 border-indigo-200">
                    <p class="text-3xl font-black text-indigo-600">{{ number_format($client->special_discount, 0) }}%</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($vouchers as $cv)
                    <div class="group relative bg-white rounded-[2rem] p-8 border-2 {{ $cv->used ? 'border-gray-100 opacity-60' : 'border-indigo-50 hover:border-indigo-200' }} transition-all shadow-sm hover:shadow-xl">
                        @if($cv->used)
                            <div class="absolute inset-0 flex items-center justify-center z-10">
                                <span class="bg-gray-100 text-gray-400 px-4 py-2 rounded-xl font-black text-xs uppercase tracking-widest border-2 border-gray-200">Utilizat</span>
                            </div>
                        @endif

                        <div class="space-y-6">
                            <div class="flex justify-between items-start">
                                <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Valoare</p>
                                    <p class="text-2xl font-black text-indigo-600">
                                        @if($cv->voucher->type === 'percentage')
                                            {{ number_format($cv->voucher->value, 0) }}%
                                        @else
                                            {{ number_format($cv->voucher->value, 0) }} RON
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div>
                                <h4 class="text-sm font-bold text-gray-500 uppercase tracking-widest">Cod Voucher</h4>
                                <div class="mt-1 flex items-center gap-2">
                                    <span class="text-xl font-mono font-black text-gray-900 select-all">{{ $cv->voucher->code }}</span>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-gray-50 flex justify-between items-center text-[10px] font-bold uppercase tracking-widest">
                                <span class="text-gray-400">Expiră la:</span>
                                <span class="text-gray-900">{{ $cv->expires_at ? $cv->expires_at->format('d.m.Y') : 'Fără expirare' }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-24 text-center bg-gray-50 rounded-[2rem] border-2 border-dashed border-gray-200">
                        <div class="max-w-xs mx-auto space-y-4">
                            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center text-gray-300 mx-auto shadow-sm">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </div>
                            <p class="text-gray-400 font-medium italic">Momentan nu ai niciun voucher disponibil.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $vouchers->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
