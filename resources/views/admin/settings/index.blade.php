@extends('layouts.admin')

@section('page_title', __('messages.system_settings'))

@section('content')
<div class="max-w-5xl bg-white border border-slate-100 rounded-xl pl-4 pb-2 pt-0 shadow-sm">
    @if($errors->any())
        <div class="mb-5 p-3.5 bg-rose-50 border border-rose-100 text-rose-700 text-xs font-semibold rounded-xl">
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
            <h3 class="text-xs font-bold text-slate-800 border-b border-slate-100 pb-2">{{ __('messages.branding_seo_configurations') }}</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="space-y-1">
                    <label class="text-[9px] font-bold text-slate-400 uppercase">{{ __('messages.website_name') }}</label>
                    <input type="text" name="website_name" value="{{ old('website_name', $settings['website_name'] ?? '') }}" class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:border-primary-400">
                </div>

                <div class="space-y-1">
                    <label class="text-[9px] font-bold text-slate-400 uppercase">{{ __('messages.primary_color') }}</label>
                    <div class="flex space-x-2">
                        <input type="color" name="primary_color" value="{{ old('primary_color', $settings['primary_color'] ?? '#ef4444') }}" class="w-10 h-8 p-0 bg-transparent border-0 rounded cursor-pointer shrink-0">
                        <input type="text" value="{{ old('primary_color', $settings['primary_color'] ?? '#ef4444') }}" disabled class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-100 border border-slate-200 rounded-lg text-slate-500">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-[9px] font-bold text-slate-400 uppercase">{{ __('messages.footer_text') }}</label>
                    <input type="text" name="footer_text" value="{{ old('footer_text', $settings['footer_text'] ?? '') }}" class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:border-primary-400">
                </div>

                <div class="space-y-1">
                    <label class="text-[9px] font-bold text-slate-400 uppercase">{{ __('messages.website_logo') }}</label>
                    <input type="file" name="website_logo" class="w-full text-xs font-semibold px-2 py-1 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white">
                    @if(!empty($settings['website_logo']))
                        <div class="mt-1 flex items-center space-x-2">
                            <img src="{{ asset('storage/' . $settings['website_logo']) }}" class="h-6 w-auto object-contain rounded border border-slate-200">
                            <span class="text-[9px] text-slate-400">{{ __('messages.current_logo') }}</span>
                        </div>
                    @endif
                </div>

                <div class="space-y-1">
                    <label class="text-[9px] font-bold text-slate-400 uppercase">{{ __('messages.website_favicon') }}</label>
                    <input type="file" name="website_favicon" class="w-full text-xs font-semibold px-2 py-1 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white">
                    @if(!empty($settings['website_favicon']))
                        <div class="mt-1 flex items-center space-x-2">
                            <img src="{{ asset('storage/' . $settings['website_favicon']) }}" class="h-6 w-6 object-contain rounded border border-slate-200">
                            <span class="text-[9px] text-slate-400">{{ __('messages.current_favicon') }}</span>
                        </div>
                    @endif
                </div>

                <div class="space-y-1">
                    <label class="text-[9px] font-bold text-slate-400 uppercase">{{ __('messages.seo_title') }}</label>
                    <input type="text" name="seo_title" value="{{ old('seo_title', $settings['seo_title'] ?? '') }}" class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:border-primary-400">
                </div>

                <div class="space-y-1 sm:col-span-3">
                    <label class="text-[9px] font-bold text-slate-400 uppercase">{{ __('messages.seo_meta_description') }}</label>
                    <textarea name="seo_description" rows="2" class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:border-primary-400">{{ old('seo_description', $settings['seo_description'] ?? '') }}</textarea>
                </div>
            </div>
        </div>
        
        <!-- Section: General Content -->
        <div class="space-y-4 pt-4 border-t border-slate-100">
            <h3 class="text-xs font-bold text-slate-800 border-b border-slate-100 pb-2">{{ __('messages.about_us_configurations') }}</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="text-[9px] font-bold text-slate-400 uppercase">{{ __('messages.community_mission') }}</label>
                    <textarea name="about_mission" rows="2" class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:border-primary-400">{{ old('about_mission', $settings['about_mission'] ?? '') }}</textarea>
                </div>

                <div class="space-y-1">
                    <label class="text-[9px] font-bold text-slate-400 uppercase">{{ __('messages.community_vision') }}</label>
                    <textarea name="about_vision" rows="2" class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:border-primary-400">{{ old('about_vision', $settings['about_vision'] ?? '') }}</textarea>
                </div>

                <div class="space-y-1">
                    <label class="text-[9px] font-bold text-slate-400 uppercase">{{ __('messages.community_history_text') }}</label>
                    <textarea name="about_history" rows="3" class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:border-primary-400">{{ old('about_history', $settings['about_history'] ?? '') }}</textarea>
                </div>

                <div class="space-y-1">
                    <label class="text-[9px] font-bold text-slate-400 uppercase">{{ __('messages.objectives_html_bulletins') }}</label>
                    <textarea name="about_objectives" rows="3" placeholder="e.g. <li>Provide scholarship support</li>" class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:border-primary-400 font-mono">{{ old('about_objectives', $settings['about_objectives'] ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Section: Contact Details -->
        <div class="space-y-4 pt-4 border-t border-slate-100">
            <h3 class="text-xs font-bold text-slate-800 border-b border-slate-50 pb-2">{{ __('messages.contact_social_coordinates') }}</h3>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="space-y-1">
                    <label class="text-[9px] font-bold text-slate-400 uppercase">{{ __('messages.contact_office_phone') }}</label>
                    <input type="text" name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}" class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:border-primary-400">
                </div>

                <div class="space-y-1">
                    <label class="text-[9px] font-bold text-slate-400 uppercase">{{ __('messages.contact_office_email') }}</label>
                    <input type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email'] ?? '') }}" class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:border-primary-400">
                </div>

                <div class="space-y-1">
                    <label class="text-[9px] font-bold text-slate-400 uppercase">{{ __('messages.contact_whatsapp') }}</label>
                    <input type="text" name="contact_whatsapp" value="{{ old('contact_whatsapp', $settings['contact_whatsapp'] ?? '') }}" class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:border-primary-400">
                </div>

                <div class="space-y-1 sm:col-span-3">
                    <label class="text-[9px] font-bold text-slate-400 uppercase">{{ __('messages.contact_office_address') }}</label>
                    <textarea name="contact_address" rows="1.5" class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:border-primary-400">{{ old('contact_address', $settings['contact_address'] ?? '') }}</textarea>
                </div>

                <div class="space-y-1 sm:col-span-3">
                    <label class="text-[9px] font-bold text-slate-400 uppercase">{{ __('messages.google_maps_iframe_embed_html') }}</label>
                    <textarea name="contact_map_iframe" rows="2" placeholder="<iframe src='...'></iframe>" class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:border-primary-400 font-mono">{{ old('contact_map_iframe', $settings['contact_map_iframe'] ?? '') }}</textarea>
                </div>

                <div class="space-y-1">
                    <label class="text-[9px] font-bold text-slate-400 uppercase">{{ __('messages.facebook_link') }}</label>
                    <input type="text" name="social_facebook" value="{{ old('social_facebook', $settings['social_facebook'] ?? '') }}" class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:border-primary-400">
                </div>

                <div class="space-y-1">
                    <label class="text-[9px] font-bold text-slate-400 uppercase">{{ __('messages.twitter_link') }}</label>
                    <input type="text" name="social_twitter" value="{{ old('social_twitter', $settings['social_twitter'] ?? '') }}" class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:border-primary-400">
                </div>

                <div class="space-y-1">
                    <label class="text-[9px] font-bold text-slate-400 uppercase">{{ __('messages.instagram_link') }}</label>
                    <input type="text" name="social_instagram" value="{{ old('social_instagram', $settings['social_instagram'] ?? '') }}" class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:border-primary-400">
                </div>

                <div class="space-y-1">
                    <label class="text-[9px] font-bold text-slate-400 uppercase">{{ __('messages.youtube_link') }}</label>
                    <input type="text" name="social_youtube" value="{{ old('social_youtube', $settings['social_youtube'] ?? '') }}" placeholder="https://youtube.com/@channel" class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:border-primary-400">
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="pt-4 border-t border-slate-100 flex justify-end">
            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-primary-500 hover:bg-primary-600 font-bold text-[10px] text-white uppercase tracking-wider rounded-lg shadow-sm transition-transform hover:-translate-y-0.5">
                {{ __('messages.save_system_settings') }}
            </button>
        </div>
    </form>
</div>
@endsection
