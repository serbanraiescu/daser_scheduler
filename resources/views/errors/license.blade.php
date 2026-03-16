@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center bg-gray-50 px-4">
    <div class="max-w-md w-full bg-white rounded-[32px] shadow-2xl p-10 border border-red-50 text-center">
        <div class="w-20 h-20 bg-red-50 rounded-2xl flex items-center justify-center mx-auto mb-8">
            <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0-8V11m0 0v2m-9 4h18a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        
        <h1 class="text-3xl font-black text-gray-900 mb-4 tracking-tight uppercase italic">Licență Necesară</h1>
        
        <p class="text-gray-600 mb-8 leading-relaxed font-medium">
            Accesul la această aplicație a fost temporar suspendat deoarece licența nu este activă sau nu a putut fi verificată cu serverul central.
        </p>

        @if(isset($status))
            <div class="bg-gray-50 rounded-2xl p-6 mb-8 text-left border border-gray-100">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-xs font-black text-gray-400 uppercase tracking-widest">Status</span>
                    <span class="text-xs font-black text-red-500 uppercase tracking-widest">{{ $status->status ?? 'Necunoscut' }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs font-black text-gray-400 uppercase tracking-widest">Ultima Verificare</span>
                    <span class="text-xs font-medium text-gray-700">{{ $status->last_check ?? 'Niciodată' }}</span>
                </div>
            </div>
        @endif

        <div class="space-y-4">
            <a href="{{ route('admin.settings.index') }}" class="w-full inline-flex items-center justify-center px-8 py-4 bg-indigo-600 text-white font-black rounded-2xl hover:bg-indigo-700 transition shadow-xl shadow-indigo-100 uppercase tracking-widest text-sm">
                Configurează Licența
            </a>
            
            <form action="{{ route('admin.license.reverify') }}" method="POST">
                @csrf
                <button type="submit" class="w-full text-indigo-600 font-bold uppercase tracking-widest text-xs hover:text-indigo-800 transition">
                    Încearcă Re-verificare
                </button>
            </form>
        </div>
    </div>
    
    <div class="mt-8 text-center">
        <p class="text-gray-400 text-xs font-bold uppercase tracking-[0.2em] mb-2">Developed by</p>
        <span class="font-black text-gray-900 text-lg tracking-tighter uppercase">DASER<span class="text-indigo-600">.</span></span>
    </div>
</div>
@endsection
