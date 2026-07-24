@extends('layouts.public')

@section('content')
<!-- Business Banner / Header -->
<section class="bg-gradient-to-br from-slate-900 via-slate-950 to-slate-900 py-12 text-white relative overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-primary-500/10 via-transparent to-transparent"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('business.directory') }}" class="inline-flex items-center gap-1.5 text-xs text-slate-400 hover:text-white font-bold transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Directory
            </a>
        </div>
        
        <div class="flex flex-col md:flex-row items-center gap-6">
            <img class="w-24 h-24 rounded-2xl object-cover bg-white border-2 border-slate-700 shadow-xl shrink-0" 
                 src="{{ str_starts_with($business->logo_path, 'http') ? $business->logo_path : asset('storage/' . $business->logo_path) }}" 
                 alt="{{ $business->business_name }}">
            <div class="text-center md:text-left space-y-2">
                <span class="text-[10px] font-extrabold text-primary-400 bg-primary-500/10 border border-primary-500/20 px-3 py-1 rounded-full uppercase tracking-wider inline-block">
                    {{ $business->category?->name ?? 'General' }}
                </span>
                <h1 class="text-2xl md:text-4xl font-black mt-1 tracking-tight">{{ $business->business_name }}</h1>
                <p class="text-xs text-slate-400 font-bold">Owned by: <span class="text-white">{{ $business->owner_name }}</span></p>
            </div>
        </div>
    </div>
</section>

