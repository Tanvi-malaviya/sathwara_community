@extends('layouts.member')

@section('page_title', $event->title)

@section('content')
<div class="space-y-4">
    <!-- Back to Events List -->
    <div>
        <a href="{{ route('member.events.index') }}" class="inline-flex items-center text-xs font-bold text-slate-500 hover:text-slate-900 transition-colors gap-1">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Events
        </a>
    </div>

    <!-- Main Content Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-2 items-start" x-data="{ showConfirmModal: {{ request()->has('open_form') ? 'true' : 'false' }} }">
        <!-- Left 2 Columns: Banner, Details, and Gallery -->
        <div class="lg:col-span-2 space-y-2">
            <!-- Event Hero Split Card -->
            <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden flex flex-col md:flex-row md:items-stretch relative">
                <!-- Status & Event Type Badges (Overlaid on card) -->
                <div class="absolute top-3 left-6 z-20 flex items-center gap-1.5">
                    @if($event->date < now()->toDateString())
                        <span class="text-[9px] font-extrabold text-slate-500 bg-white/95 backdrop-blur-sm border border-slate-200 px-2.5 py-1 rounded-full uppercase tracking-wider">Passed</span>
                    @elseif($event->status === 'cancelled')
                        <span class="text-[9px] font-extrabold text-rose-600 bg-white/95 backdrop-blur-sm border border-rose-100 px-2.5 py-1 rounded-full uppercase tracking-wider">Cancelled</span>
                    @else
                        <span class="text-[9px] font-extrabold text-emerald-600 bg-white/95 backdrop-blur-sm border border-emerald-100 px-2.5 py-1 rounded-full uppercase tracking-wider">Upcoming</span>
                    @endif

                    @if(($event->event_type ?? 'normal') === 'inam_vitaran')
                        <span class="text-[9px] font-extrabold text-amber-700 bg-amber-50/95 backdrop-blur-sm border border-amber-200 px-2.5 py-1 rounded-full uppercase tracking-wider">🏆 Inam Vitaran</span>
                    @elseif(($event->event_type ?? 'normal') === 'yuva_melo')
                        <span class="text-[9px] font-extrabold text-purple-700 bg-purple-50/95 backdrop-blur-sm border border-purple-200 px-2.5 py-1 rounded-full uppercase tracking-wider">⚡ Yuva Melo</span>
                    @endif
                </div>

                <!-- Left: Banner Image -->
                <div class="md:w-1/2 h-52 md:h-auto overflow-hidden shrink-0 relative" x-data="{ imageError: false }">
                    @if(!empty($event->banner_path))
                        <img x-show="!imageError" 
                             x-on:error="imageError = true"
                             class="w-full h-full object-cover transition-transform duration-500 hover:scale-105" 
                             src="{{ str_starts_with($event->banner_path, 'http') ? $event->banner_path : asset('storage/' . $event->banner_path) }}" 
                             alt="{{ $event->title }}">
                    @endif
                    
                    <div x-show="imageError || !'{{ $event->banner_path }}'" 
                         class="absolute inset-0 bg-gradient-to-br from-primary-500 via-primary-600 to-slate-900 flex flex-col items-center justify-center p-4"
                         x-cloak>
                        <span class="text-4xl">📅</span>
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-primary-100 mt-2">Community Event</span>
                    </div>
                </div>
                
                <!-- Right: Content & Metadata Overview -->
                <div class="md:w-1/2 p-5 flex flex-col justify-between space-y-4 bg-gradient-to-br from-white to-slate-50/30 relative z-10">
                    <div class="space-y-1.5">
                        <div class="flex items-center gap-1.5 flex-wrap">
                            @if(($event->event_type ?? 'normal') === 'inam_vitaran')
                                <span class="px-2 py-0.5 rounded bg-amber-50 text-amber-700 border border-amber-200/80 text-[10px] font-extrabold uppercase">🏆 Inam Vitaran</span>
                            @elseif(($event->event_type ?? 'normal') === 'yuva_melo')
                                <span class="px-2 py-0.5 rounded bg-purple-50 text-purple-700 border border-purple-200/80 text-[10px] font-extrabold uppercase">⚡ Yuva Melo</span>
                            @else
                                <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 border border-blue-200/80 text-[10px] font-extrabold uppercase">🎉 Normal Event</span>
                            @endif

                            @if(($event->pass_fee ?? 0) > 0)
                                <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-200/80 text-[10px] font-black uppercase">₹{{ number_format($event->pass_fee, 0) }} Pass Fee</span>
                            @else
                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 border border-slate-200/80 text-[10px] font-black uppercase">Free Pass</span>
                            @endif
                        </div>
                        <h1 class="text-base font-extrabold text-slate-900 leading-snug">{{ $event->title }}</h1>
                    </div>

                    <!-- Meta Details -->
                    <div class="space-y-2.5">
                        <div class="flex items-center gap-3 text-xs font-semibold text-slate-600">
                            <div class="p-1.5 bg-blue-50 text-blue-500 rounded-lg shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="text-[9px] text-slate-400 block font-bold uppercase tracking-wider leading-none">Date</span>
                                <span class="text-slate-800">{{ date('d-M-Y', strtotime($event->date)) }}</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 text-xs font-semibold text-slate-600">
                            <div class="p-1.5 bg-emerald-50 text-emerald-500 rounded-lg shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="text-[9px] text-slate-400 block font-bold uppercase tracking-wider leading-none">Time</span>
                                <span class="text-slate-800">{{ date('h:i A', strtotime($event->time)) }}</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 text-xs font-semibold text-slate-600">
                            <div class="p-1.5 bg-amber-50 text-amber-500 rounded-lg shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="text-[9px] text-slate-400 block font-bold uppercase tracking-wider leading-none">Venue</span>
                                <span class="text-slate-800 truncate block max-w-[200px]" title="{{ $event->venue }}">{{ $event->venue }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description Card -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 space-y-3">
                <div class="flex items-center gap-2 border-b border-slate-50 pb-3">
                    <div class="p-1.5 bg-slate-50 text-slate-700 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"></path>
                        </svg>
                    </div>
                    <h2 class="text-sm font-bold text-slate-900">About the Event</h2>
                </div>
                <div class="rich-text text-xs text-slate-600 leading-relaxed">
                    {!! $event->description !!}
                </div>
            </div>

            <!-- Event Gallery -->
            @if($gallery->count() > 0)
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 space-y-4">
                    <div class="flex items-center gap-2 border-b border-slate-50 pb-3">
                        <div class="p-1.5 bg-slate-50 text-slate-700 rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h2 class="text-sm font-bold text-slate-900">Event Gallery</h2>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3" x-data="{ lightbox: false, activeSrc: '' }">
                        @foreach($gallery as $photo)
                            <div class="aspect-video rounded-xl overflow-hidden bg-slate-50 border border-slate-100 group relative cursor-pointer shadow-sm"
                                 @click="activeSrc = '{{ str_starts_with($photo->image_path, 'http') ? $photo->image_path : asset('storage/' . $photo->image_path) }}'; lightbox = true">
                                <img class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                     src="{{ str_starts_with($photo->image_path, 'http') ? $photo->image_path : asset('storage/' . $photo->image_path) }}"
                                     alt="{{ $photo->caption }}">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"></path>
                                    </svg>
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

        <!-- Right 1 Column: Ticket-Style Registration Widget -->
        <div class="lg:sticky lg:top-4">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden relative pb-4">
                <!-- Ticket Top Header -->
                <div class="p-4 space-y-3">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                        <span class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Pass Status</span>
                        @if($registration)
                            <span class="text-[9px] font-extrabold text-emerald-600 bg-emerald-50 border border-emerald-100 px-2 py-0.5 rounded-full uppercase tracking-wider">Registered</span>
                        @else
                            <span class="text-[9px] font-extrabold text-slate-500 bg-slate-50 border border-slate-200 px-2 py-0.5 rounded-full uppercase tracking-wider">Available</span>
                        @endif
                    </div>

                    <!-- Ticket Event Mini Info -->
                    <div class="space-y-2">
                        <div class="space-y-0.5">
                            <span class="text-[9px] text-slate-400 block font-bold uppercase tracking-wider">Event Title</span>
                            <span class="text-xs font-bold text-slate-900 block truncate">{{ $event->title }}</span>
                        </div>
                    </div>
                </div>

                <!-- Dashed Separation Line & Cut-outs -->
                <div class="relative my-2.5">
                    <div class="absolute -left-3.5 -top-3 w-7 h-7 rounded-full bg-slate-50 border-r border-slate-100/80 z-10"></div>
                    <div class="absolute -right-3.5 -top-3 w-7 h-7 rounded-full bg-slate-50 border-l border-slate-100/80 z-10"></div>
                    <div class="border-t-2 border-dashed border-slate-100/90 w-full"></div>
                </div>

                <!-- Ticket Bottom Section -->
                <div class="px-4 pt-1 space-y-3">
                    @if(in_array($event->event_type ?? 'normal', ['inam_vitaran', 'yuva_melo']))
                        <div class="space-y-3">
                            @if($event->date < now()->toDateString())
                                <!-- Concluded -->
                                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-500 text-xs font-bold text-center">
                                    This event has concluded.
                                </div>
                            @elseif($event->status === 'cancelled')
                                <!-- Cancelled -->
                                <div class="p-3 rounded-xl bg-rose-50 border border-rose-100/50 text-rose-600 text-xs font-bold text-center">
                                    This event has been cancelled.
                                </div>
                            @else
                                <!-- Active Flow -->
                                @if($registration)
                                    <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-800 text-xs font-bold text-center flex flex-col items-center justify-center gap-1">
                                        <span class="flex items-center gap-1">Registered ✅</span>
                                    </div>
                                    @if(($event->event_type ?? 'normal') === 'inam_vitaran')
                                        <a href="{{ route('member.events.register_form', $event->id) }}" class="w-full flex items-center justify-center px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-xl shadow-sm transition-all duration-150 text-center gap-1">
                                            🏆 Fill Inam Form &rarr;
                                        </a>
                                    @elseif(($event->event_type ?? 'normal') === 'yuva_melo')
                                        <a href="{{ route('member.events.register_form', $event->id) }}" class="w-full flex items-center justify-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold rounded-xl shadow-sm transition-all duration-150 text-center gap-1">
                                            ⚡ Fill Yuva Form &rarr;
                                        </a>
                                    @else
                                        <a href="{{ route('member.events.register_form', $event->id) }}" class="w-full flex items-center justify-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white text-xs font-bold rounded-xl shadow-sm transition-all duration-150 text-center">
                                            View Registration Details &rarr;
                                        </a>
                                    @endif
                                @else
                                    <!-- Check Capacity -->
                                    @php
                                        $currentCount = $event->registrations()->whereIn('status', ['pending', 'approved'])->count();
                                        $isFull = $event->max_participants && ($currentCount >= $event->max_participants);
                                    @endphp

                                    @if($isFull)
                                        <div class="p-3 rounded-xl bg-rose-50 border border-rose-100 text-rose-700 text-xs font-bold text-center">
                                            Capacity Full ❌
                                        </div>
                                    @else
                                        @if(($event->event_type ?? 'normal') === 'inam_vitaran')
                                            <a href="{{ route('member.events.register_form', $event->id) }}" class="w-full flex items-center justify-center px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-xl shadow-sm transition-all duration-150 text-center gap-1">
                                                🏆 Fill Inam Form &rarr;
                                            </a>
                                        @elseif(($event->event_type ?? 'normal') === 'yuva_melo')
                                            <a href="{{ route('member.events.register_form', $event->id) }}" class="w-full flex items-center justify-center px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold rounded-xl shadow-sm transition-all duration-150 text-center gap-1">
                                                ⚡ Fill Yuva Form &rarr;
                                            </a>
                                        @else
                                            <a href="{{ route('member.events.register_form', $event->id) }}" class="w-full flex items-center justify-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white text-xs font-bold rounded-xl shadow-sm transition-all duration-150 text-center">
                                                Register for Event &rarr;
                                            </a>
                                        @endif
                                    @endif
                                @endif
                            @endif
                        </div>
                    @else
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-slate-500 text-xs font-bold text-center">
                            Open entry. No registration required.
                        </div>
                    @endif

                    <!-- Ticket Barcode Footer decoration -->
                    <div class="flex flex-col items-center justify-center pt-1.5 space-y-1 opacity-60">
                        <div class="h-3 w-full flex items-center justify-center gap-[2px]">
                            <div class="h-full w-[2px] bg-slate-400"></div>
                            <div class="h-full w-[1px] bg-slate-400"></div>
                            <div class="h-full w-[3px] bg-slate-400"></div>
                            <div class="h-full w-[1px] bg-slate-400"></div>
                            <div class="h-full w-[2px] bg-slate-400"></div>
                            <div class="h-full w-[4px] bg-slate-400"></div>
                            <div class="h-full w-[1px] bg-slate-400"></div>
                            <div class="h-full w-[2px] bg-slate-400"></div>
                            <div class="h-full w-[3px] bg-slate-400"></div>
                            <div class="h-full w-[1px] bg-slate-400"></div>
                        </div>
                        <!-- <span class="text-[8px] tracking-[0.2em] font-mono text-slate-500 uppercase leading-none">EVT-{{ str_pad($event->id, 5, '0', STR_PAD_LEFT) }}</span> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
