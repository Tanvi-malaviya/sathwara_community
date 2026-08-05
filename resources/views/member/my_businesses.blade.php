@extends('layouts.member')

@section('page_title', __('messages.my_registered_businesses'))

@section('content')
<div class="space-y-3">
    <!-- Compact Header with Action -->
    <div class="bg-white p-3.5 sm:p-4 rounded-xl border border-slate-100 shadow-2xs flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="space-y-0.5 text-center sm:text-left">
            <h2 class="text-sm font-black text-slate-900 flex items-center justify-center sm:justify-start gap-1.5">
                <span>💼</span> {{ __('messages.my_registered_businesses') }}
            </h2>
            <p class="text-[11px] text-slate-500 font-medium">{{ __('messages.businesses_linked_to_member_id') }} (<span class="font-bold text-primary-600">#{{ sprintf('%05d', auth()->user()->id) }}</span>)</p>
        </div>

        @if($businesses->count() < 1)
        <a href="{{ route('register.business') }}" target="_blank" class="px-3 py-1.5 bg-primary-500 hover:bg-primary-600 text-white font-bold text-xs rounded-lg shadow-2xs transition-transform hover:-translate-y-0.5 inline-flex items-center gap-1 shrink-0">
            <span>+ {{ __('messages.register_new_business') }}</span>
        </a>
        @endif
    </div>

    <!-- Businesses Grid (Small & Compact Layout) -->
    @if($businesses->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($businesses as $b)
                <div class="bg-white rounded-xl p-3.5 border border-slate-100 shadow-2xs space-y-2.5 relative overflow-hidden group hover:border-slate-300 transition-all">
                    <!-- Top Info: Logo, Badges, Name -->
                    <div class="flex items-start gap-2.5">
                        <img src="{{ str_starts_with($b->logo_path, 'http') ? $b->logo_path : asset('storage/' . $b->logo_path) }}" 
                             alt="{{ $b->business_name }}" 
                             class="w-10 h-10 rounded-lg object-cover border border-slate-100 bg-slate-50 shrink-0">
                        
                        <div class="space-y-0.5 flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-1">
                                <span class="text-[8px] font-black text-primary-600 bg-primary-50 px-1.5 py-0.5 rounded uppercase truncate max-w-[110px]">
                                    {{ $b->category?->name ?? 'General' }}
                                </span>

                                @if($b->status === 'approved')
                                    <span class="text-[8px] font-extrabold text-emerald-700 bg-emerald-50 border border-emerald-100 px-1.5 py-0.5 rounded-full uppercase">{{ __('messages.approved') }}</span>
                                @elseif($b->status === 'pending')
                                    <span class="text-[8px] font-extrabold text-amber-700 bg-amber-50 border border-amber-100 px-1.5 py-0.5 rounded-full uppercase">{{ __('messages.pending') }}</span>
                                @else
                                    <span class="text-[8px] font-extrabold text-rose-700 bg-rose-50 border border-rose-100 px-1.5 py-0.5 rounded-full uppercase">{{ __('messages.rejected') }}</span>
                                @endif
                            </div>
                            
                            <h3 class="text-xs font-black text-slate-900 truncate leading-snug" title="{{ $b->business_name }}">{{ $b->business_name }}</h3>
                            <p class="text-[10px] text-slate-500 font-medium truncate">{{ __('messages.owner') }}: {{ $b->owner_name }}</p>
                        </div>
                    </div>

                    <!-- Compact Details Box -->
                    <div class="grid grid-cols-2 gap-1.5 text-[10px] font-semibold text-slate-600 bg-slate-50/80 p-2 rounded-lg border border-slate-100">
                        <div>
                            <span class="text-[8px] text-slate-400 font-extrabold uppercase block leading-none mb-0.5">{{ __('messages.phone') }}</span>
                            <span class="text-slate-900 font-bold block truncate">{{ $b->phone }}</span>
                        </div>
                        <div>
                            <span class="text-[8px] text-slate-400 font-extrabold uppercase block leading-none mb-0.5">{{ __('messages.location') }}</span>
                            <span class="text-slate-900 font-bold truncate block">{{ $b->area?->name ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <!-- Card Footer -->
                    <div class="flex items-center justify-between pt-0.5 text-[10px]">
                        <span class="text-[9px] text-slate-400 font-semibold">{{ $b->created_at->format('d M Y') }}</span>
                        <a href="{{ route('business.details', $b->id) }}" target="_blank" class="text-primary-600 font-bold hover:underline inline-flex items-center gap-0.5">
                            <span>{{ __('messages.details') }} ➔</span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-xl p-8 border border-slate-100 text-center space-y-2.5">
            <span class="text-3xl block">🏢</span>
            <h3 class="text-xs font-black text-slate-800">{{ __('messages.no_businesses_registered_yet') }}</h3>
            <p class="text-[11px] text-slate-500 max-w-sm mx-auto font-medium">{{ __('messages.no_businesses_registered_desc') }} (#{{ sprintf('%05d', auth()->user()->id) }}).</p>
            <a href="{{ route('register.business') }}" target="_blank" class="inline-block px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white font-bold text-xs rounded-lg shadow-2xs">
                + {{ __('messages.register_your_business_now') }}
            </a>
        </div>
    @endif
</div>
@endsection
