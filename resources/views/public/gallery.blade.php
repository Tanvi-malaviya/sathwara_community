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
    generalImages: {{ json_encode(collect($generalImages->items())->map(function($img) { return ['src' => str_starts_with($img->image_path, 'http') ? $img->image_path : asset('storage/' . $img->image_path), 'caption' => $img->caption ?? '']; })) }},
    eventImages: {
        @foreach($eventsWithGallery as $event)
            '{{ $event->id }}': {{ json_encode($event->galleries->map(function($img) { return ['src' => str_starts_with($img->image_path, 'http') ? $img->image_path : asset('storage/' . $img->image_path), 'caption' => $img->caption ?? '']; })) }},
        @endforeach
    },
    lightbox: false,
    lightboxIndex: 0,
    currentGallery: [],
    selectEvent(id, title, date) {
        this.activeEventId = id;
        this.activeEventTitle = title;
        this.activeEventDate = date;
    },
    openLightbox(index, type, eventId = null) {
        this.lightboxIndex = index;
        let selected = [];
        if (type === 'general') {
            selected = this.generalImages || [];
        } else {
            selected = (this.eventImages && this.eventImages[eventId]) ? this.eventImages[eventId] : [];
        }
        
        // If current set has multiple images, use it
        if (selected.length > 1) {
            this.currentGallery = selected;
        } else {
            // Pool all images loaded on page so user can navigate between all available photos
            let all = [];
            if (this.generalImages) all.push(...this.generalImages);
            if (this.eventImages) {
                Object.values(this.eventImages).forEach(arr => {
                    if (Array.isArray(arr)) all.push(...arr);
                });
            }
            // Remove duplicates
            let map = new Set();
            let unique = [];
            all.forEach(item => {
                if (item && item.src && !map.has(item.src)) {
                    map.add(item.src);
                    unique.push(item);
                }
            });
            
            if (unique.length > 0) {
                let targetSrc = selected[index]?.src || (unique[0] ? unique[0].src : null);
                this.currentGallery = unique;
                let found = unique.findIndex(i => i.src === targetSrc);
                this.lightboxIndex = found !== -1 ? found : 0;
            } else {
                this.currentGallery = selected;
            }
        }
        this.lightbox = true;
    },
    nextImage() {
        if (this.currentGallery.length > 0) {
            this.lightboxIndex = (this.lightboxIndex + 1) % this.currentGallery.length;
        }
    },
    prevImage() {
        if (this.currentGallery.length > 0) {
            this.lightboxIndex = (this.lightboxIndex - 1 + this.currentGallery.length) % this.currentGallery.length;
        }
    }
}" @keydown.window.escape="lightbox = false" @keydown.window.right="if(lightbox) nextImage()" @keydown.window.left="if(lightbox) prevImage()">
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
        <div class="flex justify-center mb-8">
            <div class="inline-flex p-1.5 bg-white rounded-2xl border border-slate-200/80 shadow-xs gap-1.5">
                <button @click="activeTab = 'general'; activeEventId = null" 
                        :class="activeTab === 'general' ? 'bg-primary-600 text-white shadow-md' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                        class="px-6 py-2.5 rounded-xl font-extrabold text-xs sm:text-sm transition-all duration-200 cursor-pointer">
                    {{ __('messages.general_gallery_btn') }}
                </button>
                <button @click="activeTab = 'events'" 
                        :class="activeTab === 'events' ? 'bg-primary-600 text-white shadow-md' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                        class="px-6 py-2.5 rounded-xl font-extrabold text-xs sm:text-sm transition-all duration-200 cursor-pointer">
                    {{ __('messages.event_galleries_btn') }}
                </button>
            </div>
        </div>

        <!-- General Gallery Grid (Modern Art Cards) -->
        <div x-show="activeTab === 'general'" class="space-y-8">
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 sm:gap-6">
                @forelse($generalImages as $photo)
                    <div class="bg-white rounded-3xl border border-slate-200/80 p-2.5 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 cursor-pointer flex flex-col group overflow-hidden"
                         @click="openLightbox({{ $loop->index }}, 'general')">
                        <div class="relative overflow-hidden rounded-2xl bg-slate-50" style="aspect-ratio: 4/3;">
                            <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" 
                                 src="{{ str_starts_with($photo->image_path, 'http') ? $photo->image_path : asset('storage/' . $photo->image_path) }}" 
                                 alt="{{ $photo->caption }}">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span class="text-white text-xs font-bold bg-slate-900/80 px-3 py-1.5 rounded-xl border border-white/20">🔍 {{ __('messages.click_to_enlarge') }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-4 text-center py-20 text-slate-400">
                        <p class="text-base font-bold">{{ __('messages.no_general_images') }}</p>
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
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-5 sm:gap-6 pt-2">
                    @forelse($eventsWithGallery as $event)
                        <div class="group relative cursor-pointer" 
                             @click="selectEvent({{ $event->id }}, '{{ addslashes($event->title) }}', '{{ date('F d, Y', strtotime($event->date)) }}')">
                            <!-- Stack Effect Container -->
                            <div class="relative aspect-square w-full select-none mb-3">
                                <!-- Back Stack 2 -->
                                <div class="absolute inset-0 bg-slate-200/80 rounded-3xl transform translate-x-2 translate-y-2.5 rotate-3 group-hover:translate-x-3.5 group-hover:translate-y-3.5 group-hover:rotate-6 transition-all duration-300"></div>
                                <!-- Back Stack 1 -->
                                <div class="absolute inset-0 bg-slate-300/80 rounded-3xl transform -translate-x-1 -translate-y-1.5 -rotate-2 group-hover:-translate-x-2 group-hover:-translate-y-2 group-hover:-rotate-4 transition-all duration-300"></div>
                                
                                <!-- Main Album Cover -->
                                <div class="relative w-full h-full rounded-3xl overflow-hidden bg-white border border-slate-200/80 shadow-sm z-10 p-2.5">
                                    <div class="relative aspect-square w-full rounded-2xl overflow-hidden bg-slate-100 shrink-0">
                                        @if($event->galleries->first())
                                            <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" 
                                                 src="{{ str_starts_with($event->galleries->first()->image_path, 'http') ? $event->galleries->first()->image_path : asset('storage/' . $event->galleries->first()->image_path) }}" 
                                                 alt="{{ $event->title }}">
                                        @elseif($event->banner_path)
                                            <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" 
                                                 src="{{ str_starts_with($event->banner_path, 'http') ? $event->banner_path : asset('storage/' . $event->banner_path) }}" 
                                                 alt="{{ $event->title }}">
                                        @else
                                            <div class="w-full h-full bg-primary-50 flex items-center justify-center text-primary-500 text-3xl">
                                                📁
                                            </div>
                                        @endif
                                        
                                        <!-- Overlay -->
                                        <div class="absolute inset-0 bg-slate-950/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <span class="text-white text-xs font-extrabold bg-primary-600 px-3 py-1.5 rounded-xl tracking-wider uppercase shadow-md">
                                                {{ __('messages.view_album') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Event Folder Title & Photo Count -->
                            <div class="px-2 pt-1 text-center space-y-1">
                                <h4 class="text-sm sm:text-base font-black text-slate-900 leading-snug line-clamp-2 group-hover:text-primary-600 transition-colors" title="{{ $event->title }}">
                                    {{ $event->title }}
                                </h4>
                                <span class="text-xs text-slate-500 font-bold block">
                                    {{ $event->galleries->count() }} {{ __('messages.photos') }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-4 text-center py-20 text-slate-400">
                            <p class="text-base font-bold">{{ __('messages.no_event_galleries') }}</p>
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

            <!-- 2. Inner Album View (Modern Art Cards) -->
            <div x-show="activeEventId !== null" class="space-y-6" x-cloak>
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200/80">
                    <button @click="activeEventId = null" 
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white hover:bg-slate-100 text-slate-800 border border-slate-200 font-extrabold text-sm transition-colors shadow-xs shrink-0 cursor-pointer">
                        &larr; {{ __('messages.back_to_albums') }}
                    </button>
                    <div class="text-left sm:text-right space-y-1">
                        <h3 class="text-lg sm:text-xl font-black text-slate-900" x-text="activeEventTitle"></h3>
                        <p class="text-xs sm:text-sm font-bold text-slate-500" x-text="'📅 ' + activeEventDate"></p>
                    </div>
                </div>

                @foreach($eventsWithGallery as $event)
                    <div x-show="activeEventId === {{ $event->id }}" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 sm:gap-6">
                        @foreach($event->galleries as $photo)
                            <div class="bg-white rounded-3xl border border-slate-200/80 p-2.5 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 cursor-pointer flex flex-col group overflow-hidden"
                                 @click="openLightbox({{ $loop->index }}, 'event', {{ $event->id }})">
                                <div class="relative overflow-hidden rounded-2xl bg-slate-50" style="aspect-ratio: 4/3;">
                                    <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" 
                                         src="{{ str_starts_with($photo->image_path, 'http') ? $photo->image_path : asset('storage/' . $photo->image_path) }}" 
                                         alt="{{ $photo->caption }}">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <span class="text-white text-xs font-bold bg-slate-900/80 px-3 py-1.5 rounded-xl border border-white/20">🔍 {{ __('messages.click_to_enlarge') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>

        </div>

        <!-- Lightbox Modal -->
        <template x-teleport="body">
            <div x-show="lightbox" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 9999999; background-color: rgba(2, 6, 23, 0.96); backdrop-filter: blur(20px); overflow: hidden;"
                 class="flex flex-col items-center justify-between p-4 sm:p-6 select-none relative" 
                 @click="lightbox = false" 
                 x-cloak>
                
                <!-- PREVIOUS ARROW BUTTON (Fixed on Far Left Edge of Screen) -->
                <button x-show="currentGallery.length > 0" 
                        @click.stop="prevImage()" 
                        style="position: absolute; left: 1.5rem; top: 50%; transform: translateY(-50%); z-index: 10000000;"
                        class="left-4 sm:left-8 group p-2.5 sm:p-3 rounded-full bg-slate-900/80 hover:bg-primary-500 text-white border border-white/20 hover:border-primary-400 shadow-2xl transition-all duration-300 hover:scale-110 active:scale-95 cursor-pointer backdrop-blur-md"
                        title="Previous Image (Left Arrow)">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 transition-transform duration-300 group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>

                <!-- NEXT ARROW BUTTON (Fixed on Far Right Edge of Screen) -->
                <button x-show="currentGallery.length > 0" 
                        @click.stop="nextImage()" 
                        style="position: absolute; right: 1.5rem; top: 50%; transform: translateY(-50%); z-index: 10000000;"
                        class="right-4 sm:right-8 group p-2.5 sm:p-3 rounded-full bg-slate-900/80 hover:bg-primary-500 text-white border border-white/20 hover:border-primary-400 shadow-2xl transition-all duration-300 hover:scale-110 active:scale-95 cursor-pointer backdrop-blur-md"
                        title="Next Image (Right Arrow)">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 transition-transform duration-300 group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>

                <!-- Top Bar: Close Button -->
                <div class="w-full flex items-center justify-end z-[10000000] max-w-7xl mx-auto pt-1 px-2" @click.stop>
                    <button @click="lightbox = false" 
                            class="group p-2 rounded-full bg-slate-900/80 hover:bg-rose-500 text-white border border-white/20 hover:border-rose-400 transition-all duration-300 cursor-pointer shadow-xl hover:rotate-90 hover:scale-110 active:scale-95"
                            title="Close (Esc)">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Main Section: Centered Image -->
                <div class="relative w-full flex-1 flex items-center justify-center my-auto max-w-5xl mx-auto px-4 pb-14" @click.stop>
                    <!-- Center Container: Image -->
                    <div class="relative flex flex-col items-center justify-center max-w-full max-h-full">
                        <div class="relative rounded-2xl overflow-hidden shadow-2xl border border-white/20 bg-slate-900/50">
                            <img :src="currentGallery[lightboxIndex]?.src" 
                                 :alt="currentGallery[lightboxIndex]?.caption || 'Gallery Image'" 
                                 class="w-auto max-w-full object-contain rounded-xl shadow-2xl transition-all duration-300"
                                 style="max-height: 68vh; max-width: 80vw;">
                        </div>
                    </div>
                </div>

                <!-- Bottom Bar: Image Numbers Counter (Bottom Center) -->
                <div x-show="currentGallery.length > 0" 
                     style="position: absolute; bottom: 1rem; left: 50%; transform: translateX(-50%); z-index: 10000000;"
                     class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full bg-slate-900/90 border border-white/20 text-white text-xs font-bold shadow-2xl backdrop-blur-md">
                    <span class="text-primary-400">🖼️</span>
                    <span>
                        <span class="text-white" x-text="lightboxIndex + 1"></span>
                        <span class="text-white/40"> / </span>
                        <span class="text-white/70" x-text="currentGallery.length"></span>
                    </span>
                </div>

            </div>
        </template>
    </div>
</section>
@endsection
