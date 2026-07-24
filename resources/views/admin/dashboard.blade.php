@extends('layouts.admin')
 
@section('page_title', __('messages.admin_dashboard_overview'))
 
@section('content')
<div class="space-y-2">
    <!-- Quick Cards Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
        <div class="bg-white rounded-xl p-4 border border-slate-100 shadow-sm flex items-center space-x-3">
            <span class="text-2xl bg-primary-50 text-primary-500 p-2.5 rounded-xl shrink-0">👤</span>
            <div>
                <span class="text-xl font-black text-slate-900 leading-tight block">{{ $stats['total_members'] }}</span>
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">{{ __('messages.approved_members') }}</p>
            </div>
        </div>
 
        <div class="bg-white rounded-xl p-4 border border-slate-100 shadow-sm flex items-center space-x-3">
            <span class="text-2xl bg-amber-50 text-amber-500 p-2.5 rounded-xl shrink-0">⏳</span>
            <div>
                <span class="text-xl font-black text-slate-900 leading-tight block">{{ $stats['pending_members'] }}</span>
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">{{ __('messages.pending_members') }}</p>
            </div>
        </div>
 
        <div class="bg-white rounded-xl p-4 border border-slate-100 shadow-sm flex items-center space-x-3">
            <span class="text-2xl bg-emerald-50 text-emerald-500 p-2.5 rounded-xl shrink-0">💼</span>
            <div>
                <span class="text-xl font-black text-slate-900 leading-tight block">{{ $stats['total_businesses'] }}</span>
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">{{ __('messages.registered_shops') }}</p>
            </div>
        </div>
 
        <div class="bg-white rounded-xl p-4 border border-slate-100 shadow-sm flex items-center space-x-3">
            <span class="text-2xl bg-rose-50 text-rose-500 p-2.5 rounded-xl shrink-0">🏆</span>
            <div>
                <span class="text-xl font-black text-slate-900 leading-tight block">{{ $stats['pending_awards'] }}</span>
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">{{ __('messages.pending_awards') }}</p>
            </div>
        </div>
    </div>
 
  
 
    <!-- Lists Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-2">
        <!-- Recent Registrations -->
        <div class="bg-white rounded-xl p-4 border border-slate-100 shadow-sm space-y-4">
            <div class="flex justify-between items-center border-b border-slate-50 pb-2.5">
                <h3 class="text-xs font-extrabold text-slate-950">{{ __('messages.recent_member_signups') }}</h3>
                <a href="{{ route('admin.members.index') }}" class="text-[10px] font-bold text-primary-500 hover:underline">{{ __('messages.manage_all') }}</a>
            </div>
            
            <div class="space-y-2">
                @forelse($latestMembers as $m)
                    <div class="flex items-center justify-between p-2.5 bg-slate-50 rounded-xl hover:bg-slate-100/50 transition-colors">
                        <div class="flex items-center space-x-2.5 min-w-0">
                            <span class="text-base">👤</span>
                            <div class="min-w-0">
                                <h4 class="text-xs font-bold text-slate-900 truncate leading-tight">{{ $m->name }}</h4>
                                <p class="text-[9px] text-slate-400 font-medium truncate mt-0.5">{{ $m->email }}</p>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[8px] font-extrabold uppercase {{ $m->status == 'approved' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                            {{ __('messages.' . $m->status) }}
                        </span>
                    </div>
                @empty
                    <p class="text-center py-4 text-xs text-slate-400">{{ __('messages.no_recent_signups') }}</p>
                @endforelse
            </div>
        </div>
 
        <!-- Recent Businesses -->
        <div class="bg-white rounded-xl p-4 border border-slate-100 shadow-sm space-y-4">
            <div class="flex justify-between items-center border-b border-slate-50 pb-2.5">
                <h3 class="text-xs font-extrabold text-slate-950">{{ __('messages.recent_business_submissions') }}</h3>
                <a href="{{ route('admin.businesses.index') }}" class="text-[10px] font-bold text-primary-500 hover:underline">{{ __('messages.manage_all') }}</a>
            </div>
 
            <div class="space-y-2">
                @forelse($latestBusinesses as $b)
                    <div class="flex items-center justify-between p-2.5 bg-slate-50 rounded-xl hover:bg-slate-100/50 transition-colors">
                        <div class="flex items-center space-x-2.5 min-w-0">
                            <span class="text-base">💼</span>
                            <div class="min-w-0">
                                <h4 class="text-xs font-bold text-slate-900 truncate leading-tight">{{ $b->business_name }}</h4>
                                <p class="text-[9px] text-slate-400 font-medium truncate mt-0.5">{{ __('messages.owner_label', ['owner' => $b->owner_name]) }}</p>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[8px] font-extrabold uppercase {{ $b->status == 'approved' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                            {{ __('messages.' . $b->status) }}
                        </span>
                    </div>
                @empty
                    <p class="text-center py-4 text-xs text-slate-400">{{ __('messages.no_recent_businesses') }}</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
