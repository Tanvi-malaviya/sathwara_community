@extends('layouts.public')

@section('content')
@include('partials.page_header', [
    'title' => __('messages.business_directory'),
    'subtitle' => __('messages.business_subtitle'),
    'breadcrumb' => __('messages.business_directory')
])

<!-- Search and Directory Grid -->
<section class="py-10 bg-transparent">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Filters Sidebar -->
            <div class="lg:col-span-1 space-y-4">
                <!-- Search Box -->
                <div class="bg-white rounded-2xl p-5 border border-slate-200/60 shadow-xs space-y-3">
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-wider pb-2 border-b border-slate-100">{{ __('messages.search_directory') }}</h3>
                    <form method="GET" action="{{ route('business.directory') }}" class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('messages.search_company_owner') }}" 
                               class="w-full text-xs sm:text-[13.5px] font-bold pl-3 pr-9 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-0">
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-primary-500 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- Categories -->
                <div class="bg-white rounded-2xl p-5 border border-slate-200/60 shadow-xs space-y-4">
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-wider pb-2 border-b border-slate-100">{{ __('messages.categories') }}</h3>
                    <div class="flex flex-col space-y-1.5">
                        <a href="{{ route('business.directory', request()->only('search')) }}" 
                           class="text-xs sm:text-[13.5px] font-bold px-3 py-2 rounded-xl flex justify-between items-center transition-all {{ !request('category') ? 'bg-primary-50 text-primary-600' : 'text-slate-600 hover:bg-slate-50' }}">
                            <span>{{ __('messages.all_categories') }}</span>
                        </a>
                        @foreach($categories as $cat)
                            <a href="{{ route('business.directory', array_merge(request()->only('search'), ['category' => $cat->slug])) }}" 
                               class="text-xs sm:text-[13.5px] font-bold px-3 py-2 rounded-xl flex justify-between items-center transition-all {{ request('category') == $cat->slug ? 'bg-primary-50 text-primary-600' : 'text-slate-600 hover:bg-slate-50' }}">
                                <span>{{ $cat->name }}</span>
                                <span class="text-[11px] font-black px-2 py-0.5 rounded-md transition-all {{ request('category') == $cat->slug ? 'bg-primary-100 text-primary-700' : 'bg-slate-100 text-slate-600' }}">{{ $cat->businesses_count }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Register Callout -->
                <div class="bg-gradient-to-br from-slate-900 via-slate-950 to-slate-900 rounded-2xl p-5 text-white shadow-xs space-y-3 relative overflow-hidden">
                    <div class="absolute -right-8 -bottom-8 w-24 h-24 bg-primary-500/10 rounded-full blur-xl"></div>
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-primary-400 block">{{ __('messages.community_network') }}</span>
                    <h4 class="text-sm font-black uppercase tracking-wide">{{ __('messages.add_your_business') }}</h4>
                    <p class="text-xs text-slate-300 leading-relaxed">{{ __('messages.promote_org') }}</p>
                    <a href="{{ route('register.business') }}" class="inline-flex items-center justify-center w-full px-4 py-2.5 bg-primary-500 hover:bg-primary-600 text-white font-extrabold text-xs uppercase tracking-wider rounded-xl shadow-xs transition-transform hover:-translate-y-0.5 mt-2">
                        {{ __('messages.register_now') }}
                    </a>
                </div>
            </div>

            <!-- Businesses Grid (Compact Vertical Cards Grid) -->
            <div class="lg:col-span-3 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3.5">
                    @forelse($businesses as $biz)
                        <div class="group bg-white border border-slate-200/70 rounded-2xl p-4 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 flex flex-col justify-between space-y-3">
                            <div>
                                <!-- Top Row: Logo + Category Badge (Zero Overlapping) -->
                                <div class="flex items-start justify-between gap-2 border-b border-slate-100 pb-3">
                                    <img class="w-12 h-12 rounded-xl object-cover bg-slate-50 border border-slate-200/80 shadow-xs shrink-0 group-hover:scale-105 transition-transform duration-300" 
                                         src="{{ str_starts_with($biz->logo_path, 'http') ? $biz->logo_path : asset('storage/' . $biz->logo_path) }}" 
                                         alt="{{ $biz->business_name }}">
                                    
                                    <div class="flex flex-col items-end gap-1.5 shrink-0">
                                        <span class="text-[10.5px] font-black text-primary-700 bg-primary-50 border border-primary-100/80 px-2.5 py-0.5 rounded-md uppercase tracking-wider">
                                            {{ $biz->category?->name ?? __('messages.general') }}
                                        </span>
                                        @if($biz->membership_status === 'active')
                                            <span class="text-[10.5px] font-black text-emerald-700 bg-emerald-50 border border-emerald-100/80 px-2.5 py-0.5 rounded-md uppercase tracking-wider">{{ __('messages.active') }}</span>
                                        @else
                                            <span class="text-[10.5px] font-black text-rose-700 bg-rose-50 border border-rose-100/80 px-2.5 py-0.5 rounded-md uppercase tracking-wider">{{ __('messages.expired') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Info Content -->
                                <div class="pt-3 space-y-2">
                                    <div>
                                        <a href="{{ route('business.details', $biz->id) }}" class="block">
                                            <h3 class="text-sm sm:text-base font-black text-slate-900 group-hover:text-primary-600 transition-colors line-clamp-1" title="{{ $biz->business_name }}">
                                                {{ $biz->business_name }}
                                            </h3>
                                        </a>
                                        <p class="text-xs text-slate-500 font-semibold truncate mt-1">
                                            {{ __('messages.owner') }}: <span class="text-slate-800 font-bold">{{ $biz->owner_name }}</span>
                                            @if($biz->member_id)
                                                <span class="mx-1 text-slate-300">•</span> {{ __('messages.member_id') }}: <span class="text-slate-800 font-bold">{{ $biz->member_id }}</span>
                                            @endif
                                        </p>
                                        @if($biz->approved_at)
                                            <p class="text-[11px] text-slate-400 font-semibold mt-1">
                                                {{ __('messages.purchase') }}: <span class="text-slate-600">{{ $biz->approved_at->format('d M Y') }}</span>
                                                <span class="mx-1 text-slate-300">•</span>
                                                {{ __('messages.expires') }}: <span class="text-slate-600">{{ $biz->approved_at->addYear()->format('d M Y') }}</span>
                                            </p>
                                        @endif
                                    </div>

                                    <p class="text-xs sm:text-[13px] text-slate-600 line-clamp-2 leading-relaxed min-h-[36px]">
                                        {{ $biz->description ?? __('messages.no_business_description') }}
                                    </p>

                                    <!-- Address & Contact Info Badges -->
                                    <div class="space-y-1 pt-2.5 text-xs text-slate-600 font-bold border-t border-slate-100">
                                        <div class="flex items-center gap-1.5 truncate" title="{{ $biz->area?->name ?? $biz->address }}">
                                            <span class="text-slate-400 text-xs">📍</span>
                                            <span class="truncate">{{ $biz->area?->name ?? $biz->address }}</span>
                                        </div>
                                        @if($biz->phone)
                                            <div class="flex items-center gap-1.5">
                                                <span class="text-slate-400 text-xs">📞</span>
                                                <a href="tel:{{ $biz->phone }}" class="hover:text-primary-600 transition-colors">{{ $biz->phone }}</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Footer Actions -->
                            <div class="pt-2.5 border-t border-slate-100 flex items-center gap-2">
                                @if($biz->phone)
                                    <a href="tel:{{ $biz->phone }}" class="flex-1 text-center py-2 text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                                        {{ __('messages.call_now') }}
                                    </a>
                                @endif
                                <a href="{{ route('business.details', $biz->id) }}" class="flex-1 text-center py-2 text-xs font-bold text-white bg-primary-600 hover:bg-primary-700 rounded-xl transition-colors shadow-2xs">
                                    {{ __('messages.view_details') }} &rarr;
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-20 text-slate-500 font-bold bg-white rounded-2xl border border-slate-200/60 shadow-xs">
                            {{ __('messages.no_matching_businesses') }}
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $businesses->links() }}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
