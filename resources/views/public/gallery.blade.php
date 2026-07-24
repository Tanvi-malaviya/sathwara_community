@extends('layouts.public')

@section('content')
@include('partials.page_header', [
    'title' => __('messages.gallery'),
    'subtitle' => __('messages.gallery_subtitle'),
    'breadcrumb' => __('messages.gallery')
])

<!-- Gallery Section -->
<section class="py-6 bg-transparent" x-data="{ 
    activeTab: '{{ request()->has('events_page') ? 'events' : 'general' }}',
    activeEventId: null,
    activeEventTitle: '',
    activeEventDate: '',
    lightbox: false,
    lightboxSrc: '',
    lightboxCaption: '',
    selectEvent(id, title, date) {
        this.activeEventId = id;
        this.activeEventTitle = title;
        this.activeEventDate = date;
    },
    openLightbox(src, caption) {
        this.lightboxSrc = src;
        this.lightboxCaption = caption;
        this.lightbox = true;
    }
}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Search bar -->
        {{-- <div class="mb-6 max-w-md mx-auto">
            <form method="GET" action="{{ route('gallery') }}" class="flex items-center gap-2">
                <div class="relative flex-grow">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('messages.search_gallery_placeholder') }}" 
                           class="text-xs font-semibold pl-10 pr-4 py-2.5 w-full bg-white border border-slate-200 rounded-2xl focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-colors shadow-xs">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    @if(request()->filled('search'))
                        <a href="{{ route('gallery') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-rose-500 font-extrabold text-sm" title="Clear search">
                            &times;
                        </a>
                    @endif
                </div>
                <button type="submit" class="px-4 py-2.5 bg-primary-500 hover:bg-primary-600 text-white font-bold text-xs rounded-2xl shadow-xs transition-colors shrink-0">
                    {{ __('messages.search') }}
                </button>
            </form>
        </div> --}}

        <!-- Tabs selector (Segmented Toggle Pill) -->
        <div class="flex justify-center mb-4">
            <div class="inline-flex p-1 bg-white rounded-2xl border border-slate-200/60 shadow-xs">
                <button @click="activeTab = 'general'; activeEventId = null" 
                        :class="activeTab === 'general' ? 'bg-primary-500 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                        class="px-5 py-2 rounded-xl font-bold text-xs transition-all duration-300">
                    {{ __('messages.general_gallery_btn') }}
                </button>
                <button @click="activeTab = 'events'" 
                        :class="activeTab === 'events' ? 'bg-primary-500 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                        class="px-5 py-2 rounded-xl font-bold text-xs transition-all duration-300">
                    {{ __('messages.event_galleries_btn') }}
                </button>
            </div>
        </div>

        <!-- General Gallery Grid (Modern Polaroid Art Cards) -->
        <div x-show="activeTab === 'general'" class="space-y-8">
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                @forelse($generalImages as $photo)
                    <div class="bg-white rounded-2xl border border-slate-200/60 p-2 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 cursor-pointer flex flex-col group overflow-hidden"
                         @click="openLightbox('{{ str_starts_with($photo->image_path, 'http') ? $photo->image_path : asset('storage/' . $photo->image_path) }}', '{{ $photo->caption }}')">
                        <div class="relative overflow-hidden rounded-xl bg-slate-50" style="aspect-ratio: 4/3;">
                            <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" 
                                 src="{{ str_starts_with($photo->image_path, 'http') ? $photo->image_path : asset('storage/' . $photo->image_path) }}" 
                                 alt="{{ $photo->caption }}">
                        </div>
                    </div>
                @empty
                    <div class="col-span-5 text-center py-12 text-slate-400">
                        {{ __('messages.no_general_images') }}
                    </div>
                @endforelse
            </div>

            <!-- Pagination for General Gallery -->
            @if($generalImages->hasPages())
                <div class="pt-6 border-t border-slate-200/60">
                    {{ $generalImages->links() }}
                </div>
            @endif
        </div>

        <!-- Event Galleries Grouped (Folders & Inner Album Views) -->
        <div x-show="activeTab === 'events'">
            
            <!-- 1. Grid of Event Folders (Stacked Photo Deck Design) -->
            <div x-show="activeEventId === null" class="space-y-8">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 pt-2">
                    @forelse($eventsWithGallery as $event)
                        <div class="group relative cursor-pointer" 
                             @click="selectEvent({{ $event->id }}, '{{ addslashes($event->title) }}', '{{ date('F d, Y', strtotime($event->date)) }}')">
                            <!-- Stack Effect Container -->
                            <div class="relative aspect-square w-full select-none mb-2">
                                <!-- Back Stack 2 -->
                                <div class="absolute inset-0 bg-slate-200/80 rounded-2xl transform translate-x-2 translate-y-2.5 rotate-3 group-hover:translate-x-3.5 group-hover:translate-y-3.5 group-hover:rotate-6 transition-all duration-300"></div>
                                <!-- Back Stack 1 -->
                                <div class="absolute inset-0 bg-slate-300/80 rounded-2xl transform -translate-x-1 -translate-y-1.5 -rotate-2 group-hover:-translate-x-2 group-hover:-translate-y-2 group-hover:-rotate-4 transition-all duration-300"></div>
                                
                                <!-- Main Album Cover -->
                                <div class="relative w-full h-full rounded-2xl overflow-hidden bg-white border border-slate-200/70 shadow-xs z-10 p-2">
                                    <div class="relative aspect-square w-full rounded-xl overflow-hidden bg-slate-100 shrink-0">
                                        @if($event->galleries->first())
                                            <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" 
                                                 src="{{ str_starts_with($event->galleries->first()->image_path, 'http') ? $event->galleries->first()->image_path : asset('storage/' . $event->galleries->first()->image_path) }}" 
                                                 alt="{{ $event->title }}">
                                        @elseif($event->banner_path)
                                            <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" 
                                                 src="{{ str_starts_with($event->banner_path, 'http') ? $event->banner_path : asset('storage/' . $event->banner_path) }}" 
                                                 alt="{{ $event->title }}">
                                        @else
                                            <div class="w-full h-full bg-primary-50 flex items-center justify-center text-primary-500">
                                                📁
                                            </div>
                                        @endif
                                        
                                        <!-- Overlay -->
                                        <div class="absolute inset-0 bg-slate-950/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <span class="text-white text-[9px] font-extrabold bg-primary-600/90 px-2 py-1 rounded-md tracking-wider uppercase shadow-xs">
                                                {{ __('messages.view_album') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Event Folder Title & Photo Count -->
                            <div class="px-1 text-center space-y-0.5">
                                <h4 class="text-xs font-bold text-slate-800 line-clamp-1 group-hover:text-primary-600 transition-colors" title="{{ $event->title }}">
                                    {{ $event->title }}
                                </h4>
                                <span class="text-[10px] text-slate-400 font-semibold block">
                                    {{ $event->galleries->count() }} Photos
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-5 text-center py-12 text-slate-400">
                            {{ __('messages.no_event_galleries') }}
                        </div>
                    @endforelse
                </div>

                <!-- Pagination for Event Galleries -->
                @if($eventsWithGallery->hasPages())
                    <div class="pt-6 border-t border-slate-200/60">
                        {{ $eventsWithGallery->links() }}
                    </div>
                @endif
            </div>

            <!-- 2. Inner Album View (Modern Polaroid Art Cards) -->
            <div x-show="activeEventId !== null" class="space-y-6" x-cloak>
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200/60">
                    <button @click="activeEventId = null" 
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 font-bold text-xs transition-colors shadow-sm shrink-0">
                        &larr; {{ __('messages.back_to_albums') }}
                    </button>
                    <div class="text-left sm:text-right">
                        <h3 class="text-base font-extrabold text-slate-900" x-text="activeEventTitle"></h3>
                        <p class="text-[11px] text-slate-400 mt-0.5" x-text="'📅 ' + activeEventDate"></p>
                    </div>
                </div>

                @foreach($eventsWithGallery as $event)
                    <div x-show="activeEventId === {{ $event->id }}" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                        @foreach($event->galleries as $photo)
                            <div class="bg-white rounded-2xl border border-slate-200/60 p-2 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 cursor-pointer flex flex-col group overflow-hidden"
                                 @click="openLightbox('{{ str_starts_with($photo->image_path, 'http') ? $photo->image_path : asset('storage/' . $photo->image_path) }}', '{{ $photo->caption }}')">
                                <div class="relative overflow-hidden rounded-xl bg-slate-50" style="aspect-ratio: 4/3;">
                                    <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" 
                                         src="{{ str_starts_with($photo->image_path, 'http') ? $photo->image_path : asset('storage/' . $photo->image_path) }}" 
                                         alt="{{ $photo->caption }}">
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>

        </div>

        <!-- Lightbox Modal -->
        <div x-show="lightbox" class="fixed inset-0 z-50 bg-black/95 flex flex-col items-center justify-center p-4" x-cloak>
            <button @click="lightbox = false" class="absolute top-4 right-4 text-white text-3xl font-black">&times;</button>
            <img :src="lightboxSrc" class="max-h-[80vh] max-w-full rounded-lg shadow-2xl">
            <p class="text-white text-sm font-semibold mt-4" x-text="lightboxCaption"></p>
        </div>
    </div>
</section>
@endsection
