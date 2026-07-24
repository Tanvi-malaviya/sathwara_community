@extends('layouts.public')

@section('content')
@include('partials.page_header', [
    'title' => __('messages.events'),
    'subtitle' => __('messages.events_subtitle'),
    'breadcrumb' => __('messages.events')
])

<!-- Events Grid -->
<section class="py-5 bg-slate-50/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Search bar -->
        {{-- <div class="mb-6 max-w-md mx-auto">
            <form method="GET" action="{{ route('events') }}" class="flex items-center gap-2">
                <div class="relative flex-grow">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('messages.search_events_placeholder') }}" 
                           class="text-xs font-semibold pl-10 pr-4 py-2.5 w-full bg-white border border-slate-200 rounded-2xl focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-colors shadow-xs">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    @if(request()->filled('search'))
                        <a href="{{ route('events') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-rose-500 font-extrabold text-sm" title="Clear search">
                            &times;
                        </a>
                    @endif
                </div>
                <button type="submit" class="px-4 py-2.5 bg-primary-500 hover:bg-primary-600 text-white font-bold text-xs rounded-2xl shadow-xs transition-colors shrink-0">
                    {{ __('messages.search') }}
                </button>
            </form>
        </div> --}}

        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            @forelse($events as $event)
                <a href="{{ route('event.details', $event->id) }}" class="group bg-white rounded-3xl overflow-hidden border border-slate-200/60 flex flex-col hover:shadow-xl hover:-translate-y-1 transition-all duration-300 block cursor-pointer">
                    <div class="relative h-44 w-full overflow-hidden shrink-0">
                        <img class="h-full w-full object-cover bg-slate-100 group-hover:scale-105 transition-transform duration-500" 
                             src="{{ str_starts_with($event->banner_path, 'http') ? $event->banner_path : asset('storage/' . $event->banner_path) }}" 
                             alt="{{ $event->title }}">
                        
                        <!-- Date Badge (Top-Left overlay) -->
                        <div class="absolute top-3 left-3 z-10">
                            <span class="text-[9px] font-extrabold text-white bg-slate-900/80 backdrop-blur-md px-2 py-1 rounded-lg uppercase tracking-wider shadow-sm">
                                {{ date('M d, Y', strtotime($event->date)) }}
                            </span>
                        </div>

                        <!-- Status & Type Badges (Top-Right overlay) -->
                        <div class="absolute top-3 right-3 z-10 flex flex-col items-end gap-1">
                            @if($event->date < now()->toDateString())
                                <span class="text-[8px] font-black text-slate-700 bg-slate-100/90 backdrop-blur-sm px-2 py-0.5 rounded-md uppercase tracking-wider shadow-sm">{{ __('messages.passed') }}</span>
                            @else
                                <span class="text-[8px] font-black text-emerald-700 bg-emerald-50/90 backdrop-blur-sm px-2 py-0.5 rounded-md uppercase tracking-wider shadow-sm">{{ __('messages.upcoming') }}</span>
                            @endif

                            @if(($event->event_type ?? 'normal') === 'inam_vitaran')
                                <span class="text-[8px] font-black text-amber-800 bg-amber-50/95 backdrop-blur-sm border border-amber-200 px-2 py-0.5 rounded-md uppercase tracking-wider shadow-sm">🏆 Inam</span>
                            @elseif(($event->event_type ?? 'normal') === 'yuva_melo')
                                <span class="text-[8px] font-black text-purple-800 bg-purple-50/95 backdrop-blur-sm border border-purple-200 px-2 py-0.5 rounded-md uppercase tracking-wider shadow-sm">⚡ Yuva</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="p-5 flex-grow flex flex-col justify-between space-y-3">
                        <div class="space-y-1.5">
                            <h3 class="text-sm font-bold text-slate-900 group-hover:text-primary-600 transition-colors line-clamp-1" title="{{ $event->title }}">{{ $event->title }}</h3>
                            @php
                                $cleanDesc = preg_replace('/<span class="ql-ui"[^>]*>.*?<\/span>/i', '', $event->description);
                                $cleanDesc = strip_tags(str_replace(['<br>', '</p>', '</li>', '</div>'], ' ', $cleanDesc));
                                $cleanDesc = trim(preg_replace('/\s+/', ' ', $cleanDesc));
                            @endphp
                            <p class="text-[11px] text-slate-500 line-clamp-2 leading-relaxed break-words">
                                {{ \Illuminate\Support\Str::limit($cleanDesc, 100, '...') }}
                            </p>
                        </div>

                        <div class="flex justify-between items-center pt-3 border-t border-slate-100 min-w-0 gap-3">
                            <div class="min-w-0 flex-1">
                                <span class="text-[10px] font-bold text-slate-400 truncate block" title="{{ $event->venue }}">📍 {{ $event->venue }}</span>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-3 text-center py-20 text-slate-400">
                    {{ __('messages.no_events_scheduled') }}
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $events->links() }}
        </div>
    </div>
</section>
@endsection
