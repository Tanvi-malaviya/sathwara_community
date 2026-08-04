@extends('layouts.admin')

@section('page_title', __('messages.event_registrations'))

@section('content')
<div class="space-y-4" x-data="{ activeTab: 'all', showDetailsModal: false, selectedRegistration: {}, search: '' }">
    <!-- Header banner card -->
    <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-2">
                <h3 class="text-base font-extrabold text-slate-900">{{ $event->title }}</h3>
                @if(($event->event_type ?? 'normal') === 'inam_vitaran')
                    <span class="px-2 py-0.5 rounded bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-black uppercase">🏆 {{ __('messages.award_form') }}</span>
                @elseif(($event->event_type ?? 'normal') === 'yuva_melo')
                    <span class="px-2 py-0.5 rounded bg-purple-50 text-purple-700 border border-purple-200 text-[10px] font-black uppercase">⚡ {{ __('messages.yuva_melo') }}</span>
                @endif
            </div>
            <p class="text-xs text-slate-500 font-medium mt-1">📅 {{ date('F d, Y', strtotime($event->date)) }} • 📍 {{ $event->venue }}</p>
        </div>
        <a href="{{ route('admin.events.index') }}" 
           class="px-3.5 py-1.5 bg-slate-50 hover:bg-slate-100 text-slate-700 font-bold text-xs rounded-xl border border-slate-200 transition-colors flex items-center gap-1.5 shrink-0">
            <span>&larr;</span>
            <span>{{ __('messages.back_to_events') }}</span>
        </a>
    </div>

    <!-- Filter Tabs (All / Selected) -->
    @php
        $user = auth()->user();
        $userPerms = $user->permissions->pluck('name');
        $canEditThisEvent = $user->hasRole('Administrator') || 
                            $userPerms->contains('events_manage') || 
                            $userPerms->contains('event_manage_' . $event->id) || 
                            $userPerms->contains('event_edit_' . $event->id);
        $totalCount = count($registrations);
        $selectedCount = $registrations->where('is_selected', true)->count();
    @endphp

    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 bg-white p-2.5 rounded-xl border border-slate-100 shadow-sm">
        <!-- Filter Tabs -->
        <div class="flex items-center gap-1.5 p-1 bg-slate-100/80 rounded-xl border border-slate-200/60 overflow-x-auto">
            <button type="button" @click="activeTab = 'all'" 
                    :class="activeTab === 'all' ? 'bg-white text-slate-900 shadow-xs font-extrabold border border-slate-200/80' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                    class="px-3.5 py-1.5 text-xs rounded-lg transition-all flex items-center gap-1.5 whitespace-nowrap">
                <span>{{ __('messages.all_registrations') }}</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700" :class="activeTab === 'all' ? 'bg-slate-200' : ''">{{ $totalCount }}</span>
            </button>

            <button type="button" @click="activeTab = 'selected'" 
                    :class="activeTab === 'selected' ? 'bg-white text-emerald-700 shadow-xs font-extrabold border border-emerald-200/80' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                    class="px-3.5 py-1.5 text-xs rounded-lg transition-all flex items-center gap-1.5 whitespace-nowrap">
                <span>Selected</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800" :class="activeTab === 'selected' ? 'bg-emerald-200' : ''">{{ $selectedCount }}</span>
            </button>
        </div>

        <!-- Search input & Export -->
        <div class="flex items-center gap-2">
            <div class="relative min-w-[200px]">
                <input type="text" x-model="search" placeholder="{{ __('messages.search_registrations') }}" 
                       class="h-9 w-full text-xs font-semibold pl-9 pr-8 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 focus:outline-none transition-all">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <button type="button" x-show="search" @click="search = ''" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-rose-500 font-extrabold text-sm" title="Clear search">
                    &times;
                </button>
            </div>

            <a href="{{ route('admin.events.registrations.export', $event->id) }}" 
               class="h-9 px-3.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-xs rounded-xl border border-emerald-200/60 shadow-xs transition-colors inline-flex items-center justify-center gap-1.5 whitespace-nowrap">
                📊 <span>{{ __('messages.export_excel') }}</span>
            </a>
        </div>
    </div>

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

                $modalData = [
                    'user_name' => $userName,
                    'email' => $userEmail ?? '-',
                    'phone' => $userPhone ?? '-',
                    'city' => $userCity ?? '-',
                    'is_selected' => (bool)$reg->is_selected,
                    'date' => $reg->created_at->format('d-M-Y h:i A'),
                    'form_data' => $fd,
                ];
            @endphp
            <div x-show="(activeTab === 'all' || (activeTab === 'selected' && {{ $reg->is_selected ? 'true' : 'false' }})) && 
                        (!search || 
                         '{{ addslashes(strtolower($userName)) }}'.includes(search.toLowerCase()) || 
                         '{{ addslashes(strtolower($userEmail ?? '')) }}'.includes(search.toLowerCase()) || 
                         '{{ addslashes(strtolower($userPhone ?? '')) }}'.includes(search.toLowerCase()) || 
                         '{{ addslashes(strtolower($userCity ?? '')) }}'.includes(search.toLowerCase()))" 
                 class="bg-white border border-slate-200/90 rounded-2xl p-3 shadow-2xs hover:shadow-md hover:border-primary-400 transition-all space-y-2 relative group flex flex-col justify-between">
                
                <div class="space-y-2">
                    <!-- Card Header: Checkbox + Reg # + Name & Date -->
                    <div class="flex items-start justify-between gap-2 border-b border-slate-100 pb-2">
                        <div class="flex items-center gap-2 min-w-0">
                            <form method="POST" action="{{ route('admin.events.registrations.toggle_select', $reg->id) }}">
                                @csrf
                                <input type="checkbox" name="is_selected" value="1" {{ $reg->is_selected ? 'checked' : '' }} 
                                       @if($canEditThisEvent) onchange="this.form.submit()" @else disabled @endif 
                                       title="{{ $canEditThisEvent ? 'Select candidate' : 'View only permission' }}" 
                                       class="rounded border-slate-300 text-primary-600 focus:ring-primary-500 w-3.5 h-3.5 {{ $canEditThisEvent ? 'cursor-pointer' : 'cursor-not-allowed opacity-60' }}">
                            </form>
                            
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

                        <div class="text-right shrink-0">
                            <span class="text-[9px] font-bold text-slate-400 block">{{ $reg->created_at->format('d-M-Y') }}</span>
                            <span class="text-[8px] font-medium text-slate-300 block">{{ $reg->created_at->format('h:i A') }}</span>
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

                    <!-- Direct Form Data Details Embedded Inside Card -->
                    <div class="grid grid-cols-2 gap-1.5 text-[10px]">
                        @if(!empty($fd['education_type']))
                            <div class="bg-slate-50 p-1.5 rounded-lg border border-slate-100 col-span-2">
                                <span class="text-[8px] font-extrabold text-slate-400 uppercase block tracking-wider">Education Type</span>
                                <span class="font-bold text-slate-800 truncate block">{{ $fd['education_type'] }}</span>
                            </div>
                        @endif

                        @if(!empty($fd['education']))
                            <div class="bg-slate-50 p-1.5 rounded-lg border border-slate-100 {{ empty($fd['school_college']) ? 'col-span-2' : '' }}">
                                <span class="text-[8px] font-extrabold text-slate-400 uppercase block tracking-wider">Course / Standard</span>
                                <span class="font-bold text-slate-800 truncate block" title="{{ $fd['education'] }}">{{ $fd['education'] }}</span>
                            </div>
                        @endif

                        @if(!empty($fd['school_college']))
                            <div class="bg-slate-50 p-1.5 rounded-lg border border-slate-100 {{ empty($fd['education']) ? 'col-span-2' : '' }}">
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

                    <!-- Direct File Attachment Link (Marksheet / Document) -->
                    @if(!empty($fd['marksheet_url']))
                        <div class="pt-0.5">
                            <a href="{{ str_starts_with($fd['marksheet_url'], 'http') ? $fd['marksheet_url'] : asset('storage/' . $fd['marksheet_url']) }}" target="_blank" 
                               class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg text-[9px] font-extrabold border border-blue-200/80 transition-colors w-full justify-center">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                <span>View Uploaded Marksheet / Certificate ↗</span>
                            </a>
                        </div>
                    @endif
                </div>

                <!-- View Complete Details Button (For Yuva Melo & other events with extra fields) -->
                @if(($event->event_type ?? 'normal') !== 'inam_vitaran')
                    <div class="pt-2 mt-2 border-t border-slate-100">
                        <button type="button" 
                                @click='selectedRegistration = @json($modalData); showDetailsModal = true'
                                class="w-full py-1.5 px-3 bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-[11px] rounded-xl flex items-center justify-center gap-1.5 transition-colors cursor-pointer shadow-2xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <span>View Complete Details</span>
                        </button>
                    </div>
                @endif
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-slate-400 font-medium bg-white rounded-2xl border border-slate-100 shadow-2xs">
                {{ __('messages.no_registrations_found') }}
            </div>
        @endforelse
    </div>

    <!-- Registration Details Pop-up Modal -->
    <template x-teleport="body">
        <div x-show="showDetailsModal" 
             class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-5 bg-slate-900/60 backdrop-blur-sm"
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-cloak>
            <div @click.away="showDetailsModal = false" 
                 class="bg-white rounded-2xl border border-slate-100 shadow-2xl max-w-5xl w-full max-h-[90vh] flex flex-col overflow-hidden relative">
                
                <!-- Modal Header -->
                <div class="px-5 py-4 bg-slate-900 text-white flex items-center justify-between shrink-0">
                    <div>
                        <h3 class="text-sm font-extrabold flex items-center gap-2" x-text="selectedRegistration.user_name + ' - ' + '{{ __('messages.submitted_details') }}'"></h3>
                        <p class="text-[11px] text-slate-400 font-medium mt-0.5" x-text="'{{ __('messages.registered_date') }}: ' + (selectedRegistration.date || '')"></p>
                    </div>
                    <button type="button" @click="showDetailsModal = false" 
                            class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors text-xs font-bold">
                        ✕
                    </button>
                </div>

                <!-- Modal Content Body -->
                <div class="p-5 space-y-4 overflow-y-auto max-h-[78vh] text-xs">
                    <!-- Member Summary Card -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 bg-slate-50 p-3.5 rounded-xl border border-slate-200/80 text-xs">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">{{ __('messages.applicant_email') }}</span>
                            <span class="font-extrabold text-slate-800 text-xs truncate block" x-text="selectedRegistration.email"></span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">{{ __('messages.contact_info') }}</span>
                            <span class="font-extrabold text-slate-800 text-xs block" x-text="selectedRegistration.phone"></span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">{{ __('messages.city') }}</span>
                            <span class="font-extrabold text-slate-800 text-xs block" x-text="selectedRegistration.city"></span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Selection Status</span>
                            <span class="font-extrabold text-[10px] uppercase tracking-wider px-2.5 py-0.5 rounded inline-block mt-0.5"
                                  :class="selectedRegistration.is_selected ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-slate-200 text-slate-700'" 
                                  x-text="selectedRegistration.is_selected ? 'Selected' : 'Not Selected'"></span>
                        </div>
                    </div>

                    <!-- Uploaded Documents & Media Grid -->
                    <template x-if="Object.keys(selectedRegistration.form_data || {}).some(k => k.endsWith('_url') || k.endsWith('_image') || k.endsWith('_photo'))">
                        <div class="space-y-1.5">
                            <h4 class="text-[11px] font-black text-slate-400 uppercase tracking-wider">Uploaded Documents & Attachments</h4>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2.5 bg-slate-50/80 p-3 rounded-xl border border-slate-200/60">
                                <template x-for="(val, key) in (selectedRegistration.form_data || {})" :key="key">
                                    <template x-if="(key.endsWith('_url') || key.endsWith('_image') || key.endsWith('_photo')) && val">
                                        <div class="bg-white p-2 rounded-lg border border-slate-200 shadow-2xs flex items-center gap-2">
                                            <a :href="val" target="_blank" class="block w-10 h-10 shrink-0 overflow-hidden rounded-lg bg-slate-100 border border-slate-200">
                                                <img :src="val" class="w-full h-full object-cover" onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'%2364748b\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z\'/></svg>'">
                                            </a>
                                            <div class="min-w-0 flex-1">
                                                <span class="text-[9px] font-black text-slate-700 uppercase block truncate" x-text="key.replace('_url', '').replace('_photo', '').replace('_image', '').replace(/_/g, ' ')"></span>
                                                <a :href="val" target="_blank" class="text-[10px] font-bold text-primary-600 hover:underline">Open File ↗</a>
                                            </div>
                                        </div>
                                    </template>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- Form Fields Grid (4 Columns) -->
                    <div class="space-y-1.5">
                        <h4 class="text-[11px] font-black text-slate-400 uppercase tracking-wider">{{ __('messages.candidate_form_details') }}</h4>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-2.5">
                            <template x-for="(val, key) in (selectedRegistration.form_data || {})" :key="key">
                                <template x-if="!key.endsWith('_url') && !key.endsWith('_image') && !key.endsWith('_photo') && key !== 'submission_date'">
                                    <div class="bg-slate-50/90 p-2.5 rounded-xl border border-slate-200/80 hover:bg-slate-100/70 transition-colors">
                                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block truncate" x-text="key.replace(/_/g, ' ')"></span>
                                        
                                        <!-- If json formatted data (e.g. siblings_json) -->
                                        <template x-if="typeof val === 'string' && (val.startsWith('[') || val.startsWith('{'))">
                                            <span class="font-bold text-slate-800 text-xs block break-words mt-1 bg-white p-1.5 rounded border border-slate-200 font-mono text-[10px]" x-text="val"></span>
                                        </template>
                                        
                                        <template x-if="!(typeof val === 'string' && (val.startsWith('[') || val.startsWith('{')))">
                                            <span class="font-bold text-slate-900 text-xs block break-words mt-0.5" x-text="val || '-'"></span>
                                        </template>
                                    </div>
                                </template>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Footer Action Buttons -->
                <div class="px-5 py-3 bg-slate-50 border-t border-slate-100 flex items-center justify-end shrink-0">
                    <button type="button" @click="showDetailsModal = false" 
                            class="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-xs rounded-xl shadow-xs transition-colors cursor-pointer">
                        {{ __('messages.close') }}
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>
@endsection
