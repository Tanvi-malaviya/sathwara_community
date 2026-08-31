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
    generalImages: {{ json_encode(collect($generalImages->items())->map(function($img) { return ['src' => $img->url, 'caption' => $img->caption ?? '', 'isVideo' => $img->isVideo()]; })) }},
    eventImages: {
        @foreach($eventsWithGallery as $event)
            '{{ $event->id }}': {{ json_encode($event->galleries->map(function($img) { return ['src' => $img->url, 'caption' => $img->caption ?? '', 'isVideo' => $img->isVideo()]; })) }},
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
                    @php $photoImgUrl = $photo->url; $isVideo = $photo->isVideo(); @endphp
                    <div class="bg-white rounded-3xl border border-slate-200/80 p-2.5 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 cursor-pointer flex flex-col group overflow-hidden"
                         @click="openLightbox({{ $loop->index }}, 'general')">
                        <div class="relative overflow-hidden rounded-2xl bg-slate-950 flex items-center justify-center" style="aspect-ratio: 4/3;">
                            @if($isVideo)
                                <video class="w-full h-full object-cover pointer-events-none" preload="metadata" muted playsinline>
                                    <source src="{{ $photoImgUrl }}">
                                </video>
                                <div class="absolute inset-0 flex items-center justify-center pointer-events-none" style="z-index: 2;">
                                    <div class="w-11 h-11 rounded-full bg-slate-900/85 border border-white/30 text-white flex items-center justify-center shadow-xl backdrop-blur-xs group-hover:scale-110 transition-transform">
                                        <svg class="w-5 h-5 fill-current ml-0.5 text-primary-400" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    </div>
                                </div>
                                <span class="absolute top-2.5 left-2.5 z-10 px-2.5 py-0.5 rounded-lg bg-slate-900/85 text-white text-[10px] font-black uppercase tracking-wider backdrop-blur-xs border border-white/20 flex items-center gap-1">
                                    <svg class="w-2.5 h-2.5 fill-current text-primary-400" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    Video
                                </span>
                            @else
                                <!-- Blurred Background Image -->
                                <img src="{{ $photoImgUrl }}" 
                                     alt="" 
                                     aria-hidden="true"
                                     class="absolute inset-0 w-full h-full pointer-events-none select-none"
                                     style="object-fit: cover; object-position: center; filter: blur(14px) brightness(0.45); transform: scale(1.12); z-index: 0;">

                                <!-- Main Image -->
                                <img class="relative w-full h-full object-contain transition-transform duration-500 group-hover:scale-105" 
                                     style="z-index: 1;"
                                     src="{{ $photoImgUrl }}" 
                                     alt="{{ $photo->caption }}">
                            @endif

                            <!-- Overlay -->
                            <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center" style="z-index: 10;">
                                <span class="inline-flex items-center gap-1.5 text-white text-xs font-bold bg-slate-900/80 px-3 py-1.5 rounded-xl border border-white/20 backdrop-blur-xs shadow-md">
                                    @if($isVideo)
                                        <svg class="w-3.5 h-3.5 text-primary-400 fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                        <span>Play Video</span>
                                    @else
                                        <svg class="w-3.5 h-3.5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/></svg>
                                        <span>{{ __('messages.click_to_enlarge') }}</span>
                                    @endif
                                </span>
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
                                    <div class="relative aspect-square w-full rounded-2xl overflow-hidden bg-slate-950 shrink-0 flex items-center justify-center">
                                        @php
                                            $coverImg = $event->galleries->first()?->image_path ?? $event->banner_path;
                                            $coverImgUrl = $coverImg ? (str_starts_with($coverImg, 'http') ? $coverImg : asset('storage/' . $coverImg)) : null;
                                        @endphp
                                        @if($coverImgUrl)
                                            <img src="{{ $coverImgUrl }}" 
                                                 alt="" 
                                                 aria-hidden="true"
                                                 class="absolute inset-0 w-full h-full pointer-events-none select-none"
                                                 style="object-fit: cover; object-position: center; filter: blur(14px) brightness(0.45); transform: scale(1.12); z-index: 0;">

                                            <img class="relative w-full h-full object-contain transition-transform duration-500 group-hover:scale-105" 
                                                 style="z-index: 1;"
                                                 src="{{ $coverImgUrl }}" 
                                                 alt="{{ $event->title }}">
                                        @else
                                            <div class="w-full h-full bg-primary-50 flex items-center justify-center text-primary-500">
                                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                                            </div>
                                        @endif
                                        
                                        <!-- Overlay -->
                                        <div class="absolute inset-0 bg-slate-950/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center" style="z-index: 10;">
                                            <span class="text-white text-xs font-extrabold bg-primary-600 px-3 py-1.5 rounded-xl tracking-wider uppercase shadow-md">
                                                {{ __('messages.view_album') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Event Folder Title & Media Count -->
                            <div class="px-2 pt-1 text-center space-y-1">
                                <h4 class="text-sm sm:text-base font-black text-slate-900 leading-snug line-clamp-2 group-hover:text-primary-600 transition-colors" title="{{ $event->title }}">
                                    {{ $event->title }}
                                </h4>
                                <span class="text-xs text-slate-500 font-bold block">
                                    {{ $event->galleries->count() }} {{ __('messages.photos') }} & Videos
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
                        <p class="text-xs sm:text-sm font-bold text-slate-500 flex items-center sm:justify-end gap-1.5">
                            <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span x-text="activeEventDate"></span>
                        </p>
                    </div>
                </div>

                @foreach($eventsWithGallery as $event)
                    <div x-show="activeEventId === {{ $event->id }}" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 sm:gap-6">
                        @foreach($event->galleries as $photo)
                            @php $innerPhotoImgUrl = $photo->url; $isInnerVideo = $photo->isVideo(); @endphp
                            <div class="bg-white rounded-3xl border border-slate-200/80 p-2.5 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 cursor-pointer flex flex-col group overflow-hidden"
                                 @click="openLightbox({{ $loop->index }}, 'event', {{ $event->id }})">
                                <div class="relative overflow-hidden rounded-2xl bg-slate-950 flex items-center justify-center" style="aspect-ratio: 4/3;">
                                    @if($isInnerVideo)
                                        <video class="w-full h-full object-cover pointer-events-none" preload="metadata" muted playsinline>
                                            <source src="{{ $innerPhotoImgUrl }}">
                                        </video>
                                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none" style="z-index: 2;">
                                            <div class="w-11 h-11 rounded-full bg-slate-900/85 border border-white/30 text-white flex items-center justify-center shadow-xl backdrop-blur-xs group-hover:scale-110 transition-transform">
                                                <svg class="w-5 h-5 fill-current ml-0.5 text-primary-400" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                            </div>
                                        </div>
                                        <span class="absolute top-2.5 left-2.5 z-10 px-2.5 py-0.5 rounded-lg bg-slate-900/85 text-white text-[10px] font-black uppercase tracking-wider backdrop-blur-xs border border-white/20 flex items-center gap-1">
                                            <svg class="w-2.5 h-2.5 fill-current text-primary-400" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                            Video
                                        </span>
                                    @else
                                        <!-- Blurred Background Image -->
                                        <img src="{{ $innerPhotoImgUrl }}" 
                                             alt="" 
                                             aria-hidden="true"
                                             class="absolute inset-0 w-full h-full pointer-events-none select-none"
                                             style="object-fit: cover; object-position: center; filter: blur(14px) brightness(0.45); transform: scale(1.12); z-index: 0;">

                                        <!-- Main Image -->
                                        <img class="relative w-full h-full object-contain transition-transform duration-500 group-hover:scale-105" 
                                             style="z-index: 1;"
                                             src="{{ $innerPhotoImgUrl }}" 
                                             alt="{{ $photo->caption }}">
                                    @endif

                                    <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center" style="z-index: 10;">
                                        <span class="inline-flex items-center gap-1.5 text-white text-xs font-bold bg-slate-900/80 px-3 py-1.5 rounded-xl border border-white/20 backdrop-blur-xs shadow-md">
                                            @if($isInnerVideo)
                                                <svg class="w-3.5 h-3.5 text-primary-400 fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                                <span>Play Video</span>
                                            @else
                                                <svg class="w-3.5 h-3.5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/></svg>
                                                <span>{{ __('messages.click_to_enlarge') }}</span>
                                            @endif
                                        </span>
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
                
                <!-- PREVIOUS ARROW BUTTON -->
                <button x-show="currentGallery.length > 0" 
                        @click.stop="prevImage()" 
                        style="position: absolute; left: 1.5rem; top: 50%; transform: translateY(-50%); z-index: 10000000;"
                        class="left-4 sm:left-8 group p-2.5 sm:p-3 rounded-full bg-slate-900/80 hover:bg-primary-500 text-white border border-white/20 hover:border-primary-400 shadow-2xl transition-all duration-300 hover:scale-110 active:scale-95 cursor-pointer backdrop-blur-md"
                        title="Previous (Left Arrow)">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 transition-transform duration-300 group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>

                <!-- NEXT ARROW BUTTON -->
                <button x-show="currentGallery.length > 0" 
                        @click.stop="nextImage()" 
                        style="position: absolute; right: 1.5rem; top: 50%; transform: translateY(-50%); z-index: 10000000;"
                        class="right-4 sm:right-8 group p-2.5 sm:p-3 rounded-full bg-slate-900/80 hover:bg-primary-500 text-white border border-white/20 hover:border-primary-400 shadow-2xl transition-all duration-300 hover:scale-110 active:scale-95 cursor-pointer backdrop-blur-md"
                        title="Next (Right Arrow)">
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

                <!-- Main Section: Centered Media -->
                <div class="relative w-full flex-1 flex items-center justify-center my-auto max-w-5xl mx-auto px-4 pb-14" @click.stop>
                    <div class="relative flex flex-col items-center justify-center max-w-full max-h-full">
                        <div class="relative rounded-2xl overflow-hidden shadow-2xl border border-white/20 bg-slate-900/50 flex items-center justify-center">
                            <template x-if="currentGallery[lightboxIndex]?.isVideo">
                                <video :src="currentGallery[lightboxIndex]?.src"
                                       controls
                                       autoplay
                                       playsinline
                                       class="w-auto max-w-full object-contain rounded-xl shadow-2xl transition-all duration-300"
                                       style="max-height: 68vh; max-width: 80vw; outline: none;">
                                </video>
                            </template>
                            <template x-if="!currentGallery[lightboxIndex]?.isVideo">
                                <img :src="currentGallery[lightboxIndex]?.src" 
                                     :alt="currentGallery[lightboxIndex]?.caption || 'Gallery Image'" 
                                     class="w-auto max-w-full object-contain rounded-xl shadow-2xl transition-all duration-300"
                                     style="max-height: 68vh; max-width: 80vw;">
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Bottom Bar: Numbers Counter -->
                <div x-show="currentGallery.length > 0" 
                     style="position: absolute; bottom: 1rem; left: 50%; transform: translateX(-50%); z-index: 10000000;"
                     class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full bg-slate-900/90 border border-white/20 text-white text-xs font-bold shadow-2xl backdrop-blur-md">
                    <svg class="w-3.5 h-3.5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
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
