@extends('layouts.public')

@section('content')
    <!-- Event Header Banner (Top Hero Section) -->
    <section class="relative bg-slate-950 text-white overflow-hidden py-10 md:py-14">
        <!-- Ambient background lighting -->
        <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-900 to-slate-950"></div>
        <div class="absolute top-0 right-1/4 w-96 h-96 bg-primary-600/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                
                <!-- Left Side Text & Details (8 cols) -->
                <div class="{{ !empty($event->banner_path) ? 'lg:col-span-8' : 'lg:col-span-12' }} space-y-3.5">
                    <!-- Breadcrumbs -->
                    <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400">
                        <a href="{{ route('home') }}" class="hover:text-white transition-colors flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span>{{ __('messages.home') }}</span>
                        </a>
                        <span class="text-slate-600">/</span>
                        <a href="{{ route('events') }}" class="hover:text-white transition-colors">
                            <span>{{ __('messages.events') }}</span>
                        </a>
                        <span class="text-slate-600">/</span>
                        <span class="text-primary-400 font-bold truncate max-w-[200px]">{{ $event->title }}</span>
                    </nav>

                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-extrabold bg-primary-500/20 text-primary-300 border border-primary-500/30 uppercase tracking-wider">
                            {{ __('messages.community_gathering') }}
                        </span>
                        @if($event->date < now()->toDateString())
                            <span class="text-[10px] font-extrabold text-slate-400 bg-slate-800/80 px-3 py-1 rounded-full border border-slate-700 uppercase tracking-wider">{{ __('messages.passed') }}</span>
                        @else
                            <span class="text-[10px] font-extrabold text-emerald-400 bg-emerald-950/60 px-3 py-1 rounded-full border border-emerald-700/60 uppercase tracking-wider">{{ __('messages.upcoming') }}</span>
                        @endif
                    </div>

                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black tracking-tight leading-tight text-white">
                        {{ $event->title }}
                    </h1>

                    <!-- Meta Details -->
                    <div class="flex flex-wrap items-center gap-2.5 text-xs text-slate-300 font-semibold pt-1">
                        <span class="flex items-center gap-1.5 bg-slate-900/80 border border-slate-800 px-3 py-1.5 rounded-xl">📅 {{ date('F d, Y', strtotime($event->date)) }}</span>
                        <span class="flex items-center gap-1.5 bg-slate-900/80 border border-slate-800 px-3 py-1.5 rounded-xl">⏰ {{ date('h:i A', strtotime($event->time)) }}</span>
                        <span class="flex items-center gap-1.5 bg-slate-900/80 border border-slate-800 px-3 py-1.5 rounded-xl">📍 {{ $event->venue }}</span>
                        @if(!empty($event->registration_end_date))
                            <span class="flex items-center gap-1.5 text-rose-300 font-extrabold bg-rose-950/80 px-3 py-1.5 rounded-xl border border-rose-700/60">⏳ Last Date: {{ date('d-M-Y', strtotime($event->registration_end_date)) }}</span>
                        @endif
                    </div>
                </div>

                <!-- Right Side Small Image (4 cols) -->
                @if(!empty($event->banner_path))
                    <div class="lg:col-span-4 flex justify-center lg:justify-end">
                        <div class="relative max-w-[220px] sm:max-w-[260px] w-full bg-white rounded-2xl p-1.5 border border-slate-700/40 shadow-xl">
                            <img src="{{ str_starts_with($event->banner_path, 'http') ? $event->banner_path : asset('storage/' . $event->banner_path) }}" 
                                 alt="{{ $event->title }}" 
                                 class="w-full max-h-[220px] sm:max-h-[250px] object-contain rounded-xl bg-white shadow-xs">
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Event Content & Registration -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Left: Description / Gallery -->
                <div class="lg:col-span-2 space-y-12">
                    <div class="space-y-4">
                        <h2 class="text-xl font-extrabold text-slate-900">{{ __('messages.event_description') }}</h2>
                        <div class="rich-text text-xs text-slate-600 leading-relaxed">
                            {!! $event->description !!}
                        </div>
                    </div>

                    <!-- Venue & Google Map Location -->
                    @if(!empty($event->map_embed_url))
                        <div class="space-y-4 pt-4 border-t border-slate-100">
                            <div class="flex items-center justify-between gap-2 flex-wrap">
                                <h2 class="text-lg font-extrabold text-slate-900 flex items-center gap-2">
                                    <span>📍 Event Location & Venue Map</span>
                                </h2>
                                @if(!empty($event->google_map_link) && str_starts_with($event->google_map_link, 'http'))
                                    <!-- <a href="{{ $event->google_map_link }}" target="_blank" class="text-xs font-bold text-primary-600 hover:text-primary-700 flex items-center gap-1">
                                        <span>Open in Google Maps</span> &rarr;
                                    </a> -->
                                @endif
                            </div>
                            <div class="rounded-2xl overflow-hidden border border-slate-200 shadow-sm h-52 sm:h-60 w-full bg-slate-50">
                                <iframe src="{{ $event->map_embed_url }}" class="w-full h-full border-0" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
                        </div>
                    @endif

                    <!-- Event Gallery -->
                    @if($gallery->count() > 0)
                        <div class="space-y-6">
                            <h2 class="text-xl font-extrabold text-slate-900">{{ __('messages.event_gallery') }}</h2>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4"
                                 x-data="{ 
                                     lightbox: false, 
                                     lightboxIndex: 0,
                                     galleryImages: [
                                         @foreach($gallery as $photo)
                                             {
                                                 src: '{{ str_starts_with($photo->image_path, 'http') ? $photo->image_path : asset('storage/' . $photo->image_path) }}',
                                                 caption: '{{ addslashes($photo->caption ?? $event->title) }}'
                                             },
                                         @endforeach
                                     ],
                                     init() {
                                         this.$nextTick(() => {
                                             if (this.$refs.lightboxModal) {
                                                 document.body.appendChild(this.$refs.lightboxModal);
                                             }
                                         });
                                     },
                                     nextImage() {
                                         if (this.galleryImages.length > 0) {
                                             this.lightboxIndex = (this.lightboxIndex + 1) % this.galleryImages.length;
                                         }
                                     },
                                     prevImage() {
                                         if (this.galleryImages.length > 0) {
                                             this.lightboxIndex = (this.lightboxIndex - 1 + this.galleryImages.length) % this.galleryImages.length;
                                         }
                                     }
                                 }"
                                 @keydown.window.escape="lightbox = false" 
                                 @keydown.window.right="if(lightbox) nextImage()" 
                                 @keydown.window.left="if(lightbox) prevImage()">

                                @foreach($gallery as $index => $photo)
                                    <div class="aspect-video rounded-xl overflow-hidden bg-slate-50 border border-slate-100 group relative cursor-pointer"
                                         @click="lightboxIndex = {{ $index }}; lightbox = true">
                                        <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                             src="{{ str_starts_with($photo->image_path, 'http') ? $photo->image_path : asset('storage/' . $photo->image_path) }}"
                                             alt="{{ $photo->caption }}">
                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <span class="text-white text-xs font-bold">🔍 Zoom</span>
                                        </div>
                                    </div>
                                @endforeach

                                 <!-- Lightbox Modal with Navigation Arrows -->
                                 <div x-ref="lightboxModal" x-show="lightbox" 
                                      x-transition:enter="transition ease-out duration-300"
                                      x-transition:enter-start="opacity-0 scale-95"
                                      x-transition:enter-end="opacity-100 scale-100"
                                      x-transition:leave="transition ease-in duration-200"
                                      x-transition:leave-start="opacity-100 scale-100"
                                      x-transition:leave-end="opacity-0 scale-95"
                                      style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 9999999 !important; background-color: rgba(2, 6, 23, 0.96); backdrop-filter: blur(20px); overflow: hidden;"
                                      class="flex flex-col items-center justify-between p-4 sm:p-6 select-none relative" 
                                      @click="lightbox = false" 
                                      x-cloak>
                                     
                                     <!-- PREVIOUS ARROW BUTTON (<) -->
                                     <button x-show="galleryImages.length > 1" 
                                             @click.stop="prevImage()" 
                                             style="position: absolute; left: 1.5rem; top: 50%; transform: translateY(-50%); z-index: 10000000;"
                                             class="left-4 sm:left-8 group p-2.5 sm:p-3 rounded-full bg-slate-900/80 hover:bg-primary-500 text-white border border-white/20 hover:border-primary-400 shadow-2xl transition-all duration-300 hover:scale-110 active:scale-95 cursor-pointer backdrop-blur-md"
                                             title="Previous Image (Left Arrow)">
                                            <svg class="w-5 h-5 sm:w-6 sm:h-6 transition-transform duration-300 group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                                            </svg>
                                        </button>

                                        <!-- NEXT ARROW BUTTON (>) -->
                                        <button x-show="galleryImages.length > 1" 
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
                                            <div class="relative flex flex-col items-center justify-center max-w-full max-h-full">
                                                <div class="relative rounded-2xl overflow-hidden shadow-2xl border border-white/20 bg-slate-900/50">
                                                    <img :src="galleryImages[lightboxIndex]?.src" 
                                                         :alt="galleryImages[lightboxIndex]?.caption || 'Gallery Image'" 
                                                         class="w-auto max-w-full object-contain rounded-xl shadow-2xl transition-all duration-300"
                                                         style="max-height: 68vh; max-width: 80vw;">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Bottom Bar: Image Numbers Counter (Bottom Center) -->
                                        <div x-show="galleryImages.length > 0" 
                                             style="position: absolute; bottom: 1rem; left: 50%; transform: translateX(-50%); z-index: 10000000;"
                                             class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full bg-slate-900/90 border border-white/20 text-white text-xs font-bold shadow-2xl backdrop-blur-md">
                                            <span class="text-primary-400">🖼️</span>
                                            <span>
                                                <span class="text-white" x-text="lightboxIndex + 1"></span>
                                                <span class="text-white/40"> / </span>
                                                <span class="text-white/70" x-text="galleryImages.length"></span>
                                            </span>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Right: Registration Widget -->
                <div x-data="{ showConfirmModal: false }">
                    <div class="bg-slate-50 rounded-3xl p-8 border border-slate-100 shadow-sm space-y-6">
                        <h3 class="text-lg font-bold text-slate-950 border-b border-slate-200 pb-4">
                            @if(($event->event_type ?? 'normal') === 'inam_vitaran')
                                {{ __('messages.inam_vitaran_registration') }}
                            @elseif(($event->event_type ?? 'normal') === 'yuva_melo')
                                {{ __('messages.yuva_melo_registration') }}
                            @else
                                {{ __('messages.event_registration') }}
                            @endif
                        </h3>

                        <div class="space-y-4">
                            @if($event->date < now()->toDateString())
                                <!-- Finished Event -->
                                <div class="p-4 rounded-xl bg-slate-100 text-slate-500 text-xs font-bold text-center">
                                    {{ __('messages.event_concluded') }}
                                </div>
                            @elseif($event->status === 'cancelled')
                                <!-- Cancelled Event -->
                                <div
                                    class="p-4 rounded-xl bg-rose-50 border border-rose-100 text-rose-600 text-xs font-bold text-center">
                                    {{ __('messages.event_cancelled') }}
                                </div>
                            @else
                                <!-- Active Event Registration check -->
                                @if(($event->event_type ?? 'normal') === 'yuva_melo')
                                    <!-- Yuva Melo Registration (NO LOGIN REQUIRED) -->
                                    <div class="space-y-3">
                                        <div class="p-3.5 bg-purple-50 border border-purple-100 rounded-xl text-purple-900 text-xs font-semibold space-y-1">
                                            <div class="flex items-center gap-1.5 font-bold text-purple-700">
                                                <span>⚡ Open Public Registration</span>
                                            </div>
                                            <p class="text-[11px] text-purple-800">Anyone can fill this form without login. If you are a member, enter your Member ID inside the form.</p>
                                        </div>
                                        <a href="{{ route('events.public_register_form', $event->id) }}" class="w-full flex items-center justify-center px-4 py-3.5 bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold rounded-xl shadow-sm transition-colors text-center gap-1.5 cursor-pointer">
                                            ⚡ Fill Yuva Melo Form &rarr;
                                        </a>
                                    </div>
                                @elseif(auth()->guest())
                                    <div class="space-y-4">
                                        <p class="text-xs text-slate-500 text-center">{{ __('messages.please_login_to_register') }}</p>
                                        <a href="{{ route('login') }}"
                                            class="w-full flex items-center justify-center px-4 py-3 bg-primary-500 hover:bg-primary-600 text-white text-xs font-bold rounded-xl shadow-sm transition-colors text-center">
                                            {{ __('messages.login_to_register') }}
                                        </a>
                                    </div>
                                @else
                                    @if(auth()->user()->hasRole('Administrator'))
                                        <div class="p-4 rounded-xl bg-slate-100 text-slate-500 text-xs font-bold text-center">
                                            {{ __('messages.administrator_account') }}
                                        </div>
                                    @elseif(auth()->user()->status !== 'approved')
                                        <div
                                            class="p-4 rounded-xl bg-amber-50 border border-amber-100 text-amber-800 text-xs font-bold text-center leading-relaxed">
                                            {!! __('messages.account_status_currently', ['status' => '<strong>'.e(auth()->user()->status).'</strong>']) !!} {{ __('messages.register_once_approved') }}
                                        </div>
                                    @else
                                        <!-- Approved Member Registration check -->
                                        @if($registration)
                                            @if(($event->event_type ?? 'normal') === 'inam_vitaran')
                                                <div class="space-y-2">
                                                    <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-800 text-xs font-bold text-center">
                                                        <span>{{ __('messages.registered_check') }}</span>
                                                    </div>
                                                    <a href="{{ route('events.public_register_form', $event->id) }}" class="w-full flex items-center justify-center px-4 py-3 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-xl shadow-sm transition-colors text-center gap-1.5">
                                                        {{ __('messages.fill_inam_form') }}
                                                    </a>
                                                </div>
                                            @else
                                                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-800 text-xs font-bold text-center flex flex-col items-center justify-center gap-1">
                                                    <span>{{ __('messages.you_are_registered') }}</span>
                                                    <span class="text-[10px] text-emerald-600 font-semibold">{{ __('messages.seat_confirmed') }}</span>
                                                </div>
                                            @endif
                                        @else
                                            @if(($event->event_type ?? 'normal') === 'inam_vitaran')
                                                <a href="{{ route('events.public_register_form', $event->id) }}" class="w-full flex items-center justify-center px-4 py-3.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-xl shadow-sm transition-colors text-center gap-1.5">
                                                    🏆 Fill Inam Form &rarr;
                                                </a>
                                            @else
                                                <form method="POST" action="{{ route('events.public_register', $event->id) }}">
                                                    @csrf
                                                    <button type="submit" class="w-full flex items-center justify-center px-4 py-3 bg-primary-500 hover:bg-primary-600 text-white text-xs font-bold rounded-xl shadow-sm transition-colors text-center cursor-pointer">
                                                        {{ __('messages.register_seat_now') }}
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    @endif
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection