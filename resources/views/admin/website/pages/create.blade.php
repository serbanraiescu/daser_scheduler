<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Creare Pagină Nouă') }}
            </h2>
            <a href="{{ route('admin.website.index') }}" class="text-gray-500 hover:text-gray-700 flex items-center">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Înapoi
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 text-left">
                    <form action="{{ route('admin.website.pages.store') }}" method="POST">
                        @csrf

                        <div class="space-y-6">
                            <!-- Titlu & Status -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="md:col-span-2">
                                    <x-input-label for="title" :value="__('Titlu Pagină')" />
                                    <x-text-input id="title" name="title" type="text" class="mt-1 block w-full text-lg font-bold" :value="old('title')" required placeholder="Ex: Termeni și Condiții" />
                                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="status" :value="__('Status')" />
                                    <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Publicată</option>
                                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Schiță (Offline)</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Continut -->
                            <div>
                                <x-input-label for="content" :value="__('Conținut (HTML permis)')" />
                                <p class="text-xs text-gray-400 mb-2 font-mono">Poți folosi tag-uri HTML precum &lt;p&gt;, &lt;b&gt;, &lt;ul&gt;, etc.</p>
                                <textarea id="content" name="content" rows="12" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm font-mono text-sm">{{ old('content') }}</textarea>
                                <x-input-error :messages="$errors->get('content')" class="mt-2" />
                            </div>

                            <hr>

                            <!-- SEO & Meniu -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-4">
                                    <h4 class="font-bold text-gray-700">Setări SEO</h4>
                                    <div>
                                        <x-input-label for="meta_title" :value="__('Meta Title (Opțional)')" />
                                        <x-text-input id="meta_title" name="meta_title" type="text" class="mt-1 block w-full" :value="old('meta_title')" />
                                    </div>
                                    <div>
                                        <x-input-label for="meta_description" :value="__('Meta Description')" />
                                        <textarea id="meta_description" name="meta_description" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3">{{ old('meta_description') }}</textarea>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <h4 class="font-bold text-gray-700">Locație în Site</h4>
                                    <div class="space-y-3 bg-gray-50 p-4 rounded-xl border border-gray-100">
                                        <label class="flex items-center space-x-3 cursor-pointer">
                                            <input type="checkbox" name="show_in_header" value="1" {{ old('show_in_header') ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                            <span class="text-sm font-medium text-gray-700">Afișează în meniul de sus (Header)</span>
                                        </label>
                                        <label class="flex items-center space-x-3 cursor-pointer">
                                            <input type="checkbox" name="show_in_footer" value="1" {{ old('show_in_footer') ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                            <span class="text-sm font-medium text-gray-700">Afișează în subsol (Footer)</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-10 pt-6 border-t flex justify-end">
                            <x-primary-button>
                                {{ __('Creează Pagina') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
