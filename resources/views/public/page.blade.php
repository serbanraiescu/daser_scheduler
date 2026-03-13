<x-public-layout :settings="$settings" :pagesHeader="$pagesHeader" :pagesFooter="$pagesFooter">
    @section('meta_title', $page->meta_title ?? $page->title . ' - ' . ($settings->business_name ?? 'Daser Scheduler'))
    @section('meta_description', $page->meta_description ?? '')

    <header class="py-24 bg-gray-50 border-b border-gray-100">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-left">
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4">{{ $page->title }}</h1>
            <div class="w-20 h-1.5 bg-[var(--primary-color)] mx-auto rounded-full"></div>
        </div>
    </header>

    <div class="py-24">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 prose prose-lg prose-indigo text-gray-600 leading-relaxed text-left italic">
            {!! $page->content !!}
        </div>
    </div>
</x-public-layout>
