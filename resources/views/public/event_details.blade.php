@extends('layouts.public')

@section('content')
    <!-- Event Header Banner (Top Hero Section) -->
    <section class="relative bg-slate-950 text-white overflow-hidden py-10 md:py-14">
        <!-- Ambient background lighting -->
        <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-900 to-slate-950"></div>
        <div class="absolute top-0 right-1/4 w-96 h-96 bg-primary-600/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                
                <!-- Left Side Text & Details -->
                <div class="{{ !empty($event->banner_path) ? 'lg:col-span-8' : 'lg:col-span-12' }} space-y-4">
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
                    <div class="flex flex-wrap items-center gap-3 text-xs text-slate-200 font-semibold pt-1">
                        <div class="inline-flex items-center gap-2 bg-slate-900/90 border border-slate-800 px-3.5 py-2 rounded-xl whitespace-nowrap shadow-xs">
                            <span>📅</span>
                            <span>{{ date('F d, Y', strtotime($event->date)) }}</span>
                        </div>
                        <div class="inline-flex items-center gap-2 bg-slate-900/90 border border-slate-800 px-3.5 py-2 rounded-xl whitespace-nowrap shadow-xs">
                            <span>⏰</span>
                            <span>{{ date('h:i A', strtotime($event->time)) }}</span>
                        </div>
                        <div class="inline-flex items-center gap-2 bg-slate-900/90 border border-slate-800 px-3.5 py-2 rounded-xl shadow-xs">
                            <span>📍</span>
                            <span class="truncate max-w-xs sm:max-w-md" title="{{ $event->venue }}">{{ $event->venue }}</span>
                        </div>
                        @if(!empty($event->registration_end_date))
                            <div class="inline-flex items-center gap-2 text-rose-300 font-extrabold bg-rose-950/80 px-3.5 py-2 rounded-xl border border-rose-700/60 whitespace-nowrap shadow-xs">
                                <span>⏳</span>
                                <span>{{ __('messages.last_date') }}: {{ date('d-M-Y', strtotime($event->registration_end_date)) }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right Side Small Image -->
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
    <section class="py-12 md:py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">
                
                <!-- Left: Description / Gallery (7 cols) -->
                <div class="lg:col-span-7 space-y-10">
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
                                    <span>📍 {{ __('messages.event_location_venue') }}</span>
                                </h2>
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

                                 <!-- Lightbox Modal -->
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
                                     
                                     <button x-show="galleryImages.length > 1" 
                                             @click.stop="prevImage()" 
                                             style="position: absolute; left: 1.5rem; top: 50%; transform: translateY(-50%); z-index: 10000000;"
                                             class="left-4 sm:left-8 group p-2.5 sm:p-3 rounded-full bg-slate-900/80 hover:bg-primary-500 text-white border border-white/20 hover:border-primary-400 shadow-2xl transition-all duration-300 hover:scale-110 active:scale-95 cursor-pointer backdrop-blur-md"
                                             title="Previous Image (Left Arrow)">
                                            <svg class="w-5 h-5 sm:w-6 sm:h-6 transition-transform duration-300 group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                                            </svg>
                                        </button>

                                        <button x-show="galleryImages.length > 1" 
                                                @click.stop="nextImage()" 
                                                style="position: absolute; right: 1.5rem; top: 50%; transform: translateY(-50%); z-index: 10000000;"
                                                class="right-4 sm:right-8 group p-2.5 sm:p-3 rounded-full bg-slate-900/80 hover:bg-primary-500 text-white border border-white/20 hover:border-primary-400 shadow-2xl transition-all duration-300 hover:scale-110 active:scale-95 cursor-pointer backdrop-blur-md"
                                                title="Next Image (Right Arrow)">
                                            <svg class="w-5 h-5 sm:w-6 sm:h-6 transition-transform duration-300 group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </button>

                                        <div class="w-full flex items-center justify-end z-[10000000] max-w-7xl mx-auto pt-1 px-2" @click.stop>
                                            <button @click="lightbox = false" 
                                                    class="group p-2 rounded-full bg-slate-900/80 hover:bg-rose-500 text-white border border-white/20 hover:border-rose-400 transition-all duration-300 cursor-pointer shadow-xl hover:rotate-90 hover:scale-110 active:scale-95"
                                                    title="Close (Esc)">
                                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>

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

                    <!-- Yuva Melo Biodata Registration Card (Below Gallery) -->
                    @if(($event->event_type ?? 'normal') === 'yuva_melo')
                        <div class="rounded-2xl sm:rounded-3xl p-5 sm:p-6 bg-gradient-to-r from-purple-50 via-indigo-50/50 to-white border border-purple-200 shadow-sm space-y-4">
                            <div class="flex items-center justify-between gap-3 flex-wrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-11 rounded-2xl bg-purple-600 text-white flex items-center justify-center text-xl font-black shadow-sm shrink-0">
                                        ⚡
                                    </div>
                                    <div>
                                        <span class="text-[10px] font-extrabold text-purple-700 uppercase tracking-wider block">{{ __('messages.candidate_biodata_form') }}</span>
                                        <h3 class="text-base sm:text-lg font-black text-slate-900 leading-tight">{{ __('messages.yuva_melo_registration') }}</h3>
                                    </div>
                                </div>

                                <!-- Fee Pill -->
                                <div class="bg-white border border-purple-200 px-3.5 py-1.5 rounded-xl flex items-center gap-2 shadow-2xs">
                                    <span class="text-xs text-slate-500 font-bold">{{ __('messages.form_fee') }}:</span>
                                    @if(($event->form_fee ?? 0) > 0)
                                        <span class="text-base font-black text-purple-700">₹{{ number_format($event->form_fee) }}</span>
                                    @else
                                        <span class="text-xs font-black text-emerald-700 uppercase bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">{{ __('messages.free') }}</span>
                                    @endif
                                </div>
                            </div>

                            <p class="text-xs text-slate-600 leading-relaxed font-medium">
                                {{ __('messages.yuva_melo_card_desc') }}
                            </p>

                            <div class="pt-1 flex items-center">
                                <a href="{{ route('events.public_register_form', $event->id) }}"
                                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 sm:px-6 sm:py-3 bg-purple-600 hover:bg-purple-700 active:scale-95 text-white text-xs sm:text-sm font-extrabold rounded-xl shadow-md hover:shadow-lg transition-all cursor-pointer">
                                    <span>⚡ {{ __('messages.fill_yuva_form') ?? 'Fill Yuva Melo Registration Form' }}</span>
                                    <span>&rarr;</span>
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Right: Registration Widget (5 cols) -->
                <div class="lg:col-span-5 lg:sticky lg:top-24">
                    <div class="bg-slate-50 rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-md space-y-6">
                        <h3 class="text-lg font-extrabold text-slate-950 border-b border-slate-200 pb-4 flex items-center justify-between gap-2 flex-wrap">
                            <span>
                                @if(($event->event_type ?? 'normal') === 'inam_vitaran')
                                    {{ __('messages.inam_vitaran_registration') }}
                                @elseif(($event->event_type ?? 'normal') === 'yuva_melo')
                                    {{ __('messages.yuva_melo_registration') }}
                                @else
                                    {{ __('messages.event_registration') }}
                                @endif
                            </span>
                            <span class="text-[10px] font-extrabold px-2.5 py-1 rounded-full uppercase tracking-wider shadow-2xs {{ ($event->event_type ?? 'normal') === 'inam_vitaran' ? 'text-amber-800 bg-amber-100 border border-amber-300' : (($event->event_type ?? 'normal') === 'yuva_melo' ? 'text-purple-800 bg-purple-100 border border-purple-300' : 'text-primary-700 bg-primary-50 border border-primary-200') }}">
                                @if(($event->event_type ?? 'normal') === 'inam_vitaran')
                                    🏆 {{ __('messages.inam_vitaran') }}
                                @elseif(($event->event_type ?? 'normal') === 'yuva_melo')
                                    ⚡ {{ __('messages.yuva_melo') }}
                                @else
                                    🎟️ {{ __('messages.general_event') }}
                                @endif
                            </span>
                        </h3>

                        <div class="space-y-4">
                            @if($event->date < now()->toDateString())
                                <!-- Finished Event -->
                                <div class="p-4 rounded-2xl bg-slate-100 border border-slate-200 text-slate-500 text-xs font-bold text-center">
                                    {{ __('messages.event_concluded') }}
                                </div>
                            @elseif($event->status === 'cancelled')
                                <!-- Cancelled Event -->
                                <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-600 text-xs font-bold text-center">
                                    {{ __('messages.event_cancelled') }}
                                </div>
                            @else
                                <!-- Active Event Registration check for all event types -->
                                <div x-data="{ showPassModal: false, showViewPassesModal: false, count: 1, passFee: {{ (float)($event->pass_fee ?? 0) }} }"
                                     @close-all-modals.window="showPassModal = false; showViewPassesModal = false">
                                    @if(auth()->guest())
                                        <div class="space-y-4">
                                            @if(($event->pass_fee ?? 0) > 0)
                                                <div class="bg-primary-50/90 border border-primary-200/80 rounded-xl p-3 flex items-center justify-between">
                                                    <span class="text-xs font-bold text-primary-800">🎟️ {{ __('messages.pass_fee_per_person') }}:</span>
                                                    <span class="text-sm font-black text-primary-700">₹{{ number_format($event->pass_fee) }}</span>
                                                </div>
                                            @endif
                                            <p class="text-xs text-slate-500 text-center">{{ __('messages.please_login_to_register') }}</p>
                                            <a href="{{ route('login') }}"
                                                class="w-full flex items-center justify-center px-4 py-3.5 bg-primary-500 hover:bg-primary-600 active:scale-95 text-white text-xs font-bold rounded-2xl shadow-sm transition-colors text-center cursor-pointer">
                                                {{ __('messages.login_to_register') }}
                                            </a>
                                        </div>
                                    @elseif(auth()->check() && auth()->user()->hasRole('Administrator'))
                                        <div class="p-4 rounded-2xl bg-slate-100 text-slate-500 text-xs font-bold text-center">
                                            {{ __('messages.administrator_account') }}
                                        </div>
                                    @elseif(auth()->check() && auth()->user()->status !== 'approved')
                                        <div class="p-4 rounded-2xl bg-amber-50 border border-amber-100 text-amber-800 text-xs font-bold text-center leading-relaxed">
                                            {!! __('messages.account_status_currently', ['status' => '<strong>'.e(auth()->user()->status).'</strong>']) !!} {{ __('messages.register_once_approved') }}
                                        </div>
                                    @else
                                        <!-- Normal, Inam Vitaran and Yuva Melo Attendee Passes -->
                                        <div class="space-y-4">
                                            @if(($event->pass_fee ?? 0) > 0)
                                                <div class="bg-primary-50/90 border border-primary-200/80 rounded-2xl p-3.5 flex items-center justify-between shadow-2xs">
                                                    <div>
                                                        <span class="text-xs font-black text-primary-900 block">🎟️ {{ __('messages.pass_fee_per_person') }}</span>
                                                        <span class="text-[10px] text-slate-500 font-semibold">{{ __('messages.per_person_pass_fee') }}</span>
                                                    </div>
                                                    <span class="text-base font-black text-primary-700">₹{{ number_format($event->pass_fee) }}</span>
                                                </div>
                                            @else
                                                <div class="bg-emerald-50/90 border border-emerald-200/80 rounded-2xl p-3 flex items-center justify-between">
                                                    <span class="text-xs font-bold text-emerald-800">🎟️ {{ __('messages.registration_fee') }}:</span>
                                                    <span class="text-xs font-black text-emerald-700 uppercase bg-emerald-100 px-2 py-0.5 rounded-lg">{{ __('messages.free_entry') }}</span>
                                                </div>
                                            @endif

                                            @if($registration)
                                                @php
                                                    $regPersons = max(1, (int)($registration->form_data['person_count'] ?? 1));
                                                    $userPasses = [];
                                                    for ($i = 1; $i <= $regPersons; $i++) {
                                                        $userPasses[] = sprintf('%03d', $i);
                                                    }
                                                    $attendeeName = $registration->form_data['full_name'] ?? (auth()->user() ? auth()->user()->name : 'Member');
                                                    $memberId = auth()->user() ? sprintf('#%05d', auth()->user()->id) : ($registration->form_data['member_id'] ?? '-');
                                                    $logoUrl = App\Models\Setting::get('website_logo') ? asset('storage/' . App\Models\Setting::get('website_logo')) : asset('logo.png');
                                                @endphp

                                                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold text-center flex flex-col items-center justify-center gap-1.5 shadow-2xs">
                                                    <span class="flex items-center gap-1.5 text-emerald-900 font-black text-sm">
                                                        <span>✅ {{ __('messages.registered_status') }}</span>
                                                    </span>
                                                    <span class="text-xs text-emerald-700 font-semibold">
                                                        {{ __('messages.attending_persons_count', ['count' => $regPersons]) }}
                                                    </span>
                                                    @if(($event->pass_fee ?? 0) > 0)
                                                        <span class="text-[10px] px-2.5 py-0.5 rounded-full uppercase font-black bg-emerald-200/80 text-emerald-900 border border-emerald-300">
                                                            💳 {{ strtoupper($registration->payment_status ?? 'unpaid') }} (₹{{ number_format($registration->payment_amount ?? ($event->pass_fee * $regPersons)) }})
                                                        </span>
                                                    @endif
                                                </div>

                                                <!-- View My Passes Button -->
                                                <button type="button" 
                                                        @click="showViewPassesModal = true"
                                                        class="w-full flex items-center justify-center px-4 py-3 bg-slate-900 hover:bg-slate-800 active:scale-95 text-white text-xs font-extrabold rounded-2xl shadow-md hover:shadow-lg transition-all text-center cursor-pointer gap-2">
                                                    <span>🎟️ {{ __('messages.view_my_passes', ['count' => $regPersons]) }}</span>
                                                    <span>👁️</span>
                                                </button>

                                                <!-- Purchase More Passes Button -->
                                                <button type="button" 
                                                        @click="count = 1; showPassModal = true;"
                                                        class="w-full flex items-center justify-center px-4 py-2.5 bg-primary-600 hover:bg-primary-700 active:scale-95 text-white text-xs font-extrabold rounded-2xl shadow-sm hover:shadow-md transition-all text-center cursor-pointer gap-2">
                                                    <span>➕ {{ __('messages.purchase_more_passes') }}</span>
                                                    <span>&rarr;</span>
                                                </button>
                                            @else
                                                <!-- Purchase Pass Button -->
                                                <button type="button" 
                                                        @click="count = 1; showPassModal = true;"
                                                        class="w-full flex items-center justify-center px-4 py-3.5 bg-primary-600 hover:bg-primary-700 active:scale-95 text-white text-xs font-extrabold rounded-2xl shadow-md hover:shadow-lg transition-all text-center cursor-pointer gap-2">
                                                    <span>🎟️ {{ __('messages.purchase_pass') }}</span>
                                                    <span>&rarr;</span>
                                                </button>
                                            @endif
                                        </div>

                                        @if($registration)
                                            <!-- View Passes Modal (Teleported to Body) -->
                                            <template x-teleport="body">
                                                <div x-show="showViewPassesModal" 
                                                     class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-slate-950/70 backdrop-blur-md"
                                                     x-transition
                                                     x-cloak>
                                                    <div @click.away="showViewPassesModal = false" 
                                                         class="bg-white rounded-3xl border border-slate-100 shadow-2xl max-w-2xl w-full max-h-[90vh] flex flex-col overflow-hidden relative">
                                                        
                                                        <!-- Modal Header -->
                                                        <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between shrink-0">
                                                            <div class="flex items-center gap-3">
                                                                <div class="w-9 h-9 rounded-xl bg-primary-600/30 border border-primary-500/40 text-primary-400 flex items-center justify-center text-lg">
                                                                    🎟️
                                                                </div>
                                                                <div>
                                                                    <h3 class="text-sm font-extrabold flex items-center gap-2">
                                                                        <span>{{ __('messages.event_entry_passes') }}</span>
                                                                        <span class="text-[10px] bg-primary-500 text-white font-black px-2 py-0.5 rounded-full">{{ count($userPasses) }} {{ __('messages.passes') }}</span>
                                                                    </h3>
                                                                    <p class="text-[11px] text-slate-400 font-medium truncate max-w-[280px] sm:max-w-md">{{ $event->title }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="flex items-center gap-2">
                                                                <button type="button" onclick="downloadAllPasses()" 
                                                                        class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-extrabold rounded-xl shadow-xs transition-colors flex items-center gap-1.5 cursor-pointer">
                                                                    ⬇️ {{ __('messages.download_all_pdf') }}
                                                                </button>
                                                                <button type="button" @click="showViewPassesModal = false" 
                                                                        class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors text-xs font-bold cursor-pointer">
                                                                    ✕
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <!-- Modal Scrollable Content containing all passes -->
                                                        <div class="p-4 sm:p-6 overflow-y-auto space-y-6 bg-slate-50 flex-1" id="printablePassesArea">
                                                            @foreach($userPasses as $idx => $pNo)
                                                                <div class="bg-white rounded-2xl border-2 border-slate-900 shadow-sm overflow-hidden text-slate-900 print-pass-item" 
                                                                     id="pass-card-{{ $idx }}"
                                                                     data-pass-no="{{ $pNo }}"
                                                                     data-event-title="{{ $event->title }}"
                                                                     data-mandal="Satwara Gyati Mandal Ahm."
                                                                     data-date="{{ date('d-M-Y', strtotime($event->date)) }}{{ $event->time ? ' | ⏰ ' . date('h:i A', strtotime($event->time)) : '' }}"
                                                                     data-venue="{{ $event->venue }}"
                                                                     data-logo="{{ $logoUrl }}">
                                                                    <!-- Top Bar -->
                                                                    <div class="bg-slate-900 text-white px-4 py-2 flex items-center justify-between text-[11px] font-black uppercase tracking-wider">
                                                                        <span>{{ __('messages.community_entry_pass') }}</span>
                                                                        <span class="text-primary-400">{{ __('messages.pass') }}</span>
                                                                    </div>

                                                                    <!-- Pass Core (Sketch Layout) -->
                                                                    <div class="p-4 sm:p-5 flex flex-col sm:flex-row items-center sm:items-start justify-between gap-4">
                                                                        <!-- Left: Circular Logo -->
                                                                        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full border-2 border-slate-300 bg-slate-50 flex items-center justify-center overflow-hidden shrink-0 shadow-inner">
                                                                            <img src="{{ $logoUrl }}" alt="Logo" class="w-full h-full object-cover" onerror="this.src='/logo.png'">
                                                                        </div>

                                                                        <!-- Middle Details: Mandal, Event Name, Date, Attendee -->
                                                                        <div class="flex-1 space-y-1.5 text-center sm:text-left">
                                                                            <div class="text-xs sm:text-sm font-black text-slate-900 uppercase tracking-tight">
                                                                                Satwara Gyati Mandal Ahm.
                                                                            </div>
                                                                            <div class="text-base sm:text-lg font-black text-rose-600 leading-tight">
                                                                                {{ $event->title }}
                                                                            </div>
                                                                            <div class="text-xs font-bold text-slate-700 flex items-center justify-center sm:justify-start gap-1">
                                                                                <span>📅 {{ __('messages.date') ?? 'Date' }}:</span>
                                                                                <span>{{ date('d-M-Y', strtotime($event->date)) }}</span>
                                                                                @if($event->time)
                                                                                    <span class="text-slate-400">|</span>
                                                                                    <span>⏰ {{ date('h:i A', strtotime($event->time)) }}</span>
                                                                                @endif
                                                                            </div>

                                                                        </div>

                                                                        <!-- Right: Dedicated Pass No. Box -->
                                                                        <div class="shrink-0 flex flex-col items-center sm:items-end justify-between self-stretch pt-2 sm:pt-0">
                                                                            <div class="border-2 border-slate-900 rounded-xl px-4 py-2 bg-slate-50 text-center shadow-xs">
                                                                                <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-widest block">{{ __('messages.pass_no') }}</span>
                                                                                <span class="text-xl font-black text-slate-900 block mt-0.5 tracking-widest">{{ $pNo }}</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Bottom Location Strip -->
                                                                    <div class="border-t-2 border-dashed border-slate-200 bg-slate-50/80 px-4 py-2.5 text-xs font-bold text-slate-700 flex items-center justify-between gap-1.5">
                                                                        <span class="flex items-center gap-1.5">
                                                                            <span class="text-rose-500">📍</span>
                                                                            <span><strong>{{ __('messages.location') ?? 'Location' }}:</strong> {{ $event->venue }}</span>
                                                                        </span>
                                                                        <button type="button" onclick="downloadSinglePass('pass-card-{{ $idx }}')"
                                                                                class="flex items-center gap-1 px-2.5 py-1 bg-slate-900 hover:bg-slate-700 text-white text-[10px] font-extrabold rounded-lg transition-colors cursor-pointer no-print">
                                                                            ⬇️ {{ __('messages.download') }}
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>

                                                        <!-- Modal Footer -->
                                                        <div class="px-6 py-3 bg-white border-t border-slate-100 flex items-center justify-between shrink-0">
                                                            <span class="text-[11px] text-slate-400 font-medium">💡 {{ __('messages.present_pass_at_entrance') }}</span>
                                                            <button type="button" @click="showViewPassesModal = false" 
                                                                    class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-colors cursor-pointer">
                                                                {{ __('messages.close') ?? 'Close' }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        @endif

                                        <!-- Purchase Pass Pop-up Modal -->
                                        <template x-teleport="body">
                                            <div x-show="showPassModal" 
                                                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
                                                 x-transition:enter="ease-out duration-200"
                                                 x-transition:enter-start="opacity-0"
                                                 x-transition:enter-end="opacity-100"
                                                 x-transition:leave="ease-in duration-150"
                                                 x-transition:leave-start="opacity-100"
                                                 x-transition:leave-end="opacity-0"
                                                 x-cloak>
                                                <div @click.away="showPassModal = false" 
                                                     class="bg-white rounded-3xl border border-slate-100 shadow-2xl max-w-md w-full overflow-hidden relative">
                                                    
                                                    <!-- Modal Header -->
                                                    <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between">
                                                        <div>
                                                            <h3 class="text-sm font-extrabold flex items-center gap-2">
                                                                <span>🎟️ {{ __('messages.purchase_event_pass') }}</span>
                                                            </h3>
                                                            <p class="text-[11px] text-slate-400 font-medium mt-0.5 truncate max-w-[280px]">{{ $event->title }}</p>
                                                        </div>
                                                        <button type="button" @click="showPassModal = false" 
                                                                class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors text-xs font-bold cursor-pointer">
                                                            ✕
                                                        </button>
                                                    </div>

                                                    <!-- Modal Body / Form -->
                                                    <form method="POST" action="{{ route('events.public_register', $event->id) }}" id="publicEventRegisterForm" class="p-6 space-y-5">
                                                        @csrf
                                                        <input type="hidden" name="razorpay_payment_id" id="event_razorpay_payment_id">
                                                        <input type="hidden" name="person_count" :value="count">

                                                        <!-- Event Snippet -->
                                                        <div class="p-3 bg-slate-50 border border-slate-200 rounded-2xl flex items-center justify-between text-xs">
                                                            <span class="font-bold text-slate-700">📅 {{ date('d-M-Y', strtotime($event->date)) }}</span>
                                                            @if(($event->pass_fee ?? 0) > 0)
                                                                <span class="font-black text-primary-700 bg-primary-50 px-2.5 py-1 rounded-xl border border-primary-200 text-xs">₹{{ number_format($event->pass_fee) }} / {{ __('messages.pass') }}</span>
                                                            @else
                                                                <span class="font-black text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-xl border border-emerald-200 text-xs">{{ __('messages.free_entry') }}</span>
                                                            @endif
                                                        </div>

                                                        <!-- Person Selector -->
                                                        <div class="space-y-2">
                                                            <label class="text-xs font-extrabold text-slate-800 flex items-center justify-between">
                                                                <span>{{ __('messages.ketla_person_attending') }}</span>
                                                                <span class="text-[10px] font-bold text-slate-400">{{ __('messages.total_persons_coming') }}</span>
                                                            </label>
                                                            
                                                            <div class="flex items-center justify-between bg-slate-50 p-2 rounded-2xl border border-slate-200 shadow-2xs">
                                                                <button type="button" 
                                                                        @click="if (count > 1) count--" 
                                                                        :disabled="count <= 1"
                                                                        class="w-12 h-12 rounded-xl bg-white hover:bg-slate-100 active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed border border-slate-200 text-slate-800 font-black text-2xl flex items-center justify-center transition-all cursor-pointer shadow-xs">
                                                                    &minus;
                                                                </button>

                                                                <div class="flex items-center gap-2 px-3">
                                                                    <span class="text-2xl font-black text-primary-600" x-text="count"></span>
                                                                    <span class="text-xs font-extrabold text-slate-700 uppercase tracking-wider" x-text="count > 1 ? '{{ __('messages.persons') }}' : '{{ __('messages.person') }}'"></span>
                                                                </div>

                                                                <button type="button" 
                                                                        @click="if (count < 20) count++" 
                                                                        :disabled="count >= 20"
                                                                        class="w-12 h-12 rounded-xl bg-white hover:bg-slate-100 active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed border border-slate-200 text-slate-800 font-black text-2xl flex items-center justify-center transition-all cursor-pointer shadow-xs">
                                                                    &plus;
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <!-- Total Breakdown -->
                                                        <div class="p-4 bg-primary-50/80 border border-primary-200/80 rounded-2xl space-y-2">
                                                            <div class="flex items-center justify-between text-xs font-bold text-slate-600">
                                                                <span>{{ __('messages.pass_fee') }}:</span>
                                                                <span>₹<span x-text="passFee"></span> &times; <span x-text="count"></span></span>
                                                            </div>
                                                            <div class="border-t border-primary-200/60 pt-2 flex items-center justify-between">
                                                                <span class="text-xs font-extrabold text-primary-950">{{ __('messages.total_amount') }}:</span>
                                                                <span class="text-lg font-black text-primary-700">₹<span x-text="(count * passFee).toLocaleString()"></span></span>
                                                            </div>
                                                        </div>

                                                        <!-- Action Buttons -->
                                                        <div class="flex items-center gap-3 pt-2">
                                                            <button type="button" @click="showPassModal = false" 
                                                                    class="px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-2xl transition-colors cursor-pointer">
                                                                {{ __('messages.cancel') }}
                                                            </button>
                                                            <button type="submit" 
                                                                    class="flex-1 py-3.5 px-4 bg-primary-600 hover:bg-primary-700 active:scale-95 text-white font-black text-xs rounded-2xl shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer uppercase tracking-wider">
                                                                @if(($event->pass_fee ?? 0) > 0)
                                                                    <span>{{ __('messages.pay_and_confirm_booking') }} (₹<span x-text="(count * passFee).toLocaleString()"></span>)</span>
                                                                @else
                                                                    <span>{{ __('messages.confirm_registration_count') }} (<span x-text="count"></span> {{ __('messages.person') }})</span>
                                                                @endif
                                                                <span>&rarr;</span>
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </template>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@if(($event->pass_fee ?? 0) > 0)
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('publicEventRegisterForm');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        const paymentIdInput = document.getElementById('event_razorpay_payment_id');
        if (paymentIdInput && paymentIdInput.value) {
            return true; // Already paid
        }

        e.preventDefault();

        const passFee = {{ (float)($event->pass_fee ?? 0) }};
        const personCountInput = form.querySelector('[name="person_count"]');
        const personCount = personCountInput ? parseInt(personCountInput.value) || 1 : 1;
        const totalAmountPaise = Math.round(passFee * personCount * 100);

        const razorpayKey = "{{ \App\Models\Setting::get('razorpay_key_id', env('RAZORPAY_KEY_ID', '')) }}";
        const userEmail = "{{ auth()->user() ? auth()->user()->email : '' }}";
        const userName = "{{ auth()->user() ? auth()->user()->name : '' }}";
        const userPhone = "{{ (auth()->user() && auth()->user()->memberProfile) ? auth()->user()->memberProfile->phone : '' }}";

        const options = {
            "key": razorpayKey || "rzp_test_key",
            "amount": totalAmountPaise,
            "currency": "INR",
            "name": "{{ config('app.name', 'Sathwara Community') }}",
            "description": "Event Pass Booking - {{ addslashes($event->title) }} (" + personCount + " Person/s)",
            "handler": function (response) {
                paymentIdInput.value = response.razorpay_payment_id;
                window.dispatchEvent(new CustomEvent('close-all-modals'));
                document.querySelectorAll('[x-show="showPassModal"], [x-show="showViewPassesModal"]').forEach(function(el) {
                    el.style.display = 'none';
                });
                form.submit();
            },
            "prefill": {
                "name": userName,
                "email": userEmail,
                "contact": userPhone
            },
            "theme": {
                "color": "#2563EB"
            }
        };

        if (window.Razorpay) {
            const rzp = new Razorpay(options);
            rzp.open();
        } else {
            alert('Razorpay Payment Gateway failed to load. Submitting registration...');
            form.submit();
        }
    });
});
</script>
@endif

