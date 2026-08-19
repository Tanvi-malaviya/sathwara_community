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
        $totalPersonsSum = $registrations->sum(function($r) {
            return (int) ($r->form_data['person_count'] ?? 1);
        });
        $totalFeeCollected = $registrations->where('payment_status', 'paid')->sum('payment_amount');
        $paidCount = $registrations->where('payment_status', 'paid')->count();
    @endphp

    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 bg-white p-2.5 rounded-xl border border-slate-100 shadow-sm">
        <!-- Filter Tabs & Total Persons Badge -->
        <div class="flex items-center gap-2 flex-wrap">
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
                    <span>{{ __('messages.selected') }}</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800" :class="activeTab === 'selected' ? 'bg-emerald-200' : ''">{{ $selectedCount }}</span>
                </button>
            </div>

            <!-- Total Persons Attending Badge -->
            <div class="px-3 py-1.5 bg-primary-50/90 border border-primary-200/80 rounded-xl text-primary-900 text-xs font-bold inline-flex items-center gap-1.5 shadow-2xs">
                <span>👥 {{ __('messages.total_attending_persons') }}:</span>
                <span class="font-black text-primary-700 text-sm">{{ $totalPersonsSum }}</span>
            </div>

            @if($event->pass_fee > 0)
                <div class="px-3 py-1.5 bg-amber-50/90 border border-amber-200/80 rounded-xl text-amber-900 text-xs font-bold inline-flex items-center gap-1.5 shadow-2xs">
                    <span>💰 Fee Collected:</span>
                    <span class="font-black text-amber-700 text-sm">₹{{ number_format($totalFeeCollected, 0) }}</span>
                    <span class="text-[10px] text-amber-600 font-semibold">({{ $paidCount }} paid)</span>
                </div>
            @endif
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
                $memberCode = $reg->user ? sprintf('#%05d', $reg->user->id) : (is_scalar($fd['member_id'] ?? null) ? (string)$fd['member_id'] : '-');
                $rawName = $reg->user ? $reg->user->name : ($fd['full_name'] ?? $fd['student_name'] ?? $fd['first_name'] ?? 'Guest Participant');
                $userName = is_scalar($rawName) ? (string)$rawName : 'Participant';

                $rawEmail = $reg->user ? $reg->user->email : ($fd['email'] ?? null);
                $userEmail = is_scalar($rawEmail) ? (string)$rawEmail : null;

                $rawPhone = $fd['mobile'] ?? $fd['contact_number'] ?? $fd['mobile_no'] ?? ($reg->user ? ($reg->user->memberProfile->phone ?? null) : null);
                $userPhone = is_scalar($rawPhone) ? (string)$rawPhone : null;

                $rawCity = $fd['city'] ?? $fd['area'] ?? $fd['district'] ?? ($reg->user ? ($reg->user->memberProfile->city ?? null) : null);
                if (is_array($rawCity)) {
                    $userCity = implode(', ', array_filter($rawCity, 'is_scalar'));
                } elseif (is_object($rawCity)) {
                    $userCity = $rawCity->name ?? (string)$rawCity;
                } else {
                    $userCity = is_scalar($rawCity) ? (string)$rawCity : null;
                }

                $personCount = (int)($fd['person_count'] ?? 1);
                $regNo = is_scalar($fd['registration_no'] ?? null) ? $fd['registration_no'] : ($index + 1);

                $modalData = [
                    'member_code' => $memberCode,
                    'user_name' => $userName,
                    'email' => $userEmail ?? '-',
                    'phone' => $userPhone ?? '-',
                    'city' => $userCity ?? '-',
                    'person_count' => $personCount,
                    'is_selected' => (bool)$reg->is_selected,
                    'payment_status' => $reg->payment_status ?? 'unpaid',
                    'payment_amount' => $reg->payment_amount ?? 0,
                    'payment_id' => $reg->payment_id ?? '-',
                    'date' => $reg->created_at->format('d-M-Y h:i A'),
                    'form_data' => $fd,
                ];
            @endphp
            <div x-show="(activeTab === 'all' || (activeTab === 'selected' && {{ $reg->is_selected ? 'true' : 'false' }})) && 
                        (!search || 
                         '{{ addslashes(strtolower($userName)) }}'.includes(search.toLowerCase()) || 
                         '{{ addslashes(strtolower($memberCode)) }}'.includes(search.toLowerCase()) || 
                         '{{ addslashes(strtolower($userEmail ?? '')) }}'.includes(search.toLowerCase()) || 
                         '{{ addslashes(strtolower($userPhone ?? '')) }}'.includes(search.toLowerCase()) || 
                         '{{ addslashes(strtolower($userCity ?? '')) }}'.includes(search.toLowerCase()))" 
                 class="bg-white border border-slate-200/90 rounded-2xl p-3.5 shadow-2xs hover:shadow-md hover:border-primary-400 transition-all space-y-2.5 relative group flex flex-col justify-between">
                
                <div class="space-y-2.5">
                    <!-- Card Header: Checkbox + Member Code + Reg Date -->
                    <div class="flex items-center justify-between gap-2 border-b border-slate-100 pb-2">
                        <div class="flex items-center gap-2 min-w-0">
                            <form method="POST" action="{{ route('admin.events.registrations.toggle_select', $reg->id) }}">
                                @csrf
                                <input type="checkbox" name="is_selected" value="1" {{ $reg->is_selected ? 'checked' : '' }} 
                                       @if($canEditThisEvent) onchange="this.form.submit()" @else disabled @endif 
                                       title="{{ $canEditThisEvent ? 'Select candidate' : 'View only permission' }}" 
                                       class="rounded border-slate-300 text-primary-600 focus:ring-primary-500 w-3.5 h-3.5 {{ $canEditThisEvent ? 'cursor-pointer' : 'cursor-not-allowed opacity-60' }}">
                            </form>
                            
                            <!-- Member Code Badge -->
                            <span class="px-2 py-0.5 rounded-lg bg-slate-900 text-white text-[10px] font-black tracking-wider shadow-2xs">
                                🆔 {{ $memberCode }}
                            </span>
                        </div>

                        <div class="text-right shrink-0">
                            <span class="text-[9px] font-bold text-slate-400 block">{{ $reg->created_at->format('d-M-Y') }}</span>
                        </div>
                    </div>

                    <!-- Participant Name & City -->
                    <div>
                        <span class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider block">Participant Name</span>
                        <h4 class="text-xs font-black text-slate-900 truncate group-hover:text-primary-600 transition-colors">
                            {{ $userName }}
                        </h4>
                        @if($userCity)
                            <p class="text-[9px] text-slate-400 font-semibold truncate mt-0.5">📍 {{ $userCity }}</p>
                        @endif
                    </div>

                    <!-- Mobile & Email Contact Pill -->
                    <div class="bg-slate-50 p-2 rounded-xl border border-slate-100 space-y-1 text-[10px]">
                        <div class="flex items-center gap-1.5 font-black text-slate-800">
                            <span>📞</span>
                            <span>{{ $userPhone ?: '-' }}</span>
                        </div>
                        @if(!empty($userEmail))
                            <div class="flex items-center gap-1.5 text-[9px] text-slate-500 font-semibold truncate" title="{{ $userEmail }}">
                                <span>✉️</span>
                                <span>{{ $userEmail }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Persons Count & Pass Fee Info -->
                    <div class="grid grid-cols-2 gap-1.5 text-[10px]">
                        <!-- Ketla Person Attending -->
                        <div class="bg-primary-50/90 p-2 rounded-xl border border-primary-100 col-span-2 flex items-center justify-between">
                            <span class="text-[9px] font-black text-primary-800 uppercase tracking-wider">👥 Attending Persons:</span>
                            <span class="font-black text-primary-700 text-xs px-2 py-0.5 bg-white rounded-lg border border-primary-200 shadow-2xs">
                                {{ $personCount }} {{ $personCount > 1 ? 'Persons' : 'Person' }}
                            </span>
                        </div>

                        @if($event->pass_fee > 0)
                            <div class="col-span-2 p-2 rounded-xl border flex items-center justify-between {{ ($reg->payment_status ?? 'unpaid') === 'paid' ? 'bg-emerald-50/90 border-emerald-200' : 'bg-rose-50 border-rose-200' }}">
                                <div>
                                    <span class="text-[8px] font-extrabold uppercase block tracking-wider {{ ($reg->payment_status ?? 'unpaid') === 'paid' ? 'text-emerald-700' : 'text-rose-600' }}">💳 Pass Fee</span>
                                    <span class="font-black text-xs {{ ($reg->payment_status ?? 'unpaid') === 'paid' ? 'text-emerald-800' : 'text-rose-700' }}">₹{{ number_format($reg->payment_amount ?? $event->pass_fee, 0) }}</span>
                                </div>
                                <span class="text-[8px] font-extrabold px-2 py-0.5 rounded-md uppercase {{ ($reg->payment_status ?? 'unpaid') === 'paid' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-rose-100 text-rose-700' }}">
                                    {{ strtoupper($reg->payment_status ?? 'unpaid') }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- View Complete Details Button -->
                @if(($event->event_type ?? 'normal') !== 'inam_vitaran')
                    <div class="pt-2 mt-2 border-t border-slate-100">
                        <button type="button" 
                                @click='selectedRegistration = @json($modalData); showDetailsModal = true'
                                class="w-full py-1.5 px-3 bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-[11px] rounded-xl flex items-center justify-center gap-1.5 transition-colors cursor-pointer shadow-2xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <span>{{ __('messages.view_complete_details') }}</span>
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
                        <template x-if="selectedRegistration.payment_amount > 0 || selectedRegistration.payment_status === 'paid'">
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Payment</span>
                                <span class="font-extrabold text-[10px] uppercase tracking-wider px-2.5 py-0.5 rounded inline-block mt-0.5"
                                      :class="selectedRegistration.payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-rose-100 text-rose-700 border border-rose-200'"
                                      x-text="'₹' + selectedRegistration.payment_amount + ' (' + (selectedRegistration.payment_status || 'unpaid').toUpperCase() + ')'"></span>
                            </div>
                        </template>
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
