@extends('layouts.public')

@section('content')
@include('partials.page_header', [
    'title' => __('messages.contact_us'),
    'subtitle' => __('messages.contact_subtitle'),
    'breadcrumb' => __('messages.contact_us')
])

<!-- Main Contact Section -->
<section class="py-6 md:py-8 bg-slate-50/70">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
        
        <!-- 4 Top Quick Contact Action Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Office Address Card -->
            <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-2xs hover:shadow-md transition-all space-y-2 group">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-primary-50 border border-primary-100 flex items-center justify-center text-primary-600 font-bold shrink-0 group-hover:scale-105 transition-transform">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest block">{{ __('messages.office_address') }}</span>
                        <h4 class="text-xs font-bold text-slate-900 line-clamp-1">Sathwara Community Bhavan</h4>
                    </div>
                </div>
                <p class="text-[11px] text-slate-500 leading-normal pl-12">
                    {{ App\Models\Setting::get('contact_address', 'Ashram Road, Ahmedabad, Gujarat 380009') }}
                </p>
            </div>

            <!-- Phone Helpline Card -->
            <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-2xs hover:shadow-md transition-all space-y-2 group">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 font-bold shrink-0 group-hover:scale-105 transition-transform">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest block">{{ __('messages.phone_helpline') }}</span>
                        <a href="tel:{{ App\Models\Setting::get('contact_phone', '+91 79 2345 6789') }}" class="text-xs font-bold text-slate-900 hover:text-primary-600 transition-colors">
                            {{ App\Models\Setting::get('contact_phone', '+91 79 2345 6789') }}
                        </a>
                    </div>
                </div>
                <p class="text-[11px] text-slate-500 leading-normal pl-12">{{ __('messages.office_hours') }}</p>
            </div>

            <!-- Email Support Card -->
            <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-2xs hover:shadow-md transition-all space-y-2 group">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-sky-50 border border-sky-100 flex items-center justify-center text-sky-600 font-bold shrink-0 group-hover:scale-105 transition-transform">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest block">{{ __('messages.email_address') }}</span>
                        <a href="mailto:{{ App\Models\Setting::get('contact_email', 'info@sathwaracommunity.org') }}" class="text-xs font-bold text-slate-900 hover:text-primary-600 transition-colors line-clamp-1">
                            {{ App\Models\Setting::get('contact_email', 'info@sathwaracommunity.org') }}
                        </a>
                    </div>
                </div>
                <p class="text-[11px] text-slate-500 leading-normal pl-12">{{ __('messages.official_desk_inquiries') }}</p>
            </div>

            <!-- WhatsApp Direct Card -->
            <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-2xs hover:shadow-md transition-all space-y-2 group">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-600 font-bold shrink-0 group-hover:scale-105 transition-transform">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l.279.444-1.157 4.226 4.327-1.134.294.171z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest block">{{ __('messages.whatsapp_desk') }}</span>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', App\Models\Setting::get('contact_whatsapp', '+917923456789')) }}" target="_blank" class="text-xs font-bold text-emerald-600 hover:underline">
                            {{ __('messages.chat_directly') }} &rarr;
                        </a>
                    </div>
                </div>
                <p class="text-[11px] text-slate-500 leading-normal pl-12">{{ __('messages.quick_assistance') }}</p>
            </div>
        </div>

        <!-- Main Form & Full Height Map Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-stretch">
            
            <!-- Left 7 Columns: Inquiry Form Card -->
            <div class="lg:col-span-7 bg-white p-6 md:p-8 rounded-xl border border-slate-200/80 shadow-2xs flex flex-col justify-between space-y-6">
                <div class="space-y-1.5 border-b border-slate-100 pb-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg md:text-xl font-extrabold text-slate-900 tracking-tight">
                            {{ __('messages.inquiry_form') }}
                        </h3>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-primary-600 bg-primary-50 px-2.5 py-1 rounded-full border border-primary-100">
                            {{ __('messages.fast_response') }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-500">
                        {{ __('messages.fill_message_details') }}
                    </p>
                </div>



                <form method="POST" action="{{ route('contact.submit') }}" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @csrf
                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-slate-700 uppercase">{{ __('messages.your_name') }} <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" required placeholder="{{ __('messages.your_name') }}" 
                            class="w-full text-xs font-semibold px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-2 focus:ring-primary-500/15 text-slate-800 transition-all outline-hidden">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-slate-700 uppercase">{{ __('messages.email_address') }} <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" required placeholder="email@example.com" 
                            class="w-full text-xs font-semibold px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-2 focus:ring-primary-500/15 text-slate-800 transition-all outline-hidden">
                    </div>

                    <div class="space-y-1 sm:col-span-2">
                        <label class="text-[11px] font-bold text-slate-700 uppercase">{{ __('messages.subject') }} <span class="text-rose-500">*</span></label>
                        <input type="text" name="subject" required placeholder="{{ __('messages.subject') }}" 
                            class="w-full text-xs font-semibold px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-2 focus:ring-primary-500/15 text-slate-800 transition-all outline-hidden">
                    </div>

                    <div class="space-y-1 sm:col-span-2">
                        <label class="text-[11px] font-bold text-slate-700 uppercase">{{ __('messages.message') }} <span class="text-rose-500">*</span></label>
                        <textarea name="message" rows="4" required placeholder="{{ __('messages.message') }}" 
                            class="w-full text-xs font-semibold px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-2 focus:ring-primary-500/15 text-slate-800 transition-all outline-hidden"></textarea>
                    </div>

                    <div class="sm:col-span-2 pt-2">
                        <button type="submit" 
                            class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-md transition-all transform active:scale-98 cursor-pointer gap-2">
                            <span>{{ __('messages.submit_message') }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right 5 Columns: Seamless Full-Height Location Map Card -->
            <div class="lg:col-span-5 bg-white p-3 rounded-xl border border-slate-200/80 shadow-2xs flex flex-col relative overflow-hidden min-h-[420px]">
                <div class="px-3 py-2 flex items-center justify-between border-b border-slate-100 mb-2 shrink-0">
                    <span class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>{{ __('messages.interactive_map') }}</span>
                    </span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Ahmedabad, India</span>
                </div>

                <!-- Styled Full-Height Iframe Wrapper -->
                <div class="grow rounded-2xl overflow-hidden bg-slate-100 border border-slate-100 relative [&>iframe]:w-full [&>iframe]:h-full [&>iframe]:border-0 [&>iframe]:min-h-[360px]">
                    @if(App\Models\Setting::get('contact_map_iframe'))
                        {!! App\Models\Setting::get('contact_map_iframe') !!}
                    @else
                        <!-- Fallback Map view if no custom iframe in settings -->
                        <iframe src="https://maps.google.com/maps?q=Ahmedabad%20Gujarat&t=&z=13&ie=UTF8&iwloc=&output=embed" 
                            class="w-full h-full border-0 min-h-[360px]" loading="lazy"></iframe>
                    @endif
                </div>
            </div>

        </div>

    </div>
</section>

@if(session('success'))
<div id="toast-success"
     style="position:fixed;top:24px;right:24px;z-index:9999;display:flex;align-items:center;gap:12px;min-width:300px;max-width:420px;
            background:#ffffff;border:1px solid #d1fae5;border-left:4px solid #10b981;border-radius:14px;
            padding:14px 18px;box-shadow:0 10px 40px rgba(0,0,0,0.12);animation:slideInToast 0.4s ease;"
     role="alert">
    <div style="width:36px;height:36px;background:#ecfdf5;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;">✅</div>
    <div style="flex:1;min-width:0;">
        <p style="margin:0;font-size:12px;font-weight:800;color:#065f46;line-height:1.3;">{{ session('success') }}</p>
    </div>
    <button onclick="document.getElementById('toast-success').remove()" style="background:none;border:none;cursor:pointer;color:#6b7280;font-size:16px;padding:0;line-height:1;flex-shrink:0;">×</button>
</div>
<style>
@keyframes slideInToast {
    from { opacity: 0; transform: translateX(60px); }
    to   { opacity: 1; transform: translateX(0); }
}
@keyframes fadeOutToast {
    from { opacity: 1; transform: translateX(0); }
    to   { opacity: 0; transform: translateX(60px); }
}
</style>
<script>
    (function() {
        var toast = document.getElementById('toast-success');
        if (toast) {
            setTimeout(function() {
                toast.style.animation = 'fadeOutToast 0.4s ease forwards';
                setTimeout(function() { toast.remove(); }, 400);
            }, 4000);
        }
    })();
</script>
@endif
@endsection
