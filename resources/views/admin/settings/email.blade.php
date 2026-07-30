@extends('layouts.admin')

@section('page_title', __('messages.email_smtp_settings'))

@section('content')
<div class="space-y-6 w-full max-w-6xl mx-auto">
    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-2xl p-6 text-white shadow-md flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <span class="p-2 bg-indigo-500/20 text-indigo-300 rounded-xl border border-indigo-500/30">📧</span>
                <h1 class="text-xl font-black tracking-tight">{{ __('messages.email_smtp_settings') }}</h1>
            </div>
            <p class="text-xs text-slate-300 font-medium max-w-2xl">
                {{ __('messages.email_settings_subtitle') }}
            </p>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                ⚡ {{ __('messages.auto_env_sync') }}
            </span>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold rounded-2xl flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2">
                <span class="text-base">✅</span>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 text-sm font-black">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold rounded-2xl flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2">
                <span class="text-base">❌</span>
                <span>{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700 text-sm font-black">&times;</button>
        </div>
    @endif

    @if(isset($errors) && $errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold rounded-2xl">
            <div class="font-extrabold mb-1">⚠️ {{ __('messages.please_correct_errors') }}:</div>
            <ul class="list-disc pl-5 space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form: SMTP Configuration (2 Columns) -->
        <div class="lg:col-span-2 bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-4 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-black text-slate-900 tracking-tight flex items-center gap-2">
                        <span>⚙️</span>
                        <span>{{ __('messages.smtp_server_configuration') }}</span>
                    </h2>
                    <p class="text-[11px] text-slate-500 font-medium mt-0.5">{{ __('messages.configure_smtp_credentials') }}</p>
                </div>
                <span class="text-[10px] font-bold text-slate-400 bg-slate-100 px-2.5 py-1 rounded-lg uppercase">
                    {{ __('messages.env_file_managed') }}
                </span>
            </div>

            <form method="POST" action="{{ route('admin.email_settings.update') }}" class="space-y-5">
                @csrf

                <!-- Row 1: Mailer Driver & Encryption -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block">
                            {{ __('messages.mail_driver') }} <span class="text-rose-500">*</span>
                        </label>
                        <select name="mail_mailer" class="h-10 w-full text-xs font-bold px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 transition-colors">
                            <option value="smtp" {{ old('mail_mailer', $emailSettings['mail_mailer']) === 'smtp' ? 'selected' : '' }}>SMTP (Recommended)</option>
                            <option value="sendmail" {{ old('mail_mailer', $emailSettings['mail_mailer']) === 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                            <option value="log" {{ old('mail_mailer', $emailSettings['mail_mailer']) === 'log' ? 'selected' : '' }}>Log (Testing Only)</option>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block">
                            {{ __('messages.mail_encryption') }}
                        </label>
                        <select name="mail_encryption" class="h-10 w-full text-xs font-bold px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 transition-colors">
                            <option value="tls" {{ old('mail_encryption', $emailSettings['mail_encryption']) === 'tls' ? 'selected' : '' }}>TLS (Port 587)</option>
                            <option value="ssl" {{ old('mail_encryption', $emailSettings['mail_encryption']) === 'ssl' ? 'selected' : '' }}>SSL (Port 465)</option>
                            <option value="null" {{ in_array(old('mail_encryption', $emailSettings['mail_encryption']), ['null', 'none', '']) ? 'selected' : '' }}>None (Port 25)</option>
                        </select>
                    </div>
                </div>

                <!-- Row 2: Host & Port -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-2 space-y-1.5">
                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block">
                            {{ __('messages.smtp_host') }} <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="mail_host" value="{{ old('mail_host', $emailSettings['mail_host']) }}" required placeholder="e.g. smtp.gmail.com or mail.domain.com" class="h-10 w-full text-xs font-semibold px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 transition-colors">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block">
                            {{ __('messages.smtp_port') }} <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" name="mail_port" value="{{ old('mail_port', $emailSettings['mail_port']) }}" required placeholder="587" class="h-10 w-full text-xs font-semibold px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 transition-colors">
                    </div>
                </div>

                <!-- Row 3: Username & Password -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block">
                            {{ __('messages.smtp_username') }}
                        </label>
                        <input type="text" name="mail_username" value="{{ old('mail_username', $emailSettings['mail_username']) }}" placeholder="user@gmail.com" class="h-10 w-full text-xs font-semibold px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 transition-colors">
                    </div>

                    <div class="space-y-1.5" x-data="{ showPass: false }">
                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block">
                            {{ __('messages.smtp_password') }}
                        </label>
                        <div class="relative">
                            <input :type="showPass ? 'text' : 'password'" name="mail_password" value="{{ old('mail_password', $emailSettings['mail_password']) }}" placeholder="••••••••••••" class="h-10 w-full text-xs font-semibold pl-3 pr-10 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 transition-colors">
                            <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                                <span x-text="showPass ? '🙈' : '👁️'" class="text-xs"></span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Row 4: From Address & From Name -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-slate-100">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block">
                            {{ __('messages.from_email_address') }} <span class="text-rose-500">*</span>
                        </label>
                        <input type="email" name="mail_from_address" value="{{ old('mail_from_address', $emailSettings['mail_from_address']) }}" required placeholder="noreply@sathwaracommunity.com" class="h-10 w-full text-xs font-semibold px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 transition-colors">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block">
                            {{ __('messages.from_sender_name') }} <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="mail_from_name" value="{{ old('mail_from_name', $emailSettings['mail_from_name']) }}" required placeholder="Sathwara Community Portal" class="h-10 w-full text-xs font-semibold px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 transition-colors">
                    </div>
                </div>

                <!-- Save Action Button -->
                <div class="pt-4 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-extrabold text-xs uppercase tracking-wider rounded-xl shadow-sm transition-all flex items-center gap-2 cursor-pointer">
                        <span>💾</span>
                        <span>{{ __('messages.save_email_settings') }}</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Sidebar Widget: Send Test Email (1 Column) -->
        <div class="space-y-6">
            <div class="bg-gradient-to-br from-indigo-900 via-slate-900 to-slate-950 text-white border border-indigo-900/50 rounded-2xl p-6 shadow-md space-y-4">
                <div class="border-b border-indigo-800/50 pb-3 flex items-center gap-2">
                    <span class="text-lg">🧪</span>
                    <div>
                        <h3 class="text-sm font-extrabold tracking-tight">{{ __('messages.send_test_email') }}</h3>
                        <p class="text-[10px] text-indigo-300 font-medium">{{ __('messages.test_smtp_configuration') }}</p>
                    </div>
                </div>

                <p class="text-xs text-slate-300 leading-relaxed font-medium">
                    {{ __('messages.test_email_instruction') }}
                </p>

                <form method="POST" action="{{ route('admin.email_settings.test') }}" class="space-y-3" x-data="{ loading: false }" @submit="loading = true">
                    @csrf
                    <div class="space-y-1">
                        <label class="text-[10px] font-extrabold uppercase text-indigo-300 tracking-wider block">
                            {{ __('messages.recipient_email') }}
                        </label>
                        <input type="email" name="test_email" value="{{ old('test_email', auth()->user()->email ?? '') }}" required placeholder="admin@example.com" class="h-10 w-full text-xs font-semibold px-3 bg-slate-800/80 border border-indigo-700/60 rounded-xl text-white placeholder-slate-400 focus:bg-slate-900 focus:border-indigo-400 focus:outline-none transition-colors">
                    </div>

                    <button type="submit" :disabled="loading" class="w-full h-10 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-extrabold uppercase tracking-wider rounded-xl shadow-sm transition-all flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50">
                        <template x-if="!loading">
                            <span class="flex items-center gap-1.5">
                                <span>🚀</span>
                                <span>{{ __('messages.send_test_mail_button') }}</span>
                            </span>
                        </template>
                        <template x-if="loading">
                            <span class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>{{ __('messages.sending_test_email') }}</span>
                            </span>
                        </template>
                    </button>
                </form>
            </div>

            <!-- Quick Tips Card -->
            <div class="bg-amber-50/80 border border-amber-200/80 rounded-2xl p-5 text-amber-900 space-y-3">
                <div class="flex items-center gap-2 text-xs font-black uppercase tracking-wider text-amber-800">
                    <span>💡</span>
                    <span>{{ __('messages.smtp_tips_header') }}</span>
                </div>
                <ul class="text-[11px] font-semibold text-amber-800 space-y-2 leading-relaxed">
                    <li class="flex items-start gap-1.5">
                        <span class="shrink-0 font-bold">•</span>
                        <span><strong>Gmail:</strong> Use Host `smtp.gmail.com`, Port `587` (TLS) or `465` (SSL) & App Password.</span>
                    </li>
                    <li class="flex items-start gap-1.5">
                        <span class="shrink-0 font-bold">•</span>
                        <span><strong>cPanel / Webmail:</strong> Use Host `mail.yourdomain.com`, Port `465` (SSL) or `587` (TLS).</span>
                    </li>
                    <li class="flex items-start gap-1.5">
                        <span class="shrink-0 font-bold">•</span>
                        <span><strong>.env Sync:</strong> All changes saved here automatically synchronize into `.env` file.</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
