<x-guest-layout>
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Creează un cont</h1>
        <p class="text-gray-500 mt-2">Alătură-te comunității noastre și gestionează-ți programările simplu</p>
    </div>

    <!-- Rest of the form -->
    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nume Complet')" class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1 ml-1" />
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-colors duration-200 group-focus-within:text-indigo-600 text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <x-text-input id="name" class="block w-full pl-10 bg-white/50 border-gray-200 focus:bg-white transition-all duration-200" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Popescu Ion" />
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Phone -->
        <div>
            <x-input-label for="phone" :value="__('Telefon')" class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1 ml-1" />
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-colors duration-200 group-focus-within:text-indigo-600 text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                </div>
                <x-text-input id="phone" class="block w-full pl-10 bg-white/50 border-gray-200 focus:bg-white transition-all duration-200" type="text" name="phone" :value="old('phone')" required placeholder="07xx xxx xxx" />
            </div>
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1 ml-1" />
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-colors duration-200 group-focus-within:text-indigo-600 text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                </div>
                <x-text-input id="email" class="block w-full pl-10 bg-white/50 border-gray-200 focus:bg-white transition-all duration-200" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="exemplu@mail.ro" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('Parolă')" class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1 ml-1" />
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-colors duration-200 group-focus-within:text-indigo-600 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <x-text-input id="password" class="block w-full pl-10 bg-white/50 border-gray-200 focus:bg-white transition-all duration-200"
                                    type="password"
                                    name="password"
                                    required autocomplete="new-password" placeholder="••••••••" />
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div>
                <x-input-label for="password_confirmation" :value="__('Confirmă Parola')" class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1 ml-1" />
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-colors duration-200 group-focus-within:text-indigo-600 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <x-text-input id="password_confirmation" class="block w-full pl-10 bg-white/50 border-gray-200 focus:bg-white transition-all duration-200"
                                    type="password"
                                    name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full flex items-center justify-center px-6 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl shadow-lg shadow-indigo-200 active:scale-[0.98] transition-all duration-200">
                <span>Înregistrare</span>
                <svg class="w-5 h-5 ms-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            </button>
        </div>

        <div class="text-center pt-4 mt-6 border-t border-white/40">
            <p class="text-sm text-gray-500">
                Aveți deja un cont? 
                <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-800 font-bold ml-1 transition-colors">
                    Autentificați-vă
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
