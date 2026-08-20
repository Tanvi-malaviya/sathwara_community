@extends('layouts.member')
@section('page_title', __('messages.account_settings'))

@section('content')
<div class="max-w-4xl space-y-6">

    @if($errors->any())
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 6000)" x-show="show" x-transition
             class="p-4 bg-rose-50 border border-rose-100 text-rose-800 rounded-xl shadow-sm flex items-start justify-between">
            <div>
                <p class="text-xs font-bold mb-2">{{ __('messages.please_correct_errors') }}</p>
                <ul class="list-disc pl-4 text-[11px] font-medium space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button @click="show = false" class="text-rose-500 font-bold ml-2">&times;</button>
        </div>
    @endif

    <!-- CARD 1: EMAIL ADDRESS UPDATE (WITH OTP FLOW) -->
    <div class="bg-white border border-slate-100 rounded-2xl p-5 sm:p-6 shadow-sm space-y-4">
        <div class="border-b border-slate-100 pb-3">
            <h3 class="text-sm sm:text-base font-black text-slate-900 flex items-center gap-2">
                <span>📧</span> {{ __('messages.update_email_address') }}
            </h3>
            <p class="text-xs text-slate-500 font-medium mt-0.5">{{ __('messages.update_email_desc') }}</p>
        </div>

        <!-- Current Email Display -->
        <div class="p-4 bg-slate-50 border border-slate-100 rounded-xl flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <span class="text-[10px] font-extrabold uppercase text-slate-400 tracking-wider">{{ __('messages.current_email_address') }}</span>
                <p class="text-xs font-bold text-slate-800 mt-0.5">{{ $user->email }}</p>
            </div>
            <div>
                <span class="text-[10px] font-black text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-md uppercase tracking-wider shadow-sm">{{ __('messages.verified_login') }}</span>
            </div>
        </div>

        @if(session('success_otp') || session('pending_email'))
            <!-- Step 2: OTP Verification Form -->
            <form method="POST" action="{{ route('member.account.settings.verify_otp') }}" class="space-y-4 pt-2">
                @csrf
                <div class="p-4 bg-blue-50/60 border border-blue-100 rounded-xl space-y-2">
                    <p class="text-xs text-blue-800 font-semibold">
                        {!! __('messages.verification_code_sent_to_email', ['email' => '<strong class="text-blue-950">'.e(session('pending_email')).'</strong>']) !!}
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-end">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700 block">{{ __('messages.verification_code_otp') }} <span class="text-rose-500">*</span></label>
                        <input type="text" name="otp" required maxlength="6" placeholder="{{ __('messages.enter_otp') }}"
                               class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 focus:outline-none tracking-widest text-center font-mono h-10">
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="submit" 
                                class="h-10 px-5 bg-primary-500 hover:bg-primary-600 text-white font-bold text-xs rounded-xl shadow-sm transition-colors uppercase tracking-wider w-full cursor-pointer">
                            {{ __('messages.verify_and_update') }}
                        </button>
                    </div>
                </div>
            </form>

            <!-- Option to cancel and start over -->
            <div class="pt-2 flex justify-start text-xs">
                <a href="{{ route('member.account.settings.cancel_otp') }}" class="text-rose-500 hover:text-rose-600 font-bold transition-colors">
                    &larr; {{ __('messages.cancel_email_change') }}
                </a>
            </div>
        @else
            <!-- Step 1: Request Email Change Form -->
            <form method="POST" action="{{ route('member.account.settings.send_otp') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-end">
                    <div class="space-y-1.5 sm:col-span-2">
                        <label class="text-xs font-bold text-slate-700 block">{{ __('messages.new_email_address') }} <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" required value="{{ old('email') }}" placeholder="example@newemail.com"
                               class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 focus:outline-none h-10">
                    </div>
                    <div>
                        <button type="submit" 
                                class="h-10 px-5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-sm transition-colors uppercase tracking-wider w-full cursor-pointer">
                            {{ __('messages.send_verification_otp') }}
                        </button>
                    </div>
                </div>
            </form>
        @endif
    </div>

    <!-- CARD 2: PASSWORD UPDATE -->
    <div class="bg-white border border-slate-100 rounded-2xl p-5 sm:p-6 shadow-sm space-y-4">
        <div class="border-b border-slate-100 pb-3">
            <h3 class="text-sm sm:text-base font-black text-slate-900 flex items-center gap-2">
                <span>🔐</span> {{ __('messages.update_account_password') }}
            </h3>
            <p class="text-xs text-slate-500 font-medium mt-0.5">{{ __('messages.update_password_desc') }}</p>
        </div>

        <form method="POST" action="{{ route('member.account.settings.update_password') }}" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700 block">{{ __('messages.current_password') }} <span class="text-rose-500">*</span></label>
                    <input type="password" name="current_password" required placeholder="••••••••"
                           class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 focus:outline-none h-10">
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700 block">{{ __('messages.new_password') }} <span class="text-rose-500">*</span></label>
                    <input type="password" name="password" required placeholder="••••••••"
                           class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 focus:outline-none h-10">
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700 block">{{ __('messages.confirm_new_password') }} <span class="text-rose-500">*</span></label>
                    <input type="password" name="password_confirmation" required placeholder="••••••••"
                           class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 focus:outline-none h-10">
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 flex justify-end">
                <button type="submit" 
                        class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-sm transition-colors uppercase tracking-wider cursor-pointer">
                    {{ __('messages.update_password_btn') }}
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
