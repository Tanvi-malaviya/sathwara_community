@extends('layouts.member')
 
@section('page_title', __('messages.membership_card'))
 
@section('content')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #id-card-print, #id-card-print * {
            visibility: visible;
        }
        #id-card-print {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%) scale(1.2);
        }
        .no-print {
            display: none !important;
        }
    }
</style>
 
<div class="space-y-6">
    <!-- Utility Buttons -->
    <div class="flex justify-between items-center no-print">
        <p class="text-xs text-slate-500">{{ __('messages.membership_card_preview_desc') }}</p>
        <button onclick="window.print()" class="inline-flex items-center justify-center px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-sm transition-colors">
            🖨️ {{ __('messages.print_membership_card') }}
        </button>
    </div>
 
    <!-- Beautiful Card Container -->
    <div id="id-card-print" class="max-w-md w-full mx-auto bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white rounded-3xl p-6 shadow-xl border border-slate-700/50 relative overflow-hidden h-72 flex flex-col justify-between">
        
        <!-- Watermark / Background accents -->
        <div class="absolute -right-16 -top-16 w-40 h-40 bg-primary-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-16 -bottom-16 w-40 h-40 bg-secondary-500/10 rounded-full blur-3xl pointer-events-none"></div>
 
        <!-- Card Header -->
        <div class="flex justify-between items-center border-b border-white/10 pb-3">
            <div class="flex items-center space-x-2">
                <div class="w-6 h-6 rounded-md bg-primary-500 flex items-center justify-center text-white font-extrabold text-[11px]">
                    S
                </div>
                <span class="font-extrabold text-xs tracking-wider">{{ __('messages.sathwara_community') }}</span>
            </div>
            <span class="text-[9px] font-black text-primary-400 bg-primary-400/10 px-2 py-0.5 rounded uppercase tracking-widest">{{ __('messages.official_card') }}</span>
        </div>
 
        <!-- Card Body -->
        <div class="flex gap-6 items-center flex-grow py-4">
            <!-- User Photo -->
            <img class="w-24 h-28 rounded-xl object-cover bg-slate-800 border border-white/10 shadow-inner shrink-0" 
                 src="{{ $profile->photo_path ? (str_starts_with($profile->photo_path, 'http') ? $profile->photo_path : asset('storage/' . $profile->photo_path)) : 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?q=80&w=100' }}" 
                 alt="User avatar">
 
            <!-- Information list -->
            <div class="space-y-2 text-[10px] font-semibold text-slate-300 min-w-0">
                <div>
                    <h3 class="text-sm font-black text-white truncate leading-tight">{{ $profile->first_name }} {{ $profile->last_name }}</h3>
                    <p class="text-[9px] text-primary-400 mt-0.5 font-bold">{{ __('messages.member_id') }}: #{{ sprintf('%05d', $user->id) }}</p>
                </div>
 
                <div class="grid grid-cols-2 gap-x-4 gap-y-1.5 pt-1">
                    <div>
                        <span class="text-[8px] text-slate-500 uppercase block tracking-wider">{{ __('messages.birth_date') }}</span>
                        <span class="text-white">{{ date('d M, Y', strtotime($profile->dob)) }}</span>
                    </div>
                    <div>
                        <span class="text-[8px] text-slate-500 uppercase block tracking-wider">{{ __('messages.blood_group') }}</span>
                        <span class="text-white">{{ $profile->blood_group ?? 'O+ve' }}</span>
                    </div>
                    <div>
                        <span class="text-[8px] text-slate-500 uppercase block tracking-wider">{{ __('messages.contact_phone') }}</span>
                        <span class="text-white">{{ $profile->phone }}</span>
                    </div>
                    <div>
                        <span class="text-[8px] text-slate-500 uppercase block tracking-wider">{{ __('messages.city_location') }}</span>
                        <span class="text-white">{{ $profile->city }}</span>
                    </div>
                </div>
            </div>
        </div>
 
        <!-- Card Footer -->
        <div class="border-t border-white/10 pt-2 flex justify-between items-center text-[8px] text-slate-400">
            <span>{{ __('messages.issue_date') }}: {{ $user->created_at->format('d-M-Y') }}</span>
            <span class="italic font-medium">{{ __('messages.united_we_stand') }}</span>
        </div>
    </div>
</div>
@endsection