<script>
/* =================== PASS PDF DOWNLOAD =================== */

function _renderPassHtmlCard(passData) {
    const logoSrc = passData.logo || '/logo.png';
    const mandal = passData.mandal || 'Satwara Gyati Mandal Ahm.';
    const title = passData.title || '';
    const date = passData.date || '';
    const passNo = passData.passNo || '001';
    const venue = passData.venue || '';

    return `
    <div style="border: 2px solid #0f172a; border-radius: 12px; overflow: hidden; margin-bottom: 22px; page-break-inside: avoid; background: #ffffff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; box-sizing: border-box;">
        <!-- Top Bar -->
        <table style="width: 100%; border-collapse: collapse; background-color: #0f172a; color: #ffffff;">
            <tr>
                <td style="padding: 7px 16px; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; text-align: left; color: #ffffff;">
                    SATHWARA COMMUNITY ENTRY PASS
                </td>
                <td style="padding: 7px 16px; font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; text-align: right; color: #f59e0b;">
                    ENTRY PASS
                </td>
            </tr>
        </table>

        <!-- Main Body -->
        <table style="width: 100%; border-collapse: collapse; background-color: #ffffff;">
            <tr>
                <!-- Circular Logo -->
                <td style="width: 90px; vertical-align: middle; padding: 14px 0 14px 16px; text-align: center;">
                    <div style="width: 76px; height: 76px; border-radius: 50%; border: 2px solid #cbd5e1; background-color: #f8fafc; overflow: hidden; display: inline-block;">
                        <img src="${logoSrc}" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.style.display='none'">
                    </div>
                </td>

                <!-- Details (Mandal, Title, Date) -->
                <td style="vertical-align: middle; padding: 14px 16px; text-align: left;">
                    <div style="font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: 1.2px; color: #0f172a; margin-bottom: 4px;">
                        ${mandal}
                    </div>
                    <div style="font-size: 16px; font-weight: 900; color: #e11d48; line-height: 1.25; margin-bottom: 6px;">
                        ${title}
                    </div>
                    <div style="font-size: 12px; font-weight: 700; color: #334155;">
                        📅 ${date}
                    </div>
                </td>

                <!-- Pass No Box -->
                <td style="width: 110px; vertical-align: middle; padding: 14px 16px 14px 0; text-align: right;">
                    <div style="display: inline-block; border: 2px solid #0f172a; border-radius: 10px; background-color: #f8fafc; padding: 8px 14px; text-align: center; min-width: 85px; box-sizing: border-box;">
                        <div style="font-size: 8px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: #64748b;">PASS NO.</div>
                        <div style="font-size: 22px; font-weight: 900; letter-spacing: 4px; color: #0f172a; margin-top: 2px;">${passNo}</div>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Bottom Location Strip -->
        <div style="border-top: 2px dashed #e2e8f0; background-color: #f8fafc; padding: 9px 16px; font-size: 11px; font-weight: 700; color: #334155;">
            📍 <strong>Location / Venue:</strong> ${venue}
        </div>
    </div>`;
}

