@extends('layouts.member')
 
@section('page_title', __('messages.membership_card'))

@php
    $logoSetting = \App\Models\Setting::get('website_logo');
    $logoUrl = $logoSetting ? asset('storage/' . $logoSetting) : asset('logo.png');
    $hasRealPhoto = (!empty($profile->photo_path) && $profile->photo_path !== 'NOT_SPECIFIED' && $profile->photo_path !== 'N/A');
    $userPhoto = $hasRealPhoto 
        ? (str_starts_with($profile->photo_path, 'http') ? $profile->photo_path : asset('storage/' . $profile->photo_path)) 
        : $logoUrl;
    $memberCode = $user->member_code ?: $user->formatted_member_id;
    $bloodGroup = (!empty($profile->blood_group) && !in_array($profile->blood_group, ['NOT_SPECIFIED', 'N/A', '-'])) ? $profile->blood_group : '-';
@endphp
 
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
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2.5 no-print">
        <p class="text-xs text-slate-500">{{ __('messages.membership_card_preview_desc') }}</p>
        <button onclick="window.print()" class="inline-flex items-center justify-center px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-sm transition-colors shrink-0 cursor-pointer">
             {{ __('messages.print_membership_card') }}
        </button>
    </div>
 
    <!-- Beautiful Card Container (Original Card Size with Increased Font Sizes) -->
    <div id="id-card-print" class="max-w-md w-full mx-auto bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white rounded-3xl p-5 sm:p-6 shadow-xl border border-slate-700/50 relative overflow-hidden min-h-[300px] flex flex-col justify-between">
        
        <!-- Watermark / Background accents -->
        <div class="absolute -right-16 -top-16 w-40 h-40 bg-primary-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-16 -bottom-16 w-40 h-40 bg-secondary-500/10 rounded-full blur-3xl pointer-events-none"></div>
 
        <!-- Card Header -->
        <div class="flex justify-between items-center border-b border-white/10 pb-2.5">
            <div class="flex items-center space-x-2">
                <div class="w-6 h-6 rounded-md bg-primary-500 flex items-center justify-center text-white font-black text-xs">
                    S
                </div>
                <span class="font-black text-xs sm:text-sm tracking-wider">{{ __('messages.satwara_community') }}</span>
            </div>
            <span class="text-[10px] font-black text-primary-400 bg-primary-400/10 px-2.5 py-0.5 rounded uppercase tracking-wider">{{ __('messages.official_card') }}</span>
        </div>
 
        <!-- Card Body -->
        <div class="flex gap-4 sm:gap-5 items-center flex-grow py-3">
            <!-- User Photo / Community Logo Fallback -->
            @php
                $hasValidPhoto = (!empty($profile->photo_path) && !str_contains($profile->photo_path, 'unsplash.com') && $profile->photo_path !== 'NOT_SPECIFIED' && $profile->photo_path !== 'N/A');
                $photoUrl = $hasValidPhoto ? (str_starts_with($profile->photo_path, 'http') ? $profile->photo_path : asset('storage/' . $profile->photo_path)) : asset('logo.png');
            @endphp
            @if($hasValidPhoto)
                <img class="w-24 h-28 rounded-xl object-cover bg-slate-800 border border-white/10 shadow-inner shrink-0" 
                     src="{{ $photoUrl }}" 
                     alt="{{ $profile->first_name }}">
            @else
                <div class="w-24 h-28 rounded-xl bg-slate-800/90 border border-white/10 shadow-inner shrink-0 flex items-center justify-center p-3">
                    <img class="w-16 h-16 object-contain filter drop-shadow-md" 
                         src="{{ asset('logo.png') }}" 
                         alt="Community Logo">
                </div>
            @endif
 
            <!-- Information list -->
            <div class="space-y-2 text-xs font-semibold text-slate-300 min-w-0 flex-1">
                <div>
                    <h3 class="text-sm sm:text-base font-black text-white truncate leading-tight">{{ $profile->first_name }} {{ $profile->last_name }}</h3>
                    <p class="text-xs text-primary-400 mt-0.5 font-bold font-mono tracking-wide">{{ __('messages.member_id') }}: {{ $memberCode }}</p>
                </div>
 
                <!-- 2-Row Grid with Proper Space -->
                <div class="grid grid-cols-2 gap-x-4 gap-y-3 pt-1">
                    <div class="space-y-0.5">
                        <span class="text-[9px] text-slate-400 font-bold uppercase block tracking-wider leading-none">{{ __('messages.birth_date') }}</span>
                        <span class="text-xs font-bold text-white block leading-tight">{{ (!empty($profile->dob) && $profile->dob !== '1970-01-01') ? date('d M, Y', strtotime($profile->dob)) : '-' }}</span>
                    </div>
                    <div class="space-y-0.5">
                        <span class="text-[9px] text-slate-400 font-bold uppercase block tracking-wider leading-none">{{ __('messages.blood_group') }}</span>
                        <span class="text-xs font-black text-rose-400 block leading-tight">{{ $bloodGroup }}</span>
                    </div>
                    <div class="space-y-0.5">
                        <span class="text-[9px] text-slate-400 font-bold uppercase block tracking-wider leading-none">{{ __('messages.contact_phone') }}</span>
                        <span class="text-xs font-bold text-white block leading-tight">{{ $profile->phone ?: '-' }}</span>
                    </div>
                    <div class="space-y-0.5">
                        <span class="text-[9px] text-slate-400 font-bold uppercase block tracking-wider leading-none">{{ __('messages.city_location') }}</span>
                        <span class="text-xs font-bold text-white truncate block leading-tight">{{ $profile->city ?: '-' }}</span>
                    </div>
                </div>
            </div>
        </div>
 
        <!-- Card Footer -->
        <div class="border-t border-white/10 pt-2 flex justify-between items-center text-[9px] sm:text-[10px] text-slate-400 font-medium">
            <span>{{ __('messages.issue_date') }}: <strong class="text-slate-300">{{ $user->created_at->format('d-M-Y') }}</strong></span>
            <span class="italic font-bold text-slate-300">{{ __('messages.united_we_stand') }}</span>
        </div>
    </div>
</div>
@endsection