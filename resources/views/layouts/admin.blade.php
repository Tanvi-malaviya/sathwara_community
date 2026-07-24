<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    class="{{ app()->getLocale() == 'gu' ? 'font-gujarati' : 'font-sans' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? App\Models\Setting::get('website_name', 'Admin Dashboard') }}</title>
    @if(App\Models\Setting::get('website_favicon'))
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . App\Models\Setting::get('website_favicon')) }}">
    @endif

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&family=Noto+Sans+Gujarati:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Quill Rich Text Editor Assets -->
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

    {{--
    @php
    $primaryColor = App\Models\Setting::get('primary_color', '#ef4444');
    @endphp
    <style>
        :root {
            --primary-hex: {
                    {
                    $primaryColor
                }
            }

            ;
        }

        .text-primary-500,
        .group-hover\:text-primary-500:hover,
        .hover\:text-primary-500:hover {
            color: var(--primary-hex) !important;
        }

        .text-primary-600 {
            color: var(--primary-hex) !important;
            filter: brightness(90%);
        }

        .bg-primary-50 {
            background-color: var(--primary-hex) !important;
            opacity: 0.1;
        }

        .bg-primary-500 {
            background-color: var(--primary-hex) !important;
        }

        .hover\:bg-primary-600:hover {
            background-color: var(--primary-hex) !important;
            filter: brightness(90%);
        }

        .border-primary-500 {
            border-color: var(--primary-hex) !important;
        }

        .from-primary-500 {
            --tw-gradient-from: var(--primary-hex) var(--tw-gradient-from-position, ) !important;
            --tw-gradient-to: rgb(255 255 255 / 0) var(--tw-gradient-to-position, ) !important;
            --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important;
        }

        .to-primary-500 {
            --tw-gradient-to: var(--primary-hex) var(--tw-gradient-to-position, ) !important;
        }
    </style>
    --}}

    <style>
        .font-gujarati {
            font-family: 'Noto Sans Gujarati', sans-serif !important;
        }

        .font-sans,
        body {
            font-family: 'Manrope', sans-serif !important;
        }

        /* Custom Scrollbar Styles */
        html {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f1f5f9;
        }

        aside {
            scrollbar-width: thin;
            scrollbar-color: #334155 #000000;
        }

        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Sidebar Scrollbar */
        aside ::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }

        aside ::-webkit-scrollbar-track {
            background: #000000;
        }

        aside ::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 2px;
        }

        aside ::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }
    </style>
</head>

