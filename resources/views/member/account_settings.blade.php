@extends('layouts.member')
@section('page_title', 'Account Settings')

@section('content')
<div class="max-w-4xl space-y-6">

    <!-- Success & Error Alert Messages -->


    @if($errors->any())
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 6000)" x-show="show" x-transition
             class="p-4 bg-rose-50 border border-rose-100 text-rose-800 rounded-xl shadow-sm flex items-start justify-between">
            <div>
                <p class="text-xs font-bold mb-2">Please correct the following errors:</p>
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
    <div class="bg-white border border-slate-100 rounded-xl p-5 shadow-sm space-y-4">
        <div class="border-b border-slate-100 pb-3">
            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                <span>📧</span> Update Email Address
            </h3>
            <p class="text-[11px] text-slate-400 font-medium mt-0.5">Change your login email address. This will require verification via OTP.</p>
        </div>

        <!-- Current Email Display -->
        <div class="p-4 bg-slate-50 border border-slate-100 rounded-xl flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <span class="text-[9px] font-extrabold uppercase text-slate-400 tracking-wider">Current Email Address</span>
                <p class="text-xs font-bold text-slate-800 mt-0.5">{{ $user->email }}</p>
            </div>
            <div>
                <span class="text-[9px] font-black text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-md uppercase tracking-wider shadow-sm">Verified Login</span>
            </div>
        </div>

        @if(session('success_otp') || session('pending_email'))
            <!-- Step 2: OTP Verification Form -->
            <form method="POST" action="{{ route('member.account.settings.verify_otp') }}" class="space-y-4 pt-2">
                @csrf
                <div class="p-4 bg-blue-50/60 border border-blue-100 rounded-xl space-y-2">
                    <p class="text-[11px] text-blue-800 font-semibold">
                        A verification code was sent to <strong class="text-blue-950">{{ session('pending_email') }}</strong>. Please check your inbox (including spam folder).
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-end">
                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-slate-500 uppercase">Verification Code (OTP) *</label>
                        <input type="text" name="otp" required maxlength="6" placeholder="Enter 6-digit OTP"
                               class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:outline-none tracking-widest text-center font-mono">
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="submit" 
                                class="px-5 py-2.5 bg-primary-500 hover:bg-primary-600 text-white font-bold text-xs rounded-xl shadow-sm transition-colors uppercase tracking-wider w-full">
                            Verify & Update
                        </button>
                    </div>
                </div>
            </form>

            <!-- Option to cancel and start over -->
            <div class="pt-2 flex justify-start text-[11px]">
                <a href="{{ route('member.account.settings') }}" class="text-rose-500 hover:text-rose-600 font-bold transition-colors">
                    &larr; Cancel and change email again
                </a>
            </div>
        @else
            <!-- Step 1: Request Email Change Form -->
            <form method="POST" action="{{ route('member.account.settings.send_otp') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-end">
                    <div class="space-y-1 sm:col-span-2">
                        <label class="text-[11px] font-bold text-slate-500 uppercase">New Email Address *</label>
                        <input type="email" name="email" required value="{{ old('email') }}" placeholder="example@newemail.com"
                               class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:outline-none">
                    </div>
                    <div>
                        <button type="submit" 
                                class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-sm transition-colors uppercase tracking-wider w-full">
                            Send Verification OTP
                        </button>
                    </div>
                </div>
            </form>
        @endif
    </div>

    <!-- CARD 2: PASSWORD UPDATE -->
    <div class="bg-white border border-slate-100 rounded-xl p-5 shadow-sm space-y-4">
        <div class="border-b border-slate-100 pb-3">
            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                <span>🔐</span> Update Account Password
            </h3>
            <p class="text-[11px] text-slate-400 font-medium mt-0.5">Change your login password. Keep it secure and private.</p>
        </div>

        <form method="POST" action="{{ route('member.account.settings.update_password') }}" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-500 uppercase">Current Password *</label>
                    <input type="password" name="current_password" required placeholder="••••••••"
                           class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:outline-none">
                </div>

                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-500 uppercase">New Password *</label>
                    <input type="password" name="password" required placeholder="••••••••"
                           class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:outline-none">
                </div>

                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-500 uppercase">Confirm Password *</label>
                    <input type="password" name="password_confirmation" required placeholder="••••••••"
                           class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 focus:outline-none">
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 flex justify-end">
                <button type="submit" 
                        class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-sm transition-colors uppercase tracking-wider">
                    Update Password
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
