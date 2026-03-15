<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-black text-3xl text-gray-900 leading-tight">
                    {{ __('Vouchere & Oferte') }} 🎁
                </h2>
                <p class="text-gray-500 font-medium">Bucură-te de beneficiile tale de fidelitate și oferte speciale.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('bookings.index') }}" class="px-6 py-3 bg-indigo-600 text-white rounded-2xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200 active:scale-95">
                    + Rezervă Acum
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-12">
            
            <!-- Fidelity Status Banner -->
            <div class="bg-indigo-600 rounded-[2.5rem] p-8 text-white shadow-2xl shadow-indigo-200 relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="absolute top-0 right-0 -m-8 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                
                <div class="relative space-y-2 text-center md:text-left">
                    <div class="inline-flex px-3 py-1 bg-white/20 rounded-full text-[10px] font-black uppercase tracking-widest backdrop-blur-md mb-2">
                        Statut Fidelitate
                    </div>
                    <h3 class="text-2xl font-black">Discount permanent de {{ number_format($client->special_discount, 0) }}%</h3>
                    <p class="text-indigo-100 font-medium">Acest discount se aplică automat la orice serviciu rezervat de tine.</p>
                </div>
                
                <div class="relative bg-white/10 backdrop-blur-xl border border-white/20 rounded-[2rem] p-6 flex items-center gap-6">
                    <div class="text-center">
                        <p class="text-[10px] font-black uppercase tracking-widest text-indigo-100 mb-1">Puncte Acumulate</p>
                        <p class="text-4xl font-black text-white">{{ $client->loyalty_points }}</p>
                    </div>
                    <div class="w-px h-12 bg-white/20"></div>
                    <div class="text-center">
                        <p class="text-[10px] font-black uppercase tracking-widest text-indigo-100 mb-1">Vouchere Noi</p>
                        <p class="text-4xl font-black text-white">{{ $voucherCount }}</p>
                    </div>
                </div>
            </div>

            <!-- Vouchers Grid -->
            <div class="space-y-6">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] px-4">Portofel Vouchere</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse($vouchers as $cv)
                        <div class="group relative bg-white rounded-[2.5rem] p-8 border-2 {{ $cv->used ? 'border-gray-100 opacity-60 grayscale' : 'border-indigo-50 hover:border-indigo-200 hover:shadow-2xl' }} transition-all duration-300 shadow-xl shadow-gray-100/50 overflow-hidden">
                            @if($cv->used)
                                <div class="absolute inset-0 flex items-center justify-center z-20 pointer-events-none">
                                    <span class="bg-gray-100/90 text-gray-400 px-6 py-2 rounded-2xl font-black text-xs uppercase tracking-widest border-2 border-gray-200 backdrop-blur-sm -rotate-12">Utilizat</span>
                                </div>
                            @endif

                            <div class="relative z-10 space-y-8">
                                <div class="flex justify-between items-start">
                                    <div class="w-16 h-16 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 transition-colors group-hover:bg-indigo-600 group-hover:text-white">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5h14a2 2 0 0 1 2 2v3a2 2 0 0 0 0 4v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3a2 2 0 0 0 0-4V7a2 2 0 0 1 2-2Z"/></svg>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Valoare Beneficiu</p>
                                        <p class="text-3xl font-black text-indigo-600">
                                            @if($cv->voucher->type === 'percentage')
                                                {{ number_format($cv->voucher->value, 0) }}%
                                            @else
                                                {{ number_format($cv->voucher->value, 0) }} <span class="text-sm font-bold uppercase tracking-tighter">RON</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 group-hover:bg-indigo-50 group-hover:border-indigo-100 transition-colors">
                                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Cod Unic pentru Programare</h4>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xl font-mono font-black text-gray-900 select-all tracking-wider">{{ $cv->voucher->code }}</span>
                                        <button onclick="navigator.clipboard.writeText('{{ $cv->voucher->code }}')" class="p-2 text-indigo-400 hover:text-indigo-600 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="pt-6 border-t border-gray-50 flex justify-between items-center">
                                    <div class="flex items-center gap-2">
                                        <div class="w-1.5 h-1.5 rounded-full {{ $cv->expires_at && $cv->expires_at->isPast() ? 'bg-red-400' : 'bg-green-400' }}"></div>
                                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                            {{ $cv->expires_at ? 'Expiră în ' . $cv->expires_at->diffForHumans() : 'Fără expirare' }}
                                        </span>
                                    </div>
                                    <p class="text-[10px] font-bold text-gray-300">#{{ str_pad($cv->id, 5, '0', STR_PAD_LEFT) }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-24 text-center bg-gray-50/50 rounded-[3rem] border-2 border-dashed border-gray-200">
                            <div class="max-w-xs mx-auto space-y-6">
                                <div class="w-20 h-20 bg-white rounded-[2rem] flex items-center justify-center text-gray-300 mx-auto shadow-sm">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                </div>
                                <div>
                                    <p class="text-gray-900 font-black uppercase text-xs tracking-widest mb-2">Momentan niciun voucher</p>
                                    <p class="text-gray-400 font-medium italic text-sm">Fii pe fază! Vei primi oferte speciale direct aici după următoarele tale vizite.</p>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="mt-8 px-4">
                {{ $vouchers->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
