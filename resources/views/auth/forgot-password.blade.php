@extends('layouts.public')

@section('content')
<section class="py-6 md:py-10 bg-slate-50/70 min-h-[calc(100vh-16rem)] flex items-center justify-center">
    <div class="max-w-md w-full px-4 sm:px-6">
        
        <div class="bg-white p-5 md:p-6 rounded-2xl border border-slate-200/60 shadow-md relative overflow-hidden space-y-4">
            
            <div class="text-center space-y-1.5">
                <div class="inline-flex items-center justify-center w-11 h-11 rounded-xl bg-amber-50 border border-amber-100 text-amber-600 shadow-2xs mx-auto">
                    <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>

                <div class="space-y-0.5">
                    <h2 class="text-base font-black text-slate-900 tracking-tight">
                        {{ __('messages.reset_password') }}
                    </h2>
                    <p class="text-[11px] font-medium text-slate-500 max-w-xs mx-auto">
                        {{ __('messages.forgot_password_subtitle') }}
                    </p>
                </div>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-2" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}" class="space-y-3 pt-1">
                @csrf

                <!-- Email Field -->
                <div class="space-y-0.5">
                    <label for="email" class="text-[10px] font-bold text-slate-700 uppercase tracking-wider">
                        {{ __('messages.email_address_label') }} <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            placeholder="{{ __('messages.email_placeholder') }}"
                            class="w-full text-xs font-semibold pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-0 text-slate-800 transition-all outline-hidden">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <div class="pt-1">
                    <button type="submit" 
                        class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-extrabold text-[10px] uppercase tracking-wider rounded-lg shadow-xs transition-all cursor-pointer gap-1.5">
                        <span>{{ __('messages.send_password_reset_link') }}</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </div>
            </form>

            <div class="pt-3 border-t border-slate-100 text-center">
                <a href="{{ route('login') }}" class="text-[11px] font-bold text-slate-600 hover:text-primary-600 transition-colors">
                    &larr; {{ __('messages.back_to_login') }}
                </a>
            </div>

        </div>

    </div>
</section>
@endsection
