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
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if($members->count() > 0)
                <!-- Top Core Leaders (First 2) -->
                <div class="flex flex-wrap justify-center gap-6 md:gap-8 pb-2">
                    @foreach($members->take(2) as $member)
                        <div class="flex flex-col items-center text-center group max-w-[200px]">
                            <!-- Bordered Photo Container with Designation Overlay Badge -->
                            <div class="relative p-1 bg-white rounded-2xl border-3 border-primary-700/80 shadow-sm transition-all duration-300">
                                <img class="w-32 h-40 md:w-36 md:h-44 object-cover rounded-xl bg-slate-100" 
                                     src="{{ str_starts_with($member->photo_path, 'http') ? $member->photo_path : asset('storage/' . $member->photo_path) }}" 
                                     alt="{{ $member->localized_name }}">
                                
                                <!-- Floating Designation Badge (Supports Long Multi-line Titles) -->
                                <div class="absolute -bottom-3 left-1/2 -translate-x-1/2 w-max max-w-[95%] bg-gradient-to-r from-primary-700 to-primary-600 text-white font-extrabold text-[10px] md:text-[11px] px-2.5 py-0.5 rounded-full shadow-xs border border-white tracking-tight flex items-center justify-center gap-1 text-center leading-tight whitespace-normal z-10">
                                    <span>{{ $member->localized_designation }}</span>
                                </div>
                            </div>

                            <!-- Name Below Photo -->
                            <div class="mt-4 space-y-0.5">
                                <h3 class="text-xs md:text-sm font-black text-slate-900 leading-snug font-gujarati">
                                    {{ $member->localized_name }}
                                </h3>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Other Committee / Management Members (Remaining) -->
                @if($members->count() > 2)
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 md:gap-6 pt-4 border-t border-slate-100">
                        @foreach($members->skip(2) as $member)
                            <div class="flex flex-col items-center text-center group">
                                <!-- Bordered Photo Container with Designation Overlay Badge -->
                                <div class="relative p-1 bg-white rounded-xl border-3 border-primary-700/80 shadow-xs transition-all duration-300 mb-1">
                                    <img class="w-28 h-34 md:w-32 md:h-40 object-cover rounded-lg bg-slate-100" 
                                         src="{{ str_starts_with($member->photo_path, 'http') ? $member->photo_path : asset('storage/' . $member->photo_path) }}" 
                                         alt="{{ $member->localized_name }}">

                                    <!-- Floating Designation Badge (Supports Long Multi-line Titles) -->
                                    <div class="absolute -bottom-3 left-1/2 -translate-x-1/2 w-max max-w-[95%] bg-primary-700 text-white font-extrabold text-[9px] md:text-[10px] px-2 py-0.5 rounded-full shadow-xs border border-white text-center leading-tight whitespace-normal z-10">
                                        {{ $member->localized_designation }}
                                    </div>
                                </div>

                                <!-- Name Below Photo -->
                                <div class="mt-3.5 space-y-0.5">
                                    <h4 class="text-xs font-bold text-slate-900 leading-snug font-gujarati">
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