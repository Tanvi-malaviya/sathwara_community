@extends('layouts.member')

@section('page_title', __('messages.dashboard_overview'))

@section('content')
<div class="space-y-2">
    <!-- Welcome Header card -->
    {{-- <div class="bg-gradient-to-tr from-primary-500 to-secondary-500 rounded-xl p-8 text-white flex flex-col md:flex-row justify-between items-center gap-2 shadow-md">
        <div class="space-y-2">
            <h2 class="text-2xl md:text-3xl font-black">{{ __('messages.welcome_back', ['name' => $user->name]) }}</h2>
            <p class="text-xs text-primary-100 font-medium">{{ __('messages.profile_approved_desc') }}</p>
        </div>
        <a href="{{ route('member.card') }}" class="px-5 py-3 bg-white text-primary-600 hover:bg-slate-50 font-bold text-xs rounded-xl shadow-md transition-transform hover:-translate-y-0.5 inline-flex items-center gap-1.5 shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z" />
            </svg>
            <span>{{ __('messages.view_membership_card') }}</span>
        </a>
    </div> --}}

    <!-- Stats Grid -->
    {{-- <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
        <div class="bg-white rounded-xl p-6 border border-slate-100 shadow-sm flex items-center space-x-4">
            <span class="w-12 h-12 bg-primary-50 text-primary-500 rounded-xl flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                </svg>
            </span>
            <div>
                <span class="text-2xl font-black text-slate-900 leading-tight block">{{ $familyCount }}</span>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-1">{{ __('messages.declared_family_members') }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl p-6 border border-slate-100 shadow-sm flex items-center space-x-4">
            <span class="w-12 h-12 bg-secondary-50 text-secondary-500 rounded-xl flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                </svg>
            </span>
            <div>
                <span class="text-2xl font-black text-slate-900 leading-tight block">{{ $registeredEvents->count() }}</span>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-1">{{ __('messages.registered_meetings') }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl p-6 border border-slate-100 shadow-sm flex items-center space-x-4">
            <span class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                </svg>
            </span>
            <div>
                <span class="text-xl font-black text-emerald-600 leading-tight block uppercase tracking-wide">{{ $user->status === 'approved' ? 'Approved' : ucfirst($user->status) }}</span>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-1">Membership Status</p>
            </div>
        </div>
    </div> --}}

    <!-- Details Columns -->
    {{-- <div class="grid grid-cols-1 lg:grid-cols-3 gap-2">
        <!-- Profile Sheet -->
        <div class="bg-white rounded-xl p-6 border border-slate-100 shadow-sm space-y-6 lg:col-span-2">
            <div class="flex justify-between items-center border-b border-slate-50 pb-4">
                <h3 class="text-sm font-extrabold text-slate-950">{{ __('messages.your_profile_sheet') }}</h3>
                <a href="{{ route('member.profile.edit') }}" class="text-xs font-bold text-primary-500 hover:underline">{{ __('messages.edit_info') }}</a>
            </div>
 
            @if($profile)
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs text-slate-700 leading-relaxed">
                    <div>
                        <h4 class="text-[10px] text-slate-400 font-extrabold uppercase tracking-wide">{{ __('messages.gender_dob') }}</h4>
                        <p class="font-bold text-slate-900 mt-1">{{ $profile->gender }} ({{ date('d-M-Y', strtotime($profile->dob)) }})</p>
                    </div>
                    <div>
                        <h4 class="text-[10px] text-slate-400 font-extrabold uppercase tracking-wide">{{ __('messages.blood_group') }}</h4>
                        <p class="font-bold text-slate-900 mt-1">{{ $profile->blood_group ?? 'Not Specified' }}</p>
                    </div>
                    <div>
                        <h4 class="text-[10px] text-slate-400 font-extrabold uppercase tracking-wide">{{ __('messages.education_qualification') }}</h4>
                        <p class="font-bold text-slate-900 mt-1">{{ $profile->education ?? 'Not Specified' }}</p>
                    </div>
                    <div>
                        <h4 class="text-[10px] text-slate-400 font-extrabold uppercase tracking-wide">{{ __('messages.occupation_details') }}</h4>
                        <p class="font-bold text-slate-900 mt-1">{{ $profile->occupation ?? 'Not Specified' }}</p>
                    </div>
                    <div>
                        <h4 class="text-[10px] text-slate-400 font-extrabold uppercase tracking-wide">{{ __('messages.phone_whatsapp') }}</h4>
                        <p class="font-bold text-slate-900 mt-1">{{ $profile->phone }} {{ $profile->whatsapp ? '(WA: '.$profile->whatsapp.')' : '' }}</p>
                    </div>
                    <div>
                        <h4 class="text-[10px] text-slate-400 font-extrabold uppercase tracking-wide">{{ __('messages.member_email_id') }}</h4>
                        <p class="font-bold text-slate-900 mt-1">{{ $user->email ?? auth()->user()->email }}</p>
                    </div>
                    @if($profile->father_member_id)
                    <div>
                        <h4 class="text-[10px] text-slate-400 font-extrabold uppercase tracking-wide">{{ __('messages.father_member_id') }}</h4>
                        <p class="font-bold text-slate-900 mt-1">
                            {{ $profile->father_member_id }}
                            @if($profile->father_user)
                                <span class="text-xs text-emerald-600 font-semibold block sm:inline">({{ $profile->father_user->name }})</span>
                            @endif
                        </p>
                    </div>
                    @endif
                    <div>
                        <h4 class="text-[10px] text-slate-400 font-extrabold uppercase tracking-wide">{{ __('messages.full_address') }}</h4>
                        <p class="font-bold text-slate-900 mt-1">{{ $profile->address }}, {{ $profile->city }} - {{ $profile->pincode }}</p>
                    </div>
                </div>
            @else
                <div class="text-center py-6 text-xs text-slate-400">
                    {{ __('messages.no_profile_details') }}
                </div>
            @endif
        </div>

        <!-- Upcoming Registered Events -->
        <div class="bg-white rounded-xl p-6 border border-slate-100 shadow-sm space-y-6">
            <h3 class="text-sm font-extrabold text-slate-950 border-b border-slate-50 pb-4">{{ __('messages.registered_events') }}</h3>
            <div class="space-y-4">
                @forelse($registeredEvents as $event)
                    <div class="flex items-start space-x-3 p-3 rounded-xl hover:bg-slate-50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-12h12c.621 0 1.125.504 1.125 1.125V17c0 .621-.504 1.125-1.125 1.125h-12A1.125 1.125 0 013 17V7c0-.621.504-1.125 1.125-1.125zm.621 3.75a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm0 4.5a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0z" />
                        </svg>
                        <div class="min-w-0 space-y-1">
                            <h4 class="text-xs font-bold text-slate-900 truncate">{{ $event->title }}</h4>
                            <p class="text-[10px] text-slate-400 font-semibold">{{ date('d M, Y', strtotime($event->date)) }} @ {{ $event->time }}</p>
                            <p class="text-[10px] text-slate-500 truncate flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25s-7.5-4.108-7.5-11.25a7.5 7.5 0 1115 0z" />
                                </svg>
                                <span>{{ $event->venue }}</span>
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 text-xs text-slate-400 leading-relaxed">
                        {{ __('messages.no_upcoming_events') }}<br>
                        <a href="{{ route('events') }}" class="text-primary-500 font-bold hover:underline inline-flex items-center gap-1 mt-2">
                            <span>{{ __('messages.register_for_active_events') }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div> --}}

    <!-- Family Tree Section -->
    <div class="bg-white rounded-xl p-6 border border-slate-100 shadow-sm space-y-4">
        <div class="flex items-center justify-between border-b border-slate-50 pb-4">
            <div class="flex items-center space-x-2.5">
                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 font-black flex items-center justify-center text-base border border-emerald-100">
                    🌳
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-slate-950">{{ __('messages.family_tree_title') }}</h3>
                    <p class="text-[10px] text-slate-400 font-medium">{{ __('messages.family_tree_subtitle') }}</p>
                </div>
            </div>
            {{-- <a href="{{ route('member.family.index') }}" class="px-3.5 py-1.5 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 font-bold text-xs rounded-xl border border-emerald-200/60 transition-all inline-flex items-center gap-1.5 shrink-0">
                <span>+ {{ __('messages.add_family_member') }}</span>
            </a> --}}
        </div>

        @if(isset($family) && $family->count() > 0)
            @php
                $wives = $family->filter(fn($m) => in_array($m->relationship, ['Wife', 'Spouse', 'પત્ની']));
                $husbands = $family->filter(fn($m) => in_array($m->relationship, ['Husband', 'Spouse', 'पति']));
                $spouses = $wives->concat($husbands);
                $sons = $family->filter(fn($m) => in_array($m->relationship, ['Son', 'Son (દીકરો)', 'દીકરો']));
                $daughters = $family->filter(fn($m) => in_array($m->relationship, ['Daughter', 'Daughter (દીકરી)', 'દીકરી']));
                $children = $sons->concat($daughters);
                $inlaws = $family->filter(fn($m) => in_array($m->relationship, ['Daughter-in-law', 'Son-in-law', 'વહુ', 'જમાઈ']));
                $grandchildren = $family->filter(fn($m) => in_array($m->relationship, ["Grandson (Son's Son)", "Granddaughter (Son's Daughter)", "Grandson (Daughter's Son)", "Granddaughter (Daughter's Daughter)", 'પૌત્ર', 'પૌત્રી', 'દોહિત્ર', 'દોહિત્રી']));
                $others = $family->reject(fn($m) => 
                    in_array($m->relationship, ['Wife', 'Husband', 'Spouse', 'પત્ની', 'पति', 'Son', 'Daughter', 'Son (દીકરો)', 'Daughter (દીકરી)', 'દીકરો', 'દીકરી', 'Daughter-in-law', 'Son-in-law', 'વહુ', 'જમાઈ', "Grandson (Son's Son)", "Granddaughter (Son's Daughter)", "Grandson (Daughter's Son)", "Granddaughter (Daughter's Daughter)", 'પૌત્ર', 'પૌત્રી', 'દોહિત્ર', 'દોહિત્રી'])
                );
            @endphp

            <div class="overflow-x-auto p-4 bg-slate-50/60 rounded-2xl border border-slate-100/90">
                <div class="w-full min-w-[320px] flex flex-col items-center">
                    
                    <!-- PRIMARY MEMBER & SPOUSE -->
                    <div class="flex flex-col items-center">
                        <div class="flex items-center justify-center space-x-2">
                            <!-- Primary Member Card -->
                            <div class="bg-primary-50/90 border-2 border-primary-400 rounded-xl px-3 py-2 text-center shadow-xs min-w-[110px] max-w-[140px]">
                                <h4 class="text-xs font-black text-slate-900 truncate flex items-center justify-center gap-1" title="{{ $user->name }}">
                                    <span>👤</span> {{ $user->name }}
                                </h4>
                                <span class="text-[8px] font-bold text-primary-700 bg-white px-1.5 py-0.5 rounded border border-primary-200 inline-block mt-0.5">
                                    Head (#{{ sprintf('%05d', $user->id) }})
                                </span>
                            </div>

                            @foreach($spouses as $spouse)
                                <!-- Marriage Line Connection -->
                                <div class="flex items-center space-x-0.5 px-0.5">
                                    <div style="width: 12px; height: 2px; background-color: #f43f5e;"></div>
                                    <span class="text-[10px]">💖</span>
                                    <div style="width: 12px; height: 2px; background-color: #f43f5e;"></div>
                                </div>

                                <!-- Spouse Card -->
                                <div class="bg-rose-50/80 border border-rose-300 rounded-xl px-3 py-2 text-center shadow-xs min-w-[110px] max-w-[140px]">
                                    <h4 class="text-xs font-bold text-slate-900 truncate flex items-center justify-center gap-1" title="{{ $spouse->name }}">
                                        <span>{{ in_array($spouse->relationship, ['Husband', 'Spouse', 'पति']) && $spouse->gender != 'Female' ? '👨‍💼' : '👩' }}</span> {{ $spouse->name }}
                                    </h4>
                                    <span class="text-[8px] font-bold text-rose-700 bg-white px-1.5 py-0.5 rounded border border-rose-200 inline-block mt-0.5">
                                        {{ $spouse->relationship }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- CHILDREN BRANCH -->
                    @if($children->count() > 0)
                    <div class="flex flex-col items-center w-full relative">
                        <div style="width: 2.5px; height: 16px; background-color: #64748b;"></div>

                        <div class="flex justify-center items-start w-full relative">
                            @foreach($children as $child)
                                @php
                                    $childDependents = $family->filter(fn($m) => $m->parent_id == $child->id);
                                @endphp
                                <div class="flex flex-col items-center relative px-2">
                                    @if($children->count() > 1)
                                        @if(!$loop->first)
                                            <div class="absolute top-0 left-0 w-1/2" style="height: 2.5px; background-color: #64748b;"></div>
                                        @endif
                                        @if(!$loop->last)
                                            <div class="absolute top-0 right-0 w-1/2" style="height: 2.5px; background-color: #64748b;"></div>
                                        @endif
                                    @endif

                                    <div class="z-10" style="width: 2.5px; height: 14px; background-color: #64748b;"></div>

                                    <div class="bg-indigo-50/70 border border-indigo-300 rounded-xl px-2.5 py-1.5 text-center shadow-xs min-w-[100px] max-w-[130px] z-10">
                                        <h5 class="text-xs font-bold text-slate-900 truncate flex items-center justify-center gap-1" title="{{ $child->name }}">
                                            <span>{{ $child->gender == 'Female' ? '👧' : '👦' }}</span> {{ $child->name }}
                                        </h5>
                                        <span class="text-[8px] font-bold text-indigo-700 bg-white px-1.5 py-0.5 rounded border border-indigo-200 inline-block mt-0.5">
                                            {{ $child->relationship }}
                                        </span>
                                    </div>

                                    @if($childDependents->count() > 0)
                                        <div class="flex flex-col items-center w-full relative">
                                            <div style="width: 2.5px; height: 16px; background-color: #64748b;"></div>

                                            <div class="flex justify-center items-start relative w-full">
                                                @foreach($childDependents as $dep)
                                                    <div class="flex flex-col items-center relative px-1">
                                                        @if($childDependents->count() > 1)
                                                            @if(!$loop->first)
                                                                <div class="absolute top-0 left-0 w-1/2" style="height: 2.5px; background-color: #64748b;"></div>
                                                            @endif
                                                            @if(!$loop->last)
                                                                <div class="absolute top-0 right-0 w-1/2" style="height: 2.5px; background-color: #64748b;"></div>
                                                            @endif
                                                        @endif
                                                        <div class="z-10" style="width: 2.5px; height: 12px; background-color: #64748b;"></div>

                                                        <div class="bg-amber-50/80 border border-amber-300 rounded-xl px-2 py-1 text-center shadow-xs min-w-[90px] max-w-[115px] z-10">
                                                            <h6 class="text-[10px] font-bold text-slate-900 truncate flex items-center justify-center gap-1" title="{{ $dep->name }}">
                                                                <span>🌟</span> {{ $dep->name }}
                                                            </h6>
                                                            <span class="text-[8px] font-bold text-amber-800 bg-white px-1 py-0.5 rounded border border-amber-200 inline-block mt-0.5">
                                                                {{ $dep->relationship }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- UNASSIGNED DEPENDENTS -->
                    @php
                        $unassignedGrandchildren = $inlaws->concat($grandchildren)->filter(fn($m) => !$m->parent_id);
                    @endphp

                    @if($unassignedGrandchildren->count() > 0)
                    <div class="flex flex-col items-center w-full relative">
                        <div style="width: 2.5px; height: 16px; background-color: #64748b;"></div>

                        <div class="flex justify-center items-start relative w-full">
                            @foreach($unassignedGrandchildren as $gc)
                                <div class="flex flex-col items-center relative px-1 sm:px-1.5">
                                    @if($unassignedGrandchildren->count() > 1)
                                        @if(!$loop->first)
                                            <div class="absolute top-0 left-0 w-1/2" style="height: 2.5px; background-color: #64748b;"></div>
                                        @endif
                                        @if(!$loop->last)
                                            <div class="absolute top-0 right-0 w-1/2" style="height: 2.5px; background-color: #64748b;"></div>
                                        @endif
                                    @endif

                                    <div class="z-10" style="width: 2.5px; height: 12px; background-color: #64748b;"></div>

                                    <div class="bg-amber-50/70 border border-amber-300 rounded-xl px-2 py-1 text-center shadow-xs min-w-[90px] max-w-[115px] z-10">
                                        <h6 class="text-[10px] font-bold text-slate-900 truncate flex items-center justify-center gap-1" title="{{ $gc->name }}">
                                            <span>🌟</span> {{ $gc->name }}
                                        </h6>
                                        <span class="text-[8px] font-bold text-amber-800 bg-white px-1 py-0.5 rounded border border-amber-200 inline-block mt-0.5">
                                            {{ $gc->relationship }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- OTHER EXTENDED MEMBERS -->
                    @if($others->count() > 0)
                    <div class="border-t border-slate-100 pt-2 mt-2 w-full flex flex-col items-center">
                        <div class="flex flex-wrap justify-center gap-1.5">
                            @foreach($others as $ot)
                                <span class="text-[9px] font-semibold text-slate-700 bg-white px-2 py-0.5 rounded-lg border border-slate-200 shadow-xs">
                                    {{ $ot->name }} ({{ $ot->relationship }})
                                </span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        @else
            <div class="p-8 text-center bg-slate-50/60 rounded-xl border border-slate-100 space-y-3">
                <span class="text-3xl">👨‍👩‍👧‍👦</span>
                <div class="space-y-1">
                    <h4 class="text-xs font-bold text-slate-800">No family members added yet</h4>
                    <p class="text-[11px] text-slate-400">Add your spouse, children, and family members to build your visual Family Tree.</p>
                </div>
                <a href="{{ route('member.family.index') }}" class="inline-block px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white font-bold text-xs rounded-xl shadow-xs transition-all">
                    + Add Family Members
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
