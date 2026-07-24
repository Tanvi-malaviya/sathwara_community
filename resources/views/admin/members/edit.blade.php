@extends('layouts.admin')

@section('page_title', __('messages.edit_member_details'))

@section('content')
<div class="max-w-6xl bg-white border border-slate-100 rounded-xl p-6 shadow-sm">
    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-800 rounded-2xl">
            <p class="text-xs font-bold mb-2">{{ __('messages.please_correct_errors') }}</p>
            <ul class="list-disc pl-4 text-[11px] font-medium space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.members.update', $member->id) }}" class="space-y-4" enctype="multipart/form-data">
        @csrf
        
        <!-- SECTION 1: LOGIN & ACCOUNT -->
        <div>
            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-3 pb-1.5 border-b border-slate-100">
                {{ __('messages.account_credentials_status') }}
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-500 uppercase">{{ __('messages.username_display_name') }}</label>
                    <input type="text" name="name" value="{{ old('name', $member->name) }}" required placeholder="e.g. Karan Sathwara" 
                           class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                </div>

                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-500 uppercase">{{ __('messages.email_address_login') }}</label>
                    <input type="email" name="email" value="{{ old('email', $member->email) }}" required placeholder="member@community.com" 
                           class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                </div>

                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-500 uppercase">{{ __('messages.status') }}</label>
                    <select name="status" required class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                        <option value="pending" {{ old('status', $member->status) == 'pending' ? 'selected' : '' }}>{{ __('messages.pending') }}</option>
                        <option value="approved" {{ old('status', $member->status) == 'approved' ? 'selected' : '' }}>{{ __('messages.approved') }}</option>
                        <option value="rejected" {{ old('status', $member->status) == 'rejected' ? 'selected' : '' }}>{{ __('messages.rejected') }}</option>
                    </select>
                </div>
            </div>
        </div>

        @php
            $profile = $member->memberProfile;
        @endphp

        <!-- SECTION 2: PERSONAL DETAILS -->
        <div class="pt-2">
            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-3 pb-1.5 border-b border-slate-100">
                {{ __('messages.personal_details_sec') }}
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-500 uppercase">{{ __('messages.first_name_label') }}</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $profile ? $profile->first_name : '') }}" required placeholder="First Name" 
                           class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                </div>

                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-500 uppercase">{{ __('messages.middle_name_label') }}</label>
                    <input type="text" name="middle_name" value="{{ old('middle_name', $profile ? $profile->middle_name : '') }}" placeholder="Middle Name" 
                           class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                </div>

                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-500 uppercase">{{ __('messages.last_name_label') }}</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $profile ? $profile->last_name : '') }}" required placeholder="Last Name" 
                           class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                </div>

                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-500 uppercase">{{ __('messages.father_member_id_label') }}</label>
                    <input type="text" name="father_member_id" value="{{ old('father_member_id', $profile ? $profile->father_member_id : '') }}" placeholder="e.g. #00005" 
                           class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                </div>

                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-500 uppercase">{{ __('messages.phone_number_label') }}</label>
                    <input type="text" name="phone" value="{{ old('phone', $profile ? $profile->phone : '') }}" required placeholder="9876543210" 
                           maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)"
                           class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                </div>

                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-500 uppercase">{{ __('messages.whatsapp_number_label') }}</label>
                    <input type="text" name="whatsapp" value="{{ old('whatsapp', $profile ? $profile->whatsapp : '') }}" placeholder="WhatsApp contact" 
                           maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)"
                           class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                </div>

                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-500 uppercase">{{ __('messages.gender_label') }}</label>
                    <select name="gender" required class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                        <option value="Male" {{ old('gender', $profile ? $profile->gender : '') == 'Male' ? 'selected' : '' }}>{{ __('messages.gender_male') }}</option>
                        <option value="Female" {{ old('gender', $profile ? $profile->gender : '') == 'Female' ? 'selected' : '' }}>{{ __('messages.gender_female') }}</option>
                        <option value="Other" {{ old('gender', $profile ? $profile->gender : '') == 'Other' ? 'selected' : '' }}>{{ __('messages.gender_other') }}</option>
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-500 uppercase">{{ __('messages.date_of_birth_label') }}</label>
                    <input type="date" name="dob" value="{{ old('dob', $profile ? ($profile->dob ? \Carbon\Carbon::parse($profile->dob)->format('Y-m-d') : '') : '') }}" required 
                           class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                </div>

                <div class="space-y-1 sm:col-span-2">
                    <label class="text-[11px] font-bold text-slate-500 uppercase">{{ __('messages.profile_photo_label') }}</label>
                    <div class="flex items-center space-x-3">
                        @if($profile && $profile->photo_path && $profile->photo_path !== 'NOT_SPECIFIED')
                            <img src="{{ str_starts_with($profile->photo_path, 'http') ? $profile->photo_path : asset('storage/' . $profile->photo_path) }}" 
                                 class="w-8 h-8 rounded-lg object-cover border border-slate-200 bg-white shadow-sm shrink-0">
                        @endif
                        <input type="file" name="photo" accept="image/*"
                               class="w-full text-xs font-semibold px-3 py-1 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 3: ADDRESS DETAILS -->
        <div class="pt-2">
            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-3 pb-1.5 border-b border-slate-100">
                {{ __('messages.address_location_sec') }}
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="space-y-1 sm:col-span-3">
                    <label class="text-[11px] font-bold text-slate-500 uppercase">{{ __('messages.street_address_label') }}</label>
                    <textarea name="address" rows="3" required placeholder="Full street address..." 
                              class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">{{ old('address', $profile ? $profile->address : '') }}</textarea>
                </div>

                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-500 uppercase">{{ __('messages.city_label') }}</label>
                    <input type="text" name="city" value="{{ old('city', $profile ? $profile->city : '') }}" required placeholder="Ahmedabad" 
                           class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                </div>

                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-500 uppercase">{{ __('messages.state_label') }}</label>
                    <input type="text" name="state" value="{{ old('state', $profile ? $profile->state : 'Gujarat') }}" required placeholder="Gujarat" 
                           class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                </div>

                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-500 uppercase">{{ __('messages.pincode_label') }}</label>
                    <input type="text" name="pincode" value="{{ old('pincode', $profile ? $profile->pincode : '') }}" required placeholder="380001" 
                           class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                </div>
            </div>
        </div>

        <!-- ACTIONS -->
        <div class="pt-4 border-t border-slate-100 flex justify-end items-center space-x-3">
            <a href="{{ route('admin.members.show', $member->id) }}" 
               class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs rounded-xl transition-colors">
                {{ __('messages.cancel') }}
            </a>
            <button type="submit" 
                    class="px-5 py-2.5 bg-primary-500 hover:bg-primary-600 text-white font-bold text-xs rounded-xl shadow-sm transition-colors">
                {{ __('messages.save_changes') }}
            </button>
        </div>
    </form>
</div>
@endsection
