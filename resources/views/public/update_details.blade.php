@extends('layouts.public')

@section('content')
@include('partials.page_header', [
    'title' => $update->title,
    'subtitle' => date('F d, Y', strtotime($update->publish_date)),
    'breadcrumb' => __('messages.updates')
])

<!-- Content -->
<section class="py-6 bg-slate-50/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
            <!-- Left Main Content Column -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl border border-slate-200/60 p-6 sm:p-8 shadow-sm">
                    @if($update->image_path)
                        <div class="relative w-full max-h-[420px] rounded-2xl overflow-hidden mb-6 bg-slate-100 border border-slate-100 shadow-xs">
                            <img class="w-full h-full object-cover" 
                                 src="{{ str_starts_with($update->image_path, 'http') ? $update->image_path : asset('storage/' . $update->image_path) }}" 
                                 alt=""
                                 onerror="this.style.display='none'">
                        </div>
                    @endif

                    <div class="flex items-center gap-2 mb-4">
                        <span class="text-[10px] font-extrabold text-primary-600 bg-primary-50 px-2.5 py-1 rounded-lg uppercase tracking-wider">
                            📅 {{ __('messages.published') }}: {{ date('M d, Y', strtotime($update->publish_date)) }}
                        </span>
                        <span class="text-[10px] font-extrabold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-lg uppercase tracking-wider">
                            📌 {{ __('messages.announcement') }}
                        </span>
                    </div>

                    <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 leading-tight mb-4">{{ $update->title }}</h2>

                    <div class="text-sm sm:text-base text-slate-600 leading-relaxed space-y-4 break-words">
                        {!! nl2br(e($update->description)) !!}
                    </div>

                    <div class="mt-8 pt-6 border-t border-slate-100 flex justify-between items-center">
                        <a href="{{ route('updates') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-slate-900 transition-colors">
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
                                        <div class="w-12 h-12 rounded-lg bg-primary-50 flex items-center justify-center text-primary-500 text-sm shrink-0 border border-primary-100">
                                            📢
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
                            <span>📅 {{ __('messages.community_events') }}</span>
                            <span>&rarr;</span>
                        </a>
                        <a href="{{ route('business.directory') }}" class="flex items-center justify-between p-2.5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-xs font-bold transition-all">
                            <span>💼 {{ __('messages.business_directory') }}</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
