@extends('layouts.admin')

@section('page_title', __('messages.member_profile_sheet'))

@section('content')
    @php
        $user = auth()->user();
        $userPerms = $user->permissions->pluck('name');
        $canEditMember = $user->hasRole('Administrator') || $userPerms->contains('members_manage') || $userPerms->contains('members_edit');
    @endphp
    <div class="space-y-5 max-w-6xl mx-auto" x-data="{ showRejectModal: false }">
        <!-- Profile Details Card with Perfect Balance & Zero Empty Space -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
            <div class="flex flex-col md:flex-row items-start gap-5 lg:gap-6">

                <!-- Left Column: Profile Photo -->
                <div class="flex flex-col items-center shrink-0 w-full md:w-auto">
                    @php
                        $profile = $member->memberProfile;
                        $hasPhoto = ($profile && !empty($profile->photo_path) && !str_contains($profile->photo_path, 'unsplash.com') && $profile->photo_path !== 'NOT_SPECIFIED' && $profile->photo_path !== 'N/A');
                    @endphp
                    <div
                        class="relative w-40 h-40 sm:w-40 sm:h-40 rounded-xl overflow-hidden bg-slate-50 border border-slate-200/80 shadow-xs flex items-center justify-center">
                        @if($hasPhoto)
                            <img class="w-full h-full object-cover"
                                src="{{ str_starts_with($profile->photo_path, 'http') ? $profile->photo_path : asset('storage/' . $profile->photo_path) }}"
                                alt="{{ $member->name }}">
                        @else
                            <div
                                class="w-full h-full flex flex-col items-center justify-center bg-slate-100/80 text-slate-400 p-3 text-center">
                                <svg class="w-12 h-12 text-slate-300 mb-1" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider">No Photo</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right Column: Member Header & Full Details -->
                <div class="flex-1 w-full space-y-4">

                    <!-- Header: Member Name, Status Badge & Actions -->
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 pb-3">
                        <div>
                            <h3 class="text-lg font-black text-slate-900 leading-tight">{{ $member->display_name }}</h3>
                            <p class="text-[11px] text-slate-400 font-bold tracking-wider uppercase mt-1">
                                {{ __('messages.registered_at') }}: {{ $member->created_at->format('d-M-Y') }}
                            </p>
                        </div>

                        <div class="flex flex-wrap items-center gap-2">
                            <!-- Highlighted Member ID Badge on Right Corner -->
                            <span
                                class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-black bg-primary-50 text-primary-700 border border-primary-300 shadow-2xs tracking-wider">
                                {{ $member->member_code ?: $member->formatted_member_id }}
                            </span>

                            <span
                                class="px-2.5 py-1.5 rounded-lg text-[10px] font-extrabold uppercase tracking-wider {{ $member->status == 'approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($member->status == 'rejected' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-amber-50 text-amber-700 border border-amber-200') }}">
                                {{ __('messages.status_label') }}:
                                @if($member->status == 'approved')
                                    {{ __('messages.approved') }}
                                @elseif($member->status == 'rejected')
                                    {{ __('messages.rejected') }}
                                @else
                                    {{ __('messages.pending') }}
                                @endif
                            </span>

                            <span
                                class="px-2.5 py-1.5 rounded-lg text-[10px] font-extrabold uppercase tracking-wider {{ $member->account_status == 'close' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                                Account: {{ $member->account_status == 'close' ? 'Close' : 'Open' }}
                            </span>

                            @if($canEditMember)
                                <!-- Toggle Account Status Button -->
                                <form method="POST" action="{{ route('admin.members.toggle_account_status', $member->id) }}" class="inline">
                                    @csrf
                                    @if($member->account_status == 'close')
                                        <button type="submit"
                                            class="inline-flex items-center px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 font-bold text-xs rounded-lg transition-all shadow-xs gap-1">
                                            🔓 Open Account
                                        </button>
                                    @else
                                        <button type="submit"
                                            class="inline-flex items-center px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 font-bold text-xs rounded-lg transition-all shadow-xs gap-1">
                                            🔒 Close Account
                                        </button>
                                    @endif
                                </form>

                                @if($member->status == 'pending' || $member->status == 'rejected')
                                    <form method="POST" action="{{ route('admin.members.approve', $member->id) }}" class="inline">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs rounded-lg transition-all shadow-xs gap-1">
                                            ✓ {{ __('messages.approve') }}
                                        </button>
                                    </form>
                                @endif

                                @if($member->status == 'pending' || $member->status == 'approved')
                                    <button type="button" @click="showRejectModal = true"
                                        class="inline-flex items-center px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold text-xs rounded-lg transition-all shadow-xs gap-1">
                                        ✕ {{ __('messages.reject') }}
                                    </button>
                                @endif

                                <a href="{{ route('admin.members.edit', $member->id) }}"
                                    class="inline-flex items-center px-3 py-1.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 font-bold text-xs rounded-lg transition-all shadow-xs gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    <span>{{ __('messages.edit_profile') }}</span>
                                </a>
                            @endif
                        </div>
                    </div>

                    @if($member->status == 'rejected' && $member->rejection_reason)
                        <div class="p-2.5 bg-rose-50 border border-rose-100 rounded-lg text-xs text-rose-700 leading-relaxed">
                            <strong>{{ __('messages.rejection_reason') }}:</strong> {{ $member->rejection_reason }}
                        </div>
                    @endif

                    <!-- Profile Data Grid -->
                    @if($member->memberProfile)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-3 text-xs text-slate-700">
                            <div>
                                <h5 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-0.5">
                                    {{ __('messages.full_name') }}</h5>
                                <p class="font-bold text-slate-900 text-xs">{{ $member->memberProfile->first_name }}
                                    {{ $member->memberProfile->middle_name }} {{ $member->memberProfile->last_name }}</p>
                            </div>
                            <div>
                                <h5 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-0.5">
                                    {{ __('messages.gender') }}</h5>
                                <p class="font-bold text-slate-900 text-xs">{{ $member->memberProfile->gender }}</p>
                            </div>
                            <div>
                                <h5 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-0.5">
                                    {{ __('messages.dob') }}</h5>
                                <p class="font-bold text-slate-900 text-xs">
                                    {{ (!empty($member->memberProfile->dob) && $member->memberProfile->dob !== '0000-00-00' && $member->memberProfile->dob !== '1970-01-01') ? date('d-M-Y', strtotime($member->memberProfile->dob)) : __('messages.not_declared') }}</p>
                            </div>
                            <div>
                                <h5 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-0.5">
                                    {{ __('messages.email') }}</h5>
                                <p class="font-bold text-slate-900 text-xs">
                                    {{ $member->email ?: '-' }}</p>
                            </div>
                            <div>
                                <h5 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-0.5">
                                    {{ __('messages.phone_whatsapp') }}</h5>
                                <p class="font-bold text-slate-900 text-xs">
                                    {{ $member->memberProfile->phone }}
                                    @if($member->memberProfile->whatsapp)
                                        <span class="text-slate-400 font-medium text-[11px]">/
                                            {{ $member->memberProfile->whatsapp }}</span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <h5 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-0.5">
                                    {{ __('messages.blood_group') }}</h5>
                                <p class="font-bold text-slate-900 text-xs">
                                    {{ $member->memberProfile->blood_group ?? __('messages.not_declared') }}</p>
                            </div>
                            <div>
                                <h5 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-0.5">
                                    {{ __('messages.education') }}</h5>
                                <p class="font-bold text-slate-900 text-xs">
                                    {{ $member->memberProfile->education ?? __('messages.not_declared') }}</p>
                            </div>
                            <div>
                                <h5 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-0.5">
                                    {{ __('messages.occupation') }}</h5>
                                <p class="font-bold text-slate-900 text-xs">
                                    {{ $member->memberProfile->occupation ?? __('messages.not_declared') }}</p>
                            </div>
                            <div class="sm:col-span-2 lg:col-span-2">
                                <h5 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-0.5">
                                    {{ __('messages.address') }}</h5>
                                <p class="font-bold text-slate-900 text-xs leading-snug">
                                    {{ $member->memberProfile->address }}@if($member->memberProfile->area),
                                    {{ $member->memberProfile->area->name }}@endif,
                                    {{ $member->memberProfile->city }}@if($member->memberProfile->pincode) -
                                    {{ $member->memberProfile->pincode }}@endif, {{ $member->memberProfile->state }}
                                </p>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>

        <!-- Declared Family Members Section (Compact Card Style) -->
        <div class="space-y-2.5">
            <div class="flex items-center justify-between px-0.5">
                <h3 class="text-[11px] font-black uppercase tracking-wider text-slate-400">
                    {{ __('messages.family_members') }} ({{ $member->familyMembers->count() }})
                </h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                @forelse($member->familyMembers as $fam)
                    <div
                        class="bg-white rounded-xl p-3 border border-slate-200/80 shadow-xs space-y-2 hover:border-slate-300 transition-all">

                        <!-- Card Header: Name & Relationship Badge -->
                        <div class="flex items-start justify-between gap-1.5 border-b border-slate-100 pb-1.5">
                            <div class="min-w-0">
                                <h4 class="font-bold text-slate-900 text-xs truncate">{{ $fam->name }}</h4>
                                <p class="text-[9px] text-slate-400 font-semibold uppercase tracking-wide truncate">
                                    {{ $fam->gender }} &bull; {{ $fam->dob ? date('d-M-Y', strtotime($fam->dob)) : 'DOB N/A' }}
                                </p>
                            </div>
                            <span
                                class="px-2 py-0.5 rounded-md bg-primary-50 text-primary-700 border border-primary-100/80 text-[9px] font-extrabold uppercase shrink-0">
                                {{ $fam->relationship }}
                            </span>
                        </div>

                        <!-- Compact Details List -->
                        <div class="space-y-1 text-xs">
                            <div class="flex items-center justify-between gap-2">
                                <span
                                    class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">{{ __('messages.blood_group') }}</span>
                                <span
                                    class="font-bold text-slate-800 text-[10px] truncate">{{ $fam->blood_group ?? __('messages.not_declared') }}</span>
                            </div>
                            <div class="flex items-center justify-between gap-2">
                                <span
                                    class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">{{ __('messages.mobile') }}</span>
                                <span
                                    class="font-bold text-slate-800 text-[10px] truncate max-w-[110px]">{{ $fam->phone ?? __('messages.not_declared') }}</span>
                            </div>
                            <div class="flex items-center justify-between gap-2">
                                <span
                                    class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">{{ __('messages.email') }}</span>
                                <span
                                    class="font-bold text-slate-800 text-[10px] truncate max-w-[120px]">{{ $fam->email ?? __('messages.not_declared') }}</span>
                            </div>
                            <div class="flex items-center justify-between gap-2">
                                <span
                                    class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">{{ __('messages.education') }}</span>
                                <span
                                    class="font-bold text-slate-800 text-[10px] truncate max-w-[110px]">{{ $fam->education ?? __('messages.not_declared') }}</span>
                            </div>
                            <div class="flex items-center justify-between gap-2">
                                <span
                                    class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">{{ __('messages.occupation') }}</span>
                                <span
                                    class="font-bold text-slate-800 text-[10px] truncate max-w-[110px]">{{ $fam->occupation ?? __('messages.not_declared') }}</span>
                            </div>
                        </div>

                    </div>
                @empty
                    <div
                        class="col-span-full bg-white rounded-xl p-5 text-center text-slate-400 border border-slate-200/80 text-xs font-semibold">
                        {{ __('messages.no_family_members') }}
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Reject Member Modal -->
        <template x-teleport="body">
            <div x-show="showRejectModal"
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
                x-transition x-cloak>
                <div @click.away="showRejectModal = false"
                    class="bg-white rounded-2xl p-5 border border-slate-100 shadow-2xl max-w-md w-full space-y-4 relative">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <h3 class="text-sm font-black text-rose-600">Reject Member Application</h3>
                        <button type="button" @click="showRejectModal = false"
                            class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
                    </div>
                    <form method="POST" action="{{ route('admin.members.reject', $member->id) }}" class="space-y-3">
                        @csrf
                        <p class="text-xs text-slate-600 font-semibold">Please specify the reason for rejecting <strong
                                class="text-slate-900">{{ $member->name }}</strong>:</p>
                        <textarea name="rejection_reason" required rows="3"
                            placeholder="e.g. Incomplete address or incorrect details..."
                            class="w-full text-xs font-semibold p-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-rose-500 outline-none"></textarea>
                        <div class="pt-2 border-t border-slate-100 flex justify-end gap-2">
                            <button type="button" @click="showRejectModal = false"
                                class="px-4 py-2 border border-slate-200 text-slate-600 font-bold text-xs rounded-xl">Cancel</button>
                            <button type="submit"
                                class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-xs">Reject
                                Member</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>
@endsection