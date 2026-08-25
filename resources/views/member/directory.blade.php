@extends('layouts.member')

@section('page_title', __('messages.member_directory'))

@section('content')
<div class="space-y-4">
    <!-- Integrated Search & Filter Toolbar -->
    <div class="bg-white p-2.5 rounded-xl border border-slate-100 shadow-xs">
        <form method="GET" action="{{ route('member.directory') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-2.5">
            <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 flex-1 min-w-0">
                <!-- Search Input -->
                <div class="relative flex-1 min-w-[200px] max-w-md">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="{{ __('messages.search_members') }}"
                           class="h-9 w-full pl-8.5 pr-8 text-xs font-semibold rounded-lg bg-slate-50 border border-slate-200 focus:bg-white focus:border-primary-500 focus:outline-none transition-all placeholder-slate-400">
                    <!-- <svg class="h-3.5 w-3.5 text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg> -->
                    @if(request('search') || request('area_id'))
                        <a href="{{ route('member.directory') }}" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-rose-500 text-sm font-bold" title="Clear Filters">&times;</a>
                    @endif
                </div>

                <!-- Search Button -->
                <button type="submit" class="h-9 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-lg transition-colors shrink-0">
                    {{ __('messages.search') }}
                </button>

                <!-- Area Select Filter -->
                @if(isset($areas) && $areas->count() > 0)
                    <div class="w-48 sm:w-56 shrink-0">
                        <select name="area_id" onchange="this.form.submit()" class="h-9 w-full px-3 text-xs font-semibold rounded-lg bg-slate-50 border border-slate-200 focus:bg-white focus:border-primary-500 focus:outline-none transition-all text-slate-700">
                            <option value="">-- {{ __('messages.all_areas') }} --</option>
                            @foreach($areas as $area)
                                <option value="{{ $area->id }}" {{ request('area_id') == $area->id ? 'selected' : '' }}>
                                    {{ $area->name }} {{ $area->pincode ? '(' . $area->pincode . ')' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>

            <!-- Total Count Badge -->
            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-50 border border-slate-200/60 text-xs font-bold text-slate-600 shrink-0 self-start sm:self-center">
                <span>{{ __('messages.total_members') }}:</span>
                <span class="font-black text-primary-600">{{ number_format($members->total()) }}</span>
            </div>
        </form>
    </div>

    <!-- Compact Member Directory Cards Grid -->
    @if($members->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-3.5">
            @foreach($members as $member)
                @php
                    $profile = $member->memberProfile;
                    $fullName = $member->display_name;
                    $phone = $profile->phone ?? 'N/A';
                    $photoPath = $profile && $profile->photo_path 
                        ? (str_starts_with($profile->photo_path, 'http') 
                            ? $profile->photo_path 
                            : asset('storage/' . $profile->photo_path)) 
                        : null;
                    
                    // Format address cleanly without duplication
                    $fullAddress = trim($profile->address ?? '');
                    if (empty($fullAddress)) {
                        $fallbackParts = array_filter([
                            $profile->area->name ?? null,
                            $profile->city ?? null,
                            $profile->pincode ?? null,
                        ]);
                        $fullAddress = implode(', ', $fallbackParts);
                    }
                @endphp

                <div class="bg-white rounded-xl p-3.5 border border-slate-100 shadow-2xs space-y-3 hover:border-slate-300 hover:shadow-xs transition-all duration-200 flex flex-col justify-between group">
                    <!-- Top Header: Photo & Name & Member ID -->
                    <div class="flex items-start gap-3">
                        @if($photoPath)
                            <img src="{{ $photoPath }}" 
                                 alt="{{ $fullName }}" 
                                 class="w-11 h-11 rounded-full object-cover border border-slate-100 bg-slate-50 shrink-0 shadow-2xs">
                        @else
                            <div class="w-11 h-11 rounded-full bg-gradient-to-br from-primary-500 to-rose-600 text-white flex items-center justify-center font-black text-sm shrink-0 shadow-2xs">
                                {{ mb_substr($fullName, 0, 1) }}
                            </div>
                        @endif

                        <div class="space-y-1 flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-1.5 flex-wrap">
                                <span class="text-[10px] sm:text-[10.5px] font-extrabold text-primary-700 bg-primary-50 border border-primary-200/60 px-2 py-0.5 rounded-md tracking-wide shrink-0">
                                    {{ $member->formatted_member_id }}
                                </span>
                                @if(!empty($profile->area->name))
                                    <span class="inline-flex items-center gap-1 text-[10px] sm:text-[10.5px] font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-md shrink-0" title="{{ $profile->area->name }}">
                                        <svg class="w-3 h-3 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        {{ $profile->area->name }}
                                    </span>
                                @elseif(!empty($profile->city))
                                    <span class="inline-flex items-center gap-1 text-[10px] sm:text-[10.5px] font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-md shrink-0" title="{{ $profile->city }}">
                                        <svg class="w-3 h-3 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        {{ $profile->city }}
                                    </span>
                                @endif
                            </div>
                            <h3 class="text-xs sm:text-[13.5px] font-black text-slate-900 leading-snug group-hover:text-primary-600 transition-colors break-words">
                                {{ $fullName }}
                            </h3>
                        </div>
                    </div>

                    <!-- Details Box: Mobile Number & Address -->
                    <div class="bg-slate-50/80 rounded-lg p-2.5 border border-slate-100/80 space-y-1.5 text-xs">
                        <!-- Mobile Number -->
                        <div class="flex items-center justify-between text-slate-700">
                            <div class="flex items-center gap-1.5 min-w-0">
                                <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <a href="tel:{{ $phone }}" class="font-bold text-xs sm:text-[13px] text-slate-800 hover:text-primary-600 truncate transition-colors">
                                    {{ $phone }}
                                </a>
                            </div>
                        </div>

                        <!-- Full Address (No truncation) -->
                        <div class="flex items-start gap-1.5 text-slate-600 pt-0.5">
                            <svg class="w-3.5 h-3.5 text-slate-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="text-[11px] sm:text-xs font-medium text-slate-600 leading-snug break-words">
                                {{ $fullAddress ?: 'N/A' }}
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="pt-2">
            {{ $members->links() }}
        </div>
    @else
        <div class="bg-white rounded-xl p-8 border border-slate-100 text-center space-y-2">
            <h3 class="text-xs font-black text-slate-800">{{ __('messages.no_members_found') }}</h3>
            <p class="text-[11px] text-slate-500 font-medium max-w-sm mx-auto">
                {{ __('messages.try_adjusting_search') }}
            </p>
            @if(request('search'))
                <a href="{{ route('member.directory') }}" class="inline-block px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-lg transition-colors">
                    {{ __('messages.clear') }}
                </a>
            @endif
        </div>
    @endif
</div>
@endsection
