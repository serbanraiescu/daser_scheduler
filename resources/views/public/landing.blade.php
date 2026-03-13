<x-public-layout :settings="$settings" :pagesHeader="$pagesHeader" :pagesFooter="$pagesFooter">
    <!-- Hero Section -->
    <section class="relative h-screen min-h-[700px] flex items-center justify-center overflow-hidden">
        @if($settings->hero_image)
            <img src="{{ $settings->hero_image }}" class="absolute inset-0 w-full h-full object-cover scale-105" alt="Hero">
        @else
            <div class="absolute inset-0 bg-gray-900 group-hover:scale-105 transition-transform duration-700"></div>
        @endif
        
        <!-- Dark Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/50 to-black/70"></div>

        <div class="relative z-10 max-w-5xl mx-auto px-4 text-center">
            <h5 class="text-[var(--secondary-color)] font-black text-xs md:text-sm uppercase tracking-[0.5em] mb-6 animate-fade-in-up">
                {{ $settings->business_name ?? 'Elite Experience' }}
            </h5>
            <h1 class="text-5xl md:text-8xl font-black text-white tracking-tighter leading-none mb-8 animate-fade-in-up delay-100">
                {{ $settings->hero_title ?? 'Stilul tău, viziunea noastră.' }}
            </h1>
            <p class="text-lg md:text-2xl text-gray-300 max-w-2xl mx-auto mb-12 font-medium leading-relaxed animate-fade-in-up delay-200">
                {{ $settings->hero_subtitle ?? 'Redescoperă arta îngrijirii premium într-o atmosferă modernă și relaxantă.' }}
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-6 animate-fade-in-up delay-300">
                <a href="{{ route('bookings.index') }}" class="group relative inline-flex items-center justify-center px-10 py-5 font-black text-white bg-[var(--primary-color)] rounded-full overflow-hidden shadow-2xl shadow-[var(--primary-color)]/30 hover:scale-105 transition-all">
                    <span class="relative z-10 uppercase tracking-widest text-sm">Programează-te acum</span>
                    <div class="absolute inset-0 bg-white/20 -translate-x-full group-hover:translate-x-0 transition-transform duration-500"></div>
                </a>
                <a href="#services" class="inline-flex items-center px-10 py-5 font-bold text-white border-2 border-white/30 rounded-full hover:bg-white hover:text-black transition-all">
                    <span class="uppercase tracking-widest text-sm">Vezi Servicii</span>
                </a>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-10 left-1/2 -translate-x-1/2 hidden md:block animate-bounce">
            <svg class="w-6 h-6 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
        </div>
    </section>

    <!-- Services Section -->
    @if($settings->show_services_section && count($services) > 0)
    <section id="services" class="py-[80px] bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-6xl font-black text-gray-900 tracking-tighter mb-4 italic uppercase">Servicii de Top</h2>
                <div class="w-20 h-1.5 bg-[var(--secondary-color)] mx-auto rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($services as $service)
                    <div class="group p-8 rounded-[24px] bg-gray-50 border border-gray-100 card-hover flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start mb-6">
                                <div class="w-14 h-14 rounded-2xl bg-[var(--primary-color)] flex items-center justify-center shadow-lg shadow-[var(--primary-color)]/20">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758L5 19m0-14l4.121 4.121"/></svg>
                                </div>
                                <span class="bg-[var(--secondary-color)]/10 text-[var(--secondary-color)] px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-widest">
                                    {{ $service->duration_minutes }} min
                                </span>
                            </div>
                            <h3 class="text-2xl font-black text-gray-900 mb-3 group-hover:text-[var(--primary-color)] transition-colors tracking-tight">{{ $service->name }}</h3>
                            <p class="text-gray-500 mb-8 leading-relaxed font-medium">
                                {{ $service->description ?? 'Experimentează un serviciu premium realizat de experții noștri dedicați calității.' }}
                            </p>
                        </div>
                        <div class="flex items-center justify-between mt-auto pt-6 border-t border-gray-100">
                            <span class="text-3xl font-black text-gray-900 leading-none">{{ number_format($service->price, 0) }} RON</span>
                            <a href="{{ route('bookings.index') }}" class="w-12 h-12 rounded-full bg-white border border-gray-200 flex items-center justify-center group-hover:bg-[var(--primary-color)] group-hover:border-[var(--primary-color)] transition-all">
                                <svg class="w-5 h-5 text-gray-900 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Team Section -->
    @if(count($employees) > 0)
    <section id="team" class="py-[100px] bg-gray-900 text-white overflow-hidden relative">
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-[var(--primary-color)]/20 rounded-full blur-[120px]"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col md:flex-row items-end justify-between mb-20 gap-8">
                <div class="max-w-xl">
                    <h2 class="text-4xl md:text-7xl font-black tracking-tighter italic uppercase leading-none mb-6">Maeștrii Stilizării</h2>
                    <p class="text-xl text-gray-400 font-medium">O echipă de profesioniști pregătiți să transforme fiecare programare într-o operă de artă.</p>
                </div>
                <div class="pb-2">
                    <a href="{{ route('bookings.index') }}" class="inline-flex items-center text-[var(--secondary-color)] font-black uppercase tracking-[0.2em] group border-b-2 border-transparent hover:border-[var(--secondary-color)] pb-2 transition-all">
                        Rezervă cu expertul tău <svg class="w-5 h-5 ml-2 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 md:gap-10">
                @foreach($employees as $employee)
                    <div class="group relative">
                        <div class="aspect-[3/4] rounded-[32px] overflow-hidden bg-gray-800 mb-6 group-hover:rotate-1 transition-transform duration-500">
                            <!-- Image Placeholder -->
                            <div class="w-full h-full flex items-center justify-center text-[var(--secondary-color)] bg-gradient-to-tr from-gray-950 to-gray-800">
                                <svg class="w-20 h-20 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                        </div>
                        <h4 class="text-2xl font-black tracking-tight text-white mb-1 leading-none">{{ $employee->user->name }}</h4>
                        <p class="text-[var(--secondary-color)] uppercase text-xs font-black tracking-widest">{{ $employee->role ?? 'Expert' }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Gallery Section -->
    <section class="py-[80px] bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-6xl font-black text-gray-900 tracking-tighter mb-4 italic uppercase">Portofoliu Vizual</h2>
                <p class="text-gray-500 font-bold uppercase tracking-widest text-sm">Rezultate care vorbesc de la sine</p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8">
                @for($i = 1; $i <= 4; $i++)
                    <div class="group relative aspect-square overflow-hidden rounded-[24px] bg-gray-100">
                        <div class="absolute inset-0 bg-gray-800 flex items-center justify-center">
                            <svg class="w-10 h-10 text-white/10" fill="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div class="absolute inset-0 bg-[var(--primary-color)]/80 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-sm">
                            <span class="text-white font-black uppercase tracking-widest text-sm">Zoom View</span>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </section>

    <!-- Reviews Section -->
    <section class="py-[100px] bg-gray-50 border-y border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-6xl font-black text-gray-900 tracking-tighter mb-4 italic uppercase italic leading-none">Clienți Mulțumiți</h2>
                <div class="flex justify-center gap-1 text-[var(--secondary-color)]">
                    @for($i = 0; $i < 5; $i++)
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @php
                    $reviews = [
                        ['name' => 'Adrian Popescu', 'text' => 'Am găsit aici cei mai buni experți. Atmosfera este relaxantă și rezultatul a fost exact ce mi-am dorit.'],
                        ['name' => 'Andreea Ionescu', 'text' => 'Programarea online funcționează impecabil. Am salvat timp prețios și am plecat mulțumită extrem de repede.'],
                        ['name' => 'Marius Georgescu', 'text' => 'Atenție extraordinară la detalii. Un salon premium unde calitatea chiar este pusă pe primul loc.'],
                    ];
                @endphp
                @foreach($reviews as $review)
                    <div class="p-10 rounded-[32px] bg-white shadow-sm border border-gray-100 flex flex-col justify-between italic">
                        <p class="text-xl text-gray-600 leading-relaxed font-medium mb-8">"{{ $review['text'] }}"</p>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-gray-200 uppercase flex items-center justify-center font-black text-gray-500 text-xs">{{ substr($review['name'], 0, 1) }}</div>
                            <div class="font-black text-gray-900 tracking-tight">{{ $review['name'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    @if($settings->show_contact_section)
    <section id="contact" class="py-[80px] bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div>
                    <h2 class="text-5xl md:text-7xl font-black text-gray-900 tracking-tighter italic uppercase leading-tight mb-8">Te Așteptăm în Salon</h2>
                    <ul class="space-y-8 mb-12">
                        <li class="flex items-start">
                            <div class="w-14 h-14 rounded-2xl bg-[var(--primary-color)] flex items-center justify-center mr-6 shrink-0 shadow-lg shadow-[var(--primary-color)]/20">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <span class="text-xs font-black text-[var(--secondary-color)] uppercase tracking-widest mb-1 block">Locație Premium</span>
                                <span class="text-2xl font-bold text-gray-900 leading-tight block">{{ $settings->address ?? 'Strada Victoriei nr. 10, București' }}</span>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <div class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center mr-6 shrink-0 border border-gray-100">
                                <svg class="w-7 h-7 text-[var(--primary-color)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </div>
                            <div>
                                <span class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1 block">Contact Rapid</span>
                                <span class="text-2xl font-bold text-gray-900 leading-tight block">{{ $settings->phone ?? '+40 722 000 000' }}</span>
                            </div>
                        </li>
                    </ul>
                    <div class="flex items-center gap-6">
                        @if($settings->phone)
                            <a href="tel:{{ $settings->phone }}" class="inline-flex items-center px-8 py-4 border-2 border-gray-900 rounded-full text-sm font-black uppercase tracking-widest hover:bg-gray-900 hover:text-white transition-all">
                                Sună Acum
                            </a>
                        @endif
                        <a href="{{ route('bookings.index') }}" class="inline-flex items-center text-gray-900 font-black uppercase tracking-widest group">
                            Rezervă Online <svg class="w-5 h-5 ml-2 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                </div>
                <div class="rounded-[40px] overflow-hidden shadow-2xl h-[500px] border-8 border-white bg-gray-100">
                    @if($settings->map_embed_url)
                        <iframe src="{{ $settings->map_embed_url }}" class="w-full h-full grayscale hover:grayscale-0 transition-all duration-700" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    @else
                       <div class="w-full h-full flex items-center justify-center bg-gray-50 italic text-gray-400 font-bold p-10 text-center">
                           (Vă rugăm să configurați URL-ul Google Maps în Admin Settings)
                       </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    @endif
</x-public-layout>
