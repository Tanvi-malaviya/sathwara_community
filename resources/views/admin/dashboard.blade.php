@extends('layouts.admin')
 
@section('page_title', __('messages.admin_dashboard_overview'))
 
@section('content')
<div class="space-y-3">
    <!-- Quick Cards Grid (3 Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        {{-- Total Approved Members --}}
        <div class="bg-white rounded-xl p-3.5 border border-slate-100 shadow-sm flex items-center space-x-3 hover:shadow-md transition-shadow">
            <span class="w-11 h-11 bg-primary-50 text-primary-600 rounded-xl shrink-0 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </span>
            <div>
                <span class="text-xl sm:text-2xl font-black text-slate-900 leading-tight block">{{ $stats['total_members'] }}</span>
                <p class="text-[11px] text-slate-500 font-bold uppercase tracking-wider mt-0.5">{{ __('messages.approved_members') }}</p>
            </div>
        </div>
 
        {{-- Pending Member Approvals --}}
        <div class="bg-white rounded-xl p-3.5 border border-slate-100 shadow-sm flex items-center space-x-3 hover:shadow-md transition-shadow">
            <span class="w-11 h-11 bg-amber-50 text-amber-600 rounded-xl shrink-0 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </span>
            <div>
                <span class="text-xl sm:text-2xl font-black text-slate-900 leading-tight block">{{ $stats['pending_members'] }}</span>
                <p class="text-[11px] text-slate-500 font-bold uppercase tracking-wider mt-0.5">{{ __('messages.pending_members') }}</p>
            </div>
        </div>
 
        {{-- Registered Businesses / Shops --}}
        <div class="bg-white rounded-xl p-3.5 border border-slate-100 shadow-sm flex items-center space-x-3 hover:shadow-md transition-shadow">
            <span class="w-11 h-11 bg-emerald-50 text-emerald-600 rounded-xl shrink-0 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </span>
            <div>
                <span class="text-xl sm:text-2xl font-black text-slate-900 leading-tight block">{{ $stats['total_businesses'] }}</span>
                <p class="text-[11px] text-slate-500 font-bold uppercase tracking-wider mt-0.5">{{ __('messages.registered_shops') }}</p>
            </div>
        </div>
    </div>
 
    <!-- Lists Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
        <!-- Recent Registrations -->
        <div class="bg-white rounded-xl p-4 border border-slate-100 shadow-sm space-y-3">
            <div class="flex justify-between items-center border-b border-slate-100 pb-2.5">
                <h3 class="text-xs sm:text-sm font-black text-slate-900">{{ __('messages.recent_member_signups') }}</h3>
                <a href="{{ route('admin.members.index') }}" class="text-[11px] font-bold text-primary-600 hover:text-primary-700 hover:underline">{{ __('messages.manage_all') }} &rarr;</a>
            </div>
            
            <div class="space-y-2">
                @forelse($latestMembers as $m)
                    <div class="flex items-center justify-between p-2.5 bg-slate-50 rounded-xl hover:bg-slate-100/70 transition-colors">
                        <div class="flex items-center space-x-2.5 min-w-0">
                            <span class="w-8 h-8 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </span>
                            <div class="min-w-0">
                                <h4 class="text-xs sm:text-sm font-bold text-slate-900 truncate leading-snug">{{ $m->name }}</h4>
                                <p class="text-[11px] text-slate-400 font-medium truncate mt-0.5">{{ $m->email ?: ($m->mobile ?? '—') }}</p>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase {{ $m->status == 'approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' : 'bg-amber-50 text-amber-700 border border-amber-200/60' }}">
                            {{ __('messages.' . $m->status) }}
                        </span>
                    </div>
                @empty
                    <p class="text-center py-4 text-xs text-slate-400 font-medium">{{ __('messages.no_recent_signups') }}</p>
                @endforelse
            </div>
        </div>
 
        <!-- Recent Businesses -->
        <div class="bg-white rounded-xl p-4 border border-slate-100 shadow-sm space-y-3">
            <div class="flex justify-between items-center border-b border-slate-100 pb-2.5">
                <h3 class="text-xs sm:text-sm font-black text-slate-900">{{ __('messages.recent_business_submissions') }}</h3>
                <a href="{{ route('admin.businesses.index') }}" class="text-[11px] font-bold text-primary-600 hover:text-primary-700 hover:underline">{{ __('messages.manage_all') }} &rarr;</a>
            </div>
 
            <div class="space-y-2">
                @forelse($latestBusinesses as $b)
                    <div class="flex items-center justify-between p-2.5 bg-slate-50 rounded-xl hover:bg-slate-100/70 transition-colors">
                        <div class="flex items-center space-x-2.5 min-w-0">
                            <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </span>
                            <div class="min-w-0">
                                <h4 class="text-xs sm:text-sm font-bold text-slate-900 truncate leading-snug">{{ $b->business_name }}</h4>
                                <p class="text-[11px] text-slate-400 font-medium truncate mt-0.5">{{ __('messages.owner_label', ['owner' => $b->owner_name]) }}</p>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase {{ $b->status == 'approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' : 'bg-amber-50 text-amber-700 border border-amber-200/60' }}">
                            {{ __('messages.' . $b->status) }}
                        </span>
                    </div>
                @empty
                    <p class="text-center py-4 text-xs text-slate-400 font-medium">{{ __('messages.no_recent_businesses') }}</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
