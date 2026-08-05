@extends('layouts.public')

@section('content')
<!-- Edge-to-Edge Full Screen Hero Slider Section -->
<section class="relative w-full overflow-hidden bg-slate-950 py-0 border-b border-slate-900" x-data="{ 
    activeSlide: 0,
    slides: {{ json_encode($sliders->toArray()) }},
    timer: null,
    next() { this.activeSlide = (this.activeSlide + 1) % (this.slides.length || 1) },
    prev() { this.activeSlide = (this.activeSlide - 1 + (this.slides.length || 1)) % (this.slides.length || 1) },
    startTimer() { this.timer = setInterval(() => this.next(), 6000) },
    resetTimer() { clearInterval(this.timer); this.startTimer(); },
    init() { if(this.slides.length > 0) this.startTimer(); }
}">
    @if($sliders->count() > 0)
        <!-- Full Viewport Height Edge-to-Edge Slider Wrapper -->
        <div class="relative w-full h-[calc(100vh-70px)] min-h-[550px] sm:min-h-[620px] lg:min-h-[500px] overflow-hidden bg-slate-950 flex items-center">
            
            @foreach($sliders as $idx => $slide)
                <div x-show="activeSlide === {{ $idx }}"
                     x-transition:enter="transition ease-out duration-700 transform"
                     x-transition:enter-start="opacity-0 scale-105"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-500 transform absolute inset-0"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute inset-0 w-full h-full flex items-center">
                    
                    <!-- Slide Full Screen Edge-to-Edge Background Image -->
                    <div class="absolute inset-0 w-full h-full bg-cover bg-center bg-no-repeat transition-transform duration-1000 scale-100"
                         style="background-image: url('{{ str_starts_with($slide->image_path, 'http') ? $slide->image_path : asset('storage/' . $slide->image_path) }}');">
                    </div>

                </div>
            @endforeach

            <!-- Unified Hero Slider Control Bar & Dynamic Action Button (Bottom Right) -->
            <div class="absolute bottom-4 sm:bottom-6 right-4 sm:right-8 z-30 flex flex-wrap items-center gap-3">
                
                <!-- Dynamic Action Button (Shows ONLY if button_text is set) -->
                <template x-if="slides[activeSlide] && slides[activeSlide].button_text && slides[activeSlide].button_text.trim() !== ''">
                    <a :href="slides[activeSlide].button_link || '#'" 
                       class="px-5 py-2.5 sm:px-6 sm:py-3 bg-primary-600 hover:bg-primary-500 text-white font-extrabold text-xs sm:text-sm rounded-full shadow-2xl border border-white/20 backdrop-blur-md transition-all duration-300 flex items-center gap-2 shrink-0 active:scale-95 hover:shadow-primary-500/30">
                        <span x-text="slides[activeSlide].button_text"></span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                        </svg>
                    </a>
                </template>

                <!-- Slider Control Bar (Prev, Counter, Indicators, Next) -->
                <div class="flex items-center gap-2 bg-slate-950/80 backdrop-blur-xl p-1.5 rounded-full border border-white/20 text-white shadow-2xl">
                    <!-- Prev Arrow Button -->
                    <button @click="prev(); resetTimer();" 
                            class="w-9 h-9 sm:w-10 sm:h-10 bg-white/10 hover:bg-white/25 text-white rounded-full flex items-center justify-center transition-all duration-200 cursor-pointer active:scale-90"
                            aria-label="Previous slide">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                        </svg>
                    </button>

                    <!-- Slide Counter -->
                    <span class="px-2 text-xs sm:text-sm font-black tracking-widest text-slate-200 select-none" x-text="(activeSlide + 1) + ' / ' + slides.length"></span>

                    <!-- Pagination Dots -->
                    <div class="flex items-center space-x-1.5 px-1">
                        @foreach($sliders as $dotIdx => $dotSlide)
                            <button @click="activeSlide = {{ $dotIdx }}; resetTimer();"
                                    class="h-2 rounded-full transition-all duration-300 cursor-pointer"
                                    :class="activeSlide === {{ $dotIdx }} ? 'w-6 bg-primary-500' : 'w-2 bg-white/40 hover:bg-white/70'"
                                    :aria-label="'Go to slide ' + ({{ $dotIdx }} + 1)"></button>
                        @endforeach
                    </div>

                    <!-- Next Arrow Button -->
                    <button @click="next(); resetTimer();" 
                            class="w-9 h-9 sm:w-10 sm:h-10 bg-white/10 hover:bg-white/25 text-white rounded-full flex items-center justify-center transition-all duration-200 cursor-pointer active:scale-90"
                            aria-label="Next slide">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                        </svg>
                    </button>
                </div>
            </div>

        </div>
    @else
        <!-- Full Screen Fallback Hero Banner -->
        <div class="w-full h-[calc(100vh-80px)] min-h-[500px] flex items-center justify-center bg-gradient-to-br from-slate-900 via-slate-950 to-slate-900 text-center text-white px-4">
            <div class="max-w-4xl space-y-6">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 text-white text-xs font-bold border border-white/15 mx-auto">
                    <span class="w-2.5 h-2.5 rounded-full bg-primary-500"></span>
                    <span class="uppercase tracking-widest text-[11px]">Sathwara Community Portal</span>
                </div>
                <h1 class="text-4xl sm:text-6xl font-black text-white tracking-tight">
                    Welcome to Sathwara Community
                </h1>
                <p class="text-slate-300 text-base sm:text-xl max-w-2xl mx-auto leading-relaxed">
                    Empowering unity, progress, and commercial networking for all Sathwara community members.
                </p>
                <div class="pt-4">
                    <a href="{{ route('business.directory') }}" class="inline-flex items-center justify-center gap-2.5 px-8 py-4 bg-primary-600 hover:bg-primary-500 text-white font-extrabold text-base rounded-2xl transition-all shadow-xl">
                        Explore Business Directory
                    </a>
                </div>
            </div>
        </div>
    @endif
