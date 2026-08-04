@extends('layouts.member')
@section('page_title', __('messages.edit_profile'))

@section('content')
<div class="max-w-4xl space-y-3">

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="p-2.5 bg-rose-50 border border-rose-100 text-rose-800 rounded-lg text-xs">
            <p class="font-bold mb-1">{{ __('messages.please_correct_errors') }}</p>
            <ul class="list-disc pl-4 text-[11px] font-medium space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- EDITABLE PROFILE FORM -->
    <form method="POST" action="{{ route('member.profile.update') }}" enctype="multipart/form-data" id="member_profile_form" class="space-y-3">
        @csrf
        <input type="hidden" name="first_name" value="{{ old('first_name', $profile->first_name ?? '') }}">
        <input type="hidden" name="middle_name" value="{{ old('middle_name', $profile->middle_name ?? '') }}">
        <input type="hidden" name="last_name" value="{{ old('last_name', $profile->last_name ?? '') }}">

        <!-- MEMBER IDENTITY & PHOTO HEADER CARD -->
        <div class="bg-gradient-to-r from-primary-50/80 via-white to-primary-50/30 border border-primary-100 rounded-xl p-3 shadow-2xs">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                <!-- User Info -->
                <div class="flex items-center gap-3">
                    <img class="w-11 h-11 rounded-lg object-cover bg-white border border-primary-200 shadow-2xs shrink-0" 
                         src="{{ $profile && $profile->photo_path ? (str_starts_with($profile->photo_path, 'http') ? $profile->photo_path : asset('storage/' . $profile->photo_path)) : 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?q=80&w=100' }}" 
                         alt="Profile Photo">
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="px-1.5 py-0.2 rounded bg-primary-100 text-primary-700 text-[8px] font-black uppercase">MEMBER ACCOUNT</span>
                            <span class="text-[11px] font-extrabold text-slate-500">#{{ sprintf('%05d', $user->id) }}</span>
                        </div>
                        <h2 class="text-xs font-black text-slate-900 leading-tight">
                            {{ $profile->first_name ?? '' }} {{ $profile->middle_name ?? '' }} {{ $profile->last_name ?? '' }}
                        </h2>
                        <p class="text-[10px] text-slate-500 font-semibold">{{ $user->email }}</p>
                    </div>
                </div>

                <!-- Photo Upload Field -->
                <div class="flex items-center gap-2 shrink-0">
                    <label class="text-[10px] font-extrabold text-slate-500 uppercase shrink-0">{{ __('messages.profile_photo') }}:</label>
                    <input type="file" name="photo" accept="image/*"
                           class="text-[10px] text-slate-500 file:mr-1.5 file:py-0.5 file:px-2 file:rounded-md file:border-0 file:text-[9px] file:font-bold file:bg-primary-500 file:text-white hover:file:bg-primary-600 cursor-pointer">
                </div>
            </div>
        </div>

        <!-- FORM DATA CARD -->
        <div class="bg-white border border-slate-100 rounded-xl p-3.5 shadow-2xs space-y-3">
            
            <!-- PERSONAL & CONTACT DETAILS GRID -->
            <div>
                <h3 class="text-[11px] font-black text-slate-800 uppercase tracking-wider mb-2 pb-0.5 border-b border-slate-100 flex items-center gap-1">
                    <span>👤</span> {{ __('messages.personal_details_sec') }} & {{ __('messages.contact_details_sec') }}
                </h3>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                    <div class="space-y-0.5">
                        <label class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">{{ __('messages.gender') }} *</label>
                        <select name="gender" required 
                                class="w-full text-xs font-semibold px-2 py-1 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 outline-none">
                            <option value="Male" {{ old('gender', $profile->gender ?? '') == 'Male' ? 'selected' : '' }}>{{ __('messages.gender_male') }}</option>
                            <option value="Female" {{ old('gender', $profile->gender ?? '') == 'Female' ? 'selected' : '' }}>{{ __('messages.gender_female') }}</option>
                            <option value="Other" {{ old('gender', $profile->gender ?? '') == 'Other' ? 'selected' : '' }}>{{ __('messages.gender_other') }}</option>
                        </select>
                    </div>

                    <div class="space-y-0.5">
                        <label class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">{{ __('messages.dob') }} *</label>
                        <input type="date" name="dob" value="{{ old('dob', $profile->dob ?? '') }}" required
                               class="w-full text-xs font-semibold px-2 py-1 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 outline-none">
                    </div>

                    <div class="space-y-0.5">
                        <label class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Phone / WhatsApp *</label>
                        <input type="text" name="phone" value="{{ old('phone', $profile->phone ?? '') }}" required maxlength="10" 
                               oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)" placeholder="Phone"
                               class="w-full text-xs font-semibold px-2 py-1 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 outline-none">
                    </div>

                    <div class="space-y-0.5">
                        <label class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">{{ __('messages.blood_group') }}</label>
                        <select name="blood_group"
                               class="w-full text-xs font-semibold px-2 py-1 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 outline-none">
                            <option value="">-- {{ __('messages.select_blood_group') }} --</option>
                            <option value="A+" {{ old('blood_group', $profile->blood_group ?? '') == 'A+' ? 'selected' : '' }}>A+</option>
                            <option value="A-" {{ old('blood_group', $profile->blood_group ?? '') == 'A-' ? 'selected' : '' }}>A-</option>
                            <option value="B+" {{ old('blood_group', $profile->blood_group ?? '') == 'B+' ? 'selected' : '' }}>B+</option>
                            <option value="B-" {{ old('blood_group', $profile->blood_group ?? '') == 'B-' ? 'selected' : '' }}>B-</option>
                            <option value="AB+" {{ old('blood_group', $profile->blood_group ?? '') == 'AB+' ? 'selected' : '' }}>AB+</option>
                            <option value="AB-" {{ old('blood_group', $profile->blood_group ?? '') == 'AB-' ? 'selected' : '' }}>AB-</option>
                            <option value="O+" {{ old('blood_group', $profile->blood_group ?? '') == 'O+' ? 'selected' : '' }}>O+</option>
                            <option value="O-" {{ old('blood_group', $profile->blood_group ?? '') == 'O-' ? 'selected' : '' }}>O-</option>
                        </select>
                    </div>

                    <div class="space-y-0.5">
                        <label class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">{{ __('messages.education') }}</label>
                        <input type="text" name="education" value="{{ old('education', $profile->education ?? '') }}" placeholder="e.g. B.E. Computer"
                               class="w-full text-xs font-semibold px-2 py-1 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 outline-none">
                    </div>

                    <div class="space-y-0.5">
                        <label class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">{{ __('messages.occupation') }}</label>
                        <input type="text" name="occupation" value="{{ old('occupation', $profile->occupation ?? '') }}" placeholder="e.g. Engineer"
                               class="w-full text-xs font-semibold px-2 py-1 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 outline-none">
                    </div>

                    <div class="space-y-0.5 col-span-2 sm:col-span-2">
                        <label class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">{{ __('messages.father_member_id_optional') }}</label>
                        <input type="text" name="father_member_id" value="{{ old('father_member_id', $profile->father_member_id ?? '') }}" placeholder="e.g. #00005"
                               class="w-full text-xs font-semibold px-2 py-1 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 outline-none">
                        @if(!empty($profile->father_member_id))
                            @php
                                $fatherUser = $profile->father_user;
                            @endphp
                            @if($fatherUser)
                                <p class="text-[9px] text-emerald-600 font-bold mt-0.5">✓ {{ $fatherUser->name }} (#{{ sprintf('%05d', $fatherUser->id) }})</p>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <!-- ADDRESS DETAILS GRID -->
            <div>
                <h3 class="text-[11px] font-black text-slate-800 uppercase tracking-wider mb-2 pb-0.5 border-b border-slate-100 flex items-center gap-1">
                    <span>📍</span> {{ __('messages.address_location_sec') }}
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-2">
                    <div class="space-y-0.5 sm:col-span-4">
                        <label class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">{{ __('messages.street_address') }} *</label>
                        <input type="text" name="address" value="{{ old('address', $profile->address ?? '') }}" required placeholder="House No, Society, Area..."
                               class="w-full text-xs font-semibold px-2 py-1 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 outline-none">
                    </div>

                    <div class="space-y-0.5">
                        <label class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">{{ __('messages.city') }} *</label>
                        <input type="text" name="city" value="{{ old('city', $profile->city ?? '') }}" required placeholder="City"
                               class="w-full text-xs font-semibold px-2 py-1 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 outline-none">
                    </div>

                    <div class="space-y-0.5 sm:col-span-2">
                        <label class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">{{ __('messages.state') }} *</label>
                        <input type="text" name="state" value="{{ old('state', $profile->state ?? '') }}" required placeholder="State"
                               class="w-full text-xs font-semibold px-2 py-1 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 outline-none">
                    </div>

                    <div class="space-y-0.5">
                        <label class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">{{ __('messages.pincode') }} *</label>
                        <input type="text" name="pincode" value="{{ old('pincode', $profile->pincode ?? '') }}" required placeholder="Pincode"
                               class="w-full text-xs font-semibold px-2 py-1 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 outline-none">
                    </div>
                </div>
            </div>

            <!-- PROFILE ACTIONS -->
            <div class="pt-2 border-t border-slate-100 flex justify-end items-center gap-2">
                <a href="{{ route('member.dashboard') }}" 
                   class="px-3 py-1 border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs rounded-lg transition-colors">
                    {{ __('messages.cancel') }}
                </a>
                <button type="submit" 
                        class="px-4 py-1.5 bg-primary-500 hover:bg-primary-600 text-white font-bold text-xs rounded-lg shadow-2xs transition-colors uppercase tracking-wider">
                    {{ __('messages.save_profile_changes') }}
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
