@extends('layouts.public')

@section('content')
@include('partials.page_header', [
    'title' => $title ?? __('messages.about_us'),
    'subtitle' => $subtitle ?? __('messages.about_subtitle'),
    'breadcrumb' => __('messages.about_us')
])

<!-- Mission, Vision & Objectives (Simple & Sober Design) -->
<section class="py-8 md:py-12 bg-white border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Clean Section Header -->
        <div class="text-center max-w-2xl mx-auto mb-8 space-y-1.5">
            <span class="text-xs sm:text-sm font-black text-primary-600 uppercase tracking-widest">
                {{ __('messages.our_foundation') }}
            </span>
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-black text-slate-900">
                {{ __('messages.foundation_subtitle') }}
            </h2>
        </div>

        <!-- 3 Equal Clean Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Mission Card -->
            <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-xs space-y-4 hover:border-primary-300 hover:shadow-xl transition-all duration-300">
                <div class="w-12 h-12 rounded-2xl bg-primary-100/80 text-primary-600 flex items-center justify-center font-bold text-xl shadow-2xs">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke-width="2"/><circle cx="12" cy="12" r="5" stroke-width="2"/><circle cx="12" cy="12" r="1.5" fill="currentColor"/></svg>
                </div>
                <div class="space-y-2">
                    <span class="text-xs font-black text-primary-600 uppercase tracking-wider block">
                        {{ __('messages.mission') }}
                    </span>
                    <h3 class="text-lg sm:text-xl font-black text-slate-900">{{ $missionTitle ?? __('messages.empowering_people') }}</h3>
                    <p class="text-sm sm:text-[14.5px] text-slate-600 leading-relaxed font-medium">
                        {!! nl2br(e($mission)) !!}
                    </p>
                </div>
            </div>

            <!-- Vision Card -->
            <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-xs space-y-4 hover:border-amber-300 hover:shadow-xl transition-all duration-300">
                <div class="w-12 h-12 rounded-2xl bg-amber-100/80 text-amber-600 flex items-center justify-center font-bold text-xl shadow-2xs">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </div>
                <div class="space-y-2">
                    <span class="text-xs font-black text-amber-600 uppercase tracking-wider block">
                        {{ __('messages.vision') }}
                    </span>
                    <h3 class="text-lg sm:text-xl font-black text-slate-900">{{ $visionTitle ?? __('messages.future_prosperity') }}</h3>
                    <p class="text-sm sm:text-[14.5px] text-slate-600 leading-relaxed font-medium">
                        {!! nl2br(e($vision)) !!}
                    </p>
                </div>
            </div>

            <!-- Objectives Card -->
            <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-xs space-y-4 hover:border-emerald-300 hover:shadow-xl transition-all duration-300">
                <div class="w-12 h-12 rounded-2xl bg-emerald-100/80 text-emerald-600 flex items-center justify-center font-bold text-xl shadow-2xs">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
                <div class="space-y-2">
                    <span class="text-xs font-black text-emerald-600 uppercase tracking-wider block">
                        {{ __('messages.objectives') }}
                    </span>
                    <h3 class="text-lg sm:text-xl font-black text-slate-900">{{ $objectivesTitle ?? __('messages.strategic_goals') }}</h3>
                    <div class="rich-text text-sm sm:text-[14.5px] text-slate-600 leading-relaxed font-medium space-y-1">
                        {!! $objectives !!}
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- History Section -->
<section class="py-8 md:py-12 bg-slate-50/60 border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl p-6 md:p-10 border border-slate-200/80 shadow-sm relative overflow-hidden">
            <!-- Decorative Accent Background Element -->
            <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-primary-500/5 rounded-full pointer-events-none"></div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center relative">
                <!-- Left Title Column -->
                <div class="lg:col-span-4 space-y-2.5 border-b lg:border-b-0 lg:border-r border-slate-100 pb-5 lg:pb-0 lg:pr-8">
                    <span class="text-xs sm:text-sm font-black text-primary-600 uppercase tracking-widest flex items-center gap-2">
                        <svg class="w-4 h-4 text-primary-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span>{{ __('messages.history') }}</span>
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 leading-tight">
                        {{ $historyTitle ?? __('messages.heritage_journey') }}
                    </h2>
                    <p class="text-sm sm:text-base font-bold text-slate-500">
                        {{ __('messages.heritage_subtitle') }}
                    </p>
                </div>

                <!-- Right Content Column -->
                <div class="lg:col-span-8 pl-0 lg:pl-6 space-y-3">
                    <div class="relative">
                        <span class="text-4xl text-primary-200 font-serif leading-none select-none absolute -top-4 -left-4">“</span>
                        <div class="text-slate-600 text-sm sm:text-base leading-relaxed pl-4 pt-1 font-medium">
                            {!! nl2br(e($history)) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Timeline Milestones (Modern Alternating Timeline Design) -->
<section class="py-8 md:py-12 bg-slate-50/50 border-b border-slate-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-2xl mx-auto mb-10 space-y-1.5">
            <span class="text-xs sm:text-sm font-black text-primary-600 uppercase tracking-widest flex items-center justify-center gap-2">
                <svg class="w-4 h-4 text-primary-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ __('messages.timeline') }}</span>
            </span>
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-black text-slate-900 tracking-tight">
                {{ __('messages.milestones_achievements') }}
            </h2>
            <p class="text-sm sm:text-base font-bold text-slate-500">
                {{ __('messages.milestones_subtitle') }}
            </p>
        </div>

        <div class="relative">
            <!-- Center Line (desktop) / Left Line (mobile) -->
            <div class="absolute left-4 md:left-1/2 top-0 bottom-0 w-0.5 bg-gradient-to-b from-primary-500 via-primary-300 to-slate-200 md:-translate-x-1/2"></div>

            <div class="space-y-6">
                @foreach($timeline as $index => $milestone)
                    <div class="relative flex flex-col md:flex-row items-center {{ $index % 2 == 0 ? 'md:flex-row-reverse' : '' }}">
                        
                        <!-- Content Card (Left or Right) -->
                        <div class="w-full md:w-1/2 pl-10 md:pl-0 {{ $index % 2 == 0 ? 'md:pl-8' : 'md:pr-8' }}">
                            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs hover:shadow-xl transition-all duration-300 relative group">
                                <!-- Year Badge -->
                                <div class="flex items-center justify-between mb-2.5">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-primary-50 text-primary-700 border border-primary-100 shadow-2xs">
                                        <svg class="w-3.5 h-3.5 text-primary-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span>{{ $milestone->year }}</span>
                                    </span>
                                    <span class="text-xs font-black text-slate-400 group-hover:text-primary-600 transition-colors">
                                        #{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                    </span>
                                </div>
                                <h3 class="text-base sm:text-lg font-black text-slate-900 mb-1.5">
                                    {{ $milestone->title }}
                                </h3>
                                <p class="text-sm text-slate-600 leading-relaxed font-medium">
                                    {{ $milestone->description }}
                                </p>
                            </div>
                        </div>

                        <!-- Center Node Dot -->
                        <div class="absolute left-4 md:left-1/2 w-4 h-4 rounded-full bg-white border-4 border-primary-600 shadow-sm -translate-x-1/2 z-10"></div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</section>

@endsection
