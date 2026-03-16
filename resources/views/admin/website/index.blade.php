<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Administrare Website') }}
            </h2>
            <a href="{{ route('admin.website.pages.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition ease-in-out duration-150">
                Adaugă Pagină Nouă
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Setări Website -->
                <div class="p-6 text-gray-900" x-data="{ tab: 'branding' }">
                    <h3 class="text-xl font-black border-b pb-4 mb-6 flex items-center gap-3">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9-9H3m9 9L3 3m9 9V3"/></svg>
                        Personalizare Website Public
                    </h3>

                    <!-- Tab Navigation -->
                    <div class="flex flex-wrap gap-2 mb-8 border-b border-gray-100 pb-2">
                        <button @click="tab = 'branding'" :class="tab === 'branding' ? 'bg-indigo-600 text-white shadow-lg' : 'bg-gray-50 text-gray-600 hover:bg-gray-100'" class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all uppercase tracking-wider">Branding & Logo</button>
                        <button @click="tab = 'content'" :class="tab === 'content' ? 'bg-indigo-600 text-white shadow-lg' : 'bg-gray-50 text-gray-600 hover:bg-gray-100'" class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all uppercase tracking-wider">Conținut Secțiuni</button>
                        <button @click="tab = 'styling'" :class="tab === 'styling' ? 'bg-indigo-600 text-white shadow-lg' : 'bg-gray-50 text-gray-600 hover:bg-gray-100'" class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all uppercase tracking-wider">Stilizare & Layout</button>
                        <button @click="tab = 'seo'" :class="tab === 'seo' ? 'bg-indigo-600 text-white shadow-lg' : 'bg-gray-50 text-gray-600 hover:bg-gray-100'" class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all uppercase tracking-wider">SEO & Vizibilitate</button>
                    </div>
                    
                    <form action="{{ route('admin.website.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <!-- Tab: Branding -->
                        <div x-show="tab === 'branding'" x-transition class="space-y-8 animate-fade-in">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-6">
                                    <h4 class="font-bold text-gray-900 flex items-center gap-2">
                                        <div class="w-2 h-6 bg-indigo-600 rounded-full"></div> Identitate Vizuală
                                    </h4>
                                    <div>
                                        <x-input-label for="business_name" :value="__('Nume Business')" />
                                        <x-text-input id="business_name" name="business_name" type="text" class="mt-1 block w-full" :value="old('business_name', $settings->business_name)" />
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <x-input-label for="primary_color" :value="__('Culoare Primară')" />
                                            <div class="flex mt-1">
                                                <input type="color" id="primary_color" name="primary_color" value="{{ old('primary_color', $settings->primary_color ?? '#1a1a1a') }}" class="h-10 w-12 rounded-l-xl border-r-0 cursor-pointer">
                                                <x-text-input id="primary_color_hex" type="text" class="block w-full rounded-l-none" :value="old('primary_color', $settings->primary_color ?? '#1a1a1a')" onchange="document.getElementById('primary_color').value = this.value" />
                                            </div>
                                        </div>
                                        <div>
                                            <x-input-label for="secondary_color" :value="__('Culoare Secundară')" />
                                            <div class="flex mt-1">
                                                <input type="color" id="secondary_color" name="secondary_color" value="{{ old('secondary_color', $settings->secondary_color ?? '#d4af37') }}" class="h-10 w-12 rounded-l-xl border-r-0 cursor-pointer">
                                                <x-text-input id="secondary_color_hex" type="text" class="block w-full rounded-l-none" :value="old('secondary_color', $settings->secondary_color ?? '#d4af37')" onchange="document.getElementById('secondary_color').value = this.value" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="space-y-6">
                                    <h4 class="font-bold text-gray-900 flex items-center gap-2">
                                        <div class="w-2 h-6 bg-indigo-600 rounded-full"></div> Logotipuri
                                    </h4>
                                    <div class="grid grid-cols-1 gap-6">
                                        <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                            <x-input-label for="logo" :value="__('Logo Principal (pentru fundal negru/transparent)')" class="mb-2" />
                                            @if($settings->logo_url)
                                                <img src="{{ $settings->logo_url }}" alt="Logo" class="h-12 mb-4 bg-gray-900 p-2 rounded-xl">
                                            @endif
                                            <input type="file" name="logo" id="logo" class="text-xs file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-indigo-600 file:text-white" />
                                        </div>
                                        <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                            <x-input-label for="logo_alt" :value="__('Logo Alternativ (pentru fundal alb/scrolled)')" class="mb-2" />
                                            @if($settings->logo_alt_url)
                                                <img src="{{ $settings->logo_alt_url }}" alt="Logo Alt" class="h-12 mb-4 bg-white p-2 rounded-xl border">
                                            @endif
                                            <input type="file" name="logo_alt" id="logo_alt" class="text-xs file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-indigo-600 file:text-white" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab: Content -->
                        <div x-show="tab === 'content'" x-transition class="space-y-10 animate-fade-in" style="display:none">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                                <!-- Hero Content -->
                                <div class="space-y-6 p-6 bg-indigo-50/30 rounded-[32px] border border-indigo-100/50">
                                    <h4 class="font-black text-indigo-900 uppercase tracking-widest text-xs flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg> 
                                        Secțiunea Hero (Prima pagină)
                                    </h4>
                                    <div>
                                        <x-input-label for="hero_title" :value="__('Titlu Principal')" />
                                        <x-text-input id="hero_title" name="hero_title" type="text" class="mt-1 block w-full" :value="old('hero_title', $settings->hero_title)" />
                                    </div>
                                    <div>
                                        <x-input-label for="hero_subtitle" :value="__('Subtitlu / Descriere')" />
                                        <textarea id="hero_subtitle" name="hero_subtitle" class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:ring-indigo-500" rows="3">{{ old('hero_subtitle', $settings->hero_subtitle) }}</textarea>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <x-input-label for="hero_button_text" :value="__('Text Buton CTA')" />
                                            <x-text-input id="hero_button_text" name="hero_button_text" type="text" class="mt-1 block w-full" :value="old('hero_button_text', $settings->hero_button_text)" />
                                        </div>
                                        <div>
                                            <x-input-label for="hero" :value="__('Imagine Fundal')" />
                                            <input type="file" name="hero" id="hero" class="mt-1 block w-full text-xs text-gray-500 file:rounded-full file:bg-indigo-600 file:text-white file:border-0 file:px-4 file:py-1.5" />
                                        </div>
                                    </div>
                                </div>

                                <!-- Services Content -->
                                <div class="space-y-6 p-6 bg-gray-50 rounded-[32px] border border-gray-100">
                                    <h4 class="font-black text-gray-700 uppercase tracking-widest text-xs flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/></svg>
                                        Secțiunea Servicii
                                    </h4>
                                    <div>
                                        <x-input-label for="services_title" :value="__('Titlu Secțiune')" />
                                        <x-text-input id="services_title" name="services_title" type="text" class="mt-1 block w-full" :value="old('services_title', $settings->services_title ?? 'Servicii de Top')" />
                                    </div>
                                    <div>
                                        <x-input-label for="services_subtitle" :value="__('Subtitlu (Optional)')" />
                                        <x-text-input id="services_subtitle" name="services_subtitle" type="text" class="mt-1 block w-full" :value="old('services_subtitle', $settings->services_subtitle)" />
                                    </div>
                                </div>

                                <!-- Team Content -->
                                <div class="space-y-6 p-6 bg-gray-50 rounded-[32px] border border-gray-100">
                                    <h4 class="font-black text-gray-700 uppercase tracking-widest text-xs flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/></svg>
                                        Secțiunea Echipa
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <x-input-label for="team_title" :value="__('Titlu Secțiune')" />
                                            <x-text-input id="team_title" name="team_title" type="text" class="mt-1 block w-full" :value="old('team_title', $settings->team_title ?? 'Maeștrii Stilizării')" />
                                        </div>
                                        <div>
                                            <x-input-label for="team_reservation_text" :value="__('Text Link Rezervare')" />
                                            <x-text-input id="team_reservation_text" name="team_reservation_text" type="text" class="mt-1 block w-full" :value="old('team_reservation_text', $settings->team_reservation_text ?? 'Rezervă cu expertul tău')" />
                                        </div>
                                    </div>
                                    <div>
                                        <x-input-label for="team_subtitle" :value="__('Descriere Manager/Echipa')" />
                                        <textarea id="team_subtitle" name="team_subtitle" class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:ring-indigo-500" rows="2">{{ old('team_subtitle', $settings->team_subtitle ?? 'O echipă de profesioniști pregătiți să transforme fiecare programare într-o operă de artă.') }}</textarea>
                                    </div>
                                </div>

                                <!-- Contact Content -->
                                <div class="space-y-6 p-6 bg-gray-50 rounded-[32px] border border-gray-100">
                                    <h4 class="font-black text-gray-700 uppercase tracking-widest text-xs flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>
                                        Secțiunea Contact
                                    </h4>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <x-input-label for="contact_title" :value="__('Titlu Secțiune')" />
                                            <x-text-input id="contact_title" name="contact_title" type="text" class="mt-1 block w-full" :value="old('contact_title', $settings->contact_title ?? 'Te Așteptăm în Salon')" />
                                        </div>
                                        <div>
                                            <x-input-label for="phone" :value="__('Telefon Public')" />
                                            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $settings->phone)" />
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <x-input-label for="contact_label_location" :value="__('Etichetă Locație')" />
                                            <x-text-input id="contact_label_location" name="contact_label_location" type="text" class="mt-1 block w-full" :value="old('contact_label_location', $settings->contact_label_location ?? 'Locație Premium')" />
                                        </div>
                                        <div>
                                            <x-input-label for="contact_label_phone" :value="__('Etichetă Telefon')" />
                                            <x-text-input id="contact_label_phone" name="contact_label_phone" type="text" class="mt-1 block w-full" :value="old('contact_label_phone', $settings->contact_label_phone ?? 'Contact Rapid')" />
                                        </div>
                                    </div>
                                    <div>
                                        <x-input-label for="address" :value="__('Adresă Fizică Full')" />
                                        <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $settings->address)" />
                                    </div>
                                    <div>
                                        <x-input-label for="map_embed_url" :value="__('Google Maps Embed URL (Iframe Src)')" />
                                        <textarea id="map_embed_url" name="map_embed_url" class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm text-xs font-mono" rows="2">{{ old('map_embed_url', $settings->map_embed_url) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab: Styling -->
                        <div x-show="tab === 'styling'" x-transition class="space-y-10 animate-fade-in" style="display:none">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                <div class="space-y-6">
                                    <h4 class="font-bold text-gray-900 border-b pb-2">Layout & Margini</h4>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <x-input-label for="border_radius" :value="__('Rotunjire Elemente (Border Radius)')" />
                                            <x-text-input id="border_radius" name="border_radius" type="text" class="mt-1 block w-full" placeholder="1.5rem" :value="old('border_radius', $settings->border_radius ?? '1.5rem')" />
                                        </div>
                                        <div>
                                            <x-input-label for="section_padding" :value="__('Spațiere Secțiuni (Padding)')" />
                                            <x-text-input id="section_padding" name="section_padding" type="text" class="mt-1 block w-full" placeholder="80px" :value="old('section_padding', $settings->section_padding ?? '80px')" />
                                        </div>
                                    </div>
                                </div>
                                <div class="space-y-6">
                                    <h4 class="font-bold text-gray-900 border-b pb-2">Tipografie (Google Fonts)</h4>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <x-input-label for="primary_font" :value="__('Font Principal')" />
                                            <select id="primary_font" name="primary_font" class="mt-1 block w-full border-gray-300 rounded-xl">
                                                <option value="Inter" {{ (old('primary_font', $settings->primary_font) == 'Inter') ? 'selected' : '' }}>Inter</option>
                                                <option value="Instrument Sans" {{ (old('primary_font', $settings->primary_font) == 'Instrument Sans') ? 'selected' : '' }}>Instrument Sans</option>
                                                <option value="Montserrat" {{ (old('primary_font', $settings->primary_font) == 'Montserrat') ? 'selected' : '' }}>Montserrat</option>
                                                <option value="Poppins" {{ (old('primary_font', $settings->primary_font) == 'Poppins') ? 'selected' : '' }}>Poppins</option>
                                                <option value="Roboto" {{ (old('primary_font', $settings->primary_font) == 'Roboto') ? 'selected' : '' }}>Roboto</option>
                                            </select>
                                        </div>
                                        <div>
                                            <x-input-label for="secondary_font" :value="__('Font Accentuit')" />
                                            <select id="secondary_font" name="secondary_font" class="mt-1 block w-full border-gray-300 rounded-xl">
                                                <option value="Inter" {{ (old('secondary_font', $settings->secondary_font) == 'Inter') ? 'selected' : '' }}>Inter</option>
                                                <option value="Playfair Display" {{ (old('secondary_font', $settings->secondary_font) == 'Playfair Display') ? 'selected' : '' }}>Playfair Display</option>
                                                <option value="Montserrat" {{ (old('secondary_font', $settings->secondary_font) == 'Montserrat') ? 'selected' : '' }}>Montserrat</option>
                                                <option value="Bebas Neue" {{ (old('secondary_font', $settings->secondary_font) == 'Bebas Neue') ? 'selected' : '' }}>Bebas Neue</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <h4 class="font-bold text-red-600 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                    Custom CSS (Advanced)
                                </h4>
                                <p class="text-xs text-gray-500">Injectează cod CSS direct în pagină pentru modificări de finețe.</p>
                                <textarea id="custom_css" name="custom_css" class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm text-sm font-mono bg-gray-900 text-green-400 p-4" rows="8">{{ old('custom_css', $settings->custom_css) }}</textarea>
                            </div>
                        </div>

                        <!-- Tab: SEO & Visibility -->
                        <div x-show="tab === 'seo'" x-transition class="space-y-10 animate-fade-in" style="display:none">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                <div class="space-y-6">
                                    <h4 class="font-bold text-indigo-600 uppercase text-xs tracking-widest">Vizibilitate Secțiuni</h4>
                                    <div class="grid grid-cols-1 gap-3">
                                        @foreach(['show_services_section' => 'Secțiune Servicii', 'show_about_section' => 'Secțiune Despre', 'show_contact_section' => 'Secțiune Contact', 'show_team_section' => 'Secțiune Echipă', 'show_gallery_section' => 'Secțiune Galerie', 'show_reviews_section' => 'Secțiune Recenzii'] as $field => $label)
                                            <label class="flex items-center p-3 bg-gray-50 rounded-xl border border-gray-100 cursor-pointer hover:bg-white hover:shadow-sm transition-all group">
                                                <input type="checkbox" name="{{ $field }}" value="1" {{ $settings->$field ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                <span class="ml-3 text-sm font-bold text-gray-700 group-hover:text-indigo-600">{{ $label }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="space-y-6">
                                    <h4 class="font-bold text-indigo-600 uppercase text-xs tracking-widest">Metadata SEO</h4>
                                    <div>
                                        <x-input-label for="seo_title" :value="__('Browser Tab Title (SEO)')" />
                                        <x-text-input id="seo_title" name="seo_title" type="text" class="mt-1 block w-full" :value="old('seo_title', $settings->seo_title)" placeholder="Nume Salon - Servicii Premium" />
                                    </div>
                                    <div>
                                        <x-input-label for="seo_description" :value="__('SEO Description')" />
                                        <textarea id="seo_description" name="seo_description" class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm" rows="4">{{ old('seo_description', $settings->seo_description) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-12 pt-8 border-t flex justify-between items-center bg-gray-50 -mx-6 -mb-6 p-6 rounded-b-xl">
                            <p class="text-xs text-gray-500 italic max-w-md">⚠️ Toate modificările vor fi aplicate instantaneu pe pagina publică după salvare.</p>
                            <x-primary-button class="bg-indigo-600 hover:bg-indigo-700 shadow-xl shadow-indigo-200">
                                {{ __('Salvează Toate Modificările') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Pagini Personalizate -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold border-b pb-4 mb-6">Pagini Personalizate</h3>
                    
                    <div class="relative overflow-x-auto rounded-xl border">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Titlu Pagină</th>
                                    <th scope="col" class="px-6 py-3">URL (Slug)</th>
                                    <th scope="col" class="px-6 py-3">Locație Meniu</th>
                                    <th scope="col" class="px-6 py-3">Status</th>
                                    <th scope="col" class="px-6 py-3 text-right">Acțiuni</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pages as $page)
                                    <tr class="bg-white border-b hover:bg-gray-50 transition">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                            {{ $page->title }}
                                        </th>
                                        <td class="px-6 py-4 font-mono text-xs">
                                            /page/{{ $page->slug }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex space-x-2">
                                                @if($page->show_in_header)
                                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-[10px] font-bold uppercase">Header</span>
                                                @endif
                                                @if($page->show_in_footer)
                                                    <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded text-[10px] font-bold uppercase">Footer</span>
                                                @endif
                                                @if(!$page->show_in_header && !$page->show_in_footer)
                                                    <span class="text-gray-400 italic">Ascunsă</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($page->status === 'published')
                                                <span class="flex items-center text-green-600 font-semibold">
                                                    <span class="w-2 h-2 bg-green-500 rounded-full me-2"></span> Publicată
                                                </span>
                                            @else
                                                <span class="flex items-center text-gray-400 font-semibold">
                                                    <span class="w-2 h-2 bg-gray-300 rounded-full me-2"></span> Schiță
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex justify-end space-x-3">
                                                <a href="{{ route('admin.website.pages.edit', $page) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Editează</a>
                                                <form action="{{ route('admin.website.pages.destroy', $page) }}" method="POST" onsubmit="return confirm('Sigur vrei să ștergi această pagină?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 font-medium">Șterge</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-10 text-center text-gray-400 italic">
                                            Nu ai creat nicio pagină încă.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
