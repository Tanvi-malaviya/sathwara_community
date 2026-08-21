@extends('layouts.member')
@section('page_title', __('messages.edit_profile'))

@section('content')
@php
    $areas = $areas ?? \App\Models\Area::orderBy('name')->get();
@endphp
<div class="max-w-5xl mx-auto space-y-4" 
     x-data="{ 
         photoPreview: null, 
         fileName: '',
         pincode: '{{ old('pincode', $profile->pincode ?? '') }}',
         onAreaChange(event) {
             const sel = event.target.options[event.target.selectedIndex];
             const pin = sel ? sel.getAttribute('data-pincode') : '';
             if (pin) {
                 this.pincode = pin;
             }
         },
         onPhotoChange(event) {
             const file = event.target.files[0];
             if (file) {
                 this.fileName = file.name;
                 const reader = new FileReader();
                 reader.onload = (e) => { this.photoPreview = e.target.result; };
                 reader.readAsDataURL(file);
             }
         }
     }">

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-100 text-rose-800 rounded-2xl text-xs font-semibold shadow-xs">
            <p class="font-extrabold mb-1.5 flex items-center gap-1.5 text-rose-900">
                <span>⚠️</span>
                <span>{{ __('messages.please_correct_errors') }}</span>
            </p>
            <ul class="list-disc pl-5 text-[11px] font-medium space-y-1 text-rose-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- EDITABLE PROFILE FORM -->
    <form method="POST" action="{{ route('member.profile.update') }}" enctype="multipart/form-data" id="member_profile_form" class="space-y-4">
        @csrf
        <input type="hidden" name="first_name" value="{{ old('first_name', $profile->first_name ?? '') }}">
        <input type="hidden" name="middle_name" value="{{ old('middle_name', $profile->middle_name ?? '') }}">
        <input type="hidden" name="last_name" value="{{ old('last_name', $profile->last_name ?? '') }}">

        <!-- MEMBER IDENTITY & PHOTO HEADER CARD -->
        <div class="bg-white border border-slate-100 rounded-2xl p-4 sm:p-5 shadow-sm">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <!-- User Info -->
                <div class="flex items-center gap-3 sm:gap-4 min-w-0 flex-1">
                    <div class="relative group shrink-0">
                        @php
                            $hasProfilePhoto = ($profile && !empty($profile->photo_path) && !str_contains($profile->photo_path, 'unsplash.com') && $profile->photo_path !== 'NOT_SPECIFIED' && $profile->photo_path !== 'N/A');
                            $profilePhotoUrl = $hasProfilePhoto ? (str_starts_with($profile->photo_path, 'http') ? $profile->photo_path : asset('storage/' . $profile->photo_path)) : asset('logo.png');
                        @endphp
                        <img class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl object-cover bg-slate-50 border-2 border-primary-200 shadow-xs p-1" 
                             src="{{ $profilePhotoUrl }}" 
                             alt="Profile Photo">
                    </div>
                    <div class="space-y-1 min-w-0 flex-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="px-2.5 py-0.5 rounded-md bg-primary-50 text-primary-700 border border-primary-100 text-[10px] font-black uppercase tracking-wider">MEMBER ACCOUNT</span>
                            <span class="text-xs font-black text-slate-700 font-mono">{{ $user->member_code ?: $user->formatted_member_id }}</span>
                        </div>
                        <h2 class="text-base sm:text-lg font-black text-slate-900 leading-snug break-words">
                            {{ $user->display_name }}
                        </h2>
                        <p class="text-xs text-slate-500 font-semibold flex items-center gap-1 truncate">
                            <span>✉️</span>
                            <span class="truncate">{{ $user->email }}</span>
                        </p>
                    </div>
                </div>

                <!-- Photo Upload Field with Preview -->
                <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3 flex flex-col sm:flex-row items-start sm:items-center gap-3 shrink-0 w-full sm:w-auto overflow-hidden">
                    <div class="space-y-0.5 shrink-0">
                        <label class="text-xs font-bold text-slate-700 block">{{ __('messages.profile_photo') }}</label>
                        <span class="text-[10px] text-slate-400 font-medium block">JPG, PNG or WEBP (Max 2MB)</span>
                    </div>

                    <div class="flex items-center gap-3 w-full sm:w-auto flex-wrap">
                        <!-- Small thumbnail preview -->
                        <div x-show="photoPreview" class="shrink-0" x-cloak>
                            <img :src="photoPreview" class="w-10 h-10 rounded-xl object-cover border-2 border-primary-500 shadow-xs">
                        </div>

                        <label class="cursor-pointer inline-flex items-center gap-2 px-3.5 py-2 bg-primary-600 hover:bg-primary-700 active:bg-primary-800 text-white font-extrabold text-xs rounded-xl shadow-xs transition-all shrink-0 hover:-translate-y-0.5"
                               style="background-color: #e11d48 !important; color: #ffffff !important;">
                            <svg class="w-4 h-4" style="color: #ffffff !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="!text-white" style="color: #ffffff !important;">{{ __('messages.choose_file') ?? 'Choose Photo' }}</span>
                            <input type="file" name="photo" accept="image/*" @change="onPhotoChange($event)" class="hidden">
                        </label>
                        <span class="text-xs text-slate-500 font-semibold truncate max-w-[140px] sm:max-w-[200px]" x-text="fileName || 'No file chosen'"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- FORM DATA CARD -->
        <div class="bg-white border border-slate-100 rounded-2xl p-5 sm:p-6 shadow-sm space-y-6">
            
            <!-- SECTION 1: PERSONAL & CONTACT DETAILS -->
            <div class="space-y-4">
                <h3 class="text-sm sm:text-base font-black text-slate-900 pb-2.5 border-b border-slate-100 flex items-center gap-2.5">
                    <span class="w-7 h-7 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center font-bold text-sm shrink-0">👤</span>
                    <span>{{ __('messages.personal_details_sec') }} & {{ __('messages.contact_details_sec') }}</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    <!-- Gender -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700 block">{{ __('messages.gender') }} <span class="text-rose-500">*</span></label>
                        <select name="gender" required 
                                class="w-full text-xs font-semibold h-10 px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 focus:outline-none transition-colors">
                            <option value="Male" {{ old('gender', $profile->gender ?? '') == 'Male' ? 'selected' : '' }}>{{ __('messages.gender_male') }}</option>
                            <option value="Female" {{ old('gender', $profile->gender ?? '') == 'Female' ? 'selected' : '' }}>{{ __('messages.gender_female') }}</option>
                            <option value="Other" {{ old('gender', $profile->gender ?? '') == 'Other' ? 'selected' : '' }}>{{ __('messages.gender_other') }}</option>
                        </select>
                    </div>

                    <!-- Date of Birth -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700 block">{{ __('messages.dob') }} <span class="text-rose-500">*</span></label>
                        <input type="date" name="dob" value="{{ old('dob', $profile->dob ?? '') }}" required
                               class="w-full text-xs font-semibold h-10 px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 focus:outline-none transition-colors">
                    </div>

                    <!-- Phone / WhatsApp -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700 block">Phone / WhatsApp <span class="text-rose-500">*</span></label>
                        <input type="text" name="phone" value="{{ old('phone', $profile->phone ?? '') }}" required maxlength="10" 
                               oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)" placeholder="10-digit number"
                               class="w-full text-xs font-semibold h-10 px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 focus:outline-none transition-colors">
                    </div>

                    <!-- Blood Group -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700 block">{{ __('messages.blood_group') }}</label>
                        <select name="blood_group"
                               class="w-full text-xs font-semibold h-10 px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 focus:outline-none transition-colors">
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

                    <!-- Education -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700 block">{{ __('messages.education') }}</label>
                        <input type="text" name="education" value="{{ old('education', $profile->education ?? '') }}" placeholder="e.g. B.E. Computer"
                               class="w-full text-xs font-semibold h-10 px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 focus:outline-none transition-colors">
                    </div>

                    <!-- Occupation -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700 block">{{ __('messages.occupation') }}</label>
                        <input type="text" name="occupation" value="{{ old('occupation', $profile->occupation ?? '') }}" placeholder="e.g. Software Engineer"
                               class="w-full text-xs font-semibold h-10 px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 focus:outline-none transition-colors">
                    </div>

                    <!-- Father's Member ID -->
                    <div class="space-y-1.5 sm:col-span-2">
                        <label class="text-xs font-bold text-slate-700 block">{{ __('messages.father_member_id_optional') }}</label>
                        <input type="text" name="father_member_id" value="{{ old('father_member_id', $profile->father_member_id ?? '') }}" placeholder="e.g. #00005 or 5"
                               class="w-full text-xs font-semibold h-10 px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 focus:outline-none transition-colors">
                        @if(!empty($profile->father_member_id))
                            @php
                                $fatherUser = $profile->father_user;
                            @endphp
                            @if($fatherUser)
                                <p class="text-[11px] text-emerald-600 font-bold mt-0.5 flex items-center gap-1">
                                    <span>✓</span>
                                    <span>{{ $fatherUser->display_name }} (#{{ sprintf('%05d', $fatherUser->id) }})</span>
                                </p>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <!-- SECTION 2: ADDRESS & LOCATION DETAILS -->
            <div class="space-y-4 pt-2">
                <h3 class="text-sm sm:text-base font-black text-slate-900 pb-2.5 border-b border-slate-100 flex items-center gap-2.5">
                    <span class="w-7 h-7 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-sm shrink-0">📍</span>
                    <span>{{ __('messages.address_location_sec') }}</span>
                </h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <!-- Street Address -->
                    <div class="space-y-1.5 sm:col-span-3">
                        <label class="text-xs font-bold text-slate-700 block">{{ __('messages.street_address') }} <span class="text-rose-500">*</span></label>
                        <input type="text" name="address" value="{{ old('address', $profile->address ?? '') }}" required placeholder="House No, Society, Street..."
                               class="w-full text-xs font-semibold h-10 px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 focus:outline-none transition-colors">
                    </div>

                    <!-- Area Dropdown (From Admin Area Management) -->
                    <div class="space-y-1.5 sm:col-span-1">
                        <label class="text-xs font-bold text-slate-700 block">{{ __('messages.area') }}</label>
                        <select name="area_id"
                                class="w-full text-xs font-semibold h-10 px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 focus:outline-none transition-colors">
                            <option value="">-- {{ __('messages.select_area') }} --</option>
                            @foreach($areas as $area)
                                <option value="{{ $area->id }}" 
                                        {{ old('area_id', $profile->area_id ?? '') == $area->id ? 'selected' : '' }}>
                                    {{ $area->name }}{{ $area->pincode ? ' (' . $area->pincode . ')' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- City -->
                    <div class="space-y-1.5 sm:col-span-1">
                        <label class="text-xs font-bold text-slate-700 block">{{ __('messages.city') }} <span class="text-rose-500">*</span></label>
                        <input type="text" name="city" value="{{ old('city', $profile->city ?? '') }}" required placeholder="City"
                               class="w-full text-xs font-semibold h-10 px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 focus:outline-none transition-colors">
                    </div>

                    <!-- State (Fixed to Gujarat) -->
                    <div class="space-y-1.5 sm:col-span-1">
                        <label class="text-xs font-bold text-slate-700 block">{{ __('messages.state') }} <span class="text-rose-500">*</span></label>
                        <input type="text" name="state" value="Gujarat" readonly
                               class="w-full text-xs font-bold h-10 px-3 bg-slate-100 text-slate-600 border border-slate-200 rounded-xl cursor-not-allowed select-none">
                    </div>
                </div>
            </div>

            <!-- PROFILE ACTIONS -->
            <div class="pt-4 border-t border-slate-100 flex flex-col-reverse sm:flex-row justify-end items-stretch sm:items-center gap-2 sm:gap-3">
                <a href="{{ route('member.dashboard') }}" 
                   class="px-5 py-2.5 border border-slate-200 hover:bg-slate-50 text-slate-600 font-extrabold text-xs rounded-xl transition-all text-center">
                    {{ __('messages.cancel') }}
                </a>
                <button type="submit" 
                        class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-extrabold text-xs rounded-xl shadow-xs transition-all uppercase tracking-wider cursor-pointer hover:-translate-y-0.5">
                    <span>💾</span>
                    <span>{{ __('messages.save_profile_changes') }}</span>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
