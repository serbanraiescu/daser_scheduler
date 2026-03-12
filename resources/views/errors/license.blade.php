<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg text-center">
            <div class="mb-4 text-red-600">
                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Licență Invalidă sau Expirată</h1>
            <p class="text-gray-600 mb-6">
                @if($status->status === 'denied')
                    Accesul la această aplicație a fost suspendat. Vă rugăm să contactați administratorul sau să verificați cheia de licență.
                @else
                    Aplicația nu a putut verifica licența în ultimele 48 de ore. Este necesară o conexiune la internet pentru re-validare.
                @endif
            </p>

            <div class="space-y-4">
                <form action="{{ route('admin.license.reverify') }}" method="POST">
                    @csrf
                    <x-primary-button class="w-full justify-center">
                        Re-verifică Licența
                    </x-primary-button>
                </form>

                <a href="{{ route('login') }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                    Autentificare Administrator
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
