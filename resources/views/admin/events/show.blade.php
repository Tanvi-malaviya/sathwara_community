@extends('layouts.admin')

@section('page_title', __('messages.event_details'))

@section('content')
<div class="space-y-4" x-data="adminEventShowData()">
    <!-- Top Action Bar -->
    <div class="bg-white p-3.5 rounded-xl border border-slate-100 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.events.index') }}" 
               class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors flex items-center justify-center font-bold text-xs shrink-0" 
               title="{{ __('messages.back_to_website') }}">
                &larr;
            </a>
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <h1 class="text-sm font-extrabold text-slate-900 leading-tight">{{ $event->title }}</h1>
                    
                    <!-- Status Badge -->
                    <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase tracking-wider
                        {{ $event->status == 'published' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' : ($event->status == 'cancelled' ? 'bg-rose-50 text-rose-700 border border-rose-200/60' : 'bg-slate-100 text-slate-600 border border-slate-200') }}">
                        {{ __('messages.' . strtolower($event->status)) != 'messages.' . strtolower($event->status) ? __('messages.' . strtolower($event->status)) : ucfirst($event->status) }}
                    </span>

                    <!-- Event Type Badge -->
                    @if($event->event_type === 'inam_vitaran')
                        <span class="px-2 py-0.5 rounded bg-amber-50 text-amber-800 border border-amber-200 text-[10px] font-extrabold uppercase tracking-wider">🏆 {{ __('messages.inam_vitaran') }}</span>
                    @elseif($event->event_type === 'yuva_melo')
                        <span class="px-2 py-0.5 rounded bg-purple-50 text-purple-800 border border-purple-200 text-[10px] font-extrabold uppercase tracking-wider">⚡ {{ __('messages.yuva_melo') }}</span>
                    @else
                        <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 border border-slate-200 text-[10px] font-extrabold uppercase tracking-wider">📢 {{ __('messages.general_event') }}</span>
                    @endif

                    <!-- Fee Badge -->
                    @if($event->pass_fee > 0)
                        <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-800 border border-emerald-200 text-[10px] font-extrabold uppercase tracking-wider">🎟️ ₹{{ number_format($event->pass_fee, 2) }} {{ __('messages.fee') }}</span>
                    @endif

                    @if($event->event_type === 'yuva_melo' && ($event->form_fee ?? 0) > 0)
                        <span class="px-2 py-0.5 rounded bg-purple-50 text-purple-800 border border-purple-200 text-[10px] font-extrabold uppercase tracking-wider">⚡ ₹{{ number_format($event->form_fee, 2) }} Form Fee</span>
                    @endif
                </div>
                <p class="text-[11px] text-slate-400 font-medium mt-0.5">{{ __('messages.created_on') }} {{ $event->created_at->format('d M Y, h:i A') }}</p>
            </div>
        </div>

        @php
            $user = auth()->user();
            $userPerms = $user->permissions->pluck('name');
            $canEditThisEvent = $user->hasRole('Administrator') || 
                                $userPerms->contains('events_manage') || 
                                $userPerms->contains('event_manage_' . $event->id) || 
                                $userPerms->contains('event_edit_' . $event->id);

            $canDeleteThisEvent = $user->hasRole('Administrator') || 
                                  $userPerms->contains('events_manage') || 
                                  $userPerms->contains('event_manage_' . $event->id);

            $totalCount = count($registrations);
            $approvedCount = $registrations->where('status', 'approved')->count();
            $rejectedCount = $registrations->where('status', 'rejected')->count();
            $galleryCount = $gallery->count();
        @endphp

        <!-- Header Action Buttons (Only Edit & Delete, Registrations and Gallery removed) -->
        <div class="flex items-center gap-1.5 shrink-0 flex-wrap">
            @if($canEditThisEvent)
                <a href="{{ route('admin.events.edit', $event->id) }}" 
                   class="px-3 py-1.5 bg-amber-50 text-amber-700 hover:bg-amber-100 border border-amber-200/60 font-extrabold text-xs rounded-lg transition-colors flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <span>{{ __('messages.edit') }}</span>
                </a>
            @endif

            @if($canDeleteThisEvent)
                <button type="button" 
                        @click="$dispatch('confirm-delete', { action: '{{ route('admin.events.destroy', $event->id) }}', message: '{{ __('messages.delete_confirm_event', ['name' => $event->title]) }}' })"
                        class="px-3 py-1.5 bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200/60 font-extrabold text-xs rounded-lg transition-colors flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    <span>{{ __('messages.delete') }}</span>
                </button>
            @endif
        </div>
    </div>

    <!-- Main Tab Navigation Bar -->
    <div class="bg-white p-1.5 rounded-xl border border-slate-100 shadow-sm flex items-center gap-1.5 overflow-x-auto">
        <!-- Tab 1: Details -->
        <button type="button" @click="mainTab = 'details'"
                :class="mainTab === 'details' ? 'bg-primary-500 text-white shadow-xs font-black' : 'text-slate-600 hover:text-slate-900 font-bold hover:bg-slate-50'"
                class="px-4 py-2 text-xs rounded-lg transition-all flex items-center gap-2 whitespace-nowrap">
            <span>📋</span>
            <span>{{ __('messages.event_details') }}</span>
        </button>

        <!-- Tab 2: Submissions / Registrations -->
        <button type="button" @click="mainTab = 'submissions'"
                :class="mainTab === 'submissions' ? 'bg-primary-500 text-white shadow-xs font-black' : 'text-slate-600 hover:text-slate-900 font-bold hover:bg-slate-50'"
                class="px-4 py-2 text-xs rounded-lg transition-all flex items-center gap-2 whitespace-nowrap">
            <span>{{ ($event->event_type ?? 'normal') === 'yuva_melo' ? '⚡' : '🎓' }}</span>
            <span>{{ ($event->event_type ?? 'normal') === 'inam_vitaran' ? 'Student Inam Submissions' : (($event->event_type ?? 'normal') === 'yuva_melo' ? 'Yuva Melo Candidate Submissions' : __('messages.event_registrations')) }}</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-black"
                  :class="mainTab === 'submissions' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-700'">{{ $totalCount }}</span>
        </button>

        <!-- Tab 3: Gallery -->
        <button type="button" @click="mainTab = 'gallery'"
                :class="mainTab === 'gallery' ? 'bg-primary-500 text-white shadow-xs font-black' : 'text-slate-600 hover:text-slate-900 font-bold hover:bg-slate-50'"
                class="px-4 py-2 text-xs rounded-lg transition-all flex items-center gap-2 whitespace-nowrap">
            <span>🖼️</span>
            <span>{{ __('messages.gallery') }}</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-black"
                  :class="mainTab === 'gallery' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-700'">{{ $galleryCount }}</span>
        </button>
    </div>

    <!-- ================= TAB 1: EVENT DETAILS ================= -->
    <div x-show="mainTab === 'details'" x-transition class="space-y-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 items-stretch">
            <!-- Left Side: Cover Photo Card -->
            <div class="lg:col-span-1 flex flex-col">
                <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-4 flex-1 flex items-center justify-center min-h-[260px] max-h-[360px]">
                    @if($event->banner_path)
                        <img src="{{ str_starts_with($event->banner_path, 'http') ? $event->banner_path : asset('storage/' . $event->banner_path) }}" 
                             alt="{{ $event->title }}" 
                             class="w-full h-full object-contain max-h-[340px] rounded-lg">
                    @else
                        <div class="text-center text-slate-400 font-medium py-12">
                            <span class="text-3xl block mb-2">🖼️</span>
                            <span>No banner image uploaded</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Side: Schedule & Details -->
            <div class="lg:col-span-1 space-y-3 flex flex-col justify-between">
                <!-- Schedule & Location -->
                <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm space-y-3">
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2">{{ __('messages.event_schedule_location') }}</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                        <!-- Date & Time -->
                        <div class="flex items-start gap-3 p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <div class="w-8 h-8 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center shrink-0 text-sm">
                                📅
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase">{{ __('messages.date_and_time') }}</p>
                                <p class="font-extrabold text-slate-900 text-xs">{{ date('d M Y (l)', strtotime($event->date)) }}</p>
                                <p class="font-semibold text-slate-600 text-[11px]">{{ date('h:i A', strtotime($event->time)) }}</p>
                            </div>
                        </div>

                        <!-- Venue -->
                        <div class="flex items-start gap-3 p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 text-sm">
                                📍
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase">{{ __('messages.venue_location') }}</p>
                                <p class="font-extrabold text-slate-900 text-xs leading-snug">{{ $event->venue }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description Card -->
                <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm space-y-2 grow">
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-400">{{ __('messages.description') }}</h3>
                    
                    <div class="rich-text text-xs text-slate-700 leading-relaxed font-medium">
                        {!! $event->description ?: __('messages.no_description_provided') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= TAB 2: SUBMISSIONS / REGISTRATIONS ================= -->
    <div x-show="mainTab === 'submissions'" x-transition class="space-y-4">
        <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm space-y-4">
            <!-- Header Bar inside Tab 2 -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-3">
                <div>
                    <h2 class="text-sm font-black text-slate-900 flex items-center gap-2 flex-wrap">
                        @if(($event->event_type ?? 'normal') === 'inam_vitaran')
                            <span>🏆 Inam Vitaran Student Submissions</span>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-amber-50 text-amber-800 border border-amber-200/80">{{ $totalCount }} Submissions</span>
                        @else
                            <span>{{ __('messages.event_registrations') }}</span>
                            <span class="px-2 py-0.5 rounded-full text-xs font-black bg-primary-50 text-primary-700 border border-primary-100">{{ $totalCount }} {{ __('messages.submitted') }}</span>
                        @endif
                    </h2>
                    <p class="text-[11px] text-slate-400 font-medium">
                        {{ ($event->event_type ?? 'normal') === 'inam_vitaran' ? 'Review submitted student award applications for this event.' : __('messages.review_manage_registrations_desc') }}
                    </p>
                </div>

                <!-- Status Filter Tabs (Pending removed: Only All, Approved, Rejected) & Export Button -->
                <div class="flex items-center gap-2 flex-wrap">
                    <div class="flex items-center gap-1.5 p-1 bg-slate-100/80 rounded-xl border border-slate-200/60 shrink-0">
                        <button type="button" @click="statusTab = 'all'" 
                                :class="statusTab === 'all' ? 'bg-white text-slate-900 shadow-xs font-extrabold border border-slate-200/80' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                                class="px-3 py-1.5 text-xs rounded-lg transition-all flex items-center gap-1.5">
                            <span>{{ __('messages.all') }}</span>
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700" :class="statusTab === 'all' ? 'bg-slate-200' : ''">{{ $totalCount }}</span>
                        </button>

                        <button type="button" @click="statusTab = 'approved'" 
                                :class="statusTab === 'approved' ? 'bg-white text-emerald-700 shadow-xs font-extrabold border border-emerald-200/80' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                                class="px-3 py-1.5 text-xs rounded-lg transition-all flex items-center gap-1.5">
                            <span>{{ __('messages.approved') }}</span>
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800">{{ $approvedCount }}</span>
                        </button>

                        <button type="button" @click="statusTab = 'rejected'" 
                                :class="statusTab === 'rejected' ? 'bg-white text-rose-700 shadow-xs font-extrabold border border-rose-200/80' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                                class="px-3 py-1.5 text-xs rounded-lg transition-all flex items-center gap-1.5">
                            <span>{{ __('messages.rejected') }}</span>
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] font-black bg-rose-100 text-rose-800">{{ $rejectedCount }}</span>
                        </button>
                    </div>

                    @if(($event->event_type ?? 'normal') === 'inam_vitaran')
                        <a href="{{ route('admin.events.inam_submissions.export', $event->id) }}" 
                           class="h-8 px-3 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-xs rounded-xl border border-emerald-200/60 shadow-xs transition-colors inline-flex items-center justify-center gap-1.5 whitespace-nowrap">
                            📊 <span>Export Inam Excel</span>
                        </a>
                    @elseif(($event->event_type ?? 'normal') === 'yuva_melo')
                        <a href="{{ route('admin.events.yuva_submissions.export', $event->id) }}" 
                           class="h-8 px-3 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-xs rounded-xl border border-emerald-200/60 shadow-xs transition-colors inline-flex items-center justify-center gap-1.5 whitespace-nowrap">
                            📊 <span>Export Candidates Excel</span>
                        </a>
                    @else
                        <a href="{{ route('admin.events.registrations.export', $event->id) }}" 
                           class="h-8 px-3 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-xs rounded-xl border border-emerald-200/60 shadow-xs transition-colors inline-flex items-center justify-center gap-1.5 whitespace-nowrap">
                            📊 <span>{{ __('messages.export_excel') }}</span>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Cards Grid (Customized for Inam / Yuva Melo / General) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
                @forelse($registrations as $index => $reg)
                    @php
                        $fd = $reg->form_data ?? [];
                        $memberCode = $reg->user ? sprintf('#%05d', $reg->user->id) : (is_scalar($fd['member_id'] ?? null) ? (string)$fd['member_id'] : '-');
                        $isStudent = !empty($fd['student_name']);
                        $isYuvaCandidate = ($event->event_type ?? 'normal') === 'yuva_melo' || !empty($fd['surname']);
                        
                        if ($isStudent) {
                            $rawName = $fd['student_name'];
                        } elseif ($isYuvaCandidate) {
                            $rawName = ((!empty($fd['first_name']) ? $fd['first_name'] : '') . ' ' . (!empty($fd['surname']) ? $fd['surname'] : '')) ?: ($fd['full_name'] ?? ($reg->user ? $reg->user->name : 'Candidate'));
                        } else {
                            $rawName = $reg->user ? $reg->user->name : ($fd['full_name'] ?? $fd['first_name'] ?? 'Participant');
                        }
                        $userName = is_scalar($rawName) ? (string)$rawName : 'Participant';
                        
                        $rawParent = !empty($fd['father_name']) ? $fd['father_name'] : ($fd['parent_name'] ?? ($reg->user ? $reg->user->name : '-'));
                        $parentName = is_scalar($rawParent) ? (string)$rawParent : '-';

                        $rawPhone = $fd['mobile_no'] ?? $fd['mobile'] ?? $fd['contact_number'] ?? ($reg->user ? ($reg->user->memberProfile->phone ?? null) : null);
                        $userPhone = is_scalar($rawPhone) ? (string)$rawPhone : null;

                        $rawCity = $fd['native_place'] ?? $fd['city'] ?? $fd['area'] ?? $fd['district'] ?? ($reg->user ? ($reg->user->memberProfile->city ?? null) : null);
                        if (is_array($rawCity)) {
                            $userCity = implode(', ', array_filter($rawCity, 'is_scalar'));
                        } elseif (is_object($rawCity)) {
                            $userCity = $rawCity->name ?? (string)$rawCity;
                        } else {
                            $userCity = is_scalar($rawCity) ? (string)$rawCity : null;
                        }

                        $regNo = is_scalar($fd['registration_no'] ?? null) ? $fd['registration_no'] : ($index + 1);

                        $modalData = [
                            'member_code' => $memberCode,
                            'user_name' => $userName,
                            'email' => $reg->user ? $reg->user->email : ($fd['email'] ?? '-'),
                            'phone' => $userPhone ?? '-',
                            'city' => $userCity ?? '-',
                            'person_count' => (int)($fd['person_count'] ?? 1),
                            'is_selected' => (bool)$reg->is_selected,
                            'payment_status' => $reg->payment_status ?? 'paid',
                            'payment_amount' => $reg->payment_amount ?? 0,
                            'payment_id' => $reg->payment_id ?? '-',
                            'date' => $reg->created_at ? $reg->created_at->format('d-M-Y h:i A') : '',
                            'form_data' => $fd,
                            'index' => $regNo,
                        ];
                    @endphp
                    <div x-show="statusTab === 'all' || statusTab === '{{ $reg->status }}'" 
                         class="bg-white border border-slate-200/80 rounded-xl p-3 shadow-2xs hover:shadow-sm hover:border-primary-400 transition-all flex flex-col justify-between gap-2.5">
                        
                        <div class="space-y-2">
                            <!-- Card Header: Reg # + Member Code + Status -->
                            <div class="flex items-center justify-between gap-1.5 border-b border-slate-100 pb-1.5">
                                <div class="flex items-center gap-1 min-w-0">
                                    <span class="px-1.5 py-0.2 rounded bg-slate-100 text-[9px] font-black text-slate-700">#{{ $regNo }}</span>
                                    <span class="px-1.5 py-0.2 rounded bg-slate-900 text-white text-[9px] font-black tracking-wider truncate max-w-[90px]">🆔 {{ $memberCode }}</span>
                                </div>

                                <div class="shrink-0">
                                    @if($reg->status === 'approved')
                                        <span class="px-1.5 py-0.2 rounded text-[8px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase">
                                            Approved
                                        </span>
                                    @elseif($reg->status === 'rejected')
                                        <span class="px-1.5 py-0.2 rounded text-[8px] font-black bg-rose-50 text-rose-700 border border-rose-200 uppercase">
                                            Rejected
                                        </span>
                                    @else
                                        <span class="px-1.5 py-0.2 rounded text-[8px] font-black bg-amber-50 text-amber-700 border border-amber-200 uppercase">
                                            Pending
                                        </span>
                                    @endif
                                </div>
                            </div>

                            @if($isYuvaCandidate)
                                <!-- Yuva Melo Candidate Card Layout -->
                                <div>
                                    <span class="text-[8px] font-extrabold text-purple-600 uppercase tracking-wider block">
                                        ⚡ Yuva Candidate
                                    </span>
                                    <h4 class="text-xs font-black text-rose-700 truncate" title="{{ $userName }}">
                                        {{ $userName }}
                                    </h4>
                                    @if($parentName !== '-')
                                        <p class="text-[9px] text-slate-600 font-semibold truncate mt-0.5">👨‍👦 {{ $parentName }}</p>
                                    @endif
                                </div>

                                <!-- Phone & Native Place Row -->
                                <div class="flex items-center justify-between text-[9px] bg-slate-50 rounded-lg p-1.5 border border-slate-100">
                                    <span class="font-bold text-blue-700">📞 {{ $userPhone ?: '-' }}</span>
                                    @if($userCity)
                                        <span class="text-slate-600 font-bold truncate max-w-[100px]">📍 {{ $userCity }}</span>
                                    @endif
                                </div>

                                <!-- Candidate Quick Stats -->
                                <div class="space-y-1 text-[9px]">
                                    @if(!empty($fd['qualification']))
                                        <div class="bg-slate-50/80 px-2 py-0.5 rounded border border-slate-100/80 flex items-center justify-between">
                                            <span class="text-slate-500 font-medium">Edu:</span>
                                            <span class="font-bold text-slate-800 truncate max-w-[140px]">{{ $fd['qualification'] }}</span>
                                        </div>
                                    @endif

                                    @if(!empty($fd['occupation']))
                                        <div class="bg-slate-50/80 px-2 py-0.5 rounded border border-slate-100/80 flex items-center justify-between">
                                            <span class="text-slate-500 font-medium">Job:</span>
                                            <span class="font-bold text-slate-800 truncate max-w-[140px]">{{ $fd['occupation'] }}</span>
                                        </div>
                                    @endif

                                    @if(!empty($fd['birth_date']) || !empty($fd['age']))
                                        <div class="bg-purple-50/80 px-2 py-0.5 rounded border border-purple-200/60 flex items-center justify-between">
                                            <span class="text-purple-800 font-bold">Age / DOB:</span>
                                            <span class="font-black text-purple-900 text-[10px]">
                                                {{ !empty($fd['age']) ? $fd['age'] . ' Yrs' : '' }} {{ !empty($fd['birth_date']) ? '(' . $fd['birth_date'] . ')' : '' }}
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <!-- View Candidate Biodata Button -->
                                <div>
                                    <button type="button" 
                                            @click.stop="openBiodata({{ json_encode($modalData, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP) }})"
                                            class="w-full py-1.5 bg-purple-50 hover:bg-purple-100 active:scale-95 text-purple-800 rounded-lg text-[10px] font-black border border-purple-200 transition-all shadow-2xs cursor-pointer flex items-center justify-center gap-1.5">
                                        <span>👁️ View Candidate Biodata</span>
                                    </button>
                                </div>
                            @else
                                <!-- Student / General Participant Layout -->
                                <div>
                                    <span class="text-[8px] font-extrabold text-slate-400 uppercase tracking-wider block">
                                        {{ $isStudent ? '🎓 Student' : 'Participant' }}
                                    </span>
                                    <h4 class="text-xs font-black text-slate-900 truncate" title="{{ $userName }}">
                                        {{ $userName }}
                                    </h4>
                                    @if($isStudent && $parentName !== '-')
                                        <p class="text-[9px] text-slate-500 font-semibold truncate mt-0.5">👨‍👦 {{ $parentName }}</p>
                                    @endif
                                </div>

                                <!-- Phone & Location Row -->
                                <div class="flex items-center justify-between text-[9px] bg-slate-50 rounded-lg p-1.5 border border-slate-100">
                                    <span class="font-bold text-slate-800">📞 {{ $userPhone ?: '-' }}</span>
                                    @if($userCity)
                                        <span class="text-slate-500 font-semibold truncate max-w-[90px]">📍 {{ $userCity }}</span>
                                    @endif
                                </div>

                                <!-- Education / Percentage / Marks Details -->
                                <div class="space-y-1 text-[9px]">
                                    @if(!empty($fd['education']))
                                        <div class="bg-slate-50/80 px-2 py-1 rounded border border-slate-100/80 flex items-center justify-between">
                                            <span class="text-slate-500 font-medium">Edu:</span>
                                            <span class="font-bold text-slate-800 truncate max-w-[140px]">{{ $fd['education'] }}</span>
                                        </div>
                                    @endif

                                    @if(!empty($fd['school_college']))
                                        <div class="bg-slate-50/80 px-2 py-1 rounded border border-slate-100/80 flex items-center justify-between">
                                            <span class="text-slate-500 font-medium">School:</span>
                                            <span class="font-bold text-slate-800 truncate max-w-[140px]" title="{{ $fd['school_college'] }}">{{ $fd['school_college'] }}</span>
                                        </div>
                                    @endif

                                    @if(!empty($fd['percentage']))
                                        <div class="bg-amber-50/80 px-2 py-1 rounded border border-amber-200/80 flex items-center justify-between">
                                            <span class="text-amber-800 font-bold">Percentage:</span>
                                            <span class="font-black text-amber-700 text-[10px]">
                                                {{ str_contains($fd['percentage'], '%') ? $fd['percentage'] : $fd['percentage'] . '%' }}
                                            </span>
                                        </div>
                                    @elseif(!empty($fd['received_marks']) && !empty($fd['total_marks']))
                                        <div class="bg-amber-50/80 px-2 py-1 rounded border border-amber-200/80 flex items-center justify-between">
                                            <span class="text-amber-800 font-bold">Marks:</span>
                                            <span class="font-black text-amber-700 text-[10px]">
                                                {{ $fd['received_marks'] }} / {{ $fd['total_marks'] }}
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Marksheet Link (Compact) -->
                                @if(!empty($fd['marksheet_url']))
                                    <div>
                                        <a href="{{ str_starts_with($fd['marksheet_url'], 'http') ? $fd['marksheet_url'] : asset('storage/' . $fd['marksheet_url']) }}" target="_blank" 
                                           class="inline-flex items-center justify-center gap-1 w-full py-1 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg text-[9px] font-extrabold border border-blue-200/80 transition-colors shadow-2xs">
                                            <span>📄 View Marksheet ↗</span>
                                        </a>
                                    </div>
                                @endif
                            @endif
                        </div>

                        <!-- Actions (Approve/Reject) -->
                        <div class="pt-1.5 border-t border-slate-100 flex items-center justify-end gap-1">
                            @if($reg->status !== 'approved')
                                <form method="POST" action="{{ route('admin.events.registrations.approve', $reg->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" title="{{ __('messages.approve') }}" 
                                            class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 hover:bg-emerald-100 text-[8px] font-extrabold border border-emerald-200 transition-colors inline-flex items-center gap-0.5">
                                        ✓ Approve
                                    </button>
                                </form>
                            @endif
                            @if($reg->status !== 'rejected')
                                <form method="POST" action="{{ route('admin.events.registrations.reject', $reg->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" title="{{ __('messages.reject') }}" 
                                            class="px-2 py-0.5 rounded bg-rose-50 text-rose-700 hover:bg-rose-100 text-[8px] font-extrabold border border-rose-200 transition-colors inline-flex items-center gap-0.5">
                                        ✕ Reject
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-10 text-center text-slate-400 font-medium bg-slate-50 rounded-xl border border-slate-100">
                        {{ __('messages.no_registrations_found') }}
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- ================= TAB 3: GALLERY ================= -->
    <div x-show="mainTab === 'gallery'" x-transition class="space-y-4">
        <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm space-y-4">
            <!-- Gallery Header inside Tab 3 -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-3">
                <div>
                    <h2 class="text-sm font-black text-slate-900 flex items-center gap-2">
                        <span>📸 Event Photo Gallery</span>
                        <span class="px-2 py-0.5 rounded-full text-xs font-black bg-primary-50 text-primary-700 border border-primary-100">{{ $galleryCount }} Photos</span>
                    </h2>
                    <p class="text-[11px] text-slate-400 font-medium">Manage and upload event memories and celebration photos.</p>
                </div>

                <button type="button" @click="showUploadModal = true"
                        class="px-3.5 py-1.5 bg-primary-500 hover:bg-primary-600 text-white font-extrabold text-xs rounded-xl shadow-xs transition-colors flex items-center gap-1.5 self-start sm:self-auto">
                    <span>+ Add Photos</span>
                </button>
            </div>

            <!-- Gallery Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                @forelse($gallery as $photo)
                    <div class="bg-white border border-slate-100 rounded-xl overflow-hidden shadow-2xs flex flex-col justify-between group hover:border-slate-300 transition-all">
                        <div class="aspect-video w-full overflow-hidden bg-slate-50 relative">
                            <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" 
                                 src="{{ str_starts_with($photo->image_path, 'http') ? $photo->image_path : asset('storage/' . $photo->image_path) }}" 
                                 alt="Gallery Image">
                        </div>
                        <div class="p-2 border-t border-slate-100 bg-slate-50/50">
                            <button type="button" 
                                    @click="$dispatch('confirm-delete', { action: '{{ route('admin.events.gallery.destroy', $photo->id) }}', message: 'Are you sure you want to delete this photo from the event gallery?' })" 
                                    class="w-full py-1 bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold text-[9px] rounded-lg transition-colors">
                                Delete Photo
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                        <span class="text-3xl block mb-2">📸</span>
                        <p class="text-xs font-bold text-slate-600">No gallery photos uploaded yet.</p>
                        <p class="text-[11px] text-slate-400 mt-1">Upload event photos to showcase them to community members.</p>
                        <button type="button" @click="showUploadModal = true"
                                class="mt-3 px-3 py-1.5 bg-primary-500 hover:bg-primary-600 text-white font-bold text-xs rounded-xl shadow-xs transition-colors inline-flex items-center gap-1">
                            <span>+ Upload Photos</span>
                        </button>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Upload Gallery Photos Modal -->
    <div x-show="showUploadModal" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/40 backdrop-blur-sm"
         x-transition
         x-cloak>
        <div class="bg-white rounded-xl max-w-md w-full p-4 border border-slate-100 shadow-xl space-y-3.5 max-h-[90vh] overflow-y-auto"
             @click.away="showUploadModal = false">
            
            <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                <h3 class="text-xs font-extrabold text-slate-950">Add Photos to Event Gallery</h3>
                <button type="button" @click="showUploadModal = false" class="text-slate-400 hover:text-slate-600 text-sm font-black">
                    &times;
                </button>
            </div>
            
            <form method="POST" action="{{ route('admin.events.gallery.upload', $event->id) }}" enctype="multipart/form-data" class="space-y-3.5">
                @csrf
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-600 uppercase tracking-wider block">Select Images or .ZIP File (Max 50MB)</label>
                    <input type="file" name="images[]" multiple required accept=".jpg,.jpeg,.png,.webp,.gif,.zip"
                           @change="$el.name = ($el.files.length === 1 && $el.files[0].name.toLowerCase().endsWith('.zip')) ? 'image' : 'images[]'"
                           class="text-[11px] block w-full text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-semibold file:bg-primary-50 file:text-primary-700 border border-slate-200 rounded-lg p-1 bg-slate-50">
                    <p class="text-[10px] text-slate-400 font-medium">💡 You can upload multiple image files (.jpg, .png, .webp) or a single .ZIP file containing event photos.</p>
                </div>

                <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
                    <button type="button" @click="showUploadModal = false" class="px-3.5 py-1.5 border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs rounded-xl transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-1.5 bg-primary-500 hover:bg-primary-600 text-white font-bold text-xs rounded-xl shadow-xs transition-colors">
                        Upload to Gallery
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- REGISTRATION DETAILS POPUP MODAL (BILINGUAL BIODATA BOOKLET FORMAT) -->
    <template x-teleport="body">
        <div x-show="showDetailsModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-2 sm:p-4 bg-slate-900/75 backdrop-blur-sm"
            x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak>
            <div @click.away="showDetailsModal = false"
                class="bg-white rounded-2xl border border-slate-300 shadow-2xl max-w-2xl w-full max-h-[92vh] flex flex-col overflow-hidden relative">

                <!-- Modal Top Header Bar -->
                <div class="px-4 py-2.5 bg-slate-900 text-white flex items-center justify-between shrink-0">
                    <div class="flex items-center gap-2">
                        <div>
                            <h3 class="text-xs font-extrabold flex items-center gap-2">
                                <span x-text="(('{{ $event->event_type ?? 'normal' }}' === 'yuva_melo' || selectedRegistration.form_data?.surname) ? (previewLang === 'en' ? 'Candidate Biodata Preview' : 'ઉમેદવાર બાયોડેટા પ્રીવ્યૂ (Candidate Biodata)') : 'Submitted Registration Details')"></span>
                                <span class="px-2 py-0.5 rounded bg-primary-500 text-white text-[10px]"
                                    x-text="'#' + selectedRegistration.index"></span>
                            </h3>
                            <p class="text-[10px] text-slate-400 font-medium"
                                x-text="(previewLang === 'en' ? 'Submitted on: ' : 'સબમિટ તારીખ: ') + (selectedRegistration.date || '')"></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <!-- Language Toggle Switch (GU / EN) -->
                        <div class="inline-flex rounded-lg border border-slate-700 p-0.5 bg-slate-800 text-[11px] font-bold">
                            <button type="button" @click="previewLang = 'gu'" 
                                :class="previewLang === 'gu' ? 'bg-primary-600 text-white shadow-xs' : 'text-slate-300 hover:text-white'"
                                class="px-2 py-0.5 rounded-md transition-colors cursor-pointer">
                                ગુજરાતી
                            </button>
                            <button type="button" @click="previewLang = 'en'" 
                                :class="previewLang === 'en' ? 'bg-primary-600 text-white shadow-xs' : 'text-slate-300 hover:text-white'"
                                class="px-2 py-0.5 rounded-md transition-colors cursor-pointer">
                                English
                            </button>
                        </div>

                        <button type="button" @click="window.print()"
                            class="px-2.5 py-1 bg-white/10 hover:bg-white/20 text-white rounded-lg text-xs font-bold transition-colors flex items-center gap-1 cursor-pointer">
                            <span>🖨️ <span x-text="previewLang === 'en' ? 'Print' : 'પ્રિન્ટ'"></span></span>
                        </button>
                        <button type="button" @click="showDetailsModal = false"
                            class="w-6 h-6 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors text-xs cursor-pointer">
                            ✕
                        </button>
                    </div>
                </div>

                <!-- Modal Body (Compact Scrollable with Traditional Gujarati Biodata Layout) -->
                <div class="p-3 sm:p-4 overflow-y-auto text-xs bg-slate-100/70">

                    <!-- YUVA MELO TRADITIONAL BIODATA BOOKLET VIEW -->
                    <template x-if="'{{ $event->event_type ?? 'normal' }}' === 'yuva_melo' || selectedRegistration.form_data?.surname">
                        <div id="printableBiodata" class="bg-white p-3 border-2 border-slate-900 shadow-sm font-sans text-slate-900 space-y-2.5 max-w-[620px] mx-auto print:border-none print:p-0 print:shadow-none print:max-w-full">
                            
                            <!-- Top Box: Candidate Photo + Personal Header Summary -->
                            <div class="border-2 border-slate-900 p-2.5 bg-white">
                                <div class="flex gap-3 items-start">
                                    <!-- Left Photo Box (Fixed Passport Size) -->
                                    <div class="w-[105px] h-[135px] shrink-0 border border-slate-900 rounded-xs bg-slate-50 overflow-hidden relative flex items-center justify-center shadow-2xs">
                                        <template x-if="getPhotoUrl(selectedRegistration.form_data)">
                                            <img :src="getPhotoUrl(selectedRegistration.form_data)" class="w-full h-full object-cover">
                                        </template>
                                        <template x-if="!getPhotoUrl(selectedRegistration.form_data)">
                                            <div class="text-center p-1 text-slate-400">
                                                <span class="text-3xl block">👤</span>
                                                <span class="text-[9px] font-bold" x-text="previewLang === 'en' ? 'No Photo' : 'ફોટો નથી'"></span>
                                            </div>
                                        </template>
                                    </div>

                                    <!-- Right Personal & Contact Info -->
                                    <div class="flex-1 min-w-0 space-y-1 text-xs">
                                        <!-- Candidate Name (Red Bold) -->
                                        <h2 class="text-sm sm:text-base font-black text-rose-600 leading-tight"
                                            x-text="((selectedRegistration.form_data?.first_name || '') + ' ' + (selectedRegistration.form_data?.surname || '')).trim() || selectedRegistration.form_data?.full_name || '-'">
                                        </h2>

                                        <!-- Father / Grandfather Full Name -->
                                        <div class="font-bold text-slate-800 text-[11px] leading-tight">
                                            <span x-text="((selectedRegistration.form_data?.father_name || '') + ' ' + (selectedRegistration.form_data?.grandfather_name || '') + ' ' + (selectedRegistration.form_data?.surname || '')).trim() || '-'"></span>
                                        </div>

                                        <!-- Full Address -->
                                        <div class="text-[10.5px] text-slate-700 leading-tight">
                                            <span x-text="[selectedRegistration.form_data?.address, selectedRegistration.form_data?.district, selectedRegistration.form_data?.state].filter(Boolean).join(', ') || '-'"></span>
                                        </div>

                                        <!-- Mobile Numbers -->
                                        <div class="text-[10.5px] font-bold flex flex-wrap gap-x-3 gap-y-0.5 pt-0.5">
                                            <div>
                                                <span class="text-slate-800" x-text="previewLang === 'en' ? 'Cand. Mob.:' : 'ઉ. મો.:'"></span>
                                                <span class="text-blue-700 font-bold ml-0.5" x-text="selectedRegistration.form_data?.mobile_no || '-'"></span>
                                            </div>
                                            <div>
                                                <span class="text-slate-800" x-text="previewLang === 'en' ? 'Guard. Mob.:' : 'વા. મો.:'"></span>
                                                <span class="text-blue-700 font-bold ml-0.5" x-text="selectedRegistration.form_data?.father_mobile || selectedRegistration.form_data?.whatsapp || '-'"></span>
                                            </div>
                                        </div>

                                        <!-- Mini Stats Table (DOB, Age, Height, Weight) -->
                                        <table class="w-full border-collapse border border-slate-900 text-[10px] text-center mt-1 table-fixed">
                                            <colgroup>
                                                <col style="width: 25%;">
                                                <col style="width: 25%;">
                                                <col style="width: 25%;">
                                                <col style="width: 25%;">
                                            </colgroup>
                                            <tbody>
                                                <tr class="border-b border-slate-900">
                                                    <td class="border-r border-slate-900 font-bold py-0.5 px-1 bg-slate-50 text-slate-800" x-text="previewLang === 'en' ? 'Birth Date' : 'જન્મ તારીખ'"></td>
                                                    <td class="border-r border-slate-900 font-bold py-0.5 px-1 text-blue-700 truncate" x-text="selectedRegistration.form_data?.birth_date || '-'"></td>
                                                    <td class="border-r border-slate-900 font-bold py-0.5 px-1 bg-slate-50 text-slate-800" x-text="previewLang === 'en' ? 'Age' : 'ઉંમર વર્ષ'"></td>
                                                    <td class="font-bold py-0.5 px-1 text-blue-700 truncate" x-text="(selectedRegistration.form_data?.age ? selectedRegistration.form_data?.age + (previewLang === 'en' ? ' Yrs' : ' વર્ષ') : '-')"></td>
                                                </tr>
                                                <tr>
                                                    <td class="border-r border-slate-900 font-bold py-0.5 px-1 bg-slate-50 text-slate-800" x-text="previewLang === 'en' ? 'Height' : 'ઊંચાઈ'"></td>
                                                    <td class="border-r border-slate-900 font-bold py-0.5 px-1 text-blue-700 truncate" x-text="selectedRegistration.form_data?.height || '-'"></td>
                                                    <td class="border-r border-slate-900 font-bold py-0.5 px-1 bg-slate-50 text-slate-800" x-text="previewLang === 'en' ? 'Weight' : 'વજન'"></td>
                                                    <td class="font-bold py-0.5 px-1 text-blue-700 truncate" x-text="selectedRegistration.form_data?.weight || '-'"></td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <!-- Native Place -->
                                        <div class="text-[10.5px] font-bold pt-0.5">
                                            <span class="text-slate-800" x-text="previewLang === 'en' ? 'Native Place, District: ' : 'મૂળ વતન, ગામ, જિલ્લો: '"></span>
                                            <span class="text-blue-700" x-text="selectedRegistration.form_data?.native_place ? selectedRegistration.form_data?.native_place + (selectedRegistration.form_data?.district ? ', ' + selectedRegistration.form_data?.district : '') : (selectedRegistration.form_data?.district || '-')"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Main Structured Table (Labels Left, Blue Values Right) -->
                            <table class="w-full border-collapse border-2 border-slate-900 text-[10.5px] sm:text-[11px] text-left bg-white table-fixed">
                                <colgroup>
                                    <col style="width: 34%;">
                                    <col style="width: 16%;">
                                    <col style="width: 34%;">
                                    <col style="width: 16%;">
                                </colgroup>
                                <tbody>
                                    <!-- Row 1: Qualification -->
                                    <tr class="border-b border-slate-900">
                                        <td class="border-r border-slate-900 py-1 px-2 font-bold text-slate-800 bg-slate-50/80" x-text="previewLang === 'en' ? 'Candidate Qualification' : 'ઉમેદવારની શૈક્ષણિક લાયકાત'"></td>
                                        <td colspan="3" class="py-1 px-2 font-bold text-blue-700 break-words" x-text="selectedRegistration.form_data?.qualification || '-'"></td>
                                    </tr>

                                    <!-- Row 2: Occupation -->
                                    <tr class="border-b border-slate-900">
                                        <td class="border-r border-slate-900 py-1 px-2 font-bold text-slate-800 bg-slate-50/80" x-text="previewLang === 'en' ? 'Candidate Occupation' : 'ઉમેદવારનો વ્યવસાય'"></td>
                                        <td colspan="3" class="py-1 px-2 font-bold text-blue-700 break-words" x-text="selectedRegistration.form_data?.occupation || '-'"></td>
                                    </tr>

                                    <!-- Row 3: Occupation Address -->
                                    <tr class="border-b border-slate-900">
                                        <td class="border-r border-slate-900 py-1 px-2 font-bold text-slate-800 bg-slate-50/80" x-text="previewLang === 'en' ? 'Occupation Address' : 'ઉમેદવારના વ્યવસાય નું સરનામું'"></td>
                                        <td colspan="3" class="py-1 px-2 font-bold text-blue-700 break-words" x-text="selectedRegistration.form_data?.occupation_address || '-'"></td>
                                    </tr>

                                    <!-- Row 4: Monthly Income -->
                                    <tr class="border-b border-slate-900">
                                        <td class="border-r border-slate-900 py-1 px-2 font-bold text-slate-800 bg-slate-50/80" x-text="previewLang === 'en' ? 'Monthly Income' : 'ઉમેદવારની માસિક આવક'"></td>
                                        <td colspan="3" class="py-1 px-2 font-bold text-blue-700 break-words" x-text="selectedRegistration.form_data?.monthly_income ? '₹ ' + selectedRegistration.form_data?.monthly_income : '-'"></td>
                                    </tr>

                                    <!-- Row 5: Sibling - Elder Brothers -->
                                    <tr class="border-b border-slate-900">
                                        <td class="border-r border-slate-900 py-1 px-2 font-bold text-slate-800 bg-slate-50/80" x-text="previewLang === 'en' ? 'Elder Brothers Count' : 'ઉમેદવારના મોટાભાઈની સંખ્યા'"></td>
                                        <td class="border-r border-slate-900 py-1 px-1.5 font-bold text-blue-700 text-center break-words" x-text="getSiblingStat(selectedRegistration.form_data, 'Elder Brother', false)"></td>
                                        <td class="border-r border-slate-900 py-1 px-2 font-bold text-slate-800 bg-slate-50/80 text-[10px]" x-text="previewLang === 'en' ? 'Married Elder Brothers' : 'પરણેલા મોટાભાઈની સંખ્યા'"></td>
                                        <td class="py-1 px-1.5 font-bold text-blue-700 text-center break-words" x-text="getSiblingStat(selectedRegistration.form_data, 'Elder Brother', true)"></td>
                                    </tr>

                                    <!-- Row 6: Sibling - Younger Brothers -->
                                    <tr class="border-b border-slate-900">
                                        <td class="border-r border-slate-900 py-1 px-2 font-bold text-slate-800 bg-slate-50/80" x-text="previewLang === 'en' ? 'Younger Brothers Count' : 'ઉમેદવારના નાનાભાઈની સંખ્યા'"></td>
                                        <td class="border-r border-slate-900 py-1 px-1.5 font-bold text-blue-700 text-center break-words" x-text="getSiblingStat(selectedRegistration.form_data, 'Younger Brother', false)"></td>
                                        <td class="border-r border-slate-900 py-1 px-2 font-bold text-slate-800 bg-slate-50/80 text-[10px]" x-text="previewLang === 'en' ? 'Married Younger Brothers' : 'પરણેલા નાનાભાઈની સંખ્યા'"></td>
                                        <td class="py-1 px-1.5 font-bold text-blue-700 text-center break-words" x-text="getSiblingStat(selectedRegistration.form_data, 'Younger Brother', true)"></td>
                                    </tr>

                                    <!-- Row 7: Sibling - Elder Sisters -->
                                    <tr class="border-b border-slate-900">
                                        <td class="border-r border-slate-900 py-1 px-2 font-bold text-slate-800 bg-slate-50/80" x-text="previewLang === 'en' ? 'Elder Sisters Count' : 'ઉમેદવારના મોટા બહેનો ની સંખ્યા'"></td>
                                        <td class="border-r border-slate-900 py-1 px-1.5 font-bold text-blue-700 text-center break-words" x-text="getSiblingStat(selectedRegistration.form_data, 'Elder Sister', false)"></td>
                                        <td class="border-r border-slate-900 py-1 px-2 font-bold text-slate-800 bg-slate-50/80 text-[10px]" x-text="previewLang === 'en' ? 'Married Elder Sisters' : 'પરણેલા મોટા બહેનો ની સંખ્યા'"></td>
                                        <td class="py-1 px-1.5 font-bold text-blue-700 text-center break-words" x-text="getSiblingStat(selectedRegistration.form_data, 'Elder Sister', true)"></td>
                                    </tr>

                                    <!-- Row 8: Sibling - Younger Sisters -->
                                    <tr class="border-b border-slate-900">
                                        <td class="border-r border-slate-900 py-1 px-2 font-bold text-slate-800 bg-slate-50/80" x-text="previewLang === 'en' ? 'Younger Sisters Count' : 'ઉમેદવારના નાના બહેનો ની સંખ્યા'"></td>
                                        <td class="border-r border-slate-900 py-1 px-1.5 font-bold text-blue-700 text-center break-words" x-text="getSiblingStat(selectedRegistration.form_data, 'Younger Sister', false)"></td>
                                        <td class="border-r border-slate-900 py-1 px-2 font-bold text-slate-800 bg-slate-50/80 text-[10px]" x-text="previewLang === 'en' ? 'Married Younger Sisters' : 'પરણેલા નાના બહેનો ની સંખ્યા'"></td>
                                        <td class="py-1 px-1.5 font-bold text-blue-700 text-center break-words" x-text="getSiblingStat(selectedRegistration.form_data, 'Younger Sister', true)"></td>
                                    </tr>

                                    <!-- Row 9: Father's Occupation -->
                                    <tr class="border-b border-slate-900">
                                        <td class="border-r border-slate-900 py-1 px-2 font-bold text-slate-800 bg-slate-50/80" x-text="previewLang === 'en' ? 'Father Occupation' : 'ઉમેદવારના પિતાનો વ્યવસાય'"></td>
                                        <td colspan="3" class="py-1 px-2 font-bold text-blue-700 break-words" x-text="selectedRegistration.form_data?.father_occupation || '-'"></td>
                                    </tr>

                                    <!-- Row 10: Father's Occupation Address -->
                                    <tr class="border-b border-slate-900">
                                        <td class="border-r border-slate-900 py-1 px-2 font-bold text-slate-800 bg-slate-50/80" x-text="previewLang === 'en' ? 'Father Occupation Address' : 'ઉમેદવારના પિતાના વ્યવસાયનું સરનામું'"></td>
                                        <td colspan="3" class="py-1 px-2 font-bold text-blue-700 break-words" x-text="selectedRegistration.form_data?.father_occupation_address || '-'"></td>
                                    </tr>

                                    <!-- Row 11: Mother's Name -->
                                    <tr class="border-b border-slate-900">
                                        <td class="border-r border-slate-900 py-1 px-2 font-bold text-slate-800 bg-slate-50/80" x-text="previewLang === 'en' ? 'Mother Name' : 'ઉમેદવારના માતાનું નામ'"></td>
                                        <td colspan="3" class="py-1 px-2 font-bold text-blue-700 break-words" x-text="selectedRegistration.form_data?.mother_name || '-'"></td>
                                    </tr>

                                    <!-- Row 12: Maternal Grandfather Address -->
                                    <tr class="border-b border-slate-900">
                                        <td class="border-r border-slate-900 py-1 px-2 font-bold text-slate-800 bg-slate-50/80" x-text="previewLang === 'en' ? 'Maternal Address' : 'ઉમેદવારના મોસાળ નું સરનામું'"></td>
                                        <td colspan="3" class="py-1 px-2 font-bold text-blue-700 break-words" x-text="selectedRegistration.form_data?.maternal_grandfather_address || '-'"></td>
                                    </tr>

                                    <!-- Row 13: Maternal Elder Name -->
                                    <tr class="border-b border-slate-900">
                                        <td class="border-r border-slate-900 py-1 px-2 font-bold text-slate-800 bg-slate-50/80" x-text="previewLang === 'en' ? 'Maternal Uncle / Grandfather' : 'મોસાળ ના વડીલનું નામ'"></td>
                                        <td colspan="3" class="py-1 px-2 font-bold text-blue-700 break-words" x-text="[selectedRegistration.form_data?.maternal_uncle_name, selectedRegistration.form_data?.maternal_grandfather_name].filter(Boolean).join(' / ') || '-'"></td>
                                    </tr>

                                    <!-- Row 14: Maternal Elder Occupation -->
                                    <tr class="border-b border-slate-900">
                                        <td class="border-r border-slate-900 py-1 px-2 font-bold text-slate-800 bg-slate-50/80" x-text="previewLang === 'en' ? 'Maternal Uncle / Grandfather Occupation' : 'મોસાળ ના વડીલ નો વ્યવસાય'"></td>
                                        <td colspan="3" class="py-1 px-2 font-bold text-blue-700 break-words" x-text="selectedRegistration.form_data?.maternal_grandfather_occupation || '-'"></td>
                                    </tr>

                                    <!-- Row 15: Physical Disability -->
                                    <tr class="border-b border-slate-900">
                                        <td class="border-r border-slate-900 py-1 px-2 font-bold text-slate-800 bg-slate-50/80" x-text="previewLang === 'en' ? 'Physical Disability' : 'ઉમેદવારની શારીરિક ખોડ-ખાંપણ'"></td>
                                        <td class="border-r border-slate-900 py-1 px-1.5 font-bold text-blue-700 text-center break-words" x-text="selectedRegistration.form_data?.physical_disability || (previewLang === 'en' ? 'None' : 'નથી')"></td>
                                        <td class="border-r border-slate-900 py-1 px-2 font-bold text-slate-800 bg-slate-50/80 text-[10px]" x-text="previewLang === 'en' ? 'Duration' : 'કેટલા સમયથી'"></td>
                                        <td class="py-1 px-1.5 font-bold text-blue-700 text-center break-words" x-text="selectedRegistration.form_data?.disability_duration || (previewLang === 'en' ? 'None' : 'નથી')"></td>
                                    </tr>

                                    <!-- Row 16: Divorce / Second Marriage -->
                                    <tr class="border-b border-slate-900">
                                        <td class="border-r border-slate-900 py-1 px-2 font-bold text-slate-800 bg-slate-50/80" x-text="previewLang === 'en' ? 'Divorce / Other Details' : 'છૂટા-છેડા, બીજા લગ્ન અન્ય માહિતી'"></td>
                                        <td colspan="3" class="py-1 px-2 font-bold text-blue-700 break-words" x-text="(selectedRegistration.form_data?.divorce === 'Yes' ? (previewLang === 'en' ? 'Yes (Divorced)' : 'હા (Yes)') : (previewLang === 'en' ? 'None' : 'નથી')) + (selectedRegistration.form_data?.other_info ? ' - ' + selectedRegistration.form_data?.other_info : '')"></td>
                                    </tr>

                                    <!-- Row 17: Special Info -->
                                    <tr>
                                        <td class="border-r border-slate-900 py-1 px-2 font-bold text-slate-800 bg-slate-50/80" x-text="previewLang === 'en' ? 'Special Information' : 'વિશેષ માહિતી'"></td>
                                        <td colspan="3" class="py-1 px-2 font-bold text-blue-700 break-words" x-text="selectedRegistration.form_data?.special_info || '-'"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </template>

                    <!-- STANDARD / INAM VITARAN EVENT DETAILS VIEW -->
                    <template x-if="'{{ $event->event_type ?? 'normal' }}' !== 'yuva_melo' && !selectedRegistration.form_data?.surname">
                        <div class="space-y-2.5">
                            <!-- Uploaded Documents & Photos Compact Strip -->
                            <template x-if="Object.keys(selectedRegistration.form_data || {}).some(k => k.endsWith('_url'))">
                                <div class="space-y-1">
                                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Uploaded Documents & Photos</h4>
                                    <div class="flex flex-wrap items-center gap-2 bg-slate-50 p-2 rounded-xl border border-slate-100">
                                        <template x-for="(val, key) in (selectedRegistration.form_data || {})" :key="key">
                                            <template x-if="key.endsWith('_url') && val">
                                                <div class="flex items-center gap-2 bg-white px-2 py-1 rounded-lg border border-slate-200 shadow-2xs">
                                                    <a :href="val" target="_blank"
                                                        class="block w-8 h-8 shrink-0 overflow-hidden rounded bg-slate-100 border border-slate-200">
                                                        <img :src="val" class="w-full h-full object-cover">
                                                    </a>
                                                    <div class="min-w-0">
                                                        <span class="text-[9px] font-bold text-slate-700 uppercase block truncate max-w-[100px]"
                                                            x-text="key.replace('_url', '').replace(/_/g, ' ')"></span>
                                                        <a :href="val" target="_blank"
                                                            class="text-[9px] font-bold text-primary-600 hover:underline">View File ↗</a>
                                                    </div>
                                                </div>
                                            </template>
                                        </template>
                                    </div>
                                </div>
                            </template>

                            <!-- Complete Form Data Grid -->
                            <div class="space-y-1">
                                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Submitted Form Fields</h4>
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-1.5">
                                    <template x-for="(val, key) in (selectedRegistration.form_data || {})" :key="key">
                                        <template x-if="!key.endsWith('_url') && key !== 'submission_date'">
                                            <div class="bg-white px-2 py-1 rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors shadow-2xs">
                                                <span class="text-[8px] font-black text-slate-400 uppercase tracking-wider block truncate"
                                                    x-text="key.replace(/_/g, ' ')"></span>
                                                <span class="font-bold text-slate-900 text-[10.5px] block break-words leading-tight mt-0.5"
                                                    x-text="val || '-'"></span>
                                            </div>
                                        </template>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>

                </div>

                <!-- Modal Footer -->
                <div class="px-4 py-2 bg-slate-50 border-t border-slate-100 flex items-center justify-between shrink-0">
                    <button type="button" @click="window.print()"
                        class="px-3 py-1 bg-slate-200 hover:bg-slate-300 text-slate-800 font-bold text-xs rounded-xl transition-colors cursor-pointer flex items-center gap-1.5">
                        <span>🖨️ Print Biodata</span>
                    </button>
                    <button type="button" @click="showDetailsModal = false"
                        class="px-4 py-1.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl transition-colors cursor-pointer">
                        Close Details
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>

<script>
function adminEventShowData() {
    return {
        mainTab: 'details',
        statusTab: 'all',
        showUploadModal: false,
        showDetailsModal: false,
        selectedRegistration: {},
        previewLang: @json(app()->getLocale() === 'gu' ? 'gu' : 'en'),
        openBiodata(data) {
            this.selectedRegistration = data || {};
            this.showDetailsModal = true;
        },
        getPhotoUrl(fd) {
            if (!fd) return '';
            if (fd.member_photo_url) return fd.member_photo_url;
            if (fd.selfie_url) return fd.selfie_url;
            if (fd.whatsapp_image_url) return fd.whatsapp_image_url;
            if (fd.member_photo && typeof fd.member_photo === 'string' && (fd.member_photo.startsWith('http') || fd.member_photo.startsWith('/storage/'))) return fd.member_photo;
            return '';
        },
        getSiblingStat(fd, type, isMarried) {
            const noneText = this.previewLang === 'en' ? 'None' : 'નથી';
            if (!fd) return noneText;
            let arr = [];
            if (fd.siblings_json) {
                try {
                    arr = typeof fd.siblings_json === 'string' ? JSON.parse(fd.siblings_json) : fd.siblings_json;
                } catch (e) {
                    arr = [];
                }
            }
            if (Array.isArray(arr) && arr.length > 0) {
                const filtered = arr.filter(function(s) {
                    const relMatch = s.relation && s.relation.toLowerCase().includes(type.toLowerCase());
                    const marMatch = isMarried ? (s.married === 'Yes' || s.married === 'Married') : (s.married !== 'Yes' && s.married !== 'Married');
                    return relMatch && marMatch;
                });
                if (filtered.length > 0) {
                    return filtered.length + ' (' + filtered.map(function(s) { return s.details || '1'; }).join(', ') + ')';
                }
            }
            if (type.toLowerCase().includes('elder') && type.toLowerCase().includes('brother')) {
                const val = isMarried ? fd.elder_brother_married : fd.elder_brother;
                return (val && val !== 'No' && val !== '0') ? val : noneText;
            }
            if (type.toLowerCase().includes('younger') && type.toLowerCase().includes('brother')) {
                const val = isMarried ? fd.younger_brother_married : fd.younger_brother;
                return (val && val !== 'No' && val !== '0') ? val : noneText;
            }
            if (type.toLowerCase().includes('elder') && type.toLowerCase().includes('sister')) {
                const val = isMarried ? fd.elder_sister_married : fd.elder_sister;
                return (val && val !== 'No' && val !== '0') ? val : noneText;
            }
            if (type.toLowerCase().includes('younger') && type.toLowerCase().includes('sister')) {
                const val = isMarried ? fd.younger_sister_married : fd.younger_sister;
                return (val && val !== 'No' && val !== '0') ? val : noneText;
            }
            return noneText;
        }
    };
}
</script>
@endsection
