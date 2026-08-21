@extends('layouts.member')

@php
    $isGu = (app()->getLocale() === 'gu');
    $logoUrl = App\Models\Setting::get('website_logo') ? asset('storage/' . App\Models\Setting::get('website_logo')) : asset('logo.png');
    $userName = auth()->user() ? auth()->user()->name : 'Member';
    $memberCode = auth()->user() ? (auth()->user()->member_code ?: sprintf('#%05d', auth()->user()->id)) : '-';
@endphp

@section('page_title', __('messages.dashboard_overview'))

@section('content')
<div class="space-y-3.5" x-data="{ 
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
    
    <!-- Welcome Header & Quick Stats Card (Compact) -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 text-white rounded-xl p-3.5 sm:p-4 shadow-sm border border-slate-700/60 relative overflow-hidden">
        <div class="absolute -right-8 -top-8 w-28 h-28 bg-primary-500/10 rounded-full blur-xl pointer-events-none"></div>
        
        <div class="flex items-center justify-between gap-3 relative z-10">
            <h2 class="text-base sm:text-lg font-black text-white leading-tight">
                {{ $isGu ? 'નમસ્તે, ' . $user->name . '!' : 'Welcome, ' . $user->name . '!' }}
            </h2>

            <!-- Quick Action: Membership Card -->
            <a href="{{ route('member.card') }}" 
               class="px-3 py-1.5 bg-primary-500 hover:bg-primary-600 text-white font-bold text-xs rounded-lg shadow-xs transition-all flex items-center gap-1.5 cursor-pointer shrink-0">
                <span>🪪</span>
                <span>{{ $isGu ? 'મેમ્બરશિપ કાર્ડ જુઓ' : 'View Membership Card' }}</span>
            </a>
        </div>

        <!-- Quick Summary Mini Strip -->
        <div class="grid grid-cols-3 gap-2 pt-2.5 mt-2.5 border-t border-slate-700/60">
            <div class="bg-slate-800/70 rounded-lg p-2 border border-slate-700/50">
                <span class="text-[9px] font-bold text-slate-400 block uppercase tracking-wider">{{ $isGu ? 'સક્રિય કાર્યક્રમો' : 'Active Events' }}</span>
                <span class="text-sm sm:text-base font-black text-white block mt-0.5">{{ $activeEvents->count() }}</span>
            </div>
            <div class="bg-slate-800/70 rounded-lg p-2 border border-slate-700/50">
                <span class="text-[9px] font-bold text-slate-400 block uppercase tracking-wider">{{ $isGu ? 'મારા બુક કરેલ પાસ' : 'My Booked Passes' }}</span>
                <span class="text-sm sm:text-base font-black text-emerald-400 block mt-0.5">{{ $totalPersonsSum }} {{ $isGu ? 'વ્યક્તિઓ' : 'Persons' }}</span>
            </div>
            <div class="bg-slate-800/70 rounded-lg p-2 border border-slate-700/50">
                <span class="text-[9px] font-bold text-slate-400 block uppercase tracking-wider">{{ $isGu ? 'પરિવારના સભ્યો' : 'Family Members' }}</span>
                <span class="text-sm sm:text-base font-black text-primary-300 block mt-0.5">{{ $familyCount }}</span>
            </div>
        </div>
    </div>

    <!-- ================= ACTIVE EVENTS CARDS (Compact) ================= -->
    <div class="bg-white rounded-xl border border-slate-200/90 shadow-2xs p-3 sm:p-3.5 space-y-3">
        @if($activeEvents->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($activeEvents as $event)
                    @php
                        $alreadyRegistered = $myRegistrations->where('event_id', $event->id)->first();
                        $eventTypeBadge = match($event->event_type ?? 'normal') {
                            'inam_vitaran' => ['bg' => 'bg-amber-50 text-amber-700 border-amber-200', 'label' => ($isGu ? '🎓 ઇનામ વિતરણ' : 'Inam Vitran')],
                            'yuva_melo' => ['bg' => 'bg-purple-50 text-purple-700 border-purple-200', 'label' => ($isGu ? '⚡ યુવા મેળો' : 'Yuva Melo')],
                            default => ['bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'label' => ($isGu ? '🎉 સામાન્ય કાર્યક્રમ' : 'General Event')],
                        };

                        $regPasses = [];
                        if ($alreadyRegistered) {
                            $pCount = (int)($alreadyRegistered->form_data['person_count'] ?? 1);
                            $basePassNo = (int)($alreadyRegistered->pass_number ?: ($alreadyRegistered->form_data['registration_no'] ?? $alreadyRegistered->id));
                            for ($i = 0; $i < $pCount; $i++) {
                                $regPasses[] = sprintf('%03d', $basePassNo + $i);
                            }
                        }
                    @endphp
                    <div class="bg-white rounded-xl border border-slate-200/90 p-3 shadow-2xs hover:shadow-xs hover:border-primary-400 transition-all flex flex-col justify-between space-y-2.5 group">
                        
                        <div class="space-y-2">
                            <!-- Event Header -->
                            <div class="flex items-center justify-between gap-2">
                                <span class="px-2.5 py-1 rounded-md text-[10.5px] font-black uppercase tracking-wider border {{ $eventTypeBadge['bg'] }}">
                                    {{ $eventTypeBadge['label'] }}
                                </span>
                            </div>

                            <h4 class="text-sm font-black text-slate-900 group-hover:text-primary-600 transition-colors line-clamp-1" title="{{ $event->title }}">
                                {{ $event->title }}
                            </h4>

                            <!-- Date & Venue -->
                            <div class="bg-slate-50 p-2 rounded-lg border border-slate-100 space-y-1 text-xs text-slate-700">
                                <div class="flex items-center gap-1.5 font-bold text-slate-800">
                                    <span>📅</span>
                                    <span>{{ date('d M, Y', strtotime($event->date)) }}</span>
                                    @if($event->time)
                                        <span class="text-slate-400 font-normal">({{ date('h:i A', strtotime($event->time)) }})</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-1.5 text-[11px] text-slate-500 font-medium truncate" title="{{ $event->venue }}">
                                    <span>📍</span>
                                    <span class="truncate">{{ $event->venue ?: ($isGu ? 'સ્થળ જાહેર થશે' : 'Venue TBA') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Booking Action Button -->
                        <div class="pt-1.5 border-t border-slate-100 flex items-center justify-between gap-2">
                            <div>
                                <span class="text-[9.5px] font-extrabold text-slate-400 uppercase block tracking-wider">{{ $isGu ? 'પાસ ફી' : 'Pass Fee' }}</span>
                                <span class="text-xs font-black {{ $event->pass_fee > 0 ? 'text-primary-600' : 'text-emerald-600' }}">
                                    {{ $event->pass_fee > 0 ? '₹' . number_format($event->pass_fee, 0) : ($isGu ? 'મફત પ્રવેશ' : 'Free Entry') }}
                                </span>
                            </div>

                            @if($alreadyRegistered)
                                <button type="button" 
                                        @click="openPassModal({{ json_encode(['title' => $event->title, 'date' => date('d-M-Y', strtotime($event->date)), 'time' => $event->time ? date('h:i A', strtotime($event->time)) : '', 'venue' => $event->venue]) }}, {{ json_encode($regPasses) }}, '{{ addslashes($userName) }}')"
                                        class="px-3 py-1.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-lg shadow-xs transition-colors inline-flex items-center gap-1.5 cursor-pointer shrink-0">
                                    <span>🎫 {{ $isGu ? 'પાસ જુઓ (' . count($regPasses) . ')' : 'View Pass (' . count($regPasses) . ')' }}</span>
                                    <span>&rarr;</span>
                                </button>
                            @else
                                <a href="{{ route('event.details', $event->id) }}" 
                                   class="px-3 py-1.5 bg-primary-500 hover:bg-primary-600 text-white font-bold text-xs rounded-lg shadow-xs transition-colors inline-flex items-center gap-1.5 cursor-pointer shrink-0">
                                    <span>🎫 {{ $isGu ? 'પાસ બુક કરો' : 'Book Pass' }}</span>
                                    <span>&rarr;</span>
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State for Active Events -->
            <div class="text-center py-8 bg-slate-50/70 rounded-lg border border-slate-100 p-4 space-y-1.5">
                <span class="text-2xl block">📅</span>
                <h4 class="text-xs font-black text-slate-800">
                    {{ $isGu ? 'હાલમાં કોઈ સક્રિય કાર્યક્રમ ઉપલબ્ધ નથી' : 'No active events currently scheduled' }}
                </h4>
                <p class="text-[10px] text-slate-400 font-medium">
                    {{ $isGu ? 'નવા કાર્યક્રમો જાહેર થતાં જ અહીં દેખાશે.' : 'Upcoming community events will appear here once announced.' }}
                </p>
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
                             :id="'dashboard-pass-card-' + idx"
                             :data-pass-no="pNo"
                             :data-event-title="activeEvent?.title || ''"
                             data-mandal="Satwara Gyati Mandal Ahm."
                             :data-date="(activeEvent?.date || '') + (activeEvent?.time ? ' | ⏰ ' + activeEvent?.time : '')"
                             :data-venue="activeEvent?.venue || ''"
                             :data-attendee="activeAttendee || ''"
                             :data-member-code="activeMemberId || ''"
                             data-logo="{{ $logoUrl }}">
                            <!-- Top Bar -->
                            <div class="bg-slate-900 text-white px-4 py-2 flex items-center justify-between text-[11px] font-black uppercase tracking-wider">
                                <span class="flex items-center gap-1.5 truncate">
                                    <span x-text="activeAttendee"></span>
                                    <span class="text-primary-400 font-mono" x-show="activeMemberId" x-text="'(' + activeMemberId + ')'"></span>
                                </span>
                                <span class="text-amber-400 shrink-0">Entry Pass</span>
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
                                <button type="button" :data-card-id="'dashboard-pass-card-' + idx"
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
/* ===== MEMBER DASHBOARD PASS PDF DOWNLOAD ===== */

function _renderMemberPassHtmlCard(passData) {
    const logoSrc = passData.logo || '/logo.png';
    const mandal = passData.mandal || 'Satwara Gyati Mandal Ahm.';
    const title = passData.title || '';
    const date = passData.date || '';
    const passNo = passData.passNo || '001';
    const venue = passData.venue || '';
    const attendee = passData.attendee || '';
    const memberCode = passData.memberCode || '';
    const topNameWithCode = attendee ? (attendee + (memberCode ? ' (' + memberCode + ')' : '')) : 'SATWARA COMMUNITY ENTRY PASS';

    return `
    <div style="border: 2px solid #0f172a; border-radius: 12px; overflow: hidden; margin-bottom: 22px; page-break-inside: avoid; background: #ffffff; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; box-sizing: border-box;">
        <!-- Top Bar -->
        <table style="width: 100%; border-collapse: collapse; background-color: #0f172a; color: #ffffff;">
            <tr>
                <td style="padding: 7px 16px; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; text-align: left; color: #ffffff;">
                    ${topNameWithCode}
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
        * { 
            box-sizing: border-box; 
            margin: 0; 
            padding: 0; 
            -webkit-print-color-adjust: exact !important; 
            print-color-adjust: exact !important; 
            color-adjust: exact !important; 
        }
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; 
            background: #ffffff; 
            padding: 24px; 
            color: #0f172a; 
            -webkit-print-color-adjust: exact !important; 
            print-color-adjust: exact !important; 
            color-adjust: exact !important; 
        }
        @media print {
            body { 
                padding: 0; 
                -webkit-print-color-adjust: exact !important; 
                print-color-adjust: exact !important; 
                color-adjust: exact !important; 
            }
            * {
                -webkit-print-color-adjust: exact !important; 
                print-color-adjust: exact !important; 
                color-adjust: exact !important; 
            }
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
            attendee: card.dataset.attendee || '',
            memberCode: card.dataset.memberCode || '',
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
        attendee: card.dataset.attendee || '',
        memberCode: card.dataset.memberCode || '',
        logo: card.dataset.logo || card.querySelector('img')?.src || ''
    };
    _openMemberPassesWindow(_renderMemberPassHtmlCard(data), 'Event Entry Pass - ' + data.passNo);
}
</script>
@endpush
