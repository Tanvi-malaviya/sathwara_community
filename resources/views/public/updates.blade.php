@extends('layouts.public')

@section('content')
@include('partials.page_header', [
    'title' => __('messages.updates'),
    'subtitle' => __('messages.updates_subtitle'),
    'breadcrumb' => __('messages.updates')
])

<!-- Updates Stream -->
<section class="py-6 bg-transparent">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Search bar -->
        {{-- <div class="mb-6 max-w-md mx-auto">
            <form method="GET" action="{{ route('updates') }}" class="flex items-center gap-2">
                <div class="relative flex-grow">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('messages.search_announcements_placeholder') }}" 
                           class="text-xs font-semibold pl-10 pr-4 py-2.5 w-full bg-white border border-slate-200 rounded-2xl focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-colors shadow-xs">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    @if(request()->filled('search'))
                        <a href="{{ route('updates') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-rose-500 font-extrabold text-sm" title="Clear search">
                            &times;
                        </a>
                    @endif
                </div>
                <button type="submit" class="px-4 py-2.5 bg-primary-500 hover:bg-primary-600 text-white font-bold text-xs rounded-2xl shadow-xs transition-colors shrink-0">
                    {{ __('messages.search') }}
                </button>
            </form>
        </div> --}}

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            @forelse($updates as $update)
                <div class="bg-white border border-slate-200/60 rounded-2xl p-4 flex gap-4 hover:shadow-md hover:-translate-y-0.5 transition-all duration-300">
                    @if($update->image_path)
                        <div class="relative w-20 h-20 rounded-xl overflow-hidden shrink-0 bg-slate-100 border border-slate-100">
                            <img class="w-full h-full object-cover" 
                                 src="{{ str_starts_with($update->image_path, 'http') ? $update->image_path : asset('storage/' . $update->image_path) }}" 
                                 alt="{{ $update->title }}">
                        </div>
                    @else
                        <div class="relative w-20 h-20 rounded-xl overflow-hidden shrink-0 bg-primary-50/50 flex items-center justify-center text-primary-500 border border-primary-100/50">
                            📢
                        </div>
                    @endif
                    <div class="flex-grow flex flex-col justify-between min-w-0">
                        <div class="space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="text-[9px] font-extrabold text-primary-500 bg-primary-50 px-2 py-0.5 rounded uppercase tracking-wider">
                                    {{ date('M d, Y', strtotime($update->publish_date)) }}
                                </span>
                            </div>
                            <h3 class="text-sm font-bold text-slate-900 leading-snug line-clamp-1" title="{{ $update->title }}">{{ $update->title }}</h3>
                            <p class="text-[11px] text-slate-500 line-clamp-2 leading-relaxed break-words">{{ $update->description }}</p>
                        </div>
                        <div class="pt-2 flex justify-end">
                            <a href="{{ route('update.details', $update->id) }}" class="text-[10px] font-bold text-primary-600 hover:text-primary-700 bg-primary-50 px-2.5 py-1 rounded-lg border border-primary-100 transition-colors">
                                {{ __('messages.read_full_post') }} &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-2 text-center py-20 text-slate-400">
                    {{ __('messages.no_announcements') }}
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $updates->links() }}
        </div>
    </div>
</section>
@endsection
