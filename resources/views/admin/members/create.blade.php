@extends('layouts.admin')

@section('page_title', __('messages.add_new_member'))

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

            <form method="POST" action="{{ route('admin.members.store') }}" class="space-y-5" enctype="multipart/form-data">
                @csrf

                <!-- SECTION 1: LOGIN & ACCOUNT -->
                <div>
                    <h3
                        class="text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-3 pb-1.5 border-b border-slate-100">
                        1. {{ __('messages.account_credentials_status') }}
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="space-y-1 sm:col-span-2">
                            <label class="text-xs font-bold text-slate-500 uppercase">{{ __('messages.email_address_login') }}
                                <span class="text-rose-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                placeholder="member@community.com"
                                class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 uppercase">{{ __('messages.status') }} (Approval) <span
                                    class="text-rose-500">*</span></label>
                            <select name="status" required
                                class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                                <option value="approved" {{ old('status', 'approved') == 'approved' ? 'selected' : '' }}>
                                    {{ __('messages.approved') }}</option>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>
                                    {{ __('messages.pending') }}</option>
                                <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>
                                    {{ __('messages.rejected') }}</option>
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 uppercase">Account Status <span
                                    class="text-rose-500">*</span></label>
                            <select name="account_status" required
                                class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                                <option value="open" {{ old('account_status', 'open') == 'open' ? 'selected' : '' }}>Open (ચાલુ)</option>
                                <option value="close" {{ old('account_status') == 'close' ? 'selected' : '' }}>Close (બંધ)</option>
                            </select>
                        </div>

                        <div class="space-y-1 sm:col-span-2">
                            <label
                                class="text-xs font-bold text-slate-500 uppercase">{{ __('messages.security_credentials_sec') }}
                                <span class="text-rose-500">*</span></label>
                            <input type="password" name="password" required placeholder="{{ __('messages.password_min_8') }}"
                                class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                        </div>

                        <div class="space-y-1 sm:col-span-2">
                            <label class="text-xs font-bold text-slate-500 uppercase">{{ __('messages.confirm_password') }}
                                <span class="text-rose-500">*</span></label>
                            <input type="password" name="password_confirmation" required
                                placeholder="{{ __('messages.repeat_password') }}"
                                class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                        </div>
                    </div>
                </div>

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
                            <input type="text" name="first_name" value="{{ old('first_name') }}" required placeholder="Karan"
                                class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                        </div>

                        <div class="space-y-1">
                            <label
                                class="text-xs font-bold text-slate-500 uppercase">{{ __('messages.middle_name_label') }}</label>
                            <input type="text" name="middle_name" value="{{ old('middle_name') }}"
                                placeholder="Father's/Spouse's Name"
                                class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 uppercase">{{ __('messages.last_name_label') }} <span
                                    class="text-rose-500">*</span></label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" required placeholder="Satwara"
                                class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 uppercase">{{ __('messages.phone_whatsapp_number') }}
                                <span class="text-rose-500">*</span></label>
                            <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="9876543210"
                                maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)"
                                class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 uppercase">{{ __('messages.gender_label') }} <span
                                    class="text-rose-500">*</span></label>
                            <select name="gender" required
                                class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>
                                    {{ __('messages.gender_male') }}</option>
                                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>
                                    {{ __('messages.gender_female') }}</option>
                                <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>
                                    {{ __('messages.gender_other') }}</option>
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 uppercase">{{ __('messages.date_of_birth_label') }}
                                <span class="text-rose-500">*</span></label>
                            <input type="date" name="dob" value="{{ old('dob') }}" required max="{{ date('Y-m-d') }}"
                                class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                        </div>

                        <div class="space-y-1 sm:col-span-3">
                            <label
                                class="text-xs font-bold text-slate-500 uppercase">{{ __('messages.profile_photo_label') }}</label>
                            <input type="file" name="photo" accept="image/*"
                                class="w-full text-xs font-semibold px-3 py-1 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
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
                                class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">{{ old('address') }}</textarea>
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 uppercase">{{ __('messages.area') }}</label>
                            <select name="area_id"
                                class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                                <option value="">-- {{ __('messages.select_area') }} --</option>
                                @foreach($areas as $area)
                                    <option value="{{ $area->id }}" {{ old('area_id') == $area->id ? 'selected' : '' }}>
                                        {{ $area->name }}{{ $area->pincode ? ' (' . $area->pincode . ')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 uppercase">{{ __('messages.city_label') }} <span
                                    class="text-rose-500">*</span></label>
                            <input type="text" name="city" value="{{ old('city', 'Ahmedabad') }}" required
                                placeholder="Ahmedabad"
                                class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 uppercase">{{ __('messages.state_label') }} <span
                                    class="text-rose-500">*</span></label>
                            <input type="text" name="state" value="{{ old('state', 'Gujarat') }}" readonly required
                                placeholder="Gujarat"
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
                        {{ __('messages.add_member_button') }}
                    </button>
                </div>
            </form>
        </div>
    @endsection
@endsection