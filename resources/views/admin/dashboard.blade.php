@extends('layouts.admin')
 
@section('page_title', __('messages.admin_dashboard_overview'))
 
@section('content')
<div class="space-y-3">
    <!-- Quick Cards Grid (3 Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        {{-- Total Approved Members --}}
        <div class="bg-white rounded-xl p-3.5 border border-slate-100 shadow-sm flex items-center space-x-3 hover:shadow-md transition-shadow">
            <span class="text-2xl bg-primary-50 text-primary-600 p-2.5 rounded-xl shrink-0 flex items-center justify-center">👤</span>
            <div>
                <span class="text-xl sm:text-2xl font-black text-slate-900 leading-tight block">{{ $stats['total_members'] }}</span>
                <p class="text-[11px] text-slate-500 font-bold uppercase tracking-wider mt-0.5">{{ __('messages.approved_members') }}</p>
            </div>
        </div>
 
        {{-- Pending Member Approvals --}}
        <div class="bg-white rounded-xl p-3.5 border border-slate-100 shadow-sm flex items-center space-x-3 hover:shadow-md transition-shadow">
            <span class="text-2xl bg-amber-50 text-amber-600 p-2.5 rounded-xl shrink-0 flex items-center justify-center">⏳</span>
            <div>
                <span class="text-xl sm:text-2xl font-black text-slate-900 leading-tight block">{{ $stats['pending_members'] }}</span>
                <p class="text-[11px] text-slate-500 font-bold uppercase tracking-wider mt-0.5">{{ __('messages.pending_members') }}</p>
            </div>
        </div>
 
        {{-- Registered Businesses / Shops --}}
        <div class="bg-white rounded-xl p-3.5 border border-slate-100 shadow-sm flex items-center space-x-3 hover:shadow-md transition-shadow">
            <span class="text-2xl bg-emerald-50 text-emerald-600 p-2.5 rounded-xl shrink-0 flex items-center justify-center">💼</span>
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
                            <span class="text-lg">👤</span>
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
                            <span class="text-lg">💼</span>
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
