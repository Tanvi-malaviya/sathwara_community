@extends('layouts.member')

@section('page_title', __('messages.my_registered_businesses'))

@section('content')
<div class="space-y-4">
    <!-- Action Bar (If needed) -->
    <div class="flex items-center justify-between gap-3 flex-wrap">
        <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-slate-200/80 text-xs font-bold text-slate-700 shadow-xs">
            <span>🏢 {{ __('messages.total_businesses') ?? 'Total Businesses' }}:</span>
            <span class="font-black text-primary-600">{{ $businesses->count() }}</span>
        </div>

        @if($businesses->count() < 1)
        <a href="{{ route('register.business') }}" target="_blank" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-bold text-xs sm:text-sm rounded-xl shadow-xs transition-all inline-flex items-center gap-1.5 shrink-0">
            <span>+ {{ __('messages.register_new_business') }}</span>
        </a>
        @endif
    </div>

    <!-- Businesses Grid -->
    @if($businesses->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($businesses as $b)
                <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-xs space-y-3 relative overflow-hidden group hover:border-slate-300 hover:shadow-md transition-all">
                    <!-- Top Info: Logo, Badges, Name -->
                    <div class="flex items-start gap-3">
                        <img src="{{ str_starts_with($b->logo_path, 'http') ? $b->logo_path : asset('storage/' . $b->logo_path) }}" 
                             alt="{{ $b->business_name }}" 
                             class="w-12 h-12 rounded-xl object-cover border border-slate-100 bg-slate-50 shrink-0 shadow-2xs">
                        
                        <div class="space-y-1 flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-1.5 flex-wrap">
                                <span class="text-[10px] sm:text-[10.5px] font-black text-primary-700 bg-primary-50 border border-primary-200/60 px-2 py-0.5 rounded-md uppercase">
                                    {{ $b->category?->name ?? 'General' }}
                                </span>

                                @if($b->status === 'approved')
                                    <span class="text-[10px] sm:text-[10.5px] font-black text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-md uppercase">{{ __('messages.approved') }}</span>
                                @elseif($b->status === 'pending')
                                    <span class="text-[10px] sm:text-[10.5px] font-black text-amber-700 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-md uppercase">{{ __('messages.pending') }}</span>
                                @else
                                    <span class="text-[10px] sm:text-[10.5px] font-black text-rose-700 bg-rose-50 border border-rose-200 px-2 py-0.5 rounded-md uppercase">{{ __('messages.rejected') }}</span>
                                @endif
                            </div>
                            
                            <h3 class="text-sm sm:text-base font-black text-slate-900 leading-snug break-words group-hover:text-primary-600 transition-colors" title="{{ $b->business_name }}">{{ $b->business_name }}</h3>
                            <p class="text-xs text-slate-500 font-semibold">{{ __('messages.owner') }}: <span class="text-slate-800 font-bold">{{ $b->owner_name }}</span></p>
                        </div>
                    </div>

                    <!-- Details Box -->
                    <div class="grid grid-cols-2 gap-2 text-xs font-semibold text-slate-700 bg-slate-50/90 p-2.5 rounded-xl border border-slate-100">
                        <div>
                            <span class="text-[9.5px] text-slate-400 font-black uppercase block leading-none mb-1 tracking-wider">{{ __('messages.phone') }}</span>
                            <a href="tel:{{ $b->phone }}" class="text-slate-900 font-bold block truncate hover:text-primary-600 transition-colors">{{ $b->phone }}</a>
                        </div>
                        <div>
                            <span class="text-[9.5px] text-slate-400 font-black uppercase block leading-none mb-1 tracking-wider">{{ __('messages.location') }}</span>
                            <span class="text-slate-900 font-bold truncate block">{{ $b->area?->name ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <!-- Card Footer -->
                    <div class="flex items-center justify-between pt-1 border-t border-slate-100 text-xs">
                        <span class="text-[11px] text-slate-400 font-medium">📅 {{ $b->created_at->format('d M Y') }}</span>
                        <a href="{{ route('business.details', $b->id) }}" target="_blank" class="text-primary-600 font-black hover:text-primary-700 inline-flex items-center gap-1">
                            <span>{{ __('messages.details') }} ➔</span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-2xl p-10 border border-slate-100 text-center space-y-3 shadow-xs">
            <span class="text-4xl block">🏢</span>
            <h3 class="text-sm sm:text-base font-black text-slate-800">{{ __('messages.no_businesses_registered_yet') }}</h3>
            <p class="text-xs text-slate-500 max-w-sm mx-auto font-medium">{{ __('messages.no_businesses_registered_desc') }} (#{{ sprintf('%05d', auth()->user()->id) }}).</p>
            <a href="{{ route('register.business') }}" target="_blank" class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-bold text-xs sm:text-sm rounded-xl shadow-xs transition-all">
                <span>+ {{ __('messages.register_your_business_now') }}</span>
            </a>
        </div>
    @endif
</div>
@endsection