function _openPassesPrintWindow(cardsHtml, title) {
    const w = window.open('', '_blank', 'width=880,height=750');
    if (!w) {
        alert('Please allow pop-ups for this website to download passes.');
        return;
    }
    w.document.write(`<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>${title}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #ffffff; padding: 24px; color: #0f172a; }
        @media print {
            body { padding: 0; }
            @page { margin: 15mm; size: auto; }
        }
    </style>
</head>
<body>
    ${cardsHtml}
</body>
</html>`);
    w.document.close();
    w.focus();
    setTimeout(() => { w.print(); }, 500);
}

function downloadAllPasses() {
    const cards = document.querySelectorAll('.print-pass-item');
    if (!cards.length) return;
    let html = '';
    cards.forEach(card => {
        const data = {
            passNo: card.dataset.passNo || card.querySelector('.text-xl')?.innerText.trim() || '001',
            title: card.dataset.eventTitle || '',
            mandal: card.dataset.mandal || 'Satwara Gyati Mandal Ahm.',
            date: card.dataset.date || '',
            venue: card.dataset.venue || '',
            logo: card.dataset.logo || card.querySelector('img')?.src || ''
        };
        html += _renderPassHtmlCard(data);
    });
    _openPassesPrintWindow(html, 'Event Entry Passes');
}

function downloadSinglePass(cardId) {
    const card = document.getElementById(cardId);
    if (!card) return;
    const data = {
        passNo: card.dataset.passNo || card.querySelector('.text-xl')?.innerText.trim() || '001',
        title: card.dataset.eventTitle || '',
        mandal: card.dataset.mandal || 'Satwara Gyati Mandal Ahm.',
        date: card.dataset.date || '',
        venue: card.dataset.venue || '',
        logo: card.dataset.logo || card.querySelector('img')?.src || ''
    };
    _openPassesPrintWindow(_renderPassHtmlCard(data), 'Event Entry Pass - ' + data.passNo);
}
</script>
@endsection