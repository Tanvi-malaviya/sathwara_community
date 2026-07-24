@extends('layouts.admin')

@section('page_title', 'Event Details')

@section('content')
<div class="space-y-2" x-data="{ activeTab: 'all', showDetailsModal: false, showDescModal: false, selectedRegistration: {} }">
    <!-- Top Action Bar -->
    <div class="bg-white p-3.5 rounded-xl border border-slate-100 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.events.index') }}" 
               class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors flex items-center justify-center font-bold text-xs" 
               title="Back to Events">
                &larr;
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-sm font-extrabold text-slate-900 leading-tight">{{ $event->title }}</h1>
                    <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase tracking-wider
                        {{ $event->status == 'published' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' : ($event->status == 'cancelled' ? 'bg-rose-50 text-rose-700 border border-rose-200/60' : 'bg-slate-100 text-slate-600 border border-slate-200') }}">
                        {{ $event->status }}
                    </span>
                </div>
                <p class="text-[11px] text-slate-400 font-medium">Created on {{ $event->created_at->format('d M Y, h:i A') }}</p>
            </div>
        </div>

        <div class="flex items-center gap-1.5 shrink-0 flex-wrap">
            @if($event->registration_option)
                <a href="#registrations-section" 
                   class="px-3 py-1.5 bg-primary-50 text-primary-700 hover:bg-primary-100 border border-primary-200/60 font-extrabold text-xs rounded-lg transition-colors flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span>Registrations ({{ count($registrations) }})</span>
                </a>
            @endif

            <a href="{{ route('admin.events.gallery', $event->id) }}" 
               class="px-3 py-1.5 bg-sky-50 text-sky-700 hover:bg-sky-100 border border-sky-200/60 font-extrabold text-xs rounded-lg transition-colors flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Gallery</span>
            </a>

            <a href="{{ route('admin.events.edit', $event->id) }}" 
               class="px-3 py-1.5 bg-amber-50 text-amber-700 hover:bg-amber-100 border border-amber-200/60 font-extrabold text-xs rounded-lg transition-colors flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                <span>Edit</span>
            </a>

            <button type="button" 
                    @click="$dispatch('confirm-delete', { action: '{{ route('admin.events.destroy', $event->id) }}', message: '{{ __('messages.delete_confirm_event', ['name' => $event->title]) }}' })"
                    class="px-3 py-1.5 bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200/60 font-extrabold text-xs rounded-lg transition-colors flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                <span>Delete</span>
            </button>
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
                            <span class="px-2.5 py-1 rounded-md bg-amber-500 text-white text-[10px] font-black uppercase tracking-wider shadow-xs">🏆 Inam Vitaran</span>
                        @elseif($event->event_type === 'yuva_melo')
                            <span class="px-2.5 py-1 rounded-md bg-purple-600 text-white text-[10px] font-black uppercase tracking-wider shadow-xs">⚡ Yuva Melo</span>
                        @else
                            <span class="px-2.5 py-1 rounded-md bg-slate-800 text-white text-[10px] font-black uppercase tracking-wider shadow-xs">📢 Standard Event</span>
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
                <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2">Event Schedule & Location</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    <!-- Date & Time -->
                    <div class="flex items-start gap-3 p-2.5 bg-slate-50 rounded-lg border border-slate-100">
                        <div class="w-8 h-8 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center shrink-0 text-sm">
                            📅
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase">Date & Time</p>
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
                            <p class="text-[10px] font-bold text-slate-400 uppercase">Venue / Location</p>
                            <p class="font-extrabold text-slate-900 text-xs leading-snug">{{ $event->venue }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description Card -->
            <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm space-y-2 grow">
                <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-400">Description</h3>
                
                <div class="rich-text text-xs text-slate-700 leading-relaxed font-medium">
                    {!! $event->description ?: 'No detailed description provided.' !!}
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
                        <span>Event Registrations</span>
                        <span class="px-2 py-0.5 rounded-full text-xs font-black bg-primary-50 text-primary-700 border border-primary-100">{{ $totalCount }} Submitted</span>
                    </h2>
                    <p class="text-[11px] text-slate-400 font-medium">Review and manage candidate registration applications for this event.</p>
                </div>

                <!-- Status Filter Tabs -->
                <div class="flex items-center gap-1.5 p-1 bg-slate-100/80 rounded-xl border border-slate-200/60 shrink-0">
                    <button type="button" @click="activeTab = 'all'" 
                            :class="activeTab === 'all' ? 'bg-white text-slate-900 shadow-xs font-extrabold border border-slate-200/80' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                            class="px-3 py-1.5 text-xs rounded-lg transition-all flex items-center gap-1.5">
                        <span>All</span>
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700" :class="activeTab === 'all' ? 'bg-slate-200' : ''">{{ $totalCount }}</span>
                    </button>

                    <button type="button" @click="activeTab = 'pending'" 
                            :class="activeTab === 'pending' ? 'bg-white text-amber-700 shadow-xs font-extrabold border border-amber-200/80' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                            class="px-3 py-1.5 text-xs rounded-lg transition-all flex items-center gap-1.5">
                        <span>Pending</span>
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] font-black bg-amber-100 text-amber-800">{{ $pendingCount }}</span>
                    </button>

                    <button type="button" @click="activeTab = 'approved'" 
                            :class="activeTab === 'approved' ? 'bg-white text-emerald-700 shadow-xs font-extrabold border border-emerald-200/80' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                            class="px-3 py-1.5 text-xs rounded-lg transition-all flex items-center gap-1.5">
                        <span>Approved</span>
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800">{{ $approvedCount }}</span>
                    </button>

                    <button type="button" @click="activeTab = 'rejected'" 
                            :class="activeTab === 'rejected' ? 'bg-white text-rose-700 shadow-xs font-extrabold border border-rose-200/80' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                            class="px-3 py-1.5 text-xs rounded-lg transition-all flex items-center gap-1.5">
                        <span>Rejected</span>
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] font-black bg-rose-100 text-rose-800">{{ $rejectedCount }}</span>
                    </button>
                </div>
            </div>

            <!-- Registrations Table -->
            <div class="border border-slate-100 rounded-xl overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-[10px] font-extrabold uppercase text-slate-400 tracking-wider border-b border-slate-100">
                            <th class="py-2.5 px-4">Member Name</th>
                            <th class="py-2.5 px-4">Submitted Details</th>
                            <th class="py-2.5 px-4">Contact Info</th>
                            <th class="py-2.5 px-4">City</th>
                            <th class="py-2.5 px-4">Registered Date</th>
                            <th class="py-2.5 px-4">Status</th>
                            <th class="py-2.5 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs font-semibold text-slate-700">
                        @forelse($registrations as $reg)
                            <tr x-show="activeTab === 'all' || activeTab === '{{ $reg->status }}'" class="hover:bg-slate-50/60 transition-colors">
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
                                                'status' => $reg->status,
                                                'form_data' => $reg->form_data ?? [],
                                                'approve_url' => route('admin.events.registrations.approve', $reg->id),
                                                'reject_url' => route('admin.events.registrations.reject', $reg->id),
                                            ]) }}; showDetailsModal = true" 
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-bold transition-all border border-slate-200/80 shadow-2xs">
                                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.573 16.49 16.638 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"></path>
                                        </svg>
                                        <span>View Form Details</span>
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
                                <td class="py-3 px-4 whitespace-nowrap">
                                    @if($reg->status === 'approved')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase">
                                            Approved
                                        </span>
                                    @elseif($reg->status === 'rejected')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[10px] font-extrabold bg-rose-50 text-rose-700 border border-rose-200 uppercase">
                                            Rejected
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[10px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200 uppercase">
                                            Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        @if($reg->status !== 'approved')
                                            <form method="POST" action="{{ route('admin.events.registrations.approve', $reg->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" title="Approve Registration" 
                                                        class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 hover:text-emerald-800 transition-colors flex items-center justify-center">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                        @if($reg->status !== 'rejected')
                                            <form method="POST" action="{{ route('admin.events.registrations.reject', $reg->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" title="Reject Registration" 
                                                        class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 hover:text-rose-800 transition-colors flex items-center justify-center">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-10 text-center text-slate-400 font-medium">
                                    No registrations submitted for this event yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Applicant Email</span>
                            <span class="font-bold text-slate-800 text-[11px]" x-text="selectedRegistration.email"></span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Contact Number</span>
                            <span class="font-bold text-slate-800 text-[11px]" x-text="selectedRegistration.phone"></span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">City</span>
                            <span class="font-bold text-slate-800 text-[11px]" x-text="selectedRegistration.city"></span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Application Status</span>
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
                        <h4 class="text-[11px] font-black text-slate-400 uppercase tracking-wider">Candidate Form Details:</h4>
                        
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
                                        <span>✓ Approve Application</span>
                                    </button>
                                </form>
                            </template>
                            <template x-if="selectedRegistration.status !== 'rejected'">
                                <form method="POST" :action="selectedRegistration.reject_url">
                                    @csrf
                                    <button type="submit" 
                                            class="px-3.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white font-extrabold text-xs rounded-xl shadow-xs transition-colors flex items-center gap-1">
                                        <span>✕ Reject Application</span>
                                    </button>
                                </form>
                            </template>
                        </div>

                        <button type="button" @click="showDetailsModal = false" 
                                class="px-4 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-colors">
                            Close
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
                        <p class="text-[11px] text-slate-400 font-medium mt-0.5">Full Event Description</p>
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
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
@endsection
