@extends('layouts.admin')

@section('page_title', __('messages.event_details'))

@section('content')
<div class="space-y-2" x-data="{ activeTab: 'all', showDetailsModal: false, showDescModal: false, selectedRegistration: {} }">
    <!-- Top Action Bar -->
    <div class="bg-white p-3.5 rounded-xl border border-slate-100 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.events.index') }}" 
               class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors flex items-center justify-center font-bold text-xs" 
               title="{{ __('messages.back_to_website') }}">
                &larr;
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-sm font-extrabold text-slate-900 leading-tight">{{ $event->title }}</h1>
                    <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase tracking-wider
                        {{ $event->status == 'published' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' : ($event->status == 'cancelled' ? 'bg-rose-50 text-rose-700 border border-rose-200/60' : 'bg-slate-100 text-slate-600 border border-slate-200') }}">
                        {{ __('messages.' . strtolower($event->status)) != 'messages.' . strtolower($event->status) ? __('messages.' . strtolower($event->status)) : ucfirst($event->status) }}
                    </span>
                </div>
                <p class="text-[11px] text-slate-400 font-medium">{{ __('messages.created_on') }} {{ $event->created_at->format('d M Y, h:i A') }}</p>
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
        @endphp

        <div class="flex items-center gap-1.5 shrink-0 flex-wrap">
            @if($event->registration_option)
                <a href="#registrations-section" 
                   class="px-3 py-1.5 bg-primary-50 text-primary-700 hover:bg-primary-100 border border-primary-200/60 font-extrabold text-xs rounded-lg transition-colors flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span>{{ __('messages.registrations') }} ({{ count($registrations) }})</span>
                </a>
            @endif

            <a href="{{ route('admin.events.gallery', $event->id) }}"
               class="px-3 py-1.5 bg-primary-50 text-primary-700 hover:bg-primary-100 border border-primary-200/60 font-extrabold text-xs rounded-lg transition-colors">
                <span>{{ __('messages.gallery') }}</span>
            </a>

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

    <!-- Main Grid Content (50-50 Equal Split: Left Photo, Right Details) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 items-start">
        <!-- Left Side: Cover Photo Card (Equal 50% Column) -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden relative">
                <div class="relative w-full h-64 sm:h-80 lg:h-[400px] bg-slate-900">
                    <img src="{{ str_starts_with($event->banner_path, 'http') ? $event->banner_path : asset('storage/' . $event->banner_path) }}" 
                         alt="{{ $event->title }}" 
                         class="w-full h-full object-cover opacity-95">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-black/20"></div>
                    
                    <!-- Badges overlay -->
                    <div class="absolute bottom-3.5 left-3.5 right-3.5 flex items-center gap-1.5 flex-wrap">
                        @if($event->event_type === 'inam_vitaran')
                            <span class="px-2.5 py-1 rounded-md bg-amber-500 text-white text-[10px] font-black uppercase tracking-wider shadow-xs">🏆 {{ __('messages.inam_vitaran') }}</span>
                        @elseif($event->event_type === 'yuva_melo')
                            <span class="px-2.5 py-1 rounded-md bg-purple-600 text-white text-[10px] font-black uppercase tracking-wider shadow-xs">⚡ {{ __('messages.yuva_melo') }}</span>
                        @else
                            <span class="px-2.5 py-1 rounded-md bg-slate-800 text-white text-[10px] font-black uppercase tracking-wider shadow-xs">📢 {{ __('messages.general_event') }}</span>
                        @endif

                        @if($event->pass_fee > 0)
                            <span class="px-2.5 py-1 rounded-md bg-emerald-600 text-white text-[10px] font-black uppercase tracking-wider shadow-xs">₹{{ number_format($event->pass_fee, 2) }} Fee</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: All Details (Equal 50% Column) -->
        <div class="lg:col-span-1 space-y-4 flex flex-col justify-between">
            <!-- Event Schedule & Location Grid -->
            <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm space-y-3.5">
                <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2">{{ __('messages.event_schedule_location') }}</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    <!-- Date & Time -->
                    <div class="flex items-start gap-3 p-2.5 bg-slate-50 rounded-lg border border-slate-100">
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
                    <div class="flex items-start gap-3 p-2.5 bg-slate-50 rounded-lg border border-slate-100">
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

    <!-- Embedded Event Registrations Section -->
    @if($event->registration_option)
        @php
            $totalCount = count($registrations);
            $pendingCount = $registrations->where('status', 'pending')->count();
            $approvedCount = $registrations->where('status', 'approved')->count();
            $rejectedCount = $registrations->where('status', 'rejected')->count();
        @endphp

        <div id="registrations-section" class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm space-y-4 pt-4">
            <!-- Section Title & Filter Tabs -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-3">
                <div>
                    <h2 class="text-sm font-black text-slate-900 flex items-center gap-2">
                        <span>{{ __('messages.event_registrations') }}</span>
                        <span class="px-2 py-0.5 rounded-full text-xs font-black bg-primary-50 text-primary-700 border border-primary-100">{{ $totalCount }} {{ __('messages.submitted') }}</span>
                    </h2>
                    <p class="text-[11px] text-slate-400 font-medium">{{ __('messages.review_manage_registrations_desc') }}</p>
                </div>

                <!-- Status Filter Tabs -->
                <div class="flex items-center gap-1.5 p-1 bg-slate-100/80 rounded-xl border border-slate-200/60 shrink-0">
                    <button type="button" @click="activeTab = 'all'" 
                            :class="activeTab === 'all' ? 'bg-white text-slate-900 shadow-xs font-extrabold border border-slate-200/80' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                            class="px-3 py-1.5 text-xs rounded-lg transition-all flex items-center gap-1.5">
                        <span>{{ __('messages.all') }}</span>
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700" :class="activeTab === 'all' ? 'bg-slate-200' : ''">{{ $totalCount }}</span>
                    </button>

                    <button type="button" @click="activeTab = 'pending'" 
                            :class="activeTab === 'pending' ? 'bg-white text-amber-700 shadow-xs font-extrabold border border-amber-200/80' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                            class="px-3 py-1.5 text-xs rounded-lg transition-all flex items-center gap-1.5">
                        <span>{{ __('messages.pending') }}</span>
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] font-black bg-amber-100 text-amber-800">{{ $pendingCount }}</span>
                    </button>

                    <button type="button" @click="activeTab = 'approved'" 
                            :class="activeTab === 'approved' ? 'bg-white text-emerald-700 shadow-xs font-extrabold border border-emerald-200/80' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                            class="px-3 py-1.5 text-xs rounded-lg transition-all flex items-center gap-1.5">
                        <span>{{ __('messages.approved') }}</span>
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800">{{ $approvedCount }}</span>
                    </button>

                    <button type="button" @click="activeTab = 'rejected'" 
                            :class="activeTab === 'rejected' ? 'bg-white text-rose-700 shadow-xs font-extrabold border border-rose-200/80' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                            class="px-3 py-1.5 text-xs rounded-lg transition-all flex items-center gap-1.5">
                        <span>{{ __('messages.rejected') }}</span>
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] font-black bg-rose-100 text-rose-800">{{ $rejectedCount }}</span>
                    </button>
                       <!-- Registrations Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
                @forelse($registrations as $index => $reg)
                    @php
                        $fd = $reg->form_data ?? [];
                        $userName = $reg->user ? $reg->user->name : ($fd['student_name'] ?? $fd['full_name'] ?? $fd['first_name'] ?? 'Guest Participant');
                        $userEmail = $reg->user ? $reg->user->email : ($fd['email'] ?? null);
                        $userPhone = $fd['contact_number'] ?? $fd['mobile_no'] ?? ($reg->user ? ($reg->user->memberProfile->phone ?? null) : null);
                        $userCity = $fd['city'] ?? $fd['district'] ?? ($reg->user ? ($reg->user->memberProfile->city ?? null) : null);
                        $regNo = $fd['registration_no'] ?? ($index + 1);
                    @endphp
                    <div x-show="activeTab === 'all' || activeTab === '{{ $reg->status }}'" 
                         class="bg-white border border-slate-200/90 rounded-2xl p-3 shadow-2xs hover:shadow-md hover:border-primary-400 transition-all space-y-2 relative group flex flex-col justify-between">
                        
                        <div class="space-y-2">
                            <!-- Card Header: Reg # + Name & Status -->
                            <div class="flex items-start justify-between gap-2 border-b border-slate-100 pb-2">
                                <div class="flex items-center gap-2 min-w-0">
                                    <span class="px-1.5 py-0.5 rounded bg-slate-100 border border-slate-200/80 text-[10px] font-black text-slate-700 shrink-0">#{{ $regNo }}</span>

                                    <div class="min-w-0">
                                        <h4 class="text-xs font-black text-slate-900 truncate group-hover:text-primary-600 transition-colors">
                                            {{ $userName }}
                                        </h4>
                                        @if($userCity)
                                            <p class="text-[9px] text-slate-400 font-semibold truncate">{{ $userCity }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="text-right shrink-0 space-y-0.5">
                                    @if($reg->status === 'approved')
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase">
                                            Approved
                                        </span>
                                    @elseif($reg->status === 'rejected')
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-extrabold bg-rose-50 text-rose-700 border border-rose-200 uppercase">
                                            Rejected
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200 uppercase">
                                            Pending
                                        </span>
                                    @endif
                                    <span class="text-[8px] font-medium text-slate-400 block">{{ $reg->created_at->format('d-M-Y') }}</span>
                                </div>
                            </div>

                            <!-- Contact Bar -->
                            @if($userPhone || $userEmail)
                                <div class="flex items-center justify-between text-[10px] bg-slate-50/80 rounded-lg p-1.5 border border-slate-100">
                                    @if($userPhone)
                                        <div class="flex items-center gap-1 font-extrabold text-slate-800">
                                            <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                            <span>{{ $userPhone }}</span>
                                        </div>
                                    @endif
                                    @if($userEmail)
                                        <div class="text-[9px] font-semibold text-slate-400 truncate max-w-[120px]" title="{{ $userEmail }}">
                                            {{ $userEmail }}
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Embedded Form Details -->
                            <div class="grid grid-cols-2 gap-1.5 text-[10px]">
                                @if(!empty($fd['education']))
                                    <div class="bg-slate-50 p-1.5 rounded-lg border border-slate-100">
                                        <span class="text-[8px] font-extrabold text-slate-400 uppercase block tracking-wider">Education</span>
                                        <span class="font-bold text-slate-800 truncate block">{{ $fd['education'] }}</span>
                                    </div>
                                @endif

                                @if(!empty($fd['school_college']))
                                    <div class="bg-slate-50 p-1.5 rounded-lg border border-slate-100">
                                        <span class="text-[8px] font-extrabold text-slate-400 uppercase block tracking-wider">School / College</span>
                                        <span class="font-bold text-slate-800 truncate block" title="{{ $fd['school_college'] }}">{{ $fd['school_college'] }}</span>
                                    </div>
                                @endif

                                @if(!empty($fd['percentage']))
                                    <div class="bg-emerald-50/80 p-1.5 rounded-lg border border-emerald-100/90 col-span-2">
                                        <span class="text-[8px] font-extrabold text-emerald-600 uppercase block tracking-wider">Percentage</span>
                                        <span class="font-black text-emerald-700 block text-xs">{{ str_contains($fd['percentage'], '%') ? $fd['percentage'] : $fd['percentage'] . '%' }}</span>
                                    </div>
                                @elseif(!empty($fd['received_marks']) && !empty($fd['total_marks']))
                                    <div class="bg-emerald-50/80 p-1.5 rounded-lg border border-emerald-100/90 col-span-2">
                                        <span class="text-[8px] font-extrabold text-emerald-600 uppercase block tracking-wider">Obtained Marks</span>
                                        <span class="font-black text-emerald-700 block text-xs">{{ $fd['received_marks'] }} / {{ $fd['total_marks'] }}</span>
                                    </div>
                                @endif

                                @if(!empty($fd['age']))
                                    <div class="bg-slate-50 p-1.5 rounded-lg border border-slate-100">
                                        <span class="text-[8px] font-extrabold text-slate-400 uppercase block tracking-wider">Age / Gender</span>
                                        <span class="font-bold text-slate-800 truncate block">{{ $fd['age'] }} Yrs {{ !empty($fd['gender']) ? '('.ucfirst($fd['gender']).')' : '' }}</span>
                                    </div>
                                @endif

                                @if(!empty($fd['qualification']))
                                    <div class="bg-slate-50 p-1.5 rounded-lg border border-slate-100">
                                        <span class="text-[8px] font-extrabold text-slate-400 uppercase block tracking-wider">Qualification</span>
                                        <span class="font-bold text-slate-800 truncate block">{{ $fd['qualification'] }}</span>
                                    </div>
                                @endif

                                @if(!empty($fd['occupation']))
                                    <div class="bg-slate-50 p-1.5 rounded-lg border border-slate-100">
                                        <span class="text-[8px] font-extrabold text-slate-400 uppercase block tracking-wider">Occupation</span>
                                        <span class="font-bold text-slate-800 truncate block">{{ $fd['occupation'] }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Marksheet Link -->
                            @if(!empty($fd['marksheet_url']))
                                <div class="pt-0.5">
                                    <a href="{{ str_starts_with($fd['marksheet_url'], 'http') ? $fd['marksheet_url'] : asset('storage/' . $fd['marksheet_url']) }}" target="_blank" 
                                       class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg text-[9px] font-extrabold border border-blue-200/80 transition-colors w-full justify-center">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                        <span>View Marksheet / Certificate ↗</span>
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- Actions (Approve/Reject) -->
                        @if($reg->status !== 'approved' || $reg->status !== 'rejected')
                            <div class="pt-1.5 border-t border-slate-100 flex items-center justify-end gap-1.5">
                                @if($reg->status !== 'approved')
                                    <form method="POST" action="{{ route('admin.events.registrations.approve', $reg->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" title="{{ __('messages.approve') }}" 
                                                class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 hover:bg-emerald-100 text-[9px] font-extrabold border border-emerald-200 transition-colors inline-flex items-center gap-0.5">
                                            ✓ Approve
                                        </button>
                                    </form>
                                @endif
                                @if($reg->status !== 'rejected')
                                    <form method="POST" action="{{ route('admin.events.registrations.reject', $reg->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" title="{{ __('messages.reject') }}" 
                                                class="px-2 py-0.5 rounded bg-rose-50 text-rose-700 hover:bg-rose-100 text-[9px] font-empty text-[9px] font-extrabold border border-rose-200 transition-colors inline-flex items-center gap-0.5">
                                            ✕ Reject
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-slate-400 font-medium bg-white rounded-2xl border border-slate-100 shadow-2xs">
                        {{ __('messages.no_registrations_found') }}
                    </div>
                @endforelse
            </div>
        </div>
    @endif

    <!-- Registration Form Details Pop-up Modal -->
    <template x-teleport="body">
        <div x-show="showDetailsModal" 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-cloak>
            <div @click.away="showDetailsModal = false" 
                 class="bg-white rounded-2xl border border-slate-100 shadow-2xl max-w-lg w-full overflow-hidden relative">
                
                <!-- Modal Header -->
                <div class="px-5 py-4 bg-slate-900 text-white flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-extrabold" x-text="selectedRegistration.user_name + '\'s Submitted Details'"></h3>
                        <p class="text-[11px] text-slate-400 font-medium mt-0.5" x-text="'Submitted on ' + (selectedRegistration.date || '')"></p>
                    </div>
                    <button type="button" @click="showDetailsModal = false" 
                            class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors text-xs">
                        ✕
                    </button>
                </div>

                <!-- Modal Content Body -->
                <div class="p-5 space-y-4">
                    <!-- Member Summary Card -->
                    <div class="grid grid-cols-2 gap-2.5 bg-slate-50 p-3 rounded-xl border border-slate-100 text-xs">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">{{ __('messages.applicant_email') }}</span>
                            <span class="font-bold text-slate-800 text-[11px]" x-text="selectedRegistration.email"></span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">{{ __('messages.contact_number') }}</span>
                            <span class="font-bold text-slate-800 text-[11px]" x-text="selectedRegistration.phone"></span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">{{ __('messages.city') }}</span>
                            <span class="font-bold text-slate-800 text-[11px]" x-text="selectedRegistration.city"></span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">{{ __('messages.application_status') }}</span>
                            <span class="font-extrabold text-[10px] uppercase tracking-wider px-2 py-0.5 rounded inline-block mt-0.5"
                                  :class="{
                                      'bg-emerald-100 text-emerald-800': selectedRegistration.status === 'approved',
                                      'bg-amber-100 text-amber-800': selectedRegistration.status === 'pending',
                                      'bg-rose-100 text-rose-800': selectedRegistration.status === 'rejected'
                                  }" 
                                  x-text="selectedRegistration.status"></span>
                        </div>
                    </div>

                    <!-- Form Data Grid -->
                    <div class="space-y-2">
                        <h4 class="text-[11px] font-black text-slate-400 uppercase tracking-wider">{{ __('messages.candidate_form_details') }}</h4>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 max-h-64 overflow-y-auto pr-1">
                            <template x-for="(val, key) in (selectedRegistration.form_data || {})" :key="key">
                                <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block" x-text="key.replace(/_/g, ' ')"></span>
                                    <span class="font-bold text-slate-800 text-xs block mt-0.5" x-text="val"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Footer Action Buttons -->
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <template x-if="selectedRegistration.status !== 'approved'">
                                <form method="POST" :action="selectedRegistration.approve_url">
                                    @csrf
                                    <button type="submit" 
                                            class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-xs transition-colors flex items-center gap-1">
                                        <span>✓ {{ __('messages.approve_application') }}</span>
                                    </button>
                                </form>
                            </template>
                            <template x-if="selectedRegistration.status !== 'rejected'">
                                <form method="POST" :action="selectedRegistration.reject_url">
                                    @csrf
                                    <button type="submit" 
                                            class="px-3.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white font-extrabold text-xs rounded-xl shadow-xs transition-colors flex items-center gap-1">
                                        <span>✕ {{ __('messages.reject_application') }}</span>
                                    </button>
                                </form>
                            </template>
                        </div>

                        <button type="button" @click="showDetailsModal = false" 
                                class="px-4 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-colors">
                            {{ __('messages.close') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
    <!-- Event Full Description Pop-up Modal -->
    <template x-teleport="body">
        <div x-show="showDescModal" 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-cloak>
            <div @click.away="showDescModal = false" 
                 class="bg-white rounded-2xl border border-slate-100 shadow-2xl max-w-lg w-full overflow-hidden relative">
                
                <!-- Modal Header -->
                <div class="px-5 py-4 bg-slate-900 text-white flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-extrabold">{{ $event->title }}</h3>
                        <p class="text-[11px] text-slate-400 font-medium mt-0.5">{{ __('messages.description') }}</p>
                    </div>
                    <button type="button" @click="showDescModal = false" 
                            class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors text-xs">
                        ✕
                    </button>
                </div>

                <!-- Modal Content Body -->
                <div class="p-5 space-y-4">
                    <div class="text-xs text-slate-700 leading-relaxed font-medium max-h-96 overflow-y-auto pr-1 space-y-2">
                        {!! $event->description !!}
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex justify-end">
                        <button type="button" @click="showDescModal = false" 
                                class="px-4 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-colors">
                            {{ __('messages.close') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
@endsection
