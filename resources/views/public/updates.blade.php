@extends('layouts.public')

@section('content')
@include('partials.page_header', [
    'title' => __('messages.updates'),
    'subtitle' => __('messages.updates_subtitle'),
    'breadcrumb' => __('messages.updates')
])

<!-- Updates Stream -->
<section class="py-8 bg-transparent">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($updates as $update)
                <a href="{{ route('update.details', $update->id) }}" class="group bg-white border border-slate-200/80 rounded-3xl overflow-hidden flex flex-col hover:shadow-xl hover:-translate-y-1 transition-all duration-300 block cursor-pointer">
                    <!-- Image Banner with Blurred Background & Full Foreground -->
                    @if($update->image_path)
                        @php
                            $imgSrc = str_starts_with($update->image_path, 'http') ? $update->image_path : asset('storage/' . $update->image_path);
                        @endphp
                        <div class="relative h-52 w-full overflow-hidden bg-slate-950 shrink-0 flex items-center justify-center">
                            <!-- Blurred Background Image -->
                            <img class="absolute inset-0 w-full h-full object-cover pointer-events-none" 
                                 src="{{ $imgSrc }}" 
                                 alt=""
                                 style="filter: blur(14px) brightness(0.5); transform: scale(1.15);">

                            <!-- Sharp Full Foreground Image -->
                            <img class="relative max-h-full max-w-full object-contain mx-auto transition-transform duration-500 group-hover:scale-105 drop-shadow-md z-1" 
                                 src="{{ $imgSrc }}" 
                                 alt="{{ $update->title }}">
                        </div>
                    @else
                        <div class="relative h-40 w-full bg-gradient-to-br from-primary-50 via-rose-50 to-orange-50 flex items-center justify-center text-primary-500 shrink-0">
                            <svg class="w-10 h-10 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                        </div>
                    @endif

                    <!-- Card Body -->
                    <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                        <div class="space-y-2.5">
                            <div class="flex items-center justify-between">
                                <span class="inline-flex items-center gap-1.5 text-xs font-black text-primary-700 bg-primary-50 border border-primary-100 px-3 py-1 rounded-xl uppercase tracking-wider shadow-2xs">
                                    <svg class="w-3.5 h-3.5 text-primary-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>{{ date('M d, Y', strtotime($update->publish_date)) }}</span>
                                </span>
                            </div>
                            <h3 class="text-base sm:text-lg font-black text-slate-900 leading-snug line-clamp-2 group-hover:text-primary-600 transition-colors" title="{{ $update->title }}">
                                {{ $update->title }}
                            </h3>
                            @php
                                $cleanDesc = strip_tags(str_replace(['<br>', '</p>', '</li>', '</div>'], ' ', $update->description));
                                $cleanDesc = trim(preg_replace('/\s+/', ' ', $cleanDesc));
                            @endphp
                            <p class="text-sm text-slate-600 line-clamp-3 leading-relaxed break-words font-medium">
                                {{ \Illuminate\Support\Str::limit($cleanDesc, 140, '...') }}
                            </p>
                        </div>
                        <div class="pt-3 border-t border-slate-100 flex justify-end items-center">
                            <span class="text-xs font-bold text-primary-600 group-hover:text-primary-700 inline-flex items-center gap-1.5">
                                <span>{{ __('messages.read_full_post') }}</span>
                                <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                            </span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-3 text-center py-20 text-slate-400">
                    <p class="text-base font-bold">{{ __('messages.no_announcements') }}</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $updates->links() }}
        </div>
    </div>
</section>
@endsection
