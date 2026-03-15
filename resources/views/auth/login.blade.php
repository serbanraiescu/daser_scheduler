<x-guest-layout>
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Bine ai revenit!</h1>
        <p class="text-gray-500 mt-2">Introdu datele tale pentru a accesa contul</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf
        @if(request()->has('redirect'))
            <input type="hidden" name="redirect" value="{{ request()->query('redirect') }}">
        @endif

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1 ml-1" />
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-colors duration-200 group-focus-within:text-indigo-600 text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                </div>
                <x-text-input id="email" class="block w-full pl-10 bg-white/50 border-gray-200 focus:bg-white transition-all duration-200" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="exemplu@mail.ro" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

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
                                required autocomplete="current-password" placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 transition-all cursor-pointer group-hover:border-indigo-400" name="remember">
                <span class="ms-2 text-sm text-gray-500 group-hover:text-gray-700 transition-colors">{{ __('Ține-mă minte') }}</span>
            </label>

            <x-primary-button class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-lg shadow-indigo-100 transition-all active:scale-95 border-none">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    <div class="text-center pt-8 mt-8 border-t border-gray-100">
        <p class="text-sm text-gray-500">
            Nu ai un cont? 
            <a href="{{ route('register', ['redirect' => request()->query('redirect')]) }}" class="text-indigo-600 hover:text-indigo-800 font-bold ml-1 transition-colors">
                Înregistrează-te acum
            </a>
        </p>
    </div>
</x-guest-layout>
