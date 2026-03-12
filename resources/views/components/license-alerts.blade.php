@php
    $licenseService = app(\App\Services\LicenseService::class);
    $license = $licenseService->getStatus();
@endphp

@if($license->status === 'active')
    {{-- Warning Banner (Yellow) - 15 days left --}}
    @if($license->days_left <= 15 && $license->days_left > 0)
        <div class="bg-yellow-500 text-white text-center py-2 px-4 font-bold">
            ⚠️ Atenție: Licența expiră în {{ $license->days_left }} zile. Vă rugăm să o reînnoiți pentru a evita blocarea.
        </div>
    @endif

    {{-- Grace Period Ribbon (Red) --}}
    @if($license->is_grace)
        <div class="bg-red-600 text-white text-center py-2 px-4 animate-pulse relative z-50">
            🚨 Conexiune pierdută cu Master App. Perioadă de grație: {{ $license->grace_days_left }} zile rămase.
            <button onclick="document.getElementById('license-grace-modal').showModal()" class="ml-2 underline font-bold">Detalii</button>
        </div>

        {{-- Grace Modal --}}
        <dialog id="license-grace-modal" class="p-0 rounded-lg shadow-xl backdrop:bg-gray-900/50">
            <div class="p-6 max-w-sm">
                <h3 class="text-lg font-bold text-red-600 mb-2">Perioadă de Grație Activă</h3>
                <p class="text-gray-600 mb-4">
                    Sistemul nu a putut contacta serverul de licențiere. Aveți la dispoziție <strong>{{ $license->grace_days_left }} zile</strong> pentru a restabili conexiunea înainte de blocarea automată.
                </p>
                <div class="flex flex-col gap-2">
                    <form action="{{ route('admin.license.reverify') }}" method="POST">
                        @csrf
                        <x-primary-button class="w-full justify-center bg-red-600 hover:bg-red-700">Re-verifică Acum</x-primary-button>
                    </form>
                    <button onclick="this.closest('dialog').close()" class="text-sm text-gray-500 hover:text-gray-700">Continuă utilizarea</button>
                </div>
            </div>
        </dialog>
        
        <script>
            // Show modal on load if it's a new session
            if (!sessionStorage.getItem('license_grace_notified')) {
                document.getElementById('license-grace-modal').showModal();
                sessionStorage.setItem('license_grace_notified', 'true');
            }
        </script>
    @endif
@endif
