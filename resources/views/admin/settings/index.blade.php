@extends('layouts.admin')

@section('page_title', __('messages.system_settings'))

@section('content')
<div class="w-full" x-data="{ activeTab: '{{ old('active_tab', session('active_settings_tab', 'general')) }}' }">

    <!-- Tab Navigation -->
    <div class="flex items-center gap-1 bg-slate-100 rounded-2xl p-1 mb-6 w-fit">
        <button @click="activeTab = 'general'"
            :class="activeTab === 'general' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
            class="flex items-center gap-2 px-4 py-2 text-xs font-bold rounded-xl transition-all cursor-pointer">
            <span>🎨</span>
            <span>{{ __('messages.general_settings') }}</span>
        </button>
        <button @click="activeTab = 'contact'"
            :class="activeTab === 'contact' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
            class="flex items-center gap-2 px-4 py-2 text-xs font-bold rounded-xl transition-all cursor-pointer">
            <span>📍</span>
            <span>{{ __('messages.contact_social') }}</span>
        </button>
        <button @click="activeTab = 'email'"
            :class="activeTab === 'email' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
            class="flex items-center gap-2 px-4 py-2 text-xs font-bold rounded-xl transition-all cursor-pointer">
            <span>📧</span>
            <span>{{ __('messages.email_smtp_settings') }}</span>
        </button>
        <button @click="activeTab = 'payment'"
            :class="activeTab === 'payment' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
            class="flex items-center gap-2 px-4 py-2 text-xs font-bold rounded-xl transition-all cursor-pointer">
            <span>💳</span>
            <span>{{ __('messages.payment_gateway_settings') }}</span>
        </button>
    </div>

    @if($errors->any())
        <div class="mb-5 p-4 bg-rose-50 border border-rose-100 text-rose-700 text-xs font-semibold rounded-xl">
            <div class="font-extrabold mb-1">⚠️ {{ __('messages.please_correct_errors') }}:</div>
            <ul class="list-disc pl-4 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- ============================= TAB 1: GENERAL SETTINGS ============================= -->
    <div x-show="activeTab === 'general'" x-transition>
        <div class="bg-white border border-slate-100 rounded-2xl p-4 sm:p-6 shadow-sm w-full">
            <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <input type="hidden" name="active_tab" value="general">

                <!-- Section: Branding & General -->
                <div class="space-y-4">
                    <h3 class="text-sm font-extrabold text-slate-800 border-b border-slate-100 pb-2.5 flex items-center gap-2">
                        <span>🎨</span>
                        <span>{{ __('messages.branding_seo_configurations') }}</span>
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-600 block">{{ __('messages.website_name') }}</label>
                            <input type="text" name="website_name" value="{{ old('website_name', $settings['website_name'] ?? '') }}" class="h-10 w-full text-xs font-semibold px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 transition-colors">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-600 block">{{ __('messages.footer_text') }}</label>
                            <input type="text" name="footer_text" value="{{ old('footer_text', $settings['footer_text'] ?? '') }}" class="h-10 w-full text-xs font-semibold px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 transition-colors">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-600 block">{{ __('messages.seo_title') }}</label>
                            <input type="text" name="seo_title" value="{{ old('seo_title', $settings['seo_title'] ?? '') }}" class="h-10 w-full text-xs font-semibold px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 transition-colors">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-600 block">{{ __('messages.website_logo') }}</label>
                            <input type="file" name="website_logo" class="w-full text-xs font-semibold p-1.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white transition-colors">
                            @if(!empty($settings['website_logo']))
                                <div class="mt-1.5 flex items-center space-x-2">
                                    <img src="{{ asset('storage/' . $settings['website_logo']) }}" class="h-7 w-auto object-contain rounded border border-slate-200 bg-slate-50 p-0.5">
                                    <span class="text-[10px] text-slate-400 font-semibold">{{ __('messages.current_logo') }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-600 block">{{ __('messages.website_favicon') }}</label>
                            <input type="file" name="website_favicon" class="w-full text-xs font-semibold p-1.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white transition-colors">
                            @if(!empty($settings['website_favicon']))
                                <div class="mt-1.5 flex items-center space-x-2">
                                    <img src="{{ asset('storage/' . $settings['website_favicon']) }}" class="h-6 w-6 object-contain rounded border border-slate-200 bg-slate-50 p-0.5">
                                    <span class="text-[10px] text-slate-400 font-semibold">{{ __('messages.current_favicon') }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="space-y-1.5 md:col-span-2 lg:col-span-3">
                            <label class="text-xs font-bold text-slate-600 block">{{ __('messages.seo_meta_description') }}</label>
                            <textarea name="seo_description" rows="2" class="w-full text-xs font-semibold p-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 transition-colors">{{ old('seo_description', $settings['seo_description'] ?? '') }}</textarea>
                        </div>
                    </div>
                </div>


                <!-- Submit -->
                <div class="pt-5 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="inline-flex items-center justify-center px-5 py-2.5 bg-primary-500 hover:bg-primary-600 font-bold text-xs text-white uppercase tracking-wider rounded-xl shadow-xs transition-all hover:-translate-y-0.5">
                        {{ __('messages.save_system_settings') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================= TAB 2: CONTACT & SOCIAL ============================= -->
    <div x-show="activeTab === 'contact'" x-transition>
        <div class="bg-white border border-slate-100 rounded-2xl p-4 sm:p-6 shadow-sm w-full">
            <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <input type="hidden" name="active_tab" value="contact">

                <!-- Contact Details -->
                <div class="space-y-4">
                    <h3 class="text-sm font-extrabold text-slate-800 border-b border-slate-100 pb-2.5 flex items-center gap-2">
                        <span>📞</span>
                        <span>{{ __('messages.contact_office_details') }}</span>
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-600 block">{{ __('messages.contact_office_phone') }}</label>
                            <input type="text" name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)" placeholder="e.g. 9876543210" class="h-10 w-full text-xs font-semibold px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 transition-colors">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-600 block">{{ __('messages.contact_office_email') }}</label>
                            <input type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email'] ?? '') }}" class="h-10 w-full text-xs font-semibold px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 transition-colors">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-600 block">{{ __('messages.contact_whatsapp') }}</label>
                            <input type="text" name="contact_whatsapp" value="{{ old('contact_whatsapp', $settings['contact_whatsapp'] ?? '') }}" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)" placeholder="e.g. 9876543210" class="h-10 w-full text-xs font-semibold px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 transition-colors">
                        </div>

                        <div class="space-y-1.5 sm:col-span-2 lg:col-span-3">
                            <label class="text-xs font-bold text-slate-600 block">{{ __('messages.contact_office_address') }}</label>
                            <textarea name="contact_address" rows="2" class="w-full text-xs font-semibold p-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 transition-colors">{{ old('contact_address', $settings['contact_address'] ?? '') }}</textarea>
                        </div>

                        <div class="space-y-1.5 sm:col-span-2 lg:col-span-3">
                            <label class="text-xs font-bold text-slate-600 block">{{ __('messages.google_maps_iframe_embed_html') }}</label>
                            <textarea name="contact_map_iframe" rows="3" placeholder="<iframe src='...'></iframe>" class="w-full text-xs font-semibold p-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 transition-colors font-mono">{{ old('contact_map_iframe', $settings['contact_map_iframe'] ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Social Media Links -->
                <div class="space-y-4 pt-5 border-t border-slate-100">
                    <h3 class="text-sm font-extrabold text-slate-800 border-b border-slate-100 pb-2.5 flex items-center gap-2">
                        <span>🌐</span>
                        <span>{{ __('messages.social_media_links') }}</span>
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-600 block flex items-center gap-1.5">
                                <span class="text-blue-600">f</span> {{ __('messages.facebook_link') }}
                            </label>
                            <input type="text" name="social_facebook" value="{{ old('social_facebook', $settings['social_facebook'] ?? '') }}" placeholder="https://facebook.com/..." class="h-10 w-full text-xs font-semibold px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 transition-colors">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-600 block flex items-center gap-1.5">
                                <span class="text-sky-500">𝕏</span> {{ __('messages.twitter_link') }}
                            </label>
                            <input type="text" name="social_twitter" value="{{ old('social_twitter', $settings['social_twitter'] ?? '') }}" placeholder="https://twitter.com/..." class="h-10 w-full text-xs font-semibold px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 transition-colors">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-600 block flex items-center gap-1.5">
                                <span class="text-pink-500">📸</span> {{ __('messages.instagram_link') }}
                            </label>
                            <input type="text" name="social_instagram" value="{{ old('social_instagram', $settings['social_instagram'] ?? '') }}" placeholder="https://instagram.com/..." class="h-10 w-full text-xs font-semibold px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 transition-colors">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-600 block flex items-center gap-1.5">
                                <span class="text-red-600">▶</span> {{ __('messages.youtube_link') }}
                            </label>
                            <input type="text" name="social_youtube" value="{{ old('social_youtube', $settings['social_youtube'] ?? '') }}" placeholder="https://youtube.com/@channel" class="h-10 w-full text-xs font-semibold px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-400 transition-colors">
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="pt-5 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="inline-flex items-center justify-center px-5 py-2.5 bg-primary-500 hover:bg-primary-600 font-bold text-xs text-white uppercase tracking-wider rounded-xl shadow-xs transition-all hover:-translate-y-0.5">
                        {{ __('messages.save_changes') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================= TAB 3: EMAIL & SMTP SETTINGS ============================= -->

    <div x-show="activeTab === 'email'" x-transition>
        <div class="max-w-5xl">
            <!-- Main Form: SMTP Configuration -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm space-y-6">
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
                    <input type="hidden" name="active_tab" value="email">

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
        </div>
    </div>

    <!-- ============================= TAB 4: PAYMENT & GATEWAY SETTINGS ============================= -->
    <div x-show="activeTab === 'payment'" x-transition>
        <div class="bg-white border border-slate-100 rounded-2xl p-4 sm:p-6 shadow-sm w-full">
            <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="active_tab" value="payment">

                <!-- Section: Registration Fees Configuration -->
                <div class="space-y-4">
                    <h3 class="text-sm font-extrabold text-slate-800 border-b border-slate-100 pb-2.5 flex items-center gap-2">
                        <span>💰</span>
                        <span>{{ __('messages.registration_membership_fees') }}</span>
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block">
                                {{ __('messages.member_signup_fee') }} (₹) <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 text-xs font-bold">₹</span>
                                <input type="number" min="0" step="1" name="member_signup_fee" value="{{ old('member_signup_fee', $paymentSettings['member_signup_fee'] ?? '1000') }}" required class="h-10 w-full text-xs font-bold pl-7 pr-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 transition-colors">
                            </div>
                            <p class="text-[10px] text-slate-400 font-medium">{{ __('messages.member_signup_fee_help') }}</p>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block">
                                {{ __('messages.business_register_fee') }} (₹) <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 text-xs font-bold">₹</span>
                                <input type="number" min="0" step="1" name="business_registration_fee" value="{{ old('business_registration_fee', $paymentSettings['business_registration_fee'] ?? '500') }}" required class="h-10 w-full text-xs font-bold pl-7 pr-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 transition-colors">
                            </div>
                            <p class="text-[10px] text-slate-400 font-medium">{{ __('messages.business_register_fee_help') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Section: Razorpay API Credentials -->
                <div class="space-y-4 pt-2">
                    <h3 class="text-sm font-extrabold text-slate-800 border-b border-slate-100 pb-2.5 flex items-center gap-2">
                        <span>💳</span>
                        <span>{{ __('messages.razorpay_gateway_credentials') }}</span>
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block">
                                {{ __('messages.razorpay_key_id') }}
                            </label>
                            <input type="text" name="razorpay_key_id" value="{{ old('razorpay_key_id', $paymentSettings['razorpay_key_id'] ?? '') }}" placeholder="rzp_test_..." class="h-10 w-full text-xs font-semibold px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 transition-colors">
                            <p class="text-[10px] text-slate-400 font-medium">{{ __('messages.razorpay_key_id_help') }}</p>
                        </div>

                        <div class="space-y-1.5" x-data="{ showSecret: false }">
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block">
                                {{ __('messages.razorpay_key_secret') }}
                            </label>
                            <div class="relative">
                                <input :type="showSecret ? 'text' : 'password'" name="razorpay_key_secret" value="{{ old('razorpay_key_secret', $paymentSettings['razorpay_key_secret'] ?? '') }}" placeholder="••••••••••••••••" class="h-10 w-full text-xs font-semibold pl-3 pr-10 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 transition-colors">
                                <button type="button" @click="showSecret = !showSecret" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                                    <span x-text="showSecret ? '🙈' : '👁️'" class="text-xs"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Save Action Button -->
                <div class="pt-4 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-extrabold text-xs uppercase tracking-wider rounded-xl shadow-sm transition-all flex items-center gap-2 cursor-pointer">
                        <span>💾</span>
                        <span>{{ __('messages.save_payment_settings') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
