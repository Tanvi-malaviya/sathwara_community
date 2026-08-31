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
                        <p class="text-xs text-slate-500 font-semibold flex items-center gap-1.5 truncate">
                            <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
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
                        <div class="relative w-11 h-11 rounded-lg border border-slate-200 bg-white overflow-hidden shrink-0 shadow-2xs">
                            <img id="photoPreview" :src="photoPreview ? photoPreview : '{{ $profilePhotoUrl }}'" src="{{ $profilePhotoUrl }}" alt="Preview" class="w-full h-full object-cover">
                        </div>

                        <!-- Upload Trigger -->
                        <div class="flex-1 sm:flex-initial space-y-1">
                            <label class="cursor-pointer inline-flex items-center gap-1.5 px-3 py-2 bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-lg border border-slate-300 shadow-2xs transition-colors">
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                <span>{{ __('messages.choose_photo') ?? 'Choose Photo' }}</span>
                                <input type="file" name="photo" id="photoInput" accept="image/jpeg,image/png,image/webp" class="hidden" @change="onPhotoChange($event)">
                            </label>
                            <span x-show="fileName" x-text="fileName" class="text-[10px] text-primary-600 font-bold max-w-[130px] truncate block" x-cloak></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FORM DATA CARD -->
        <div class="bg-white border border-slate-100 rounded-2xl p-5 sm:p-6 shadow-sm space-y-6">
            
            <!-- SECTION 1: PERSONAL & CONTACT DETAILS -->
            <div class="space-y-4">
                <h3 class="text-sm sm:text-base font-black text-slate-900 pb-2.5 border-b border-slate-100 flex items-center gap-2.5">
                    <span class="w-7 h-7 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center font-bold text-sm shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </span>
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
                        <label class="text-xs font-bold text-slate-700 block">{{ __('messages.dob') }}</label>
                        <input type="date" name="dob" value="{{ old('dob', $profile->dob ?? '') }}"
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
                            @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                                <option value="{{ $bg }}" {{ old('blood_group', $profile->blood_group ?? '') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Marital Status -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700 block">{{ __('messages.marital_status') }}</label>
                        <select name="marital_status"
                                class="w-full text-xs font-semibold h-10 px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 focus:outline-none transition-colors">
                            <option value="">-- {{ __('messages.select_marital_status') }} --</option>
                            <option value="Single" {{ old('marital_status', $profile->marital_status ?? '') == 'Single' ? 'selected' : '' }}>{{ __('messages.single') }}</option>
                            <option value="Married" {{ old('marital_status', $profile->marital_status ?? '') == 'Married' ? 'selected' : '' }}>{{ __('messages.married') }}</option>
                            <option value="Widowed" {{ old('marital_status', $profile->marital_status ?? '') == 'Widowed' ? 'selected' : '' }}>{{ __('messages.widowed') }}</option>
                            <option value="Divorced" {{ old('marital_status', $profile->marital_status ?? '') == 'Divorced' ? 'selected' : '' }}>{{ __('messages.divorced') }}</option>
                        </select>
                    </div>

                    <!-- Education -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700 block">{{ __('messages.education') }}</label>
                        <input type="text" name="education" value="{{ old('education', $profile->education ?? '') }}" placeholder="e.g. B.Com, MBA, Graduate"
                               class="w-full text-xs font-semibold h-10 px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 focus:outline-none transition-colors">
                    </div>

                    <!-- Occupation -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700 block">{{ __('messages.occupation') }}</label>
                        <input type="text" name="occupation" value="{{ old('occupation', $profile->occupation ?? '') }}" placeholder="e.g. Software Engineer"
                               class="w-full text-xs font-semibold h-10 px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 focus:outline-none transition-colors">
                    </div>

                    <!-- Father's Member ID with Search & Live Verification -->
                    @php
                        $fatherUser = !empty($profile->father_member_id) ? $profile->father_user : null;
                        $initialFatherCode = old('father_member_id', $profile->father_member_id ?? '');
                        $initialStatus = $fatherUser ? 'found' : (!empty($initialFatherCode) ? 'not_found' : 'idle');
                        $initialStatusMsg = $fatherUser 
                            ? ($fatherUser->display_name . ' (' . ($fatherUser->member_code ?: $fatherUser->formatted_member_id) . ')') 
                            : (!empty($initialFatherCode) ? __('messages.no_member_found') : '');
                    @endphp
                    <div class="space-y-1.5 sm:col-span-2"
                         x-data="{
                             fatherCode: '{{ $initialFatherCode }}',
                             searching: false,
                             status: '{{ $initialStatus }}',
                             statusMsg: '{{ addslashes($initialStatusMsg) }}',
                             async checkFather() {
                                 const code = this.fatherCode.trim();
                                 if (!code) {
                                     this.status = 'idle';
                                     this.statusMsg = '';
                                     return;
                                 }
                                 this.searching = true;
                                 this.status = 'searching';
                                 try {
                                     const res = await fetch(`{{ route('api.lookup_father_member') }}?code=${encodeURIComponent(code)}`);
                                     const data = await res.json();
                                     if (data.found) {
                                         this.status = 'found';
                                         this.statusMsg = data.message;
                                     } else {
                                         this.status = 'not_found';
                                         this.statusMsg = data.message;
                                     }
                                 } catch (e) {
                                     this.status = 'not_found';
                                     this.statusMsg = '{{ app()->getLocale() == "gu" ? "ચકાસણીમાં ભૂલ આવી" : "Error checking member code" }}';
                                 } finally {
                                     this.searching = false;
                                 }
                             }
                         }">
                        <label class="text-xs font-bold text-slate-700 block">{{ __('messages.father_member_id_optional') }}</label>
                        
                        <div class="flex items-center gap-2">
                            <div class="relative flex-1">
                                <input type="text" 
                                       name="father_member_id" 
                                       x-model="fatherCode"
                                       @keydown.enter.prevent="checkFather()"
                                       placeholder="e.g. SSAM0653 or #00005"
                                       class="w-full text-xs font-semibold h-10 px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 focus:outline-none transition-colors">
                            </div>
                            <button type="button" 
                                    @click="checkFather()"
                                    :disabled="searching"
                                    class="inline-flex items-center justify-center gap-1.5 px-4 h-10 bg-primary-50 hover:bg-primary-100 text-primary-700 border border-primary-200 font-bold text-xs rounded-xl transition-all shadow-2xs hover:shadow-xs cursor-pointer shrink-0 disabled:opacity-50">
                                <template x-if="searching">
                                    <svg class="w-3.5 h-3.5 animate-spin text-primary-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </template>
                                <template x-if="!searching">
                                    <svg class="w-3.5 h-3.5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </template>
                                <span>{{ __('messages.search') }}</span>
                            </button>
                        </div>

                        <!-- Status feedback -->
                        <div x-show="status === 'found'" class="mt-1 flex items-center gap-1.5 text-[11px] font-bold text-emerald-600" x-cloak>
                            <span class="inline-flex items-center justify-center w-4 h-4 rounded-full bg-emerald-100 text-emerald-700 text-[10px]">✓</span>
                            <span x-text="statusMsg"></span>
                        </div>

                        <div x-show="status === 'not_found'" class="mt-1 flex items-center gap-1.5 text-[11px] font-bold text-rose-600" x-cloak>
                            <span class="inline-flex items-center justify-center w-4 h-4 rounded-full bg-rose-100 text-rose-700 text-[10px]">✕</span>
                            <span x-text="statusMsg"></span>
                        </div>
                    </div></div>
                </div>
            </div>

            <!-- SECTION 2: ADDRESS & LOCATION DETAILS -->
            <div class="space-y-4 pt-2">
                <h3 class="text-sm sm:text-base font-black text-slate-900 pb-2.5 border-b border-slate-100 flex items-center gap-2.5">
                    <span class="w-7 h-7 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-sm shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </span>
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