</section>

<!-- Agendas / Our Core Mission & Values Section -->
<section class="py-6 sm:py-8 bg-transparent">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Clean Section Header -->
        <div class="text-center max-w-2xl mx-auto mb-10 space-y-2">
            <span class="text-xs font-bold text-primary-600 uppercase tracking-widest">{{ __('messages.agenda') }}</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                {{ __('messages.core_mission_values') }}
            </h2>
            <p class="text-xs sm:text-sm text-slate-500">
                {{ __('messages.core_mission_subtitle') }}
            </p>
        </div>

        <!-- Agendas Clean Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($agendas as $index => $agenda)
                <div class="p-6 rounded-2xl bg-slate-50/60 border border-slate-200/70 hover:border-slate-300 hover:bg-white hover:shadow-md transition-all duration-300 flex flex-col justify-between space-y-4">
                    <div class="space-y-3">
                        <div class="w-10 h-10 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center text-lg font-bold border border-primary-100">
                            @if($agenda->icon == 'users') 👥
                            @elseif($agenda->icon == 'academic-cap') 🎓
                            @elseif($agenda->icon == 'briefcase') 💼
                            @else 📌 @endif
                        </div>

                        <h3 class="text-base font-bold text-slate-900">
                            {{ $agenda->localized_title }}
                        </h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            {{ $agenda->localized_description }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>


<!-- Upcoming Events Section -->
<section class="py-6 sm:py-8 bg-transparent">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Clean Section Header -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-4">
            <div class="space-y-1.5 text-center sm:text-left">
                <span class="text-xs font-bold text-primary-600 uppercase tracking-widest">{{ __('messages.upcoming_events') }}</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    {{ __('messages.gatherings_activities') }}
                </h2>
            </div>
            <a href="{{ route('events') }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl bg-white hover:bg-slate-50 text-slate-700 hover:text-slate-900 border border-slate-200 text-xs font-bold transition-all shadow-xs shrink-0">
                <span>{{ __('messages.view_all_events') }}</span>
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
        </div>

        <!-- Event Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($upcomingEvents as $event)
                <a href="{{ route('event.details', $event->id) }}" class="group bg-white rounded-2xl overflow-hidden border border-slate-200/80 shadow-xs hover:shadow-md transition-all duration-300 flex flex-col block cursor-pointer">
                    
                    <!-- Image -->
                    <div class="relative h-44 w-full overflow-hidden shrink-0">
                        {{-- Always-visible Red Background + Calendar Icon (base layer) --}}
                        <div style="position:absolute; inset:0; display:flex; flex-direction:column; align-items:center; justify-content:center; padding: 16px 12px; background: linear-gradient(135deg, #dc2626 0%, #e11d48 60%, #be123c 100%);">
                            <div style="position:absolute; inset:0; background-image: radial-gradient(circle, rgba(255,255,255,0.12) 1px, transparent 1px); background-size: 14px 14px; pointer-events:none;"></div>
                            <div style="position:relative; display:flex; flex-direction:column; align-items:center; gap:8px;">
                                <div style="width:60px; height:64px; border-radius:12px; background:#fff; overflow:hidden; display:flex; flex-direction:column; box-shadow: 0 6px 20px rgba(0,0,0,0.28), 0 0 0 2px rgba(255,255,255,0.35);">
                                    <div style="background: linear-gradient(90deg, #dc2626, #e11d48); padding: 4px 0; text-align:center; flex-shrink:0;">
                                        <span style="font-size:10px; font-weight:900; color:#fff; letter-spacing:0.14em; text-transform:uppercase; display:block; line-height:1;">
                                            {{ date('M', strtotime($event->date)) }}
                                        </span>
                                    </div>
                                    <div style="flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center;">
                                        <span style="font-size:22px; font-weight:900; color:#1e293b; line-height:1;">
                                            {{ date('d', strtotime($event->date)) }}
                                        </span>
                                        <span style="font-size:7px; font-weight:700; color:#94a3b8; margin-top:2px; line-height:1;">
                                            {{ date('Y', strtotime($event->date)) }}
                                        </span>
                                    </div>
                                </div>
                                <span style="font-size:8px; font-weight:800; text-transform:uppercase; letter-spacing:0.1em; color:#fff; background:rgba(0,0,0,0.35); border:1px solid rgba(255,255,255,0.15); padding:2px 8px; border-radius:999px; white-space:nowrap;">Community Event</span>
                            </div>
                        </div>

                        {{-- Actual image on top (covers calendar when loaded successfully) --}}
                        @if(!empty($event->banner_path))
                            <img
                                class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                src="{{ str_starts_with($event->banner_path, 'http') ? $event->banner_path : asset('storage/' . $event->banner_path) }}"
                                alt="{{ $event->title }}"
                                onerror="this.style.display='none'">
                        @endif

                        {{-- Date Badge (Top-Left overlay) --}}
                        <div class="absolute top-3 left-3 z-10">
                            <span class="text-[9px] font-extrabold text-white bg-slate-900/80 backdrop-blur-md px-2 py-1 rounded-lg uppercase tracking-wider shadow-sm">
                                {{ date('d M, Y', strtotime($event->date)) }}
                            </span>
                        </div>

                        {{-- Status Badge (Top-Right overlay) --}}
                        <div class="absolute top-3 right-3 z-10">
                            @if($event->date < now()->toDateString())
                                <span class="text-[8px] font-black text-slate-700 bg-slate-100/90 backdrop-blur-sm px-2 py-0.5 rounded-md uppercase tracking-wider shadow-sm">{{ __('messages.passed') }}</span>
                            @else
                                <span class="text-[8px] font-black text-emerald-700 bg-emerald-50/90 backdrop-blur-sm px-2 py-0.5 rounded-md uppercase tracking-wider shadow-sm">{{ __('messages.upcoming') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-5 flex-1 flex flex-col justify-between space-y-3">
                        <div class="space-y-1.5">
                            <h3 class="text-sm font-bold text-slate-900 group-hover:text-primary-600 transition-colors line-clamp-1" title="{{ $event->title }}">
                                {{ $event->title }}
                            </h3>
                            @php
                                $cleanDesc = preg_replace('/<span class="ql-ui"[^>]*>.*?<\/span>/i', '', $event->description);
                                $cleanDesc = strip_tags(str_replace(['<br>', '</p>', '</li>', '</div>'], ' ', $cleanDesc));
                                $cleanDesc = trim(preg_replace('/\s+/', ' ', $cleanDesc));
                            @endphp
                            <p class="text-[11px] text-slate-500 line-clamp-2 leading-relaxed break-words">
                                {{ \Illuminate\Support\Str::limit($cleanDesc, 85, '...') }}
                            </p>
                        </div>

                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2 min-w-0 flex-wrap">
                            <div class="min-w-0 flex-1">
                                <span class="text-[10px] font-bold text-slate-400 truncate block" title="{{ $event->venue }}">
                                    📍 {{ $event->venue }}
                                </span>
                            </div>
                            @if(!empty($event->registration_end_date))
                                <span class="text-[9px] font-extrabold text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded border border-rose-100 whitespace-nowrap">
                                    ⏳ Last Date: {{ date('d-M-Y', strtotime($event->registration_end_date)) }}
                                </span>
                            @endif
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-3 text-center py-10 bg-white rounded-2xl border border-slate-200/80 text-slate-500">
                    <p class="text-xs font-bold">{{ __('messages.no_events_scheduled') }}</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Latest Updates / Community Bulletins Section -->
<section class="py-6 sm:py-8 bg-transparent">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Clean Section Header -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-4">
            <div class="space-y-1.5 text-center sm:text-left">
                <span class="text-xs font-bold text-primary-600 uppercase tracking-widest">{{ __('messages.latest_updates') }}</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    {{ __('messages.community_bulletins') }}
                </h2>
            </div>
            <a href="{{ route('updates') }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 hover:text-slate-900 border border-slate-200 text-xs font-bold transition-all shadow-xs shrink-0">
                <span>{{ __('messages.all_announcements') }}</span>
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
        </div>

        <!-- Updates Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($latestUpdates as $update)
                <div class="group bg-slate-50/70 rounded-2xl p-5 border border-slate-200/70 hover:bg-white hover:border-slate-300 hover:shadow-md transition-all duration-300 flex flex-col justify-between space-y-3">
                    
                    <!-- Top Meta -->
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-[11px] font-bold text-slate-500">
                            📌 {{ date('M d, Y', strtotime($update->publish_date)) }}
                        </span>
                    </div>

                    <!-- Body -->
                    <div class="space-y-1.5 flex-1">
                        <h3 class="text-sm font-bold text-slate-900 group-hover:text-primary-600 transition-colors line-clamp-2">
                            {{ $update->title }}
                        </h3>
                        <p class="text-xs text-slate-600 leading-relaxed line-clamp-2">
                            {{ $update->description }}
                        </p>
                    </div>

                    <!-- Footer Link -->
                    <div class="pt-2 border-t border-slate-200/60">
                        <a href="{{ route('update.details', $update->id) }}" class="text-xs font-bold text-primary-600 hover:text-primary-700 transition-colors">
                            {{ __('messages.read_full_post') }} &rarr;
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-10 bg-slate-50/70 rounded-2xl border border-slate-200/80 text-slate-500">
                    <p class="text-xs font-bold">{{ __('messages.no_announcements') }}</p>
                </div>
            @endforelse
        </div>
    </div>
</section>



<!-- Gallery Preview Section -->
<section class="py-6 sm:py-8 bg-transparent" x-data="{ 
    lightbox: false,
    lightboxIndex: 0,
    currentGallery: {{ json_encode(collect($galleryPreview)->map(function($item) { return ['src' => str_starts_with($item->image_path, 'http') ? $item->image_path : asset('storage/' . $item->image_path), 'caption' => $item->caption ?? '']; })->values()) }},
    openLightbox(index) {
        this.lightboxIndex = index;
        this.lightbox = true;
    },
    nextImage() {
        if (this.currentGallery.length > 1) {
            this.lightboxIndex = (this.lightboxIndex + 1) % this.currentGallery.length;
        }
    },
    prevImage() {
        if (this.currentGallery.length > 1) {
            this.lightboxIndex = (this.lightboxIndex - 1 + this.currentGallery.length) % this.currentGallery.length;
        }
    }
}" @keydown.window.escape="lightbox = false" @keydown.window.right="if(lightbox) nextImage()" @keydown.window.left="if(lightbox) prevImage()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Clean Section Header -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-4">
            <div class="space-y-1.5 text-center sm:text-left">
                <span class="text-xs font-bold text-primary-600 uppercase tracking-widest">{{ __('messages.gallery_preview') }}</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    {{ __('messages.moments_togetherness') }}
                </h2>
            </div>
            <a href="{{ route('gallery') }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 hover:text-slate-900 border border-slate-200 text-xs font-bold transition-all shadow-xs shrink-0">
                <span>{{ __('messages.view_full_gallery') }}</span>
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
        </div>

        @if($galleryPreview->count() > 0)
            <!-- Staggered Masonry Collage (Random Pinterest layout, zero empty gaps) -->
            <div class="columns-1 sm:columns-2 lg:columns-3 gap-6 space-y-6">
                @foreach($galleryPreview as $index => $item)
                    @php
                        // Dynamic organic shapes & varied aspect heights for random collage feel
                        $shapes = [
                            0 => 'h-72 sm:h-80 rounded-[2.2rem]',
                            1 => 'h-52 sm:h-60 rounded-3xl',
                            2 => 'h-96 sm:h-[420px] rounded-[2.5rem]',
                            3 => 'h-64 sm:h-72 rounded-3xl',
                            4 => 'h-80 sm:h-88 rounded-[2rem]',
                            5 => 'h-60 sm:h-64 rounded-3xl',
                        ];
                        $shapeClass = $shapes[$index % count($shapes)];
                        $imageUrl = str_starts_with($item->image_path, 'http') ? $item->image_path : asset('storage/' . $item->image_path);
                    @endphp
                    <div @click="openLightbox({{ $index }})"
                         class="break-inside-avoid group relative w-full overflow-hidden bg-slate-900 border border-slate-200/80 shadow-md hover:shadow-xl transition-all duration-500 cursor-pointer {{ $shapeClass }}">
                        
                        <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 brightness-95 group-hover:brightness-100" 
                             src="{{ $imageUrl }}" 
                             alt="{{ $item->caption ?: 'Community Gallery' }}">
                        
                        <!-- Gradient Overlay & High-Contrast Caption -->
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/30 to-transparent flex items-end p-5 transition-opacity duration-300">
                            <div class="w-full space-y-2">
                                <p class="text-sm sm:text-base font-extrabold text-white leading-snug drop-shadow-md truncate">
                                    {{ $item->caption ?: 'Sathwara Community Photo' }}
                                </p>
                                
                                <!-- High-Visibility Badge -->
                                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 group-hover:bg-primary-600 backdrop-blur-md border border-white/30 text-white text-[11px] font-bold shadow-xs transition-colors duration-300">
                                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                                    </svg>
                                    <span>{{ __('messages.click_to_enlarge') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 bg-slate-50 rounded-3xl border border-slate-200/80 text-slate-500">
                <p class="text-sm font-bold">{{ __('messages.no_photos_uploaded') }}</p>
            </div>
        @endif

        <!-- Lightbox Modal -->
        <template x-teleport="body">
            <div x-show="lightbox" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 style="position: fixed; inset: 0; z-index: 999999; background-color: rgba(0, 0, 0, 0.95);"
                 class="flex flex-col items-center justify-between p-4 sm:p-6 select-none" 
                 @click="lightbox = false" 
                 x-cloak>
                
                <!-- Close button (Top Right) -->
                <button @click="lightbox = false" 
                        style="position: absolute; top: 1.5rem; right: 1.5rem; z-index: 1000000;"
                        class="p-2.5 rounded-full bg-black/60 hover:bg-rose-600 text-white border border-white/20 hover:border-rose-500 transition-all duration-200 cursor-pointer shadow-xl hover:scale-110 active:scale-95"
                        title="Close (Esc)">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <!-- PREVIOUS ARROW BUTTON (Left Side) -->
                <button x-show="currentGallery.length > 1" 
                        @click.stop="prevImage()" 
                        style="position: absolute; left: 1.5rem; top: 50%; transform: translateY(-50%); z-index: 1000000;"
                        class="p-3 sm:p-4 rounded-full bg-black/60 hover:bg-primary-600 text-white border border-white/20 hover:border-primary-400 shadow-2xl transition-all duration-200 hover:scale-110 active:scale-95 cursor-pointer backdrop-blur-md"
                        title="Previous Image">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>

                <!-- NEXT ARROW BUTTON (Right Side) -->
                <button x-show="currentGallery.length > 1" 
                        @click.stop="nextImage()" 
                        style="position: absolute; right: 1.5rem; top: 50%; transform: translateY(-50%); z-index: 1000000;"
                        class="p-3 sm:p-4 rounded-full bg-black/60 hover:bg-primary-600 text-white border border-white/20 hover:border-primary-400 shadow-2xl transition-all duration-200 hover:scale-110 active:scale-95 cursor-pointer backdrop-blur-md"
                        title="Next Image">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>

                <!-- Center Content: Image -->
                <div class="relative w-full flex-1 flex flex-col items-center justify-center my-auto max-w-4xl mx-auto px-12 sm:px-20 pb-14" @click.stop>
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl border border-white/10 bg-slate-900/40">
                        <img :src="currentGallery[lightboxIndex]?.src" 
                             :alt="currentGallery[lightboxIndex]?.caption || 'Gallery Image'" 
                             class="w-auto max-w-full object-contain rounded-xl shadow-2xl"
                             style="max-height: 68vh;">
                    </div>
                </div>

                <!-- Bottom Bar: Image Numbers Counter (Bottom Center) -->
                <div x-show="currentGallery.length > 1" 
                     style="position: absolute; bottom: 1rem; left: 50%; transform: translateX(-50%); z-index: 1000000;"
                     class="text-white/90 bg-black/80 px-5 py-1.5 rounded-full text-xs font-bold tracking-widest border border-white/20 backdrop-blur-md shadow-2xl">
                    <span class="text-primary-400">🖼️</span> 
                    <span x-text="lightboxIndex + 1"></span> / <span x-text="currentGallery.length"></span>
                </div>

            </div>
        </template>
    </div>
</section>
@endsection
