@extends('layouts.public')

@section('content')
    <!-- Page Banner with Breadcrumbs -->
    @include('partials.page_header', [
        'title' => __('messages.management_desk'),
        'subtitle' => __('messages.words_from_leadership'),
        'breadcrumb' => __('messages.management_desk')
    ])

    <!-- Messages Desk Grid -->
    <section class="py-6 md:py-8 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            @if($members->count() > 0)
                <!-- Top Core Leaders (First 2) -->
                <div class="flex flex-wrap justify-center gap-6 md:gap-10 pb-2">
                    @foreach($members->take(2) as $member)
                        @php $mPhotoUrl = str_starts_with($member->photo_path, 'http') ? $member->photo_path : asset('storage/' . $member->photo_path); @endphp
                        <div class="flex flex-col items-center text-center group max-w-[190px]">
                            <!-- Photo Container with Floating Designation Badge -->
                            <div class="relative inline-flex flex-col items-center justify-center p-0.5">
                                <div class="w-32 md:w-36 h-40 md:h-44 rounded-2xl overflow-hidden bg-slate-950 border border-slate-200/80 shadow-md flex items-center justify-center relative">
                                    <!-- Blurred Backdrop -->
                                    <img src="{{ $mPhotoUrl }}" 
                                         alt="" 
                                         aria-hidden="true"
                                         class="absolute inset-0 w-full h-full pointer-events-none select-none"
                                         style="object-fit: cover; object-position: center; filter: blur(14px) brightness(0.45); transform: scale(1.12); z-index: 0;">

                                    <!-- Full Member Photo (object-contain, never cropped) -->
                                    <img class="relative w-full h-full object-contain transition-transform duration-300 group-hover:scale-105" 
                                         style="z-index: 1;"
                                         src="{{ $mPhotoUrl }}" 
                                         alt="{{ $member->localized_name }}"
                                         onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($member->localized_name ?: 'Member') }}&background=fef2f2&color=dc2626&size=256&bold=true';">
                                </div>
                                
                                <!-- Floating Designation Badge (Supports Long Multi-line Titles) -->
                                <div class="absolute -bottom-3 left-1/2 -translate-x-1/2 w-max max-w-[95%] bg-gradient-to-r from-primary-700 to-primary-600 text-white font-extrabold text-[10px] md:text-[11px] px-2.5 py-0.5 rounded-full shadow-xs border border-white tracking-tight flex items-center justify-center gap-1 text-center leading-tight whitespace-normal z-10">
                                    <span>{{ $member->localized_designation }}</span>
                                </div>
                            </div>

                            <!-- Name Below Photo -->
                            <div class="mt-4 space-y-0.5">
                                <h3 class="text-sm md:text-base font-black text-slate-900 leading-snug font-gujarati group-hover:text-primary-600 transition-colors">
                                    {{ $member->localized_name }}
                                </h3>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Other Committee / Management Members (Remaining) -->
                @if($members->count() > 2)
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6 md:gap-8 pt-6 border-t border-slate-100 justify-items-center">
                        @foreach($members->skip(2) as $member)
                            @php $mPhotoUrl = str_starts_with($member->photo_path, 'http') ? $member->photo_path : asset('storage/' . $member->photo_path); @endphp
                            <div class="flex flex-col items-center text-center group max-w-[170px]">
                                <!-- Photo Container with Floating Designation Badge -->
                                <div class="relative inline-flex flex-col items-center justify-center p-0.5">
                                    <div class="w-28 md:w-32 h-36 md:h-40 rounded-2xl overflow-hidden bg-slate-950 border border-slate-200/80 shadow-md flex items-center justify-center relative">
                                        <!-- Blurred Backdrop -->
                                        <img src="{{ $mPhotoUrl }}" 
                                             alt="" 
                                             aria-hidden="true"
                                             class="absolute inset-0 w-full h-full pointer-events-none select-none"
                                             style="object-fit: cover; object-position: center; filter: blur(14px) brightness(0.45); transform: scale(1.12); z-index: 0;">

                                        <!-- Full Member Photo (object-contain, never cropped) -->
                                        <img class="relative w-full h-full object-contain transition-transform duration-300 group-hover:scale-105" 
                                             style="z-index: 1;"
                                             src="{{ $mPhotoUrl }}" 
                                             alt="{{ $member->localized_name }}"
                                             onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($member->localized_name ?: 'Member') }}&background=fef2f2&color=dc2626&size=256&bold=true';">
                                    </div>

                                    <!-- Floating Designation Badge (Supports Long Multi-line Titles) -->
                                    <div class="absolute -bottom-3 left-1/2 -translate-x-1/2 w-max max-w-[95%] bg-primary-700 text-white font-extrabold text-[9px] md:text-[10px] px-2 py-0.5 rounded-full shadow-xs border border-white text-center leading-tight whitespace-normal z-10">
                                        {{ $member->localized_designation }}
                                    </div>
                                </div>

                                <!-- Name Below Photo -->
                                <div class="mt-4 space-y-0.5">
                                    <h4 class="text-xs md:text-sm font-black text-slate-900 leading-snug font-gujarati group-hover:text-primary-600 transition-colors">
                                        {{ $member->localized_name }}
                                    </h4>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @else
                <div class="text-center py-16 bg-white rounded-3xl border border-slate-200/60 text-slate-400 font-medium text-sm">
                    {{ __('messages.no_desk_messages') }}
                </div>
            @endif
        </div>
    </section>
@endsection