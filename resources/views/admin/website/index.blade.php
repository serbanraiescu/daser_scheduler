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
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold border-b pb-4 mb-6">Configurare Website Public</h3>
                    
                    <form action="{{ route('admin.website.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Branding -->
                            <div class="space-y-6">
                                <h4 class="font-semibold text-indigo-600 text-sm uppercase">Branding & Culori</h4>
                                
                                <div>
                                    <x-input-label for="business_name" :value="__('Nume Business')" />
                                    <x-text-input id="business_name" name="business_name" type="text" class="mt-1 block w-full" :value="old('business_name', $settings->business_name)" />
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="primary_color" :value="__('Culoare Primară')" />
                                        <div class="flex mt-1">
                                            <input type="color" id="primary_color" name="primary_color" value="{{ old('primary_color', $settings->primary_color ?? '#6366f1') }}" class="h-10 w-12 rounded-l-md border-r-0">
                                            <x-text-input id="primary_color_hex" type="text" class="block w-full rounded-l-none" :value="old('primary_color', $settings->primary_color ?? '#6366f1')" onchange="document.getElementById('primary_color').value = this.value" />
                                        </div>
                                    </div>
                                    <div>
                                        <x-input-label for="secondary_color" :value="__('Culoare Secundară')" />
                                        <div class="flex mt-1">
                                            <input type="color" id="secondary_color" name="secondary_color" value="{{ old('secondary_color', $settings->secondary_color ?? '#a855f7') }}" class="h-10 w-12 rounded-l-md border-r-0">
                                            <x-text-input id="secondary_color_hex" type="text" class="block w-full rounded-l-none" :value="old('secondary_color', $settings->secondary_color ?? '#a855f7')" onchange="document.getElementById('secondary_color').value = this.value" />
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <x-input-label for="logo" :value="__('Logo Principal (pentru fundal închis)')" />
                                    @if($settings->logo_url)
                                        <img src="{{ $settings->logo_url }}" alt="Logo" class="h-12 mb-2 bg-gray-900 p-2 rounded">
                                    @endif
                                    <input type="file" name="logo" id="logo" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                                </div>

                                <div>
                                    <x-input-label for="logo_alt" :value="__('Logo Alternativ (pentru fundal deschis/dashboard)')" />
                                    @if($settings->logo_alt_url)
                                        <img src="{{ $settings->logo_alt_url }}" alt="Logo Alt" class="h-12 mb-2 bg-gray-100 p-2 rounded">
                                    @endif
                                    <input type="file" name="logo_alt" id="logo_alt" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                                </div>
                            </div>

                            <!-- Hero Section -->
                            <div class="space-y-6">
                                <h4 class="font-semibold text-indigo-600 text-sm uppercase">Secțiune Hero (Prima Pagină)</h4>
                                
                                <div>
                                    <x-input-label for="hero_title" :value="__('Titlu Hero')" />
                                    <x-text-input id="hero_title" name="hero_title" type="text" class="mt-1 block w-full" :value="old('hero_title', $settings->hero_title)" />
                                </div>

                                <div>
                                    <x-input-label for="hero_subtitle" :value="__('Subtitlu Hero')" />
                                    <textarea id="hero_subtitle" name="hero_subtitle" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="2">{{ old('hero_subtitle', $settings->hero_subtitle) }}</textarea>
                                </div>

                                <div>
                                    <x-input-label for="hero_button_text" :value="__('Text Buton')" />
                                    <x-text-input id="hero_button_text" name="hero_button_text" type="text" class="mt-1 block w-full" :value="old('hero_button_text', $settings->hero_button_text)" />
                                </div>

                                <div>
                                    <x-input-label for="hero" :value="__('Imagine Fundal Hero')" />
                                    @if($settings->hero_image)
                                        <img src="{{ $settings->hero_image }}" alt="Hero" class="h-20 mb-2 w-full object-cover rounded">
                                    @endif
                                    <input type="file" name="hero" id="hero" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                                </div>
                            </div>

                            <!-- About & Contact -->
                            <div class="space-y-6">
                                <h4 class="font-semibold text-indigo-600 text-sm uppercase">Despre & Contact</h4>
                                
                                <div>
                                    <x-input-label for="about_title" :value="__('Titlu Secțiune Despre')" />
                                    <x-text-input id="about_title" name="about_title" type="text" class="mt-1 block w-full" :value="old('about_title', $settings->about_title)" />
                                </div>

                                <div>
                                    <x-input-label for="about_text" :value="__('Text Despre')" />
                                    <textarea id="about_text" name="about_text" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4">{{ old('about_text', $settings->about_text) }}</textarea>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="phone" :value="__('Telefon')" />
                                        <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $settings->phone)" />
                                    </div>
                                    <div>
                                        <x-input-label for="email" :value="__('Email Public')" />
                                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $settings->email)" />
                                    </div>
                                </div>

                                <div>
                                    <x-input-label for="address" :value="__('Adresă Fizică')" />
                                    <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $settings->address)" />
                                </div>
                            </div>

                            <!-- Visibility & SEO -->
                            <div class="space-y-6">
                                <h4 class="font-semibold text-indigo-600 text-sm uppercase">Vizibilitate & SEO</h4>
                                
                                <div class="space-y-4 bg-gray-50 p-4 rounded-xl border border-gray-100">
                                    <label class="flex items-center space-x-3 cursor-pointer">
                                        <input type="checkbox" name="show_services_section" value="1" {{ $settings->show_services_section ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        <span class="text-sm font-medium text-gray-700">Afișează secțiunea Servicii</span>
                                    </label>
                                    <label class="flex items-center space-x-3 cursor-pointer">
                                        <input type="checkbox" name="show_about_section" value="1" {{ $settings->show_about_section ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        <span class="text-sm font-medium text-gray-700">Afișează secțiunea Despre</span>
                                    </label>
                                    <label class="flex items-center space-x-3 cursor-pointer">
                                        <input type="checkbox" name="show_contact_section" value="1" {{ $settings->show_contact_section ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        <span class="text-sm font-medium text-gray-700">Afișează secțiunea Contact</span>
                                    </label>
                                </div>

                                <div>
                                    <x-input-label for="seo_title" :value="__('SEO Title (Pagina Principală)')" />
                                    <x-text-input id="seo_title" name="seo_title" type="text" class="mt-1 block w-full" :value="old('seo_title', $settings->seo_title)" />
                                </div>

                                <div>
                                    <x-input-label for="seo_description" :value="__('SEO Description')" />
                                    <textarea id="seo_description" name="seo_description" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="2">{{ old('seo_description', $settings->seo_description) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t flex justify-end">
                            <x-primary-button>
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
