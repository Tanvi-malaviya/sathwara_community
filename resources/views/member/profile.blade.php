@extends('layouts.member')
@section('page_title', __('messages.edit_profile'))

@section('content')
<div class="max-w-5xl space-y-6">

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-100 text-rose-800 rounded-xl">
            <p class="text-xs font-bold mb-2">{{ __('messages.please_correct_errors') }}</p>
            <ul class="list-disc pl-4 text-[11px] font-medium space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- CARD 1: PROFILE DETAILS FORM -->
    <div class="bg-white border border-slate-100 rounded-xl p-5 shadow-sm space-y-4">
        <form method="POST" action="{{ route('member.profile.update') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <!-- SECTION 1: PERSONAL DETAILS -->
            <div>
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-3 pb-1.5 border-b border-slate-100 flex items-center gap-2">
                    <span>👤</span> {{ __('messages.personal_details_sec') }}
                </h3>
                
                <div class="flex items-center justify-between space-x-3 mb-3 pb-3 border-b border-slate-50">
                    <div class="flex items-center space-x-3">
                        <img class="w-12 h-12 rounded-xl object-cover bg-slate-50 border border-slate-100 shadow-inner shrink-0" 
                             src="{{ $profile && $profile->photo_path ? (str_starts_with($profile->photo_path, 'http') ? $profile->photo_path : asset('storage/' . $profile->photo_path)) : 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?q=80&w=100' }}" 
                             alt="Profile Photo">
                        <div class="space-y-1">
                            <h4 class="text-[10px] font-bold text-slate-800 uppercase">{{ __('messages.profile_photo') }}</h4>
                            <input type="file" name="photo" accept="image/*"
                                   class="text-[9px] block w-full text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 cursor-pointer">
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-bold bg-slate-100 text-slate-700 px-3 py-1.5 rounded-lg border border-slate-200 inline-flex items-center gap-1.5">
                            <span class="text-[10px] text-slate-400 font-extrabold uppercase">{{ __('messages.member_id') }}:</span>
                            <span class="font-extrabold text-primary-600">#{{ sprintf('%05d', $user->id) }}</span>
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-slate-500 uppercase">{{ __('messages.first_name') }} *</label>
                        <input type="text" name="first_name" value="{{ old('first_name', $profile->first_name ?? '') }}" required placeholder="{{ __('messages.first_name') }}"
                               class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:outline-none">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-slate-500 uppercase">{{ __('messages.middle_name') }} *</label>
                        <input type="text" name="middle_name" value="{{ old('middle_name', $profile->middle_name ?? '') }}" required placeholder="{{ __('messages.middle_name') }}"
                               class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:outline-none">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-slate-500 uppercase">{{ __('messages.last_name') }} *</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $profile->last_name ?? '') }}" required placeholder="{{ __('messages.last_name') }}"
                               class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:outline-none">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-slate-500 uppercase">{{ __('messages.gender') }} *</label>
                        <select name="gender" required 
                                class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:outline-none">
                            <option value="Male" {{ old('gender', $profile->gender ?? '') == 'Male' ? 'selected' : '' }}>{{ __('messages.gender_male') }}</option>
                            <option value="Female" {{ old('gender', $profile->gender ?? '') == 'Female' ? 'selected' : '' }}>{{ __('messages.gender_female') }}</option>
                            <option value="Other" {{ old('gender', $profile->gender ?? '') == 'Other' ? 'selected' : '' }}>{{ __('messages.gender_other') }}</option>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-slate-500 uppercase">{{ __('messages.dob') }} *</label>
                        <input type="date" name="dob" value="{{ old('dob', $profile->dob ?? '') }}" required
                               class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:outline-none">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-slate-500 uppercase">{{ __('messages.blood_group') }}</label>
                        <input type="text" name="blood_group" value="{{ old('blood_group', $profile->blood_group ?? '') }}" placeholder="e.g. B+"
                               class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:outline-none">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-slate-500 uppercase">{{ __('messages.education') }}</label>
                        <input type="text" name="education" value="{{ old('education', $profile->education ?? '') }}" placeholder="e.g. B.E. Computer Science"
                               class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:outline-none">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-slate-500 uppercase">{{ __('messages.occupation') }}</label>
                        <input type="text" name="occupation" value="{{ old('occupation', $profile->occupation ?? '') }}" placeholder="e.g. Software Engineer"
                               class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:outline-none">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-slate-500 uppercase">{{ __('messages.father_member_id_optional') }}</label>
                        <input type="text" name="father_member_id" value="{{ old('father_member_id', $profile->father_member_id ?? '') }}" placeholder="e.g. #00005"
                               class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:outline-none">
                        @if(!empty($profile->father_member_id))
                            @php
                                $fatherUser = $profile->father_user;
                            @endphp
                            @if($fatherUser)
                                <p class="text-[10px] text-emerald-600 font-semibold flex items-center gap-1 mt-0.5">
                                    <span>✓</span> {{ __('messages.registered_father_info') }}: <span class="font-bold">{{ $fatherUser->name }}</span> (#{{ sprintf('%05d', $fatherUser->id) }})
                                </p>
                            @else
                                <p class="text-[10px] text-slate-400 font-medium mt-0.5">
                                    ID: <span class="font-bold text-slate-600">{{ $profile->father_member_id }}</span>
                                </p>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <!-- SECTION 2: CONTACT DETAILS -->
            <div class="pt-2">
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-3 pb-1.5 border-b border-slate-100 flex items-center gap-2">
                    <span>📞</span> {{ __('messages.contact_details_sec') }}
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-slate-500 uppercase">Phone / WhatsApp Number *</label>
                        <input type="text" name="phone" value="{{ old('phone', $profile->phone ?? '') }}" required maxlength="10" 
                               oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)" placeholder="{{ __('messages.phone') }}"
                               class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:outline-none">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-slate-500 uppercase">{{ __('messages.member_email_id') }} ({{ __('messages.not_editable') }})</label>
                        <div class="relative">
                            <input type="email" value="{{ $user->email ?? auth()->user()->email ?? '' }}" readonly disabled 
                                   class="w-full text-xs font-semibold px-3 py-2 bg-slate-100/90 text-slate-500 border border-slate-200 rounded-lg cursor-not-allowed select-none focus:outline-none" 
                                   title="{{ __('messages.email_cannot_be_changed') }}">
                            <span class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs" title="{{ __('messages.not_editable') }}">🔒</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: ADDRESS DETAILS -->
            <div class="pt-2">
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-3 pb-1.5 border-b border-slate-100 flex items-center gap-2">
                    <span>📍</span> {{ __('messages.address_location_sec') }}
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="space-y-1 sm:col-span-3">
                        <label class="text-[11px] font-bold text-slate-500 uppercase">{{ __('messages.street_address') }} *</label>
                        <textarea name="address" rows="2" required placeholder="House No, Society, Landmark..."
                                  class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:outline-none">{{ old('address', $profile->address ?? '') }}</textarea>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-slate-500 uppercase">{{ __('messages.city') }} *</label>
                        <input type="text" name="city" value="{{ old('city', $profile->city ?? '') }}" required placeholder="{{ __('messages.city') }}"
                               class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:outline-none">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-slate-500 uppercase">{{ __('messages.state') }} *</label>
                        <input type="text" name="state" value="{{ old('state', $profile->state ?? '') }}" required placeholder="State"
                               class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:outline-none">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-slate-500 uppercase">{{ __('messages.pincode') }} *</label>
                        <input type="text" name="pincode" value="{{ old('pincode', $profile->pincode ?? '') }}" required placeholder="Pincode"
                               class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:outline-none">
                    </div>
                </div>
            </div>

            <!-- PROFILE ACTIONS -->
            <div class="pt-4 border-t border-slate-100 flex justify-end items-center space-x-3">
                <a href="{{ route('member.dashboard') }}" 
                   class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs rounded-xl transition-colors">
                    {{ __('messages.cancel') }}
                </a>
                <button type="submit" 
                        class="px-5 py-2.5 bg-primary-500 hover:bg-primary-600 text-white font-bold text-xs rounded-xl shadow-sm transition-colors uppercase tracking-wider">
                    {{ __('messages.save_profile_changes') }}
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
