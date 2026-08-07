@extends('layouts.member')

@section('page_title', __('messages.events'))

@section('content')
<div class="space-y-4">
    <!-- Header banner card -->
    {{-- <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <p class="text-xs text-slate-500">View upcoming community events, matches, meets, and register directly from your member panel.</p>
        </div>
        <!-- Search bar -->
        <form method="GET" action="{{ route('member.events.index') }}" class="flex items-center gap-2 shrink-0">
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('messages.search_events') }}" 
                       class="text-xs font-semibold pl-3 pr-8 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 w-48 sm:w-64 transition-colors">
                @if(request()->filled('search'))
                    <a href="{{ route('member.events.index') }}" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-rose-500 font-extrabold text-sm" title="{{ __('messages.clear_search') }}">
                        &times;
                    </a>
                @endif
            </div>
            <button type="submit" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 font-bold text-xs text-slate-700 rounded-xl transition-colors shrink-0">
                {{ __('messages.search') }}
            </button>
        </form>
    </div> --}}

    <!-- Events Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($events as $event)
            <div class="group bg-white rounded-xl border border-slate-100 shadow-sm flex flex-col overflow-hidden hover:shadow-md transition-all">
                <!-- Event Banner (Clickable to website details) -->
                <a href="{{ route('event.details', $event->id) }}" class="relative h-40 overflow-hidden block bg-white">
                    {{-- Always-visible Red Background + Calendar Icon (base layer) --}}
                    <div style="position:absolute; inset:0; display:flex; flex-direction:column; align-items:center; justify-content:center; padding: 16px 12px; background: linear-gradient(135deg, #dc2626 0%, #e11d48 60%, #be123c 100%);">
                        {{-- Dot grid texture --}}
                        <div style="position:absolute; inset:0; background-image: radial-gradient(circle, rgba(255,255,255,0.12) 1px, transparent 1px); background-size: 14px 14px; pointer-events:none;"></div>

                        {{-- Calendar card --}}
                        <div style="position:relative; display:flex; flex-direction:column; align-items:center; gap:8px;">
                            <div style="width:60px; height:64px; border-radius:12px; background:#fff; overflow:hidden; display:flex; flex-direction:column; box-shadow: 0 6px 20px rgba(0,0,0,0.28), 0 0 0 2px rgba(255,255,255,0.35);">
                                {{-- Month header --}}
                                <div style="background: linear-gradient(90deg, #dc2626, #e11d48); padding: 4px 0; text-align:center; flex-shrink:0;">
                                    <span style="font-size:10px; font-weight:900; color:#fff; letter-spacing:0.14em; text-transform:uppercase; display:block; line-height:1;">
                                        {{ date('M', strtotime($event->date)) }}
                                    </span>
                                </div>
                                {{-- Day number --}}
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
                            class="absolute inset-0 w-full h-full object-contain bg-white group-hover:scale-105 transition-transform duration-500"
                            src="{{ str_starts_with($event->banner_path, 'http') ? $event->banner_path : asset('storage/' . $event->banner_path) }}"
                            alt="{{ $event->title }}"
                            onerror="this.style.display='none'">
                    @endif

                    <div class="absolute top-2.5 left-2.5 z-10 flex items-center gap-1.5 flex-wrap">
                        @if($event->date < now()->toDateString())
                            <span class="text-[9px] font-extrabold text-slate-500 bg-white/95 backdrop-blur-sm border border-slate-200 px-2 py-0.5 rounded-full uppercase tracking-wider">{{ __('messages.passed') }}</span>
                        @else
                            <span class="text-[9px] font-extrabold text-emerald-600 bg-white/95 backdrop-blur-sm border border-emerald-100 px-2 py-0.5 rounded-full uppercase tracking-wider">{{ __('messages.upcoming') }}</span>
                        @endif

                        @if(($event->event_type ?? 'normal') === 'inam_vitaran')
                            <span class="text-[9px] font-extrabold text-amber-700 bg-amber-50/95 backdrop-blur-sm border border-amber-200 px-2 py-0.5 rounded-full uppercase tracking-wider">🏆 {{ __('messages.inam_vitaran') }}</span>
                        @elseif(($event->event_type ?? 'normal') === 'yuva_melo')
                            <span class="text-[9px] font-extrabold text-purple-700 bg-purple-50/95 backdrop-blur-sm border border-purple-200 px-2 py-0.5 rounded-full uppercase tracking-wider">⚡ {{ __('messages.yuva_melo') }}</span>
                        @endif
                    </div>
                </a>

                <!-- Event Details -->
                <div class="p-4 flex-grow flex flex-col justify-between space-y-4">
                    <div class="space-y-2">
                        <div class="flex items-center justify-between text-[10px] font-bold text-slate-400 flex-wrap gap-1">
                            <span>📅 {{ date('d-M-Y', strtotime($event->date)) }}</span>
                            @if(!empty($event->registration_end_date))
                                <span class="text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded border border-rose-100 font-extrabold">⏳ Last Date: {{ date('d-M-Y', strtotime($event->registration_end_date)) }}</span>
                            @else
                                <span>🕒 {{ date('h:i A', strtotime($event->time)) }}</span>
                            @endif
                        </div>
                        
                        <a href="{{ route('event.details', $event->id) }}" class="block">
                            <h3 class="text-sm font-bold text-slate-900 line-clamp-1 hover:text-primary-600 transition-colors">{{ $event->title }}</h3>
                        </a>
                        @php
                            $cleanDesc = preg_replace('/<span class="ql-ui"[^>]*>.*?<\/span>/i', '', $event->description);
                            $cleanDesc = strip_tags(str_replace(['<br>', '</p>', '</li>', '</div>'], ' ', $cleanDesc));
                            $cleanDesc = trim(preg_replace('/\s+/', ' ', $cleanDesc));
                        @endphp
                        <a href="{{ route('event.details', $event->id) }}" class="block">
                            <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed break-words">
                                {{ \Illuminate\Support\Str::limit($cleanDesc, 80, '...') }}
                            </p>
                        </a>
                    </div>

                    <!-- Action and Status -->
                    <div class="pt-3 mt-auto border-t border-slate-100 flex items-center justify-between gap-2.5">
                        <!-- Registration Status Badge -->
                        <div class="shrink-0">
                            @if(($event->event_type ?? 'normal') === 'normal' || !($event->has_registration_form || $event->registration_option))
                                <span class="inline-flex items-center px-2.5 py-1 text-[10px] font-bold text-slate-500 bg-slate-50 border border-slate-200 rounded-lg whitespace-nowrap">{{ __('messages.open_entry') }}</span>
                            @else
                                @if(!empty($registrations[$event->id]))
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200/80 rounded-lg uppercase whitespace-nowrap">
                                        <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                        {{ __('messages.registered') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 text-[10px] font-bold text-slate-500 bg-slate-50 border border-slate-200 rounded-lg uppercase whitespace-nowrap">{{ __('messages.not_registered') }}</span>
                                @endif
                            @endif
                        </div>

                        <!-- Action Button: context-aware based on event_type -->
                        <div class="shrink-0">
                            @if(($event->event_type ?? 'normal') === 'yuva_melo')
                                <a href="{{ route('member.events.register_form', $event->id) }}"
                                   class="inline-flex items-center px-3 py-1.5 text-white text-xs font-bold rounded-xl transition-all gap-1 shadow-sm whitespace-nowrap hover:opacity-95"
                                   style="background: linear-gradient(135deg, #7c3aed, #6d28d9);">
                                    ⚡ {{ __('messages.yuva_melo') }}  →
                                </a>
                            @elseif(($event->event_type ?? 'normal') === 'inam_vitaran')
                                <a href="{{ route('member.events.register_form', $event->id) }}"
                                   class="inline-flex items-center px-3 py-1.5 text-white text-xs font-bold rounded-xl transition-all gap-1 shadow-sm whitespace-nowrap hover:opacity-95"
                                   style="background: linear-gradient(135deg, #d97706, #b45309);">
                                    🏆 {{ __('messages.inam_vitaran') }}  →
                                </a>
                            @else
                                <a href="{{ route('event.details', $event->id) }}"
                                   class="inline-flex items-center px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-all gap-1 whitespace-nowrap">
                                    {{ __('messages.view_details') }} →
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-16 bg-white border border-slate-100 rounded-xl">
                <p class="text-xs text-slate-400">{{ __('messages.no_events_listed') }}</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($events->hasPages())
        <div class="mt-4">
            {{ $events->links() }}
        </div>
    @endif
</div>
@endsection
