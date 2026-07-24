@extends('layouts.public')

@section('content')
{{-- @include('partials.page_header', [
    'title' => 'Membership Registration',
    'subtitle' => 'Join the Sathwara Social Community Network',
    'breadcrumb' => 'Member Registration'
]) --}}

<!-- Registration Body -->
<section class="py-4 bg-slate-50/50">
    <div class="max-w-6xl mx-auto px-3 sm:px-4">
        
        <div class="bg-white border border-slate-200/80 rounded-lg p-4 md:p-5 shadow-sm">
            
            <!-- Form Header & Guidance -->
            <div class="mb-4 border-b border-slate-100 pb-2 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-semibold text-slate-800">Member Registration Form</h2>
                    <p class="text-[10px] text-slate-400 mt-0.5">Fill out your personal, contact, and address details below.</p>
                </div>
                <span class="text-[10px] font-bold text-slate-400 bg-slate-100 px-2.5 py-1 rounded-full"><span class="text-rose-500">*</span> Required Fields</span>
            </div>

            <!-- Errors Alert -->
            @if ($errors->any())
                <div class="mb-6 p-3.5 bg-rose-50 border border-rose-100 text-rose-800 rounded-xl text-xs">
                    <p class="font-bold mb-1">Please correct the following errors:</p>
                    <ul class="list-disc pl-4 text-[11px] space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register.member.submit') }}" class="space-y-4">
                @csrf

                <!-- SECTION 1: PERSONAL DETAILS -->
                <div>
                    <div class="flex items-center space-x-2 border-b border-slate-100 pb-2 mb-3">
                        <span class="w-4 h-4 rounded-md bg-primary-50 text-primary-600 font-black text-[9px] flex items-center justify-center">1</span>
                        <h3 class="text-[10px] font-semibold text-slate-700 uppercase tracking-wider">Personal Details</h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                        <div class="space-y-1">
                            <label class="text-[9px] font-semibold text-slate-500 uppercase tracking-wider">First Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" required class="w-full text-xs font-medium px-2 py-1.5 bg-slate-50/50 border border-slate-200 rounded-md focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-semibold text-slate-500 uppercase tracking-wider">Father's / Middle Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="middle_name" value="{{ old('middle_name') }}" required class="w-full text-xs font-medium px-2 py-1.5 bg-slate-50/50 border border-slate-200 rounded-md focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-semibold text-slate-500 uppercase tracking-wider">Surname / Last Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" required class="w-full text-xs font-medium px-2 py-1.5 bg-slate-50/50 border border-slate-200 rounded-md focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition">
                        </div>

                        <div class="space-y-1">
                            <label class="text-[9px] font-semibold text-slate-500 uppercase tracking-wider">Gender</label>
                            <select name="gender" class="w-full text-xs font-medium px-2 py-1.5 bg-slate-50/50 border border-slate-200 rounded-md focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition">
                                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-semibold text-slate-500 uppercase tracking-wider">Date of Birth</label>
                            <input type="date" name="dob" value="{{ old('dob') }}" class="w-full text-xs font-medium px-2 py-1.5 bg-slate-50/50 border border-slate-200 rounded-md focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-semibold text-slate-500 uppercase tracking-wider">Blood Group</label>
                            <input type="text" name="blood_group" value="{{ old('blood_group') }}" placeholder="e.g. O+ve" class="w-full text-xs font-medium px-2 py-1.5 bg-slate-50/50 border border-slate-200 rounded-md focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition">
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Education</label>
                            <input type="text" name="education" value="{{ old('education') }}" placeholder="e.g. B.Tech" class="w-full text-xs font-medium px-3 py-2 bg-slate-50/50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Occupation Details</label>
                            <input type="text" name="occupation" value="{{ old('occupation') }}" placeholder="e.g. Business Analyst" class="w-full text-xs font-medium px-3 py-2 bg-slate-50/50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Father's Member ID <span class="text-[9px] text-slate-400 font-normal">(If Registered)</span></label>
                            <input type="text" name="father_member_id" value="{{ old('father_member_id') }}" placeholder="e.g. #00005" class="w-full text-xs font-medium px-3 py-2 bg-slate-50/50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition">
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: CONTACT & ACCOUNT DETAILS -->
                <div>
                    <div class="flex items-center space-x-2 border-b border-slate-100 pb-2 mb-3">
                        <span class="w-4 h-4 rounded-md bg-primary-50 text-primary-600 font-black text-[9px] flex items-center justify-center">2</span>
                        <h3 class="text-[10px] font-semibold text-slate-700 uppercase tracking-wider">Contact & Login Account</h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                        <div class="space-y-1">
                            <label class="text-[9px] font-semibold text-slate-500 uppercase tracking-wider">Mobile / WhatsApp Number <span class="text-rose-500">*</span></label>
                            <input type="text" name="phone" value="{{ old('phone') }}" required maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)" class="w-full text-xs font-medium px-2 py-1.5 bg-slate-50/50 border border-slate-200 rounded-md focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-semibold text-slate-500 uppercase tracking-wider">Email Address <span class="text-rose-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" required class="w-full text-xs font-medium px-2 py-1.5 bg-slate-50/50 border border-slate-200 rounded-md focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition">
                        </div>

                        <div class="space-y-1 sm:col-span-1">
                            <label class="text-[9px] font-semibold text-slate-500 uppercase tracking-wider">Password <span class="text-rose-500">*</span></label>
                            <input type="password" name="password" required class="w-full text-xs font-medium px-2 py-1.5 bg-slate-50/50 border border-slate-200 rounded-md focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition">
                        </div>
                        <div class="space-y-1 sm:col-span-1">
                            <label class="text-[9px] font-semibold text-slate-500 uppercase tracking-wider">Confirm Password <span class="text-rose-500">*</span></label>
                            <input type="password" name="password_confirmation" required class="w-full text-xs font-medium px-2 py-1.5 bg-slate-50/50 border border-slate-200 rounded-md focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition">
                        </div>
                    </div>
                </div>

                <!-- SECTION 3: ADDRESS DETAILS -->
                <div>
                    <div class="flex items-center space-x-2 border-b border-slate-100 pb-2 mb-3">
                        <span class="w-4 h-4 rounded-md bg-primary-50 text-primary-600 font-black text-[9px] flex items-center justify-center">3</span>
                        <h3 class="text-[10px] font-semibold text-slate-700 uppercase tracking-wider">Address Details</h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                        <div class="space-y-1 sm:col-span-2">
                            <label class="text-[9px] font-semibold text-slate-500 uppercase tracking-wider">Address <span class="text-rose-500">*</span></label>
                            <textarea name="address" rows="2" required class="w-full text-xs font-medium px-2 py-1.5 bg-slate-50/50 border border-slate-200 rounded-md focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition">{{ old('address') }}</textarea>
                        </div>
                        <div class="space-y-1 sm:col-span-1">
                            <label class="text-[9px] font-semibold text-slate-500 uppercase tracking-wider">Area <span class="text-rose-500">*</span></label>
                            <select name="area_id" required class="w-full text-xs font-medium px-2 py-1.5 bg-slate-50/50 border border-slate-200 rounded-md focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition">
                                <option value="">-- Select Area --</option>
                                @foreach($areas as $area)
                                    <option value="{{ $area->id }}" {{ old('area_id') == $area->id ? 'selected' : '' }}>{{ $area->name }}{{ $area->pincode ? ' (' . $area->pincode . ')' : '' }}</option>
                                @endforeach
                            </select>
                            <p class="text-[10px] text-slate-400 mt-1">City, State and Pincode will be set from selected Area.</p>
                        </div>
                        <div class="sm:col-span-1">
                            <input type="hidden" name="city" value="{{ old('city', '') }}">
                            <input type="hidden" name="state" value="{{ old('state', 'Gujarat') }}">
                            <input type="hidden" name="pincode" value="{{ old('pincode') }}">
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-3 border-t border-slate-100 flex items-center justify-end">
                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-1.5 bg-primary-500 hover:bg-primary-600 font-semibold text-[11px] text-white uppercase tracking-wider rounded-md transition-transform active:scale-95">
                        Submit Application &rarr;
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
