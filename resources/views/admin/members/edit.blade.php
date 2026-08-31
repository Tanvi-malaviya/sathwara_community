@extends('layouts.admin')

@section('page_title', __('messages.edit_member_details'))

    @section('content')
        <div class="max-w-6xl bg-white border border-slate-100 rounded-xl p-6 shadow-sm">
            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-800 rounded-2xl">
                    <p class="text-xs font-bold mb-2">{{ __('messages.please_correct_errors') }}</p>
                    <ul class="list-disc pl-4 text-xs font-medium space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.members.update', $member->id) }}" class="space-y-5"
                enctype="multipart/form-data">
                @csrf

                <!-- SECTION 1: LOGIN & ACCOUNT -->
                <div>
                    <h3
                        class="text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-3 pb-1.5 border-b border-slate-100">
                        1. {{ __('messages.account_credentials_status') }}
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 uppercase">{{ __('messages.member_id') }} / Member Code</label>
                            <input type="text" name="member_code" value="{{ old('member_code', $member->member_code) }}"
                                placeholder="e.g. SSAM0015"
                                class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 font-mono uppercase">
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 uppercase">{{ __('messages.email_address_login') }}</label>
                            <input type="email" name="email" value="{{ old('email', $member->email) }}"
                                placeholder="member@community.com"
                                class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 uppercase">{{ __('messages.status') }} (Approval) <span
                                    class="text-rose-500">*</span></label>
                            <select name="status" required
                                class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                                <option value="approved" {{ old('status', $member->status) == 'approved' ? 'selected' : '' }}>
                                    {{ __('messages.approved') }}</option>
                                <option value="pending" {{ old('status', $member->status) == 'pending' ? 'selected' : '' }}>
                                    {{ __('messages.pending') }}</option>
                                <option value="rejected" {{ old('status', $member->status) == 'rejected' ? 'selected' : '' }}>
                                    {{ __('messages.rejected') }}</option>
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 uppercase">Account Status <span
                                    class="text-rose-500">*</span></label>
                            <select name="account_status" required
                                class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                                <option value="open" {{ old('account_status', $member->account_status) == 'open' ? 'selected' : '' }}>Open (ચાલુ)</option>
                                <option value="close" {{ old('account_status', $member->account_status) == 'close' ? 'selected' : '' }}>Close (બંધ)</option>
                            </select>
                        </div>
                    </div>
                </div>

                @php
                    $profile = $member->memberProfile;
                @endphp

                <!-- SECTION 2: PERSONAL DETAILS -->
                <div class="pt-2">
                    <h3
                        class="text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-3 pb-1.5 border-b border-slate-100">
                        2. {{ __('messages.personal_details_sec') }}
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 uppercase">{{ __('messages.first_name_label') }}
                                <span class="text-rose-500">*</span></label>
                            <input type="text" name="first_name"
                                value="{{ old('first_name', $profile ? $profile->first_name : '') }}" required
                                placeholder="First Name"
                                class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                        </div>

                        <div class="space-y-1">
                            <label
                                class="text-xs font-bold text-slate-500 uppercase">{{ __('messages.middle_name_label') }}</label>
                            <input type="text" name="middle_name"
                                value="{{ old('middle_name', $profile ? $profile->middle_name : '') }}"
                                placeholder="Middle Name"
                                class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 uppercase">{{ __('messages.last_name_label') }} <span
                                    class="text-rose-500">*</span></label>
                            <input type="text" name="last_name"
                                value="{{ old('last_name', $profile ? $profile->last_name : '') }}" required
                                placeholder="Last Name"
                                class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                        </div>

                        @php
                            $adminFatherUser = ($profile && !empty($profile->father_member_id)) ? $profile->father_user : null;
                            $adminInitialFatherCode = old('father_member_id', $profile ? $profile->father_member_id : '');
                            $adminInitialStatus = $adminFatherUser ? 'found' : (!empty($adminInitialFatherCode) ? 'not_found' : 'idle');
                            $adminInitialStatusMsg = $adminFatherUser 
                                ? ($adminFatherUser->display_name . ' (' . ($adminFatherUser->member_code ?: $adminFatherUser->formatted_member_id) . ')') 
                                : '';
                        @endphp
                        <div class="space-y-1"
                             x-data="{
                                 fatherCode: '{{ $adminInitialFatherCode }}',
                                 searching: false,
                                 status: '{{ $adminInitialStatus }}',
                                 statusMsg: '{{ addslashes($adminInitialStatusMsg) }}',
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
                                         this.statusMsg = 'Error checking member code';
                                     } finally {
                                         this.searching = false;
                                     }
                                 }
                             }">
                            <label
                                class="text-xs font-bold text-slate-500 uppercase">{{ __('messages.father_member_id_label') }}</label>
                            <div class="flex items-center gap-1.5">
                                <input type="text" name="father_member_id"
                                    x-model="fatherCode"
                                    @keydown.enter.prevent="checkFather()"
                                    placeholder="e.g. SSAM0653 or #00005"
                                    class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                                <button type="button" 
                                        @click="checkFather()"
                                        :disabled="searching"
                                        class="inline-flex items-center justify-center gap-1 px-3 py-2 bg-primary-50 hover:bg-primary-100 text-primary-700 border border-primary-200 font-bold text-xs rounded-lg transition-all cursor-pointer shrink-0 disabled:opacity-50">
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
                            <div x-show="status === 'found'" class="text-[11px] font-bold text-emerald-600 flex items-center gap-1" x-cloak>
                                <span>✓</span>
                                <span x-text="statusMsg"></span>
                            </div>
                            <div x-show="status === 'not_found'" class="text-[11px] font-bold text-rose-600 flex items-center gap-1" x-cloak>
                                <span>✕</span>
                                <span x-text="statusMsg"></span>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 uppercase">{{ __('messages.phone_whatsapp_number') }}
                                <span class="text-rose-500">*</span></label>
                            <input type="text" name="phone" value="{{ old('phone', $profile ? $profile->phone : '') }}" required
                                placeholder="9876543210" maxlength="10"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)"
                                class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 uppercase">{{ __('messages.gender_label') }} <span
                                    class="text-rose-500">*</span></label>
                            <select name="gender" required
                                class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                                <option value="Male" {{ old('gender', $profile ? $profile->gender : '') == 'Male' ? 'selected' : '' }}>{{ __('messages.gender_male') }}</option>
                                <option value="Female" {{ old('gender', $profile ? $profile->gender : '') == 'Female' ? 'selected' : '' }}>{{ __('messages.gender_female') }}</option>
                                <option value="Other" {{ old('gender', $profile ? $profile->gender : '') == 'Other' ? 'selected' : '' }}>{{ __('messages.gender_other') }}</option>
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 uppercase">{{ __('messages.date_of_birth_label') }}</label>
                            <input type="date" name="dob"
                                value="{{ old('dob', ($profile && $profile->dob && $profile->dob !== '0000-00-00') ? \Carbon\Carbon::parse($profile->dob)->format('Y-m-d') : '') }}"
                                max="{{ date('Y-m-d') }}"
                                class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                        </div>

                        <div class="space-y-1 sm:col-span-2">
                            <label
                                class="text-xs font-bold text-slate-500 uppercase">{{ __('messages.profile_photo_label') }}</label>
                            <div class="flex items-center space-x-3">
                                @if($profile && $profile->photo_path && $profile->photo_path !== 'NOT_SPECIFIED')
                                    <img src="{{ str_starts_with($profile->photo_path, 'http') ? $profile->photo_path : asset('storage/' . $profile->photo_path) }}"
                                        class="w-10 h-10 rounded-lg object-cover border border-slate-200 bg-white shadow-sm shrink-0">
                                @endif
                                <input type="file" name="photo" accept="image/*"
                                    class="w-full text-xs font-semibold px-3 py-1 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 3: ADDRESS DETAILS -->
                <div class="pt-2">
                    <h3
                        class="text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-3 pb-1.5 border-b border-slate-100">
                        3. {{ __('messages.address_location_sec') }}
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="space-y-1 sm:col-span-3">
                            <label class="text-xs font-bold text-slate-500 uppercase">{{ __('messages.street_address_label') }}
                                <span class="text-rose-500">*</span></label>
                            <textarea name="address" rows="3" required placeholder="Full street address..."
                                class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">{{ old('address', $profile ? $profile->address : '') }}</textarea>
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 uppercase">{{ __('messages.area') }}</label>
                            <select name="area_id"
                                class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                                <option value="">-- {{ __('messages.select_area') }} --</option>
                                @foreach($areas as $area)
                                    <option value="{{ $area->id }}" {{ old('area_id', $profile ? $profile->area_id : '') == $area->id ? 'selected' : '' }}>
                                        {{ $area->name }}{{ $area->pincode ? ' (' . $area->pincode . ')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 uppercase">{{ __('messages.city_label') }} <span
                                    class="text-rose-500">*</span></label>
                            <input type="text" name="city" value="{{ old('city', $profile ? $profile->city : 'Ahmedabad') }}"
                                required placeholder="Ahmedabad"
                                class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 uppercase">{{ __('messages.state_label') }} <span
                                    class="text-rose-500">*</span></label>
                            <input type="text" name="state" value="{{ old('state', $profile ? $profile->state : 'Gujarat') }}"
                                readonly required placeholder="Gujarat"
                                class="w-full text-xs font-bold px-3 py-2 bg-slate-100 text-slate-600 border border-slate-200 rounded-lg cursor-not-allowed select-none">
                        </div>
                    </div>
                </div>

                <!-- ACTIONS -->
                <div class="pt-4 border-t border-slate-100 flex justify-end items-center space-x-3">
                    <a href="{{ route('admin.members.index') }}"
                        class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs rounded-xl transition-colors">
                        {{ __('messages.cancel') }}
                    </a>
                    <button type="submit"
                        class="px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-bold text-xs rounded-xl shadow-sm transition-colors">
                        {{ __('messages.save_changes') }}
                    </button>
                </div>
            </form>
        </div>
    @endsection