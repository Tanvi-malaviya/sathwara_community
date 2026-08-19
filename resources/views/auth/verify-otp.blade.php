@extends('layouts.public')

@section('content')
<section class="py-6 md:py-10 bg-slate-50/70 min-h-[calc(100vh-16rem)] flex items-center justify-center">
    <div class="max-w-md w-full px-4 sm:px-6">
        
        <div class="bg-white p-5 md:p-6 rounded-2xl border border-slate-200/60 shadow-md relative overflow-hidden space-y-4">
            
            <div class="text-center space-y-1.5">
                <div class="inline-flex items-center justify-center w-11 h-11 rounded-xl bg-amber-50 border border-amber-100 text-amber-600 shadow-2xs mx-auto">
                    <!-- Key / OTP Icon -->
                    <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>

                <div class="space-y-0.5">
                    <h2 class="text-base font-black text-slate-900 tracking-tight">
                        {{ __('messages.verify_otp') }}
                    </h2>
                    <p class="text-[11px] font-medium text-slate-500 max-w-xs mx-auto">
                        {{ __('messages.verify_otp_subtitle') }}
                    </p>
                </div>
            </div>

            <!-- Session Status / Expiry Alerts -->
            @if (session('status'))
                <div class="text-xs font-semibold text-emerald-600 bg-emerald-50 border border-emerald-100 rounded-lg p-2.5 text-center">
                    @if(session('status') == 'We have emailed your password reset verification code.')
                        {{ __('messages.otp_email_sent_status') }}
                    @elseif(session('status') == 'Code verified. You can now reset your password.')
                        {{ __('messages.otp_code_verified_status') }}
                    @elseif(session('status') == 'Your password has been successfully reset. You can now log in.')
                        {{ __('messages.password_reset_success_status') }}
                    @else
                        {{ session('status') }}
                    @endif
                </div>
            @endif

            <form method="POST" action="{{ route('password.otp.verify.submit') }}" class="space-y-3 pt-1">
                @csrf

                <!-- OTP Code Field -->
                <div class="space-y-0.5">
                    <label for="otp" class="text-[10px] font-bold text-slate-700 uppercase tracking-wider">
                        {{ __('messages.six_digit_code') }} <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input id="otp" type="text" name="otp" required autofocus
                            placeholder="123456" maxlength="6" pattern="[0-9]{6}" inputmode="numeric"
                            class="w-full text-center text-sm font-bold tracking-widest pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-0 text-slate-800 transition-all outline-hidden">
                    </div>
                    <x-input-error :messages="$errors->get('otp')" class="mt-1" />
                </div>

                <div class="pt-1">
                    <button type="submit" 
                        class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-extrabold text-[10px] uppercase tracking-wider rounded-lg shadow-xs transition-all cursor-pointer gap-1.5">
                        <span>{{ __('messages.verify_code_continue') }}</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </form>

            <div class="pt-3 border-t border-slate-100 text-center flex justify-between items-center text-[11px] font-bold text-slate-500">
                <a href="{{ route('password.request') }}" class="text-slate-600 hover:text-primary-600 transition-colors">
                    &larr; {{ __('messages.request_new_otp') }}
                </a>
                <a href="{{ route('login') }}" class="text-slate-600 hover:text-primary-600 transition-colors">
                    {{ __('messages.back_to_login') }}
                </a>
            </div>

        </div>

    </div>
</section>
@endsection
