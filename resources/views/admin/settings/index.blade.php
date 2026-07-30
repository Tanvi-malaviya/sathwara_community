@extends('layouts.admin')

@section('page_title', __('messages.system_settings'))

@section('content')
<div class="bg-white border border-slate-100 rounded-2xl p-4 sm:p-6 shadow-sm w-full">
    @if($errors->any())
        <div class="mb-5 p-4 bg-rose-50 border border-rose-100 text-rose-700 text-xs font-semibold rounded-xl">
            <ul class="list-disc pl-4 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
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
        
        <!-- Section: General Content -->
        <div class="space-y-4 pt-5 border-t border-slate-100">
            <h3 class="text-sm font-extrabold text-slate-800 border-b border-slate-100 pb-2.5 flex items-center gap-2">
                <span>ℹ️</span>
                <span>{{ __('messages.about_us_configurations') }}</span>
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-600 block">{{ __('messages.community_mission') }}</label>
                    <textarea name="about_mission" rows="2.5" class="w-full text-xs font-semibold p-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 transition-colors">{{ old('about_mission', $settings['about_mission'] ?? '') }}</textarea>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-600 block">{{ __('messages.community_vision') }}</label>
                    <textarea name="about_vision" rows="2.5" class="w-full text-xs font-semibold p-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 transition-colors">{{ old('about_vision', $settings['about_vision'] ?? '') }}</textarea>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-600 block">{{ __('messages.community_history_text') }}</label>
                    <textarea name="about_history" rows="3" class="w-full text-xs font-semibold p-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 transition-colors">{{ old('about_history', $settings['about_history'] ?? '') }}</textarea>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-600 block">{{ __('messages.objectives_html_bulletins') }}</label>
                    <textarea name="about_objectives" rows="3" placeholder="e.g. <li>Provide scholarship support</li>" class="w-full text-xs font-semibold p-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 transition-colors font-mono">{{ old('about_objectives', $settings['about_objectives'] ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Section: Contact Details -->
        <div class="space-y-4 pt-5 border-t border-slate-100">
            <h3 class="text-sm font-extrabold text-slate-800 border-b border-slate-100 pb-2.5 flex items-center gap-2">
                <span>📍</span>
                <span>{{ __('messages.contact_social_coordinates') }}</span>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-600 block">{{ __('messages.contact_office_phone') }}</label>
                    <input type="text" name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}" class="h-10 w-full text-xs font-semibold px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 transition-colors">
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-600 block">{{ __('messages.contact_office_email') }}</label>
                    <input type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email'] ?? '') }}" class="h-10 w-full text-xs font-semibold px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 transition-colors">
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-600 block">{{ __('messages.contact_whatsapp') }}</label>
                    <input type="text" name="contact_whatsapp" value="{{ old('contact_whatsapp', $settings['contact_whatsapp'] ?? '') }}" class="h-10 w-full text-xs font-semibold px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 transition-colors">
                </div>

                <div class="space-y-1.5 sm:col-span-2 lg:col-span-3">
                    <label class="text-xs font-bold text-slate-600 block">{{ __('messages.contact_office_address') }}</label>
                    <textarea name="contact_address" rows="2" class="w-full text-xs font-semibold p-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 transition-colors">{{ old('contact_address', $settings['contact_address'] ?? '') }}</textarea>
                </div>

                <div class="space-y-1.5 sm:col-span-2 lg:col-span-3">
                    <label class="text-xs font-bold text-slate-600 block">{{ __('messages.google_maps_iframe_embed_html') }}</label>
                    <textarea name="contact_map_iframe" rows="2.5" placeholder="<iframe src='...'></iframe>" class="w-full text-xs font-semibold p-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 transition-colors font-mono">{{ old('contact_map_iframe', $settings['contact_map_iframe'] ?? '') }}</textarea>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-600 block">{{ __('messages.facebook_link') }}</label>
                    <input type="text" name="social_facebook" value="{{ old('social_facebook', $settings['social_facebook'] ?? '') }}" class="h-10 w-full text-xs font-semibold px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 transition-colors">
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-600 block">{{ __('messages.twitter_link') }}</label>
                    <input type="text" name="social_twitter" value="{{ old('social_twitter', $settings['social_twitter'] ?? '') }}" class="h-10 w-full text-xs font-semibold px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 transition-colors">
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-600 block">{{ __('messages.instagram_link') }}</label>
                    <input type="text" name="social_instagram" value="{{ old('social_instagram', $settings['social_instagram'] ?? '') }}" class="h-10 w-full text-xs font-semibold px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 transition-colors">
                </div>

                <div class="space-y-1.5 sm:col-span-2 lg:col-span-3">
                    <label class="text-xs font-bold text-slate-600 block">{{ __('messages.youtube_link') }}</label>
                    <input type="text" name="social_youtube" value="{{ old('social_youtube', $settings['social_youtube'] ?? '') }}" placeholder="https://youtube.com/@channel" class="h-10 w-full text-xs font-semibold px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-400 transition-colors">
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
@endsection
