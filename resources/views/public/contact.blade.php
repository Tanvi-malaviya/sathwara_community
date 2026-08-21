@extends('layouts.public')

@section('content')
@include('partials.page_header', [
    'title' => __('messages.contact_us'),
    'subtitle' => __('messages.contact_subtitle'),
    'breadcrumb' => __('messages.contact_us')
])

<!-- Main Contact Section -->
<section class="py-8 md:py-12 bg-slate-50/70">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- 4 Top Quick Contact Action Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Office Address Card -->
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 flex flex-col justify-between space-y-4 group">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-primary-50 border border-primary-100 flex items-center justify-center text-primary-600 font-bold shrink-0 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <span class="text-xs font-black text-slate-400 uppercase tracking-widest block">{{ __('messages.office_address') }}</span>
                        <h4 class="text-base font-black text-slate-900 break-words" title="{{ App\Models\Setting::get('website_name', 'Sathwara Community') }}">
                            {{ App\Models\Setting::get('website_name', 'Sathwara Community') }}
                        </h4>
                    </div>
                </div>
                <p class="text-sm font-medium text-slate-600 leading-relaxed border-t border-slate-100 pt-3.5">
                    {{ App\Models\Setting::get('contact_address', 'Ashram Road, Ahmedabad, Gujarat 380009') }}
                </p>
            </div>

            <!-- Phone Helpline Card -->
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 flex flex-col justify-between space-y-4 group">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 font-bold shrink-0 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <span class="text-xs font-black text-slate-400 uppercase tracking-widest block">{{ __('messages.phone_helpline') }}</span>
                        <a href="tel:{{ App\Models\Setting::get('contact_phone', '+91 79 2345 6789') }}" class="text-base font-black text-slate-900 hover:text-primary-600 transition-colors break-all block">
                            {{ App\Models\Setting::get('contact_phone', '+91 79 2345 6789') }}
                        </a>
                    </div>
                </div>
                <p class="text-sm font-medium text-slate-600 border-t border-slate-100 pt-3.5">
                    {{ __('messages.office_hours') }}
                </p>
            </div>

            <!-- Email Support Card -->
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 flex flex-col justify-between space-y-4 group">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-sky-50 border border-sky-100 flex items-center justify-center text-sky-600 font-bold shrink-0 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <span class="text-xs font-black text-slate-400 uppercase tracking-widest block">{{ __('messages.email_address') }}</span>
                        <a href="mailto:{{ App\Models\Setting::get('contact_email', 'info@sathwaracommunity.org') }}" class="text-sm sm:text-base font-black text-slate-900 hover:text-primary-600 transition-colors break-all block" title="{{ App\Models\Setting::get('contact_email', 'info@sathwaracommunity.org') }}">
                            {{ App\Models\Setting::get('contact_email', 'info@sathwaracommunity.org') }}
                        </a>
                    </div>
                </div>
                <p class="text-sm font-medium text-slate-600 border-t border-slate-100 pt-3.5">
                    {{ __('messages.official_desk_inquiries') }}
                </p>
            </div>

            <!-- WhatsApp Direct Card -->
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 flex flex-col justify-between space-y-4 group">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-600 font-bold shrink-0 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l.279.444-1.157 4.226 4.327-1.134.294.171z"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <span class="text-xs font-black text-slate-400 uppercase tracking-widest block">{{ __('messages.whatsapp_desk') }}</span>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', App\Models\Setting::get('contact_whatsapp', '+917923456789')) }}" target="_blank" class="text-base font-black text-emerald-600 hover:underline inline-flex items-center gap-1">
                            <span>{{ __('messages.chat_directly') }}</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>
                <p class="text-sm font-medium text-slate-600 border-t border-slate-100 pt-3.5">
                    {{ __('messages.quick_assistance') }}
                </p>
            </div>
        </div>

        <!-- Main Form & Full Height Map Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
            
            <!-- Left 7 Columns: Inquiry Form Card -->
            <div class="lg:col-span-7 bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col justify-between space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <h3 class="text-xl sm:text-2xl font-black text-slate-900">
                            {{ __('messages.inquiry_form') }}
                        </h3>
                        <p class="text-sm text-slate-500 font-medium mt-0.5">
                            {{ __('messages.fill_message_details') }}
                        </p>
                    </div>
                    <span class="text-xs font-black uppercase tracking-wider text-primary-700 bg-primary-50 px-3.5 py-1 rounded-full border border-primary-200/80 shrink-0">
                        {{ __('messages.fast_response') }}
                    </span>
                </div>

                <form method="POST" action="{{ route('contact.submit') }}" class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                    @csrf
                    <div class="space-y-1.5">
                        <label class="text-xs sm:text-sm font-bold text-slate-700 uppercase tracking-wide block">{{ __('messages.your_name') }} <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" required placeholder="{{ __('messages.your_name') }}" 
                            class="w-full text-sm font-semibold px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-0 text-slate-900 transition-all">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs sm:text-sm font-bold text-slate-700 uppercase tracking-wide block">{{ __('messages.email_address') }} <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" required placeholder="email@example.com" 
                            class="w-full text-sm font-semibold px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-0 text-slate-900 transition-all">
                    </div>

                    <div class="space-y-1.5 sm:col-span-2">
                        <label class="text-xs sm:text-sm font-bold text-slate-700 uppercase tracking-wide block">{{ __('messages.subject') }} <span class="text-rose-500">*</span></label>
                        <input type="text" name="subject" required placeholder="{{ __('messages.subject') }}" 
                            class="w-full text-sm font-semibold px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-0 text-slate-900 transition-all">
                    </div>

                    <div class="space-y-1.5 sm:col-span-2">
                        <label class="text-xs sm:text-sm font-bold text-slate-700 uppercase tracking-wide block">{{ __('messages.message') }} <span class="text-rose-500">*</span></label>
                        <textarea name="message" rows="4" required placeholder="{{ __('messages.message') }}" 
                            class="w-full text-sm font-semibold px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-0 text-slate-900 transition-all"></textarea>
                    </div>

                    <div class="sm:col-span-2 pt-2">
                        <button type="submit" 
                            class="px-8 py-3 bg-primary-600 hover:bg-primary-700 text-white font-extrabold text-sm sm:text-base rounded-xl shadow-md transition-all inline-flex items-center gap-2 cursor-pointer">
                            <span>{{ __('messages.submit_message') }}</span>
                            <span>&rarr;</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right 5 Columns: Location Map Card -->
            <div class="lg:col-span-5 bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col justify-between space-y-4 min-h-[420px]">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <span class="text-sm font-black text-slate-900 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                        <span>{{ __('messages.interactive_map') }}</span>
                    </span>
                    <span class="text-[10.5px] font-bold text-slate-400 uppercase tracking-wider">Ahmedabad, India</span>
                </div>

                <!-- Styled Full-Height Iframe Wrapper -->
                @php
                    $rawMap = trim(App\Models\Setting::get('contact_map_iframe', ''));
                @endphp
                <div class="grow rounded-xl overflow-hidden bg-slate-100 border border-slate-100 relative [&>iframe]:w-full [&>iframe]:h-full [&>iframe]:border-0 [&>iframe]:min-h-[350px]">
                    @if(!empty($rawMap))
                        @if(str_starts_with($rawMap, '<iframe'))
                            {!! $rawMap !!}
                        @else
                            <iframe src="{{ $rawMap }}" class="w-full h-full border-0 min-h-[350px]" loading="lazy" allowfullscreen referrerpolicy="no-referrer-when-downgrade"></iframe>
                        @endif
                    @else
                        <iframe src="https://maps.google.com/maps?q=Ahmedabad%20Gujarat&t=&z=13&ie=UTF8&iwloc=&output=embed" 
                            class="w-full h-full border-0 min-h-[350px]" loading="lazy"></iframe>
                    @endif
                </div>
            </div>

        </div>

    </div>
</section>
@endsection
