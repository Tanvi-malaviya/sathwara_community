@extends('layouts.public')

@section('content')
@include('partials.page_header', [
    'title' => $update->title,
    'subtitle' => date('F d, Y', strtotime($update->publish_date)),
    'breadcrumb' => __('messages.updates')
])

<!-- Content -->
<section class="py-8 bg-slate-50/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Main Content Column -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm">
                    @if($update->image_path)
                        @php
                            $imgSrc = str_starts_with($update->image_path, 'http') ? $update->image_path : asset('storage/' . $update->image_path);
                        @endphp
                        <div class="relative w-full h-72 sm:h-96 md:h-[420px] rounded-2xl overflow-hidden mb-6 bg-slate-950 border border-slate-200/80 shadow-md flex items-center justify-center p-2">
                            <!-- Blurred Background Image -->
                            <img class="absolute inset-0 w-full h-full object-cover pointer-events-none" 
                                 src="{{ $imgSrc }}" 
                                 alt=""
                                 style="filter: blur(18px) brightness(0.45); transform: scale(1.15);">

                            <!-- Sharp Full Foreground Image -->
                            <img class="relative max-h-full max-w-full object-contain mx-auto rounded-xl drop-shadow-2xl z-1" 
                                 src="{{ $imgSrc }}" 
                                 alt="{{ $update->title }}"
                                 onerror="this.style.display='none'">
                        </div>
                    @endif

                    <div class="flex items-center gap-2 mb-4 flex-wrap">
                        <span class="inline-flex items-center gap-1.5 text-xs font-black text-primary-700 bg-primary-50 px-3 py-1.5 rounded-xl uppercase tracking-wider border border-primary-100 shadow-2xs">
                            <svg class="w-3.5 h-3.5 text-primary-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>{{ __('messages.published') }}: {{ date('M d, Y', strtotime($update->publish_date)) }}</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-xs font-black text-slate-600 bg-slate-100 px-3 py-1.5 rounded-xl uppercase tracking-wider shadow-2xs">
                            <svg class="w-3.5 h-3.5 text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                            <span>{{ __('messages.announcement') }}</span>
                        </span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 leading-tight mb-5">{{ $update->title }}</h1>

                    <div class="text-base sm:text-lg text-slate-700 leading-relaxed space-y-4 break-words prose max-w-none">
                        {!! $update->description !!}
                    </div>

                    <div class="mt-8 pt-6 border-t border-slate-100 flex justify-between items-center">
                        <a href="{{ route('updates') }}" class="inline-flex items-center gap-2 text-sm font-extrabold text-slate-600 hover:text-primary-600 transition-colors">
                            &larr; {{ __('messages.back_to_announcements') }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar Column -->
            <div class="space-y-6">
                <!-- Recent Updates Widget -->
                @php
                    $recentUpdates = \App\Models\Update::where('status', 'published')
                        ->where('id', '!=', $update->id)
                        ->latest('publish_date')
                        ->take(4)
                        ->get();
                @endphp

                <div class="bg-white rounded-xl border border-slate-200/60 p-6 shadow-sm">
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100">
                        {{ __('messages.recent_announcements') }}
                    </h3>
                    
                    @if($recentUpdates->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentUpdates as $recent)
                                <a href="{{ route('update.details', $recent->id) }}" class="group flex gap-3 items-start min-w-0">
                                    @if($recent->image_path)
                                        <img class="w-12 h-12 rounded-lg object-cover bg-slate-150 shrink-0 border border-slate-100" 
                                             src="{{ str_starts_with($recent->image_path, 'http') ? $recent->image_path : asset('storage/' . $recent->image_path) }}" 
                                             alt="{{ $recent->title }}">
                                    @else
                                        <div class="w-12 h-12 rounded-lg bg-primary-50 flex items-center justify-center text-primary-500 shrink-0 border border-primary-100">
                                            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                                        </div>
                                    @endif
                                    <div class="min-w-0 flex-1">
                                        <span class="text-[9px] font-bold text-slate-400 block mb-0.5">{{ date('M d, Y', strtotime($recent->publish_date)) }}</span>
                                        <h4 class="text-xs font-bold text-slate-900 group-hover:text-primary-600 transition-colors line-clamp-1 leading-snug">
                                            {{ $recent->title }}
                                        </h4>
                                        <p class="text-[10px] text-slate-500 line-clamp-1 mt-0.5 leading-relaxed">{{ $recent->description }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-slate-400">{{ __('messages.no_other_announcements') }}</p>
                    @endif
                </div>

                <!-- Quick Links Card -->
                <div class="bg-gradient-to-br from-slate-900 via-slate-950 to-slate-900 rounded-xl p-6 text-white shadow-md relative overflow-hidden">
                    <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-primary-600/10 rounded-full blur-2xl"></div>
                    
                    <span class="text-[9px] font-extrabold uppercase tracking-widest text-primary-400 block mb-1">{{ __('messages.community_hub') }}</span>
                    <h4 class="text-sm font-black mb-2">{{ __('messages.explore_portal') }}</h4>
                    <p class="text-[11px] text-slate-300 leading-relaxed mb-4">
                        {{ __('messages.explore_portal_desc') }}
                    </p>
                    
                    <div class="space-y-2">
                        <a href="{{ route('events') }}" class="flex items-center justify-between p-2.5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-xs font-bold transition-all">
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>{{ __('messages.community_events') }}</span>
                            </span>
                            <span>&rarr;</span>
                        </a>
                        <a href="{{ route('business.directory') }}" class="flex items-center justify-between p-2.5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-xs font-bold transition-all">
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                <span>{{ __('messages.business_directory') }}</span>
                            </span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
