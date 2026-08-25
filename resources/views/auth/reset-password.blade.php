@extends('layouts.public')

@section('content')
<section class="py-6 md:py-10 bg-slate-50/70 min-h-[calc(100vh-16rem)] flex items-center justify-center">
    <div class="max-w-md w-full px-4 sm:px-6">
        
        <div class="bg-white p-5 md:p-6 rounded-2xl border border-slate-200/60 shadow-md relative overflow-hidden space-y-4">
            
            <div class="text-center space-y-1.5">
                <div class="inline-flex items-center justify-center w-11 h-11 rounded-xl bg-amber-50 border border-amber-100 text-amber-600 shadow-2xs mx-auto">
                    <!-- Lock / Password Reset Icon -->
                    <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>

                <div class="space-y-0.5">
                    <h2 class="text-base font-black text-slate-900 tracking-tight">
                        {{ __('messages.create_new_password') }}
                    </h2>
                    <p class="text-[11px] font-medium text-slate-500 max-w-xs mx-auto">
                        {{ __('messages.create_new_password_subtitle') }}
                    </p>
                </div>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="text-xs font-semibold text-emerald-600 bg-emerald-50 border border-emerald-100 rounded-lg p-2.5 text-center">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.store') }}" class="space-y-3 pt-1">
                @csrf

                <!-- Password Field -->
                <div class="space-y-0.5" x-data="{ showPass: false }">
                    <label for="password" class="text-[10px] font-bold text-slate-700 uppercase tracking-wider">
                        {{ __('messages.new_password') }} <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input id="password" :type="showPass ? 'text' : 'password'" name="password" required autocomplete="new-password"
                            placeholder="••••••••"
                            class="w-full text-xs font-semibold pl-9 pr-9 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-0 text-slate-800 transition-all outline-hidden">
                        <button type="button" @click="showPass = !showPass" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer"
                            title="{{ __('messages.toggle_password_visibility') }}">
                            <svg x-show="!showPass" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showPass" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.018 10.018 0 013.682-.863c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21M3 3l18 18"/>
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                <!-- Confirm Password Field -->
                <div class="space-y-0.5" x-data="{ showConfirmPass: false }">
                    <label for="password_confirmation" class="text-[10px] font-bold text-slate-700 uppercase tracking-wider">
                        {{ __('messages.confirm_password') }} <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input id="password_confirmation" :type="showConfirmPass ? 'text' : 'password'" name="password_confirmation" required autocomplete="new-password"
                            placeholder="••••••••"
                            class="w-full text-xs font-semibold pl-9 pr-9 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:ring-0 text-slate-800 transition-all outline-hidden">
                        <button type="button" @click="showConfirmPass = !showConfirmPass" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer"
                            title="{{ __('messages.toggle_password_visibility') }}">
                            <svg x-show="!showConfirmPass" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showConfirmPass" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.018 10.018 0 013.682-.863c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21M3 3l18 18"/>
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                </div>

                <div class="pt-1">
                    <button type="submit" 
                        class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-extrabold text-[10px] uppercase tracking-wider rounded-lg shadow-xs transition-all cursor-pointer gap-1.5">
                        <span>{{ __('messages.reset_password_login') }}</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </form>

        </div>

    </div>
</section>
@endsection
