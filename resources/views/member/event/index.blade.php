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
                <a href="{{ route('event.details', $event->id) }}" class="relative h-40 bg-slate-100 overflow-hidden block">
                    <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                         src="{{ str_starts_with($event->banner_path, 'http') ? $event->banner_path : asset('storage/' . $event->banner_path) }}" 
                         alt="{{ $event->title }}">
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
                        <div class="flex items-center justify-between text-[10px] font-bold text-slate-400">
                            <span>📅 {{ date('d-M-Y', strtotime($event->date)) }}</span>
                            <span>🕒 {{ date('h:i A', strtotime($event->time)) }}</span>
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
                    <div class="pt-3 border-t border-slate-50 flex items-center justify-between gap-2">
                        <!-- Registration Status Badge -->
                        <div>
                            @if(!($event->has_registration_form ?? $event->registration_option))
                                <span class="text-[10px] font-bold text-slate-500 bg-slate-50 border border-slate-200 px-2 py-0.5 rounded-lg">{{ __('messages.open_entry') }}</span>
                            @else
                                @if(!empty($registrations[$event->id]))
                                    <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 border border-emerald-100 px-2 py-0.5 rounded-lg uppercase">{{ __('messages.registered') }}</span>
                                @else
                                    <span class="text-[10px] font-bold text-slate-500 bg-slate-50 border border-slate-200 px-2 py-0.5 rounded-lg uppercase">{{ __('messages.not_registered') }}</span>
                                @endif
                            @endif
                        </div>

                        <!-- Action Button -->
                        <div>
                            <a href="{{ route('event.details', $event->id) }}" 
                               class="inline-flex items-center px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-all gap-1">
                                {{ __('messages.view_details') }} &rarr;
                            </a>
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
