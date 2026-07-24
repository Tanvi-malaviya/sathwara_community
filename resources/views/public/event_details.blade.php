@extends('layouts.public')

@section('content')
    <!-- Event Header Banner -->
    <section class="relative h-96 w-full bg-slate-900 overflow-hidden">
        @if(!empty($event->banner_path) && (str_starts_with($event->banner_path, 'http') || file_exists(public_path('storage/' . $event->banner_path))))
            <img class="absolute inset-0 w-full h-full object-cover opacity-40 bg-center"
                src="{{ str_starts_with($event->banner_path, 'http') ? $event->banner_path : asset('storage/' . $event->banner_path) }}"
                alt="{{ $event->title }}">
        @else
            <div class="absolute inset-0 bg-gradient-to-br from-primary-600 via-indigo-900 to-slate-950 opacity-90"></div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/60 to-transparent"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex flex-col justify-end pb-12 text-white">
            <div class="max-w-4xl space-y-4">
                <!-- Breadcrumbs -->
                <nav class="flex items-center gap-2 text-xs font-semibold text-slate-300">
                    <a href="{{ route('home') }}" class="hover:text-white transition-colors flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>{{ __('messages.home') }}</span>
                    </a>
                    <span class="text-slate-500">/</span>
                    <a href="{{ route('events') }}" class="hover:text-white transition-colors">
                        <span>{{ __('messages.events') }}</span>
                    </a>
                    <span class="text-slate-500">/</span>
                    <span class="text-primary-300 font-bold truncate max-w-[200px]">{{ $event->title }}</span>
                </nav>

                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-primary-500/30 text-primary-200 border border-primary-400">
                    {{ __('messages.community_gathering') }}
                </span>
                <h1 class="text-3xl md:text-5xl font-black tracking-tight leading-tight">
                    {{ $event->title }}
                </h1>
                <!-- Meta Details -->
                <div class="flex flex-wrap items-center gap-6 text-xs text-slate-300 font-semibold pt-2">
                    <span class="flex items-center gap-1.5">📅 {{ date('F d, Y', strtotime($event->date)) }}</span>
                    <span class="flex items-center gap-1.5">⏰ {{ date('h:i A', strtotime($event->time)) }}</span>
                    <span class="flex items-center gap-1.5">📍 {{ $event->venue }}</span>
                </div>
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

                    <!-- Event Gallery -->
                    @if($gallery->count() > 0)
                        <div class="space-y-6">
                            <h2 class="text-xl font-extrabold text-slate-900">{{ __('messages.event_gallery') }}</h2>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4" x-data="{ lightbox: false, activeSrc: '' }">
                                @foreach($gallery as $photo)
                                    <div class="aspect-video rounded-xl overflow-hidden bg-slate-50 border border-slate-100 group relative cursor-pointer"
                                        @click="activeSrc = '{{ str_starts_with($photo->image_path, 'http') ? $photo->image_path : asset('storage/' . $photo->image_path) }}'; lightbox = true">
                                        <img class="w-full h-full object-cover"
                                            src="{{ str_starts_with($photo->image_path, 'http') ? $photo->image_path : asset('storage/' . $photo->image_path) }}"
                                            alt="{{ $photo->caption }}">
                                        <div
                                            class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <span class="text-white text-xs font-bold">Zoom</span>
                                        </div>
                                    </div>
                                @endforeach

                                <!-- Lightbox Modal -->
                                <div x-show="lightbox"
                                    class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center p-4" x-cloak>
                                    <button @click="lightbox = false"
                                        class="absolute top-4 right-4 text-white text-3xl font-black">&times;</button>
                                    <img :src="activeSrc" class="max-h-[85vh] max-w-full rounded-lg shadow-2xl">
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

                        @if(in_array($event->event_type ?? 'normal', ['inam_vitaran', 'yuva_melo']))
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
                                    @guest
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
                                                @if(in_array($event->event_type ?? 'normal', ['inam_vitaran', 'yuva_melo']))
                                                    <div class="space-y-2">
                                                        <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-800 text-xs font-bold text-center">
                                                            <span>{{ __('messages.registered_check') }}</span>
                                                        </div>
                                                        @if(($event->event_type ?? 'normal') === 'inam_vitaran')
                                                            <a href="{{ route('member.events.register_form', $event->id) }}" class="w-full flex items-center justify-center px-4 py-3 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-xl shadow-sm transition-colors text-center gap-1.5">
                                                                {{ __('messages.fill_inam_form') }}
                                                            </a>
                                                        @else
                                                            <a href="{{ route('member.events.register_form', $event->id) }}" class="w-full flex items-center justify-center px-4 py-3 bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold rounded-xl shadow-sm transition-colors text-center gap-1.5">
                                                                {{ __('messages.fill_yuva_form') }}
                                                            </a>
                                                        @endif
                                                    </div>
                                                @else
                                                    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-800 text-xs font-bold text-center flex flex-col items-center justify-center gap-1">
                                                        <span>{{ __('messages.you_are_registered') }}</span>
                                                        <span class="text-[10px] text-emerald-600 font-semibold">{{ __('messages.seat_confirmed') }}</span>
                                                    </div>
                                                @endif
                                            @else
                                                @if(($event->event_type ?? 'normal') === 'inam_vitaran')
                                                    <a href="{{ route('member.events.register_form', $event->id) }}" class="w-full flex items-center justify-center px-4 py-3.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-xl shadow-sm transition-colors text-center gap-1.5">
                                                        🏆 Fill Inam Form &rarr;
                                                    </a>
                                                @elseif(($event->event_type ?? 'normal') === 'yuva_melo')
                                                    <a href="{{ route('member.events.register_form', $event->id) }}" class="w-full flex items-center justify-center px-4 py-3.5 bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold rounded-xl shadow-sm transition-colors text-center gap-1.5">
                                                        ⚡ Fill Yuva Form &rarr;
                                                    </a>
                                                @else
                                                    <form method="POST" action="{{ route('member.events.register', $event->id) }}">
                                                        @csrf
                                                        <button type="submit" class="w-full flex items-center justify-center px-4 py-3 bg-primary-500 hover:bg-primary-600 text-white text-xs font-bold rounded-xl shadow-sm transition-colors text-center cursor-pointer">
                                                            {{ __('messages.register_seat_now') }}
                                                        </button>
                                                    </form>
                                                @endif
                                            @endif
                                        @endif
                                    @endguest
                                @endif
                            </div>
                        @else
                            <!-- No registration required -->
                            <div class="p-4 rounded-xl bg-slate-100 text-slate-500 text-xs font-bold text-center">
                                {{ __('messages.open_entry_no_preregistration_required') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection