<x-public-layout :settings="$settings" :pagesHeader="$pagesHeader" :pagesFooter="$pagesFooter">
    <!-- Hero Section -->
    <section class="relative h-[85vh] flex items-center overflow-hidden">
        <div class="absolute inset-0 z-0">
            @if($settings->hero_image)
                <img src="{{ $settings->hero_image }}" class="w-full h-full object-cover" alt="Hero">
                <div class="absolute inset-0 bg-black/40"></div>
            @else
                <div class="absolute inset-0 bg-gradient-to-br from-[var(--primary-color)] to-[var(--secondary-color)] opacity-90"></div>
            @endif
        </div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-white">
            <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight mb-6">
                {{ $settings->hero_title ?? 'Bun venit la ' . ($settings->business_name ?? 'Daser Scheduler') }}
            </h1>
            <p class="text-xl md:text-2xl text-white/90 max-w-2xl mb-10 leading-relaxed italic">
                {{ $settings->hero_subtitle ?? 'Excelează prin stil. Descoperă experiența unei programări rapide și a unor servicii impecabile.' }}
            </p>
            <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-6">
                <a href="{{ route('bookings.index') }}" class="inline-flex justify-center items-center px-8 py-4 bg-white text-[var(--primary-color)] text-lg font-bold rounded-2xl hover:bg-gray-100 transition shadow-2xl">
                    {{ $settings->hero_button_text ?? 'Programează-te acum' }}
                </a>
                @if($settings->show_about_section)
                    <a href="#about" class="inline-flex justify-center items-center px-8 py-4 border-2 border-white/30 backdrop-blur-sm text-white text-lg font-bold rounded-2xl hover:bg-white/10 transition">
                        Află mai multe
                    </a>
                @endif
            </div>
        </div>
    </section>

    <!-- Services Section -->
    @if($settings->show_services_section)
    <section id="services" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4">Serviciile Noastre</h2>
                <div class="w-20 h-1.5 bg-[var(--primary-color)] mx-auto rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 text-left">
                @foreach($services as $service)
                    <div class="group p-8 bg-gray-50 rounded-3xl border border-gray-100 hover:border-[var(--primary-color)]/30 hover:bg-white hover:shadow-2xl hover:shadow-[var(--primary-color)]/10 transition-all duration-300">
                        <div class="flex justify-between items-start mb-6">
                            <h3 class="text-xl font-bold text-gray-900">{{ $service->name }}</h3>
                            <span class="text-2xl font-black text-[var(--primary-color)]">{{ number_format($service->price, 0) }} RON</span>
                        </div>
                        <p class="text-gray-500 mb-8 line-clamp-2">
                            {{ $service->description ?? 'Experimentează calitatea superioară a serviciilor noastre într-un cadru modern și relaxant.' }}
                        </p>
                        <div class="flex items-center text-sm font-bold text-gray-400">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $service->duration }} min
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- About Section -->
    @if($settings->show_about_section)
    <section id="about" class="py-24 bg-gray-50 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row items-center gap-16">
                <div class="lg:w-1/2">
                    <h2 class="text-3xl md:text-5xl font-extrabold text-gray-900 mb-8 leading-tight">
                        {{ $settings->about_title ?? 'Povestea Noastră & Viziunea' }}
                    </h2>
                    <div class="prose prose-lg text-gray-500 leading-relaxed italic">
                        {!! nl2br(e($settings->about_text ?? 'Suntem dedicați excelenței în fiecare detaliu. Salonul nostru a fost creat din pasiunea pentru frumos și dorința de a oferi fiecărui client o experiență personalizată și memorabilă. Folosim tehnici de ultimă generație și produse premium pentru a-ți garanta rezultate de excepție.')) !!}
                    </div>
                </div>
                <div class="lg:w-1/2 relative">
                    <div class="w-full aspect-square bg-[var(--primary-color)] rounded-[3rem] rotate-3 absolute -z-10 opacity-10"></div>
                    @if($settings->hero_image)
                        <img src="{{ $settings->hero_image }}" class="w-full h-auto rounded-[3rem] shadow-2xl -rotate-2 hover:rotate-0 transition duration-500 border-8 border-white" alt="About">
                    @else
                        <div class="w-full aspect-square bg-gradient-to-tr from-gray-200 to-gray-300 rounded-[3rem] shadow-2xl -rotate-2"></div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Contact Section -->
    @if($settings->show_contact_section)
    <section id="contact" class="py-24 bg-white text-left">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div>
                    <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-8">Unde ne găsești?</h2>
                    <div class="space-y-8">
                        @if($settings->address)
                        <div class="flex items-start">
                            <div class="flex-shrink-0 bg-indigo-50 p-3 rounded-2xl text-[var(--primary-color)]">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-bold text-gray-900">Adresă</h4>
                                <p class="text-gray-500">{{ $settings->address }}</p>
                            </div>
                        </div>
                        @endif

                        @if($settings->phone)
                        <div class="flex items-start">
                            <div class="flex-shrink-0 bg-indigo-50 p-3 rounded-2xl text-[var(--primary-color)]">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-bold text-gray-900">Telefon</h4>
                                <p class="text-gray-500">{{ $settings->phone }}</p>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="mt-12 p-8 bg-indigo-900 rounded-[2.5rem] text-white">
                        <h3 class="text-2xl font-bold mb-4">Ești gata pentru o schimbare?</h3>
                        <p class="text-indigo-200 mb-8 italic">Rezervă-ți locul acum și lasă experții noștri să aibă grijă de tine.</p>
                        <a href="{{ route('bookings.index') }}" class="inline-flex items-center px-8 py-4 bg-white text-indigo-900 font-bold rounded-2xl hover:bg-gray-100 transition shadow-xl">
                            Programare Rapidă
                        </a>
                    </div>
                </div>

                <div class="h-[400px] lg:h-[600px] rounded-[3rem] overflow-hidden border-8 border-gray-50 shadow-2xl">
                    @if($settings->map_embed_url)
                        {!! $settings->map_embed_url !!}
                    @else
                        <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-400 italic">
                            Harta nu este configurată.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    @endif
</x-public-layout>
