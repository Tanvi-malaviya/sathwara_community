@extends('layouts.public')

@section('content')
@include('partials.page_header', [
    'title' => $title ?? __('messages.about_us'),
    'subtitle' => $subtitle ?? __('messages.about_subtitle'),
    'breadcrumb' => __('messages.about_us')
])

<!-- Mission, Vision & Objectives (Simple & Sober Design) -->
<section class="py-6 md:py-8 bg-white border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Clean Section Header -->
        <div class="text-center max-w-2xl mx-auto mb-6 space-y-1">
            <span class="text-xs font-bold text-primary-600 uppercase tracking-widest">
                {{ __('messages.our_foundation') }}
            </span>
            <h2 class="text-xl md:text-2xl font-bold text-slate-900">
                {{ __('messages.foundation_subtitle') }}
            </h2>
        </div>

        <!-- 3 Equal Clean Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Mission Card -->
            <div class="bg-slate-50/80 rounded-2xl p-6 border border-slate-200/60 space-y-3 hover:bg-white hover:border-primary-200 hover:shadow-md transition-all duration-300">
                <div class="w-9 h-9 rounded-xl bg-primary-100/80 text-primary-600 flex items-center justify-center font-bold text-base">
                    🎯
                </div>
                <div class="space-y-1.5">
                    <span class="text-[10px] font-bold text-primary-600 uppercase tracking-wider block">
                        {{ __('messages.mission') }}
                    </span>
                    <h3 class="text-base font-bold text-slate-900">{{ $missionTitle ?? __('messages.empowering_people') }}</h3>
                    <p class="text-xs text-slate-600 leading-relaxed font-normal">
                        {!! nl2br(e($mission)) !!}
                    </p>
                </div>
            </div>

            <!-- Vision Card -->
            <div class="bg-slate-50/80 rounded-2xl p-6 border border-slate-200/60 space-y-3 hover:bg-white hover:border-amber-200 hover:shadow-md transition-all duration-300">
                <div class="w-9 h-9 rounded-xl bg-amber-100/80 text-amber-600 flex items-center justify-center font-bold text-base">
                    🌟
                </div>
                <div class="space-y-1.5">
                    <span class="text-[10px] font-bold text-amber-600 uppercase tracking-wider block">
                        {{ __('messages.vision') }}
                    </span>
                    <h3 class="text-base font-bold text-slate-900">{{ $visionTitle ?? __('messages.future_prosperity') }}</h3>
                    <p class="text-xs text-slate-600 leading-relaxed font-normal">
                        {!! nl2br(e($vision)) !!}
                    </p>
                </div>
            </div>

            <!-- Objectives Card -->
            <div class="bg-slate-50/80 rounded-2xl p-6 border border-slate-200/60 space-y-3 hover:bg-white hover:border-emerald-200 hover:shadow-md transition-all duration-300">
                <div class="w-9 h-9 rounded-xl bg-emerald-100/80 text-emerald-600 flex items-center justify-center font-bold text-base">
                    📌
                </div>
                <div class="space-y-1.5">
                    <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider block">
                        {{ __('messages.objectives') }}
                    </span>
                    <h3 class="text-base font-bold text-slate-900">{{ $objectivesTitle ?? __('messages.strategic_goals') }}</h3>
                    <div class="rich-text text-xs text-slate-600 leading-relaxed font-normal">
                        {!! $objectives !!}
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- History Section -->
<section class="py-6 md:py-8 bg-slate-50/60 border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl p-6 md:p-8 border border-slate-200/70 shadow-sm relative overflow-hidden">
            <!-- Decorative Accent Background Element -->
            <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-primary-500/5 rounded-full pointer-events-none"></div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center relative">
                <!-- Left Title Column -->
                <div class="lg:col-span-4 space-y-2 border-b lg:border-b-0 lg:border-r border-slate-100 pb-4 lg:pb-0 lg:pr-6">
                    <span class="text-[11px] font-bold text-primary-600 uppercase tracking-widest flex items-center gap-1.5">
                        <span>🏛️</span>
                        <span>{{ __('messages.history') }}</span>
                    </span>
                    <h2 class="text-xl md:text-2xl font-extrabold text-slate-900 leading-tight">
                        {{ $historyTitle ?? __('messages.heritage_journey') }}
                    </h2>
                    <p class="text-xs text-slate-500 font-medium">
                        {{ __('messages.heritage_subtitle') }}
                    </p>
                </div>

                <!-- Right Content Column -->
                <div class="lg:col-span-8 pl-0 lg:pl-4 space-y-2">
                    <div class="relative">
                        <span class="text-3xl text-primary-200 font-serif leading-none select-none absolute -top-3 -left-3">“</span>
                        <div class="text-slate-600 text-xs md:text-sm leading-relaxed pl-3 pt-1 font-normal">
                            {!! nl2br(e($history)) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Timeline Milestones (Modern Alternating Timeline Design) -->
<section class="py-6 md:py-8 bg-slate-50/50 border-b border-slate-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-2xl mx-auto mb-8 space-y-1">
            <span class="text-xs font-bold text-primary-600 uppercase tracking-widest flex items-center justify-center gap-1.5">
                <span>🚀</span>
                <span>{{ __('messages.timeline') }}</span>
            </span>
            <h2 class="text-xl md:text-3xl font-extrabold text-slate-900 tracking-tight">
                {{ __('messages.milestones_achievements') }}
            </h2>
            <p class="text-xs text-slate-500 font-medium">
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
                            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all duration-300 relative group">
                                <!-- Year Badge -->
                                <div class="flex items-center justify-between mb-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-primary-50 text-primary-600 border border-primary-100">
                                        📅 {{ $milestone->year }}
                                    </span>
                                    <span class="text-[10px] font-bold text-slate-400 group-hover:text-primary-500 transition-colors">
                                        #{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                    </span>
                                </div>
                                <h3 class="text-base font-bold text-slate-900 mb-1">
                                    {{ $milestone->title }}
                                </h3>
                                <p class="text-xs text-slate-600 leading-relaxed font-normal">
                                    {{ $milestone->description }}
                                </p>
                            </div>
                        </div>

                        <!-- Center Node Dot -->
                        <div class="absolute left-4 md:left-1/2 -translate-x-1/2 w-7 h-7 rounded-full bg-white border-4 border-primary-500 shadow-md flex items-center justify-center text-primary-600 font-bold z-10">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary-500"></span>
                        </div>

                        <!-- Spacer for balance -->
                        <div class="hidden md:block w-1/2"></div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</section>

@endsection
