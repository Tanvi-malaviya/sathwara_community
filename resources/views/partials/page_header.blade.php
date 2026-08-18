@props([
    'title' => '',
    'subtitle' => null,
    'breadcrumb' => null
])

<!-- Sleek Split Minimal Hero Header -->
<section class="py-6 md:py-8 bg-slate-50/80 border-b border-slate-200/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="text-center md:text-left space-y-0.5">
            <h1 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">
                {{ $title }}
            </h1>
            @if(!empty($subtitle))
                <p class="text-sm sm:text-base font-bold text-primary-600 tracking-wide mt-1">
                    {{ $subtitle }}
                </p>
            @endif
        </div>

        <!-- Sleek Right-Aligned Breadcrumb Badge -->
        <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500 bg-white px-4 py-1.5 rounded-full border border-slate-200/80 shadow-xs">
            <a href="{{ route('home') }}" class="hover:text-primary-600 transition-colors flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>{{ __('messages.home') }}</span>
            </a>
            <span class="text-slate-300">/</span>
            <span class="text-primary-600 font-extrabold">{{ $breadcrumb ?? $title }}</span>
        </nav>
    </div>
</section>
