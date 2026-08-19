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
            <div class="mb-5 border-b border-slate-100 pb-3 flex items-center justify-between flex-wrap gap-2">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-800">{{ __('messages.member_registration_form') }}</h2>
                    <p class="text-xs text-slate-500 mt-0.5">{{ __('messages.fill_registration_details') }}</p>
                </div>
                <span class="text-xs font-bold text-slate-500 bg-slate-100 px-3 py-1 rounded-full"><span class="text-rose-500">*</span> {{ __('messages.required_fields') }}</span>
            </div>

            <!-- Errors Alert -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-800 rounded-xl text-sm">
                    <p class="font-bold mb-1">{{ __('messages.please_correct_errors') }}</p>
                    <ul class="list-disc pl-4 text-xs space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register.member.submit') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- SECTION 1: PERSONAL DETAILS -->
                <div>
                    <div class="flex items-center space-x-2 border-b border-slate-100 pb-2.5 mb-4">
                        <span class="w-5 h-5 rounded-md bg-primary-50 text-primary-600 font-black text-xs flex items-center justify-center">1</span>
                        <h3 class="text-xs sm:text-sm font-bold text-slate-800 uppercase tracking-wider">{{ __('messages.personal_details') }}</h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">{{ __('messages.first_name') }} <span class="text-rose-500">*</span></label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" required class="w-full text-sm font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition">
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">{{ __('messages.father_middle_name') }} <span class="text-rose-500">*</span></label>
                            <input type="text" name="middle_name" value="{{ old('middle_name') }}" required class="w-full text-sm font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition">
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">{{ __('messages.surname_last_name') }} <span class="text-rose-500">*</span></label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" required class="w-full text-sm font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition">
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">{{ __('messages.gender') }}</label>
                            <select name="gender" class="w-full text-sm font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition">
                                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>{{ __('messages.gender_male') }}</option>
                                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>{{ __('messages.gender_female') }}</option>
                                <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>{{ __('messages.gender_other') }}</option>
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">{{ __('messages.dob') }}</label>
                            <input type="date" name="dob" value="{{ old('dob') }}" class="w-full text-sm font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition">
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">{{ __('messages.blood_group') }}</label>
                            <select name="blood_group" class="w-full text-sm font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition">
                                <option value="">-- {{ __('messages.select_blood_group') }} --</option>
                                <option value="A+" {{ old('blood_group') == 'A+' ? 'selected' : '' }}>A+</option>
                                <option value="A-" {{ old('blood_group') == 'A-' ? 'selected' : '' }}>A-</option>
                                <option value="B+" {{ old('blood_group') == 'B+' ? 'selected' : '' }}>B+</option>
                                <option value="B-" {{ old('blood_group') == 'B-' ? 'selected' : '' }}>B-</option>
                                <option value="AB+" {{ old('blood_group') == 'AB+' ? 'selected' : '' }}>AB+</option>
                                <option value="AB-" {{ old('blood_group') == 'AB-' ? 'selected' : '' }}>AB-</option>
                                <option value="O+" {{ old('blood_group') == 'O+' ? 'selected' : '' }}>O+</option>
                                <option value="O-" {{ old('blood_group') == 'O-' ? 'selected' : '' }}>O-</option>
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">{{ __('messages.education') }}</label>
                            <input type="text" name="education" value="{{ old('education') }}" placeholder="{{ __('messages.education_placeholder') }}" class="w-full text-sm font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition">
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">{{ __('messages.occupation_details_label') }}</label>
                            <input type="text" name="occupation" value="{{ old('occupation') }}" placeholder="{{ __('messages.occupation_placeholder') }}" class="w-full text-sm font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition">
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">{{ __('messages.father_member_id') }} <span class="text-xs text-slate-400 font-normal">{{ __('messages.if_registered') }}</span></label>
                            <input type="text" name="father_member_id" value="{{ old('father_member_id') }}" placeholder="{{ __('messages.father_id_placeholder') }}" class="w-full text-sm font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition">
                        </div>

                        <!-- Profile Photo Upload -->
                        <div class="space-y-1 sm:col-span-3">
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">{{ __('messages.profile_photo') ?? 'Profile Photo' }} <span class="text-xs text-slate-400 font-normal">(JPG, PNG max 2MB)</span></label>
                            <input type="file" name="photo" accept="image/*" class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-bold file:bg-primary-50 file:text-primary-600 hover:file:bg-primary-100 cursor-pointer">
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: CONTACT & ACCOUNT DETAILS -->
                <div>
                    <div class="flex items-center space-x-2 border-b border-slate-100 pb-2.5 mb-4">
                        <span class="w-5 h-5 rounded-md bg-primary-50 text-primary-600 font-black text-xs flex items-center justify-center">2</span>
                        <h3 class="text-xs sm:text-sm font-bold text-slate-800 uppercase tracking-wider">{{ __('messages.contact_login_account') }}</h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">{{ __('messages.mobile_whatsapp_number') }} <span class="text-rose-500">*</span></label>
                            <input type="text" name="phone" value="{{ old('phone') }}" required maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)" class="w-full text-sm font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition">
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">{{ __('messages.member_email_id') }} <span class="text-rose-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" required class="w-full text-sm font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition">
                        </div>

                        <div class="space-y-1 sm:col-span-1">
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">{{ __('messages.password') }} <span class="text-rose-500">*</span></label>
                            <input type="password" name="password" required class="w-full text-sm font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition">
                        </div>
                        <div class="space-y-1 sm:col-span-1">
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">{{ __('messages.confirm_password') }} <span class="text-rose-500">*</span></label>
                            <input type="password" name="password_confirmation" required class="w-full text-sm font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition">
                        </div>
                    </div>
                </div>

                <!-- SECTION 3: ADDRESS DETAILS -->
                <div>
                    <div class="flex items-center space-x-2 border-b border-slate-100 pb-2.5 mb-4">
                        <span class="w-5 h-5 rounded-md bg-primary-50 text-primary-600 font-black text-xs flex items-center justify-center">3</span>
                        <h3 class="text-xs sm:text-sm font-bold text-slate-800 uppercase tracking-wider">{{ __('messages.address_details') }}</h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div class="space-y-1 sm:col-span-2">
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">{{ __('messages.address') }} <span class="text-rose-500">*</span></label>
                            <textarea name="address" rows="2" required class="w-full text-sm font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition">{{ old('address') }}</textarea>
                        </div>
                        <div class="space-y-1 sm:col-span-1">
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">{{ __('messages.area') }} <span class="text-rose-500">*</span></label>
                            <select name="area_id" required class="w-full text-sm font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition">
                                <option value="">-- {{ __('messages.select_area') }} --</option>
                                @foreach($areas as $area)
                                    <option value="{{ $area->id }}" {{ old('area_id') == $area->id ? 'selected' : '' }}>{{ $area->name }}{{ $area->pincode ? ' (' . $area->pincode . ')' : '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1 sm:col-span-1">
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">{{ __('messages.city') }} <span class="text-rose-500">*</span></label>
                            <input type="text" name="city" value="{{ old('city') }}" placeholder="e.g. Ahmedabad" required class="w-full text-sm font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition">
                            <input type="hidden" name="state" value="{{ old('state', 'Gujarat') }}">
                            <input type="hidden" name="pincode" value="{{ old('pincode') }}">
                        </div>
                    </div>
                </div>

                <!-- SECTION 4: PAYMENT SUMMARY & SUBMISSION -->
                <div>
                    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
                    @if(($signupFee ?? 1000) > 0)
                        <div class="bg-primary-50/90 border border-primary-200/80 rounded-xl p-3.5 mb-3 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span class="text-lg">💳</span>
                                <div>
                                    <h4 class="text-xs sm:text-sm font-bold text-primary-900">{{ __('messages.membership_signup_fee') }}</h4>
                                    <p class="text-xs font-medium text-primary-700">{{ __('messages.payment_processed_securely') }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-base font-black text-primary-700">₹{{ number_format($signupFee ?? 1000) }}</span>
                                <span class="block text-xs font-extrabold text-slate-400">{{ __('messages.one_time_fee') }}</span>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Submit Button -->
                <div class="pt-3 border-t border-slate-100 flex items-center justify-end">
                    <button type="submit" id="submitMemberBtn" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-primary-600 hover:bg-primary-700 font-extrabold text-xs sm:text-sm text-white uppercase tracking-wider rounded-xl transition-all active:scale-95 shadow-md hover:shadow-lg cursor-pointer">
                        <span>{{ __('messages.pay_and_register_member', ['amount' => number_format($signupFee ?? 1000)]) }}</span> &rarr;
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

@if(($signupFee ?? 1000) > 0)
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('memberRegisterForm') || document.querySelector('form[action="{{ route('register.member.submit') }}"]');
    if (!form) return;

    form.id = 'memberRegisterForm';

    form.addEventListener('submit', function (e) {
        const paymentIdInput = document.getElementById('razorpay_payment_id');
        if (paymentIdInput && paymentIdInput.value) {
            return true; // Already paid, allow normal submit
        }

        e.preventDefault();

        // Trigger HTML5 validation check
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const razorpayKey = "{{ $razorpayKeyId ?? '' }}";
        const feeAmountPaise = {{ ($signupFee ?? 1000) * 100 }};
        const firstName = form.querySelector('[name="first_name"]')?.value || '';
        const lastName = form.querySelector('[name="last_name"]')?.value || '';
        const email = form.querySelector('[name="email"]')?.value || '';
        const phone = form.querySelector('[name="phone"]')?.value || '';

        const options = {
            "key": razorpayKey || "rzp_test_key",
            "amount": feeAmountPaise,
            "currency": "INR",
            "name": "{{ config('app.name', 'Sathwara Community') }}",
            "description": "Membership Registration Fee",
            "handler": function (response) {
                paymentIdInput.value = response.razorpay_payment_id;
                form.submit();
            },
            "prefill": {
                "name": firstName + " " + lastName,
                "email": email,
                "contact": phone
            },
            "theme": {
                "color": "#2563EB"
            }
        };

        if (window.Razorpay) {
            const rzp = new Razorpay(options);
            rzp.open();
        } else {
            alert('Razorpay Payment Gateway failed to load. Submitting application...');
            form.submit();
        }
    });
});
</script>
@endif
@endsection

