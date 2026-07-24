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
                    <span class="px-2 py-0.5 rounded bg-purple-50 text-purple-700 border border-purple-200 text-[10px] font-black uppercase">⚡ Yuva Melo</span>
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
                📊 <span>Export Excel</span>
            </a>
        </div>
    </div>

    <!-- Registrations table -->
    <div class="bg-white border border-slate-100 rounded-xl overflow-hidden shadow-sm">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-[10px] font-extrabold uppercase text-slate-400 tracking-wider border-b border-slate-100">
                    <th class="py-2.5 px-4 text-center w-16">Select</th>
                    <th class="py-2.5 px-4">{{ __('messages.member_name') }}</th>
                    <th class="py-2.5 px-4">{{ __('messages.submitted_details') }}</th>
                    <th class="py-2.5 px-4">{{ __('messages.contact_info') }}</th>
                    <th class="py-2.5 px-4">{{ __('messages.city') }}</th>
                    <th class="py-2.5 px-4">{{ __('messages.registered_date') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-xs font-semibold text-slate-700">
                @forelse($registrations as $reg)
                    <tr x-show="(activeTab === 'all' || (activeTab === 'selected' && {{ $reg->is_selected ? 'true' : 'false' }})) && 
                                (!search || 
                                 '{{ addslashes(strtolower($reg->user->name)) }}'.includes(search.toLowerCase()) || 
                                 '{{ addslashes(strtolower($reg->user->email)) }}'.includes(search.toLowerCase()) || 
                                 '{{ addslashes(strtolower($reg->form_data['contact_number'] ?? ($reg->user->memberProfile->phone ?? ''))) }}'.includes(search.toLowerCase()) || 
                                 '{{ addslashes(strtolower($reg->user->memberProfile->city ?? '')) }}'.includes(search.toLowerCase()))" 
                        class="hover:bg-slate-50/60 transition-colors">
                        <td class="py-3 px-4 text-center whitespace-nowrap">
                            <form method="POST" action="{{ route('admin.events.registrations.toggle_select', $reg->id) }}">
                                @csrf
                                <input type="checkbox" name="is_selected" value="1" {{ $reg->is_selected ? 'checked' : '' }} onchange="this.form.submit()" class="rounded border-slate-300 text-primary-600 focus:ring-primary-500 w-4.5 h-4.5 cursor-pointer">
                            </form>
                        </td>
                        <td class="py-3 px-4 text-slate-900 font-bold whitespace-nowrap">
                            {{ $reg->user->name }}
                        </td>
                        <td class="py-3 px-4">
                            <button type="button" 
                                    @click="selectedRegistration = {{ json_encode([
                                        'id' => $reg->id,
                                        'user_name' => $reg->user->name,
                                        'email' => $reg->user->email,
                                        'phone' => $reg->form_data['contact_number'] ?? ($reg->user->memberProfile->phone ?? 'N/A'),
                                        'city' => $reg->user->memberProfile->city ?? 'N/A',
                                        'date' => $reg->created_at->format('d-M-Y h:i A'),
                                        'is_selected' => $reg->is_selected,
                                        'form_data' => $reg->form_data ?? [],
                                    ]) }}; showDetailsModal = true" 
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-bold transition-all border border-slate-200/80 shadow-2xs">
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.573 16.49 16.638 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"></path>
                                </svg>
                                <span>{{ __('messages.view_form_details') }}</span>
                            </button>
                        </td>
                        <td class="py-3 px-4 space-y-0.5">
                            <div class="text-slate-900 font-bold text-[11px]">{{ $reg->form_data['contact_number'] ?? ($reg->user->memberProfile->phone ?? 'N/A') }}</div>
                            <div class="text-slate-400 text-[10px] truncate max-w-[150px]">{{ $reg->user->email }}</div>
                        </td>
                        <td class="py-3 px-4 text-slate-600 font-medium whitespace-nowrap">
                            {{ $reg->user->memberProfile->city ?? 'N/A' }}
                        </td>
                        <td class="py-3 px-4 text-slate-500 font-medium text-[11px] whitespace-nowrap">
                            {{ $reg->created_at->format('d-M-Y') }}
                            <span class="block text-[10px] text-slate-400 font-normal">{{ $reg->created_at->format('h:i A') }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400 font-medium">
                            {{ __('messages.no_registrations_found') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Registration Details Pop-up Modal -->
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
                        <h3 class="text-sm font-extrabold" x-text="selectedRegistration.user_name + ' - ' + '{{ __('messages.submitted_details') }}'"></h3>
                        <p class="text-[11px] text-slate-400 font-medium mt-0.5" x-text="'{{ __('messages.registered_date') }}: ' + (selectedRegistration.date || '')"></p>
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
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">{{ __('messages.contact_info') }}</span>
                            <span class="font-bold text-slate-800 text-[11px]" x-text="selectedRegistration.phone"></span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">{{ __('messages.city') }}</span>
                            <span class="font-bold text-slate-800 text-[11px]" x-text="selectedRegistration.city"></span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Selection Status</span>
                            <span class="font-extrabold text-[10px] uppercase tracking-wider px-2 py-0.5 rounded inline-block mt-0.5"
                                  :class="selectedRegistration.is_selected ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-slate-100 text-slate-800'" 
                                  x-text="selectedRegistration.is_selected ? 'Selected' : 'Not Selected'"></span>
                        </div>
                    </div>

                    <!-- Form Data Grid -->
                    <div class="space-y-2">
                        <h4 class="text-[11px] font-black text-slate-400 uppercase tracking-wider">{{ __('messages.candidate_form_details') }}</h4>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 max-h-64 overflow-y-auto pr-1">
                            <template x-for="(val, key) in (selectedRegistration.form_data || {})" :key="key">
                                <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block" x-text="key.replace(/_/g, ' ')"></span>
                                    <template x-if="key === 'marksheet_url' && val">
                                        <a :href="val" target="_blank" class="text-xs font-extrabold text-primary-600 hover:text-primary-700 hover:underline inline-flex items-center gap-1 mt-1">
                                            <span>📄 {{ __('messages.open_marksheet_file') }} &rarr;</span>
                                        </a>
                                    </template>
                                    <template x-if="key !== 'marksheet_url'">
                                        <span class="font-bold text-slate-800 text-xs block mt-0.5" x-text="val"></span>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Footer Action Buttons -->
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-end">
                        <button type="button" @click="showDetailsModal = false" 
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