<body x-data="{ sidebarOpen: false }"
    class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col md:flex-row">
    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 bg-slate-950/40 backdrop-blur-sm md:hidden"
        x-cloak>
    </div>

    <!-- Sidebar -->
    <aside
        class="fixed inset-y-0 left-0 z-50 w-64 bg-black text-slate-300 flex flex-col shrink-0 transition-transform duration-300 transform md:translate-x-0 md:static md:h-screen md:sticky md:top-0 overflow-hidden"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" x-cloak>
        <!-- Sidebar Header -->
        <div class="h-16 flex items-center justify-between px-6 border-b border-zinc-900">
            <a href="{{ route('home') }}" class="flex items-center space-x-3">
                @if(App\Models\Setting::get('website_logo'))
                    <img src="{{ asset('storage/' . App\Models\Setting::get('website_logo')) }}" alt="Logo"
                        class="w-8 h-8 rounded-lg object-contain bg-white p-0.5 shadow-sm">
                @else
                    <img src="{{ asset('logo.png') }}" alt="Logo"
                        class="w-8 h-8 rounded-lg object-contain bg-white p-0.5 shadow-sm">
                @endif
                <span class="font-extrabold text-base text-white tracking-wide">{{ __('messages.admin_portal') }}</span>
            </a>
            <!-- Mobile Close Button -->
            <button @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-white focus:outline-none">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Sidebar Navigation -->
        <div class="flex-grow p-4 space-y-1 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center space-x-3 px-4 py-2.5 text-xs font-bold rounded-lg {{ Route::is('admin.dashboard') ? 'bg-primary-500 text-white' : 'text-slate-400 hover:bg-zinc-900 hover:text-white' }} transition-colors">
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2" />
                </svg>
                <span>{{ __('messages.dashboard_overview') }}</span>
            </a>

            <div class="pt-4 pb-1 text-[10px] font-extrabold uppercase text-slate-500 tracking-widest px-4">
                {{ __('messages.core_management') }}</div>
            <a href="{{ route('admin.members.index') }}"
                class="flex items-center space-x-3 px-4 py-2.5 text-xs font-bold rounded-lg {{ Route::is('admin.members.*') ? 'bg-primary-500 text-white' : 'text-slate-400 hover:bg-zinc-900 hover:text-white' }} transition-colors">
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span>{{ __('messages.members_approvals') }}</span>
            </a>
            <a href="{{ route('admin.areas.index') }}"
                class="flex items-center space-x-3 px-4 py-2.5 text-xs font-bold rounded-lg {{ Route::is('admin.areas.*') ? 'bg-primary-500 text-white' : 'text-slate-400 hover:bg-zinc-900 hover:text-white' }} transition-colors">
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>{{ __('messages.area_management') }}</span>
            </a>
            <a href="{{ route('admin.businesses.index') }}"
                class="flex items-center space-x-3 px-4 py-2.5 text-xs font-bold rounded-lg {{ Route::is('admin.businesses.index') ? 'bg-primary-500 text-white' : 'text-slate-400 hover:bg-zinc-900 hover:text-white' }} transition-colors">
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span>{{ __('messages.business_listings') }}</span>
            </a>

            <a href="{{ route('admin.events.index') }}"
                class="flex items-center space-x-3 px-4 py-2.5 text-xs font-bold rounded-lg {{ (Route::is('admin.events.*') || Route::is('admin.awards.*')) ? 'bg-primary-500 text-white' : 'text-slate-400 hover:bg-zinc-900 hover:text-white' }} transition-colors">
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>{{ __('messages.events_manager') }}</span>
            </a>
            <a href="{{ route('admin.gallery.index') }}"
                class="flex items-center space-x-3 px-4 py-2.5 text-xs font-bold rounded-lg {{ Route::is('admin.gallery.*') ? 'bg-primary-500 text-white' : 'text-slate-400 hover:bg-zinc-900 hover:text-white' }} transition-colors">
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>{{ __('messages.general_gallery') }}</span>
            </a>

            <div class="pt-4 pb-1 text-[10px] font-extrabold uppercase text-slate-500 tracking-widest px-4">
                {{ __('messages.content_controls') }}</div>
            <a href="{{ route('admin.content.sliders') }}"
                class="flex items-center space-x-3 px-4 py-2.5 text-xs font-bold rounded-lg {{ Route::is('admin.content.sliders') ? 'bg-primary-500 text-white' : 'text-slate-400 hover:bg-zinc-900 hover:text-white' }} transition-colors">
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                </svg>
                <span>{{ __('messages.hero_sliders') }}</span>
            </a>
            <a href="{{ route('admin.content.agendas') }}"
                class="flex items-center space-x-3 px-4 py-2.5 text-xs font-bold rounded-lg {{ Route::is('admin.content.agendas') ? 'bg-primary-500 text-white' : 'text-slate-400 hover:bg-zinc-900 hover:text-white' }} transition-colors">
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <span>{{ __('messages.core_agendas') }}</span>
            </a>
            <a href="{{ route('admin.content.desk') }}"
                class="flex items-center space-x-3 px-4 py-2.5 text-xs font-bold rounded-lg {{ Route::is('admin.content.desk') ? 'bg-primary-500 text-white' : 'text-slate-400 hover:bg-zinc-900 hover:text-white' }} transition-colors">
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span>{{ __('messages.management_desk') }}</span>
            </a>
            <a href="{{ route('admin.content.committee') }}"
                class="flex items-center space-x-3 px-4 py-2.5 text-xs font-bold rounded-lg {{ Route::is('admin.content.committee') ? 'bg-primary-500 text-white' : 'text-slate-400 hover:bg-zinc-900 hover:text-white' }} transition-colors">
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span>{{ __('messages.committee_members') }}</span>
            </a>
            <a href="{{ route('admin.content.timelines') }}"
                class="flex items-center space-x-3 px-4 py-2.5 text-xs font-bold rounded-lg {{ Route::is('admin.content.timelines') ? 'bg-primary-500 text-white' : 'text-slate-400 hover:bg-zinc-900 hover:text-white' }} transition-colors">
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ __('messages.milestone_timeline') }}</span>
            </a>
            <a href="{{ route('admin.content.updates') }}"
                class="flex items-center space-x-3 px-4 py-2.5 text-xs font-bold rounded-lg {{ Route::is('admin.content.updates') ? 'bg-primary-500 text-white' : 'text-slate-400 hover:bg-zinc-900 hover:text-white' }} transition-colors">
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                </svg>
                <span>{{ __('messages.announcements') }}</span>
            </a>

            <div class="pt-4 pb-1 text-[10px] font-extrabold uppercase text-slate-500 tracking-widest px-4">
                {{ __('messages.configuration') }}
            </div>
            <a href="{{ route('admin.settings.index') }}"
                class="flex items-center space-x-3 px-4 py-2.5 text-xs font-bold rounded-lg {{ Route::is('admin.settings.*') ? 'bg-primary-500 text-white' : 'text-slate-400 hover:bg-zinc-900 hover:text-white' }} transition-colors">
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>{{ __('messages.global_settings') }}</span>
            </a>

            <div class="border-t border-zinc-900 my-4"></div>
            <a href="{{ route('home') }}"
                class="flex items-center space-x-3 px-4 py-2.5 text-xs font-bold rounded-lg text-slate-400 hover:bg-zinc-900 hover:text-white transition-colors">
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>{{ __('messages.back_to_website') }}</span>
            </a>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit"
                    class="w-full flex items-center space-x-3 px-4 py-2.5 text-xs font-bold rounded-lg text-rose-400 hover:bg-rose-950 hover:text-rose-300 transition-colors text-left">
                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>{{ __('messages.sign_out') }}</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-grow flex flex-col min-w-0">
        <!-- Top Navigation -->
        <header class="h-16 bg-white border-b border-slate-100 flex items-center justify-between px-3 shrink-0">
            <div class="flex items-center space-x-3">
                <!-- Mobile Open Menu Toggle -->
                <button @click="sidebarOpen = true"
                    class="md:hidden text-slate-500 hover:text-slate-900 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <h2 class="font-extrabold text-xl text-slate-950">@yield('page_title', 'Admin Dashboard')</h2>
            </div>

            <div class="flex items-center space-x-2">
                <!-- Language Toggle -->
                <div class="relative" x-data="{ showLang: false }">
                    <button @click="showLang = !showLang"
                        class="inline-flex items-center px-3 py-1.5 border border-slate-200 text-xs font-bold rounded-lg text-slate-700 bg-slate-50 hover:bg-slate-100 transition-colors">
                        🌐 {{ app()->getLocale() == 'en' ? 'English' : 'ગુજરાતી' }}
                    </button>
                    <div x-show="showLang" @click.away="showLang = false"
                        class="absolute right-0 mt-2 w-32 bg-white border border-slate-100 rounded-lg shadow-lg py-1 z-30"
                        x-cloak>
                        <a href="{{ route('locale.set', 'en') }}"
                             class="block px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">English</a>
                        <a href="{{ route('locale.set', 'gu') }}"
                             class="block px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 font-gujarati">ગુજરાતી</a>
                    </div>
                </div>

                <span class="text-xs font-bold bg-primary-50 text-primary-600 px-3 py-1.5 rounded-lg">
                    {{ __('messages.administrator') }}: {{ auth()->user()->name }}
                </span>
            </div>
        </header>

        <!-- Dynamic Content Body -->
        <main class="flex-grow px-4 py-3">
            <!-- Toast Alerts -->
            @if (session('success') || session('error') || session('warning') || session('info'))
                <div class="mb-6" x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show"
                    x-transition class="relative">
                    @if (session('success'))
                        <div
                            class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between shadow-sm">
                            <span class="text-xs font-bold">✅ {{ session('success') }}</span>
                            <button type="button" @click="show = false"
                                class="text-emerald-500 hover:text-emerald-700 font-extrabold text-base focus:outline-none">&times;</button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div
                            class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 flex items-center justify-between shadow-sm">
                            <span class="text-xs font-bold">❌ {{ session('error') }}</span>
                            <button type="button" @click="show = false"
                                class="text-rose-500 hover:text-rose-700 font-extrabold text-base focus:outline-none">&times;</button>
                        </div>
                    @endif
                    @if (session('warning'))
                        <div
                            class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 flex items-center justify-between shadow-sm">
                            <span class="text-xs font-bold">⚠️ {{ session('warning') }}</span>
                            <button type="button" @click="show = false"
                                class="text-amber-500 hover:text-amber-700 font-extrabold text-base focus:outline-none">&times;</button>
                        </div>
                    @endif
                    @if (session('info'))
                        <div
                            class="p-4 rounded-xl bg-blue-50 border border-blue-200 text-blue-800 flex items-center justify-between shadow-sm">
                            <span class="text-xs font-bold">ℹ️ {{ session('info') }}</span>
                            <button type="button" @click="show = false"
                                class="text-blue-500 hover:text-blue-700 font-extrabold text-base focus:outline-none">&times;</button>
                        </div>
                    @endif
                </div>
            @endif

            @yield('content')
        </main>
    </div>
    @include('partials.global_loader')
    @include('partials.delete_confirm_modal')
    @stack('modals')
</body>

</html>