<!-- Details Body -->
<section class="py-5 bg-slate-50/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
            <!-- Left: Description and portfolio -->
            <div class="lg:col-span-2 space-y-8">
                <!-- About the Business -->
                <div class="bg-white border border-slate-200/60 rounded-2xl p-6 shadow-xs space-y-4">
                    <h2 class="text-xs font-black text-slate-800 uppercase tracking-wider pb-2 border-b border-slate-100 flex items-center gap-2">
                        <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        About the Business
                    </h2>
                    @if($business->description)
                        <p class="text-xs text-slate-600 leading-relaxed whitespace-pre-line font-medium">{!! e($business->description) !!}</p>
                    @else
                        <div class="text-center py-6 text-slate-400 font-bold text-xs bg-slate-50 rounded-xl border border-slate-100">
                            This business has not provided a description yet.
                        </div>
                    @endif
                </div>

                <!-- Product/Work Gallery -->
                @if(is_array($business->gallery_images) && count($business->gallery_images) > 0)
                    <div class="bg-white border border-slate-200/60 rounded-2xl p-6 shadow-xs space-y-6">
                        <h2 class="text-sm font-black text-slate-800 uppercase tracking-wider pb-2 border-b border-slate-100 flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Product Portfolio / Gallery
                        </h2>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4" x-data="{ lightbox: false, activeSrc: '' }">
                            @foreach($business->gallery_images as $img)
                                <div class="aspect-square rounded-xl overflow-hidden bg-slate-50 border border-slate-100 group relative cursor-pointer"
                                     @click="activeSrc = '{{ str_starts_with($img, 'http') ? $img : asset('storage/' . $img) }}'; lightbox = true">
                                    <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" 
                                         src="{{ str_starts_with($img, 'http') ? $img : asset('storage/' . $img) }}" 
                                         alt="Gallery Image">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                            @endforeach

                            <!-- Lightbox Modal -->
                            <div x-show="lightbox" class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center p-4" x-cloak>
                                <button @click="lightbox = false" class="absolute top-4 right-4 text-white text-3xl font-black">&times;</button>
                                <img :src="activeSrc" class="max-h-[85vh] max-w-full rounded-lg shadow-2xl">
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right: Contact Card -->
            <div>
                <div class="bg-white rounded-2xl p-6 border border-slate-200/60 shadow-xs space-y-5">
                    <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider pb-3 border-b border-slate-100 flex items-center gap-2">
                        <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 19v-8.93a2 2 0 01.89-1.664l8-4.666a2 2 0 012.22 0l8 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-2.25-1.5a2 2 0 00-2.22 0l-2.25 1.5"></path>
                        </svg>
                        Contact Information
                    </h3>

                    <div class="space-y-4 text-xs font-semibold text-slate-700">
                        <div class="flex items-start gap-3">
                            <svg class="w-4 h-4 text-slate-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <div>
                                <h4 class="text-[9px] text-slate-400 font-extrabold uppercase tracking-wide">Registered Since</h4>
                                <p class="text-slate-800 mt-0.5 leading-relaxed">{{ $business->created_at->format('d M Y') }}</p>
                            </div>
                        </div>

                        @if($business->approved_at)
                            <div class="flex items-start gap-3">
                                <svg class="w-4 h-4 text-slate-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                <div>
                                    <h4 class="text-[9px] text-slate-400 font-extrabold uppercase tracking-wide">Subscription Status</h4>
                                    @if($business->membership_status === 'active')
                                        <span class="text-[10px] font-extrabold text-emerald-700 bg-emerald-50 border border-emerald-100 px-2 py-0.5 rounded mt-0.5 inline-block uppercase">Active</span>
                                    @else
                                        <span class="text-[10px] font-extrabold text-rose-700 bg-rose-50 border border-rose-100 px-2 py-0.5 rounded mt-0.5 inline-block uppercase">Expired</span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <svg class="w-4 h-4 text-slate-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <div>
                                    <h4 class="text-[9px] text-slate-400 font-extrabold uppercase tracking-wide">Subscription Purchase Date</h4>
                                    <p class="text-slate-800 mt-0.5 leading-relaxed">{{ $business->approved_at->format('d M Y') }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <svg class="w-4 h-4 text-slate-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <h4 class="text-[9px] text-slate-400 font-extrabold uppercase tracking-wide">Subscription Expiry Date</h4>
                                    <p class="text-slate-800 mt-0.5 leading-relaxed">{{ $business->approved_at->addYear()->format('d M Y') }}</p>
                                </div>
                            </div>
                        @endif

                        @if($business->member_id)
                            <div class="flex items-start gap-3">
                                <svg class="w-4 h-4 text-slate-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2H5z"></path>
                                </svg>
                                <div>
                                    <h4 class="text-[9px] text-slate-400 font-extrabold uppercase tracking-wide">Member ID</h4>
                                    <p class="text-slate-800 mt-0.5 leading-relaxed">{{ $business->member_id }}</p>
                                </div>
                            </div>
                        @endif

                        @if($business->area)
                            <div class="flex items-start gap-3">
                                <svg class="w-4 h-4 text-slate-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                </svg>
                                <div>
                                    <h4 class="text-[9px] text-slate-400 font-extrabold uppercase tracking-wide">Area / Location</h4>
                                    <p class="text-slate-800 mt-0.5 leading-relaxed">{{ $business->area->name }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="flex items-start gap-3">
                            <svg class="w-4 h-4 text-slate-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <div>
                                <h4 class="text-[9px] text-slate-400 font-extrabold uppercase tracking-wide">Address</h4>
                                <p class="text-slate-800 mt-0.5 leading-relaxed">{{ $business->address }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <svg class="w-4 h-4 text-slate-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <div>
                                <h4 class="text-[9px] text-slate-400 font-extrabold uppercase tracking-wide">Phone Number</h4>
                                <a href="tel:{{ $business->phone }}" class="text-primary-500 hover:underline mt-0.5 inline-block">{{ $business->phone }}</a>
                            </div>
                        </div>

                        @if($business->whatsapp)
                            <div class="flex items-start gap-3">
                                <svg class="w-4 h-4 text-emerald-500 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.004 2C6.51 2 2.014 6.5 2.014 12c0 1.89.52 3.65 1.43 5.17L2.06 22l5.02-1.32c1.47.8 3.14 1.26 4.92 1.26 5.5 0 9.99-4.5 9.99-10S17.5 2 12.004 2zm4.8 13.9c-.2.58-1.16 1.09-1.6 1.15-.42.06-.9.1-2.9-.73-2.58-1.07-4.22-3.7-4.35-3.87-.13-.17-1.11-1.48-1.11-2.82 0-1.35.7-2 .95-2.27.26-.26.56-.33.74-.33h.53c.17 0 .39-.06.6.45.2.5.7 1.76.77 1.9.07.13.11.3.02.48-.09.18-.13.3-.27.46-.14.16-.3.35-.43.47-.15.15-.3.32-.13.62.17.3.74 1.22 1.59 1.97.92.8 1.69 1.05 1.93 1.17.24.12.38.1.52-.06.14-.16.6-7.01.76-.94.16-.14.32-.12.54-.04.22.08 1.4.66 1.64.78.25.12.41.18.47.28.06.11.06.63-.14 1.21z"/>
                                </svg>
                                <div>
                                    <h4 class="text-[9px] text-slate-400 font-extrabold uppercase tracking-wide">WhatsApp</h4>
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $business->whatsapp) }}" target="_blank" class="text-emerald-500 hover:underline mt-0.5 inline-block">{{ $business->whatsapp }}</a>
                                </div>
                            </div>
                        @endif

                        @if($business->email)
                            <div class="flex items-start gap-3">
                                <svg class="w-4 h-4 text-slate-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <div>
                                    <h4 class="text-[9px] text-slate-400 font-extrabold uppercase tracking-wide">Email Address</h4>
                                    <a href="mailto:{{ $business->email }}" class="text-slate-700 hover:underline mt-0.5 inline-block">{{ $business->email }}</a>
                                </div>
                            </div>
                        @endif

                        @if($business->website)
                            <div class="flex items-start gap-3">
                                <svg class="w-4 h-4 text-slate-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                </svg>
                                <div>
                                    <h4 class="text-[9px] text-slate-400 font-extrabold uppercase tracking-wide">Website</h4>
                                    <a href="{{ $business->website }}" target="_blank" class="text-primary-500 hover:underline mt-0.5 inline-block">{{ $business->website }}</a>
                                </div>
                            </div>
                        @endif

                        @if($business->facebook || $business->instagram || $business->youtube || $business->linkedin)
                            <div class="pt-4 border-t border-slate-100">
                                <h4 class="text-[9px] text-slate-400 font-extrabold uppercase tracking-wide mb-3">Social Connections</h4>
                                <div class="flex flex-wrap gap-2">
                                    @if($business->facebook)
                                        <a href="{{ $business->facebook }}" target="_blank" class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors flex items-center justify-center" title="Facebook">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c4.56-.93 8-4.96 8-9.75z"/></svg>
                                        </a>
                                    @endif
                                    @if($business->instagram)
                                        <a href="{{ $business->instagram }}" target="_blank" class="w-7 h-7 rounded-lg bg-pink-50 text-pink-600 hover:bg-pink-100 transition-colors flex items-center justify-center" title="Instagram">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.051.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                                        </a>
                                    @endif
                                    @if($business->youtube)
                                        <a href="{{ $business->youtube }}" target="_blank" class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition-colors flex items-center justify-center" title="YouTube">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.163a3.003 3.003 0 00-2.11-2.11C19.518 3.545 12 3.545 12 3.545s-7.518 0-9.388.508a3.003 3.003 0 00-2.11 2.11C0 8.033 0 12 0 12s0 3.967.502 5.837a3.003 3.003 0 002.11 2.11c1.87.508 9.388.508 9.388.508s7.518 0 9.388-.508a3.003 3.003 0 002.11-2.11C24 15.967 24 12 24 12s0-3.967-.502-5.837zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                        </a>
                                    @endif
                                    @if($business->linkedin)
                                        <a href="{{ $business->linkedin }}" target="_blank" class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-colors flex items-center justify-center" title="LinkedIn">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.779-1.75-1.75s.784-1.75 1.75-1.75 1.75.779 1.75 1.75-.784 1.75-1.75 1.75zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
