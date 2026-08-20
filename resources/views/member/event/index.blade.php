@extends('layouts.member')

@section('page_title', __('messages.events'))

@section('content')
@php
    $isGu = (app()->getLocale() === 'gu');
    $logoUrl = App\Models\Setting::get('website_logo') ? asset('storage/' . App\Models\Setting::get('website_logo')) : asset('logo.png');
    $userName = auth()->user() ? auth()->user()->name : 'Member';
    $memberCode = auth()->user() ? sprintf('#%05d', auth()->user()->id) : '-';
@endphp

<div class="space-y-5" x-data="{ 
    currentTab: 'all',
    searchQuery: '{{ addslashes(request('search', '')) }}',
    showPassModal: false, 
    activeEvent: null, 
    activePasses: [],
    activeAttendee: '{{ addslashes($userName) }}',
    activeMemberId: '{{ $memberCode }}',
    openPassModal(eventObj, passesList, attendeeName) {
        this.activeEvent = eventObj;
        this.activePasses = passesList;
        this.activeAttendee = attendeeName || '{{ addslashes($userName) }}';
        this.showPassModal = true;
    }
}">

    <!-- Header Controls: Search Filter (Left) & Tab Navigation (Right) -->
    <div class="bg-white rounded-2xl border border-slate-100 p-3 sm:p-3.5 shadow-2xs">
        <div class="flex items-center justify-between gap-3 flex-wrap">
            <!-- Search Filter (Left) -->
            <div class="flex-1 min-w-[200px] max-w-md">
                <form method="GET" action="{{ route('member.events.index') }}" class="relative flex items-center">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           x-model="searchQuery"
                           placeholder="{{ $isGu ? 'કાર્યક્રમ શોધો (નામ, સ્થળ)...' : 'Search events by name, venue...' }}"
                           class="w-full px-3.5 pr-8 py-1.5 bg-slate-50 hover:bg-slate-100/70 focus:bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                    <template x-if="searchQuery">
                        <button type="button" @click="searchQuery = ''; window.location.href = '{{ route('member.events.index') }}';"
                                class="absolute right-2.5 text-slate-400 hover:text-slate-600 text-xs font-bold p-0.5 cursor-pointer">
                            ✕
                        </button>
                    </template>
                </form>
            </div>

            <!-- Tab Buttons (Right) -->
            <div class="flex items-center gap-1.5 bg-slate-100/90 p-1 rounded-xl border border-slate-200/80 shrink-0">
                <button type="button" @click="currentTab = 'all'"
                    :class="currentTab === 'all' ? 'bg-white text-slate-900 shadow-xs font-black' : 'text-slate-600 hover:text-slate-900 font-bold'"
                    class="px-3 py-1.5 rounded-lg text-xs transition-all flex items-center gap-1.5 cursor-pointer">
                    <span>📅 {{ $isGu ? 'બધા કાર્યક્રમો' : 'All Events' }}</span>
                    <span class="text-[10px] px-1.5 py-0.2 rounded-full font-bold"
                          :class="currentTab === 'all' ? 'bg-slate-100 text-slate-800' : 'bg-slate-200/80 text-slate-600'">
                        {{ $events->total() ?? $events->count() }}
                    </span>
                </button>

                <button type="button" @click="currentTab = 'my_passes'"
                    :class="currentTab === 'my_passes' ? 'bg-primary-600 text-white shadow-xs font-black' : 'text-slate-600 hover:text-slate-900 font-bold'"
                    class="px-3 py-1.5 rounded-lg text-xs transition-all flex items-center gap-1.5 cursor-pointer">
                    <span>🎟️ {{ $isGu ? 'મારા પાસ' : 'My Passes' }}</span>
                    @if(isset($myRegistrations) && $myRegistrations->isNotEmpty())
                        <span class="text-[10px] px-1.5 py-0.2 rounded-full font-black"
                              :class="currentTab === 'my_passes' ? 'bg-white text-primary-700' : 'bg-primary-500 text-white'">
                            {{ $myRegistrations->count() }}
                        </span>
                    @endif
                </button>
            </div>
        </div>
    </div>

    <!-- ================= TAB 2: MY REGISTERED EVENTS & PASSES ================= -->
    <div x-show="currentTab === 'my_passes'" x-cloak class="space-y-4">
        @if(isset($myRegistrations) && $myRegistrations->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                @foreach($myRegistrations as $myReg)
                    @php
                        $rEvent = $myReg->event;
                        $pCount = max(1, (int)($myReg->form_data['person_count'] ?? 1));
                        $regPasses = [];
                        if ($rEvent) {
                            for ($pi = 1; $pi <= $pCount; $pi++) {
                                $regPasses[] = sprintf('%03d', $pi);
                            }
                        }
                        $cardAttendee = $myReg->form_data['full_name'] ?? $userName;
                    @endphp
                    @if($rEvent)
                        <div class="bg-white border border-slate-200 hover:border-primary-400 rounded-xl p-3 flex flex-col justify-between space-y-2.5 shadow-2xs hover:shadow-xs transition-all"
                             x-show="!searchQuery || '{{ strtolower(addslashes($rEvent->title . ' ' . $rEvent->venue)) }}'.includes(searchQuery.toLowerCase())">
                            
                            <div class="space-y-1.5">
                                <a href="{{ route('event.details', $rEvent->id) }}" class="block">
                                    <h3 class="text-xs sm:text-sm font-extrabold text-slate-900 hover:text-primary-600 transition-colors line-clamp-1">
                                        {{ $rEvent->title }}
                                    </h3>
                                </a>

                                <div class="px-2.5 py-1.5 bg-slate-50 border border-slate-100 rounded-lg flex items-center justify-between text-[11px]">
                                    <span class="font-bold text-slate-600">👥 {{ $isGu ? 'પાસ:' : 'Passes:' }}</span>
                                    <span class="font-black text-primary-700">{{ $pCount }} {{ $isGu ? 'વ્યક્તિ' : 'Person(s)' }}</span>
                                    @if(($rEvent->pass_fee ?? 0) > 0)
                                        <span class="text-slate-300">|</span>
                                        <span class="font-extrabold text-emerald-600">₹{{ number_format($myReg->payment_amount ?? ($rEvent->pass_fee * $pCount)) }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="pt-2 border-t border-slate-100 flex items-center gap-1.5">
                                <button type="button" 
                                        @click="openPassModal({{ json_encode(['title' => $rEvent->title, 'date' => date('d-M-Y', strtotime($rEvent->date)), 'time' => $rEvent->time ? date('h:i A', strtotime($rEvent->time)) : '', 'venue' => $rEvent->venue]) }}, {{ json_encode($regPasses) }}, '{{ addslashes($cardAttendee) }}')"
                                        class="flex-1 py-1.5 px-2 bg-primary-600 hover:bg-primary-700 active:scale-95 text-white text-[11px] font-extrabold rounded-lg shadow-2xs transition-all flex items-center justify-center gap-1 cursor-pointer">
                                    <span>🎟️ {{ $isGu ? 'પાસ જુઓ (' . $pCount . ')' : 'View Passes (' . $pCount . ')' }}</span>
                                </button>
                                <a href="{{ route('event.details', $rEvent->id) }}" 
                                   class="py-1.5 px-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-[11px] font-bold rounded-lg transition-colors shrink-0">
                                    {{ $isGu ? 'વિગતો →' : 'Details →' }}
                                </a>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            <div class="bg-white border border-slate-100 rounded-2xl p-10 text-center space-y-3 shadow-2xs">
                <div class="text-4xl">🎟️</div>
                <h3 class="text-sm font-black text-slate-800">
                    {{ $isGu ? 'હજી સુધી કોઈ પાસ બુક કરેલા નથી' : 'No Event Passes Registered Yet' }}
                </h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto">
                    {{ $isGu ? 'તમે આગામી કાર્યક્રમો માટે રજીસ્ટ્રેશન કરી પ્રવેશ પાસ મેળવી શકો છો.' : 'You have not registered for any events yet. Browse events and book your entry passes.' }}
                </p>
                <button type="button" @click="currentTab = 'all'"
                        class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-bold text-xs rounded-xl shadow-xs transition-colors cursor-pointer inline-flex items-center gap-1.5">
                    <span>📅 {{ $isGu ? 'કાર્યક્રમો જુઓ' : 'Browse All Events' }}</span>
                </button>
            </div>
        @endif
    </div>

    <!-- ================= TAB 1: ALL COMMUNITY EVENTS ================= -->
    <div x-show="currentTab === 'all'" class="space-y-4">
        <!-- Events Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($events as $event)
                @php
                    $isRegistered = !empty($registrations[$event->id]);
                    $thisReg = isset($myRegistrations) ? $myRegistrations->firstWhere('event_id', $event->id) : null;
                    $pCount = $thisReg ? max(1, (int)($thisReg->form_data['person_count'] ?? 1)) : 1;
                    $cardPasses = [];
                    if ($thisReg) {
                        for ($pi = 1; $pi <= $pCount; $pi++) {
                            $cardPasses[] = sprintf('%03d', $pi);
                        }
                    }
                    $attendeeStr = $thisReg->form_data['full_name'] ?? $userName;
                @endphp
                <div class="group bg-white rounded-xl border border-slate-100 shadow-2xs flex flex-col overflow-hidden hover:shadow-md transition-all"
                     x-show="!searchQuery || '{{ strtolower(addslashes($event->title . ' ' . $event->venue)) }}'.includes(searchQuery.toLowerCase())">
                    <!-- Event Banner (Clickable to website details) -->
                    <a href="{{ route('event.details', $event->id) }}" class="relative h-36 sm:h-40 overflow-hidden block bg-white">
                        {{-- Always-visible Red Background + Calendar Icon (base layer) --}}
                        <div style="position:absolute; inset:0; display:flex; flex-direction:column; align-items:center; justify-content:center; padding: 14px; background: linear-gradient(135deg, #dc2626 0%, #e11d48 60%, #be123c 100%);">
                            {{-- Dot grid texture --}}
                            <div style="position:absolute; inset:0; background-image: radial-gradient(circle, rgba(255,255,255,0.12) 1px, transparent 1px); background-size: 12px 12px; pointer-events:none;"></div>

                            {{-- Calendar card --}}
                            <div style="position:relative; display:flex; flex-direction:column; align-items:center; gap:6px;">
                                <div style="width:54px; height:56px; border-radius:10px; background:#fff; overflow:hidden; display:flex; flex-direction:column; box-shadow: 0 4px 14px rgba(0,0,0,0.25);">
                                    {{-- Month header --}}
                                    <div style="background: linear-gradient(90deg, #dc2626, #e11d48); padding: 3px 0; text-align:center; flex-shrink:0;">
                                        <span style="font-size:9.5px; font-weight:900; color:#fff; letter-spacing:0.12em; text-transform:uppercase; display:block; line-height:1;">
                                            {{ date('M', strtotime($event->date)) }}
                                        </span>
                                    </div>
                                    {{-- Day number --}}
                                    <div style="flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center;">
                                        <span style="font-size:19px; font-weight:900; color:#1e293b; line-height:1;">
                                            {{ date('d', strtotime($event->date)) }}
                                        </span>
                                    </div>
                                </div>
                                <span style="font-size:8px; font-weight:800; text-transform:uppercase; letter-spacing:0.08em; color:#fff; background:rgba(0,0,0,0.35); border:1px solid rgba(255,255,255,0.15); padding:1.5px 6px; border-radius:999px; white-space:nowrap;">Community Event</span>
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
                                <span class="text-[8px] font-extrabold text-slate-500 bg-white/95 backdrop-blur-sm border border-slate-200 px-1.5 py-0.2 rounded-full uppercase tracking-wider">{{ __('messages.passed') }}</span>
                            @else
                                <span class="text-[8px] font-extrabold text-emerald-600 bg-white/95 backdrop-blur-sm border border-emerald-100 px-1.5 py-0.2 rounded-full uppercase tracking-wider">{{ __('messages.upcoming') }}</span>
                            @endif

                            @if(($event->event_type ?? 'normal') === 'inam_vitaran')
                                <span class="text-[8px] font-extrabold text-amber-700 bg-amber-50/95 backdrop-blur-sm border border-amber-200 px-1.5 py-0.2 rounded-full uppercase tracking-wider">🏆 {{ __('messages.inam_vitaran') }}</span>
                            @elseif(($event->event_type ?? 'normal') === 'yuva_melo')
                                <span class="text-[8px] font-extrabold text-purple-700 bg-purple-50/95 backdrop-blur-sm border border-purple-200 px-1.5 py-0.2 rounded-full uppercase tracking-wider">⚡ {{ __('messages.yuva_melo') }}</span>
                            @endif
                        </div>
                    </a>

                    <!-- Event Details -->
                    <div class="p-3.5 flex-grow flex flex-col justify-between space-y-3">
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between text-[11px] font-bold text-slate-500 flex-wrap gap-1">
                                <span>📅 {{ date('d-M-Y', strtotime($event->date)) }}</span>
                                @if(!empty($event->registration_end_date))
                                    <span class="text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded-md border border-rose-200/70 text-[10px] font-extrabold">⏳ {{ date('d-M-Y', strtotime($event->registration_end_date)) }}</span>
                                @else
                                    <span class="text-slate-400">🕒 {{ date('h:i A', strtotime($event->time)) }}</span>
                                @endif
                            </div>
                            
                            <a href="{{ route('event.details', $event->id) }}" class="block">
                                <h3 class="text-xs sm:text-sm font-extrabold text-slate-900 line-clamp-1 hover:text-primary-600 transition-colors">{{ $event->title }}</h3>
                            </a>
                            @php
                                $cleanDesc = preg_replace('/<span class="ql-ui"[^>]*>.*?<\/span>/i', '', $event->description);
                                $cleanDesc = strip_tags(str_replace(['<br>', '</p>', '</li>', '</div>'], ' ', $cleanDesc));
                                $cleanDesc = trim(preg_replace('/\s+/', ' ', $cleanDesc));
                            @endphp
                            <a href="{{ route('event.details', $event->id) }}" class="block">
                                <p class="text-[11px] text-slate-500 line-clamp-2 leading-relaxed break-words">
                                    {{ \Illuminate\Support\Str::limit($cleanDesc, 80, '...') }}
                                </p>
                            </a>
                        </div>

                        <!-- Action and Status -->
                        <div class="pt-2 mt-auto border-t border-slate-100 space-y-1.5">
                            <div class="flex items-center justify-between gap-1.5 flex-wrap">
                                <!-- Registration Status Badge -->
                                <div>
                                    @if($isRegistered)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[11px] font-extrabold text-emerald-700 bg-emerald-50 border border-emerald-200/80 rounded-lg uppercase">
                                            ✓ {{ __('messages.registered') }} ({{ $pCount }})
                                        </span>
                                    @elseif(($event->event_type ?? 'normal') === 'normal' || !($event->has_registration_form || $event->registration_option))
                                        <span class="inline-flex items-center px-2.5 py-1 text-[11px] font-bold text-slate-500 bg-slate-50 border border-slate-200 rounded-lg uppercase">{{ __('messages.open_entry') }}</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 text-[11px] font-bold text-slate-500 bg-slate-50 border border-slate-200 rounded-lg uppercase">{{ __('messages.not_registered') }}</span>
                                    @endif
                                </div>

                                @if(($event->event_type ?? 'normal') === 'inam_vitaran')
                                    <a href="{{ route('member.events.register_form', $event->id) }}"
                                       class="inline-flex items-center px-2.5 py-1 bg-amber-600 hover:bg-amber-700 active:scale-95 text-white text-[11px] font-extrabold rounded-lg transition-all gap-1 shadow-2xs">
                                        🏆 {{ $isGu ? 'ઈનામ' : 'Inam' }}
                                    </a>
                                @elseif(($event->event_type ?? 'normal') === 'yuva_melo')
                                    <a href="{{ route('member.events.register_form', $event->id) }}"
                                       class="inline-flex items-center px-2.5 py-1 bg-purple-600 hover:bg-purple-700 active:scale-95 text-white text-[11px] font-extrabold rounded-lg transition-all gap-1 shadow-2xs">
                                        ⚡ {{ $isGu ? 'યુવા મેળો' : 'Yuva Melo' }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-4 text-center py-16 bg-white border border-slate-100 rounded-xl">
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

    <!-- ================= VIEW PASSES MODAL (TELEPORTED TO BODY) ================= -->
    <template x-teleport="body">
        <div x-show="showPassModal" 
             class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-slate-950/70 backdrop-blur-md"
             x-transition
             x-cloak>
            <div @click.away="showPassModal = false" 
                 class="bg-white rounded-3xl border border-slate-100 shadow-2xl max-w-2xl w-full max-h-[90vh] flex flex-col overflow-hidden relative">
                
                <!-- Modal Header -->
                <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-primary-600/30 border border-primary-500/40 text-primary-400 flex items-center justify-center text-lg">
                            🎟️
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold flex items-center gap-2">
                                <span>{{ $isGu ? 'ઇવેન્ટ પ્રવેશ પાસ' : 'Event Entry Passes' }}</span>
                                <span class="text-[10px] bg-primary-500 text-white font-black px-2 py-0.5 rounded-full" x-text="activePasses.length + ' Passes'"></span>
                            </h3>
                            <p class="text-[11px] text-slate-400 font-medium truncate max-w-[280px] sm:max-w-md" x-text="activeEvent?.title"></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="downloadAllPassesMember()" 
                                class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-extrabold rounded-xl shadow-xs transition-colors flex items-center gap-1.5 cursor-pointer">
                            ⬇️ Download All PDF
                        </button>
                        <button type="button" @click="showPassModal = false" 
                                class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors text-xs font-bold cursor-pointer">
                            ✕
                        </button>
                    </div>
                </div>

                <!-- Modal Scrollable Content containing all passes -->
                <div class="p-4 sm:p-6 overflow-y-auto space-y-6 bg-slate-50 flex-1">
                    <template x-for="(pNo, idx) in activePasses" :key="idx">
                        <div class="bg-white rounded-2xl border-2 border-slate-900 shadow-sm overflow-hidden text-slate-900 print-pass-member-item" 
                             :id="'member-pass-card-' + idx"
                             :data-pass-no="pNo"
                             :data-event-title="activeEvent?.title || ''"
                             data-mandal="Satwara Gyati Mandal Ahm."
                             :data-date="(activeEvent?.date || '') + (activeEvent?.time ? ' | ⏰ ' + activeEvent?.time : '')"
                             :data-venue="activeEvent?.venue || ''"
                             data-logo="{{ $logoUrl }}">
                            <!-- Top Bar -->
                            <div class="bg-slate-900 text-white px-4 py-2 flex items-center justify-between text-[11px] font-black uppercase tracking-wider">
                                <span>Sathwara Community Entry Pass</span>
                                <span class="text-primary-400">Entry Pass</span>
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
                                    <div class="text-base sm:text-lg font-black text-rose-600 leading-tight" x-text="activeEvent?.title">
                                    </div>
                                    <div class="text-xs font-bold text-slate-700 flex items-center justify-center sm:justify-start gap-1">
                                        <span>📅 {{ $isGu ? 'તારીખ:' : 'Date:' }}</span>
                                        <span x-text="activeEvent?.date"></span>
                                        <span x-show="activeEvent?.time" class="text-slate-400">|</span>
                                        <span x-show="activeEvent?.time" x-text="'⏰ ' + activeEvent?.time"></span>
                                    </div>

                                </div>

                                <!-- Right: Dedicated Pass No. Box -->
                                <div class="shrink-0 flex flex-col items-center sm:items-end justify-between self-stretch pt-2 sm:pt-0">
                                    <div class="border-2 border-slate-900 rounded-xl px-4 py-2 bg-slate-50 text-center shadow-xs">
                                        <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-widest block">Pass No.</span>
                                        <span class="text-xl font-black text-slate-900 block mt-0.5 tracking-widest" x-text="pNo"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Bottom Location Strip -->
                            <div class="border-t-2 border-dashed border-slate-200 bg-slate-50/80 px-4 py-2.5 text-xs font-bold text-slate-700 flex items-center justify-between gap-1.5">
                                <span class="flex items-center gap-1.5">
                                    <span class="text-rose-500">📍</span>
                                    <span><strong>{{ $isGu ? 'સ્થળ / સરનામું:' : 'Location / Venue:' }}</strong> <span x-text="activeEvent?.venue"></span></span>
                                </span>
                                <button type="button" :data-card-id="'member-pass-card-' + idx"
                                        onclick="downloadSinglePassMember(this.dataset.cardId)"
                                        class="flex items-center gap-1 px-2.5 py-1 bg-slate-900 hover:bg-slate-700 text-white text-[10px] font-extrabold rounded-lg transition-colors cursor-pointer">
                                    ⬇️ Download
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-3 bg-white border-t border-slate-100 flex items-center justify-between shrink-0">
                    <span class="text-[11px] text-slate-400 font-medium">💡 {{ $isGu ? 'કૃપા કરીને કાર્યક્રમ સ્થળે પ્રવેશ વખતે આ પાસ દર્શાવો.' : 'Please present this pass at the event entrance.' }}</span>
                    <button type="button" @click="showPassModal = false" 
                            class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-colors cursor-pointer">
                        {{ $isGu ? 'બંધ કરો' : 'Close' }}
                    </button>
                </div>
            </div>
        </div>
    </template>

</div>
@endsection

@push('scripts')
<script>
/* ===== MEMBER PANEL PASS PDF DOWNLOAD ===== */

function _renderMemberPassHtmlCard(passData) {
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

function _openMemberPassesWindow(cardsHtml, title) {
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

function downloadAllPassesMember() {
    const cards = document.querySelectorAll('.print-pass-member-item');
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
        html += _renderMemberPassHtmlCard(data);
    });
    _openMemberPassesWindow(html, 'Event Entry Passes');
}

function downloadSinglePassMember(cardId) {
    const card = document.getElementById(cardId);
    if (!card) { console.error('Pass card not found:', cardId); return; }
    const data = {
        passNo: card.dataset.passNo || card.querySelector('.text-xl')?.innerText.trim() || '001',
        title: card.dataset.eventTitle || '',
        mandal: card.dataset.mandal || 'Satwara Gyati Mandal Ahm.',
        date: card.dataset.date || '',
        venue: card.dataset.venue || '',
        logo: card.dataset.logo || card.querySelector('img')?.src || ''
    };
    _openMemberPassesWindow(_renderMemberPassHtmlCard(data), 'Event Entry Pass - ' + data.passNo);
}
</script>
@endpush
