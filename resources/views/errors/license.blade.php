<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Licență Necesară - {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .bg-animate {
            background: linear-gradient(-45deg, #4f46e5, #7c3aed, #2563eb, #4338ca);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
        }
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="antialiased bg-animate min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full glass rounded-[40px] shadow-2xl overflow-hidden">
        <div class="p-10 text-center">
            <div class="w-24 h-24 bg-red-50 rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-inner">
                <svg class="w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0-8V11m0 0v2m-9 4h18a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            
            <h1 class="text-3xl font-black text-gray-900 mb-4 tracking-tighter uppercase italic">Licență Necesară</h1>
            
            <p class="text-gray-600 mb-8 leading-relaxed font-semibold px-2">
                Accesul la această aplicație a fost temporar suspendat deoarece licența nu este activă sau nu a putut fi verificată.
            </p>

            @if(isset($status))
                <div class="bg-gray-50/80 rounded-[28px] p-6 mb-8 text-left border border-gray-100 shadow-sm">
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Status Curent</span>
                        <span class="px-3 py-1 bg-red-100 text-red-600 rounded-full text-[10px] font-black uppercase tracking-widest border border-red-200">{{ $status->status ?? 'Necunoscut' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Ultima Sincronizare</span>
                        <span class="text-[11px] font-bold text-gray-700">{{ $status->last_check ?? 'Niciodată' }}</span>
                    </div>
                </div>
            @endif

            <div class="space-y-4">
                <a href="/admin/settings" class="w-full inline-flex items-center justify-center px-8 py-5 bg-indigo-600 text-white font-black rounded-[24px] hover:bg-indigo-700 transition shadow-xl shadow-indigo-200 uppercase tracking-widest text-xs">
                    Configurează Licența
                </a>
                
                <form action="/admin/license/reverify" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-indigo-600 font-black uppercase tracking-[0.25em] text-[10px] hover:text-indigo-800 transition py-2">
                        Încearcă Re-verificare Manuală
                    </button>
                </form>
            </div>
        </div>
        
        <div class="bg-gray-50 border-t border-gray-100 p-6 text-center">
            <p class="text-gray-400 text-[10px] font-black uppercase tracking-[0.3em] mb-1">Developed by</p>
            <span class="font-black text-gray-900 text-xl tracking-tighter uppercase">DASER<span class="text-indigo-600">.</span></span>
        </div>
    </div>
</body>
</html>
