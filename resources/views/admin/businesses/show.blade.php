@extends('layouts.admin')

@section('page_title', __('messages.business_details'))

@section('content')
<div class="bg-white rounded-xl p-3.5 border border-slate-100 shadow-sm space-y-3">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2.5 pb-3 border-b border-slate-100">
        <div class="flex items-center space-x-3">
            <img class="w-10 h-10 rounded-lg object-cover bg-slate-50 border border-slate-200/60 shrink-0" 
                 src="{{ str_starts_with($business->logo_path, 'http') ? $business->logo_path : asset('storage/' . $business->logo_path) }}" 
                 alt="Business Logo">
            <div class="min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <h3 class="text-sm font-extrabold text-slate-900 leading-tight truncate">{{ $business->business_name }}</h3>
                    <span class="px-2 py-0.2 bg-primary-50 text-primary-600 border border-primary-200/60 rounded text-[9px] font-extrabold uppercase">
                        {{ $business->category?->name ?? 'N/A' }}
                    </span>
                </div>
                <p class="text-[11px] text-slate-500 font-medium truncate mt-0.5">{{ __('messages.owner') }}: <strong class="text-slate-800">{{ $business->owner_name }}</strong></p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-1.5 shrink-0">
            <!-- Edit Button -->
            <a href="{{ route('admin.businesses.edit', $business->id) }}" 
               class="px-2.5 py-1 border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs rounded-lg transition-colors bg-white shadow-2xs flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span>{{ __('messages.edit') }}</span>
            </a>

            <!-- Approve Action -->
            @if($business->status !== 'approved')
                <form method="POST" action="{{ route('admin.businesses.approve', $business->id) }}" class="inline">
                    @csrf
                    <button type="submit" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-lg shadow-2xs transition-colors flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>{{ __('messages.approve') }}</span>
                    </button>
                </form>
            @endif

            <!-- Reject Action (Only if pending) -->
            @if($business->status === 'pending')
                <form method="POST" action="{{ route('admin.businesses.reject', $business->id) }}" class="inline">
                    @csrf
                    <button type="submit" class="px-2.5 py-1 bg-rose-600 hover:bg-rose-700 text-white font-extrabold text-xs rounded-lg shadow-2xs transition-colors flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span>{{ __('messages.reject') }}</span>
                    </button>
                </form>
            @endif

            <!-- Mark Inactive / Active Action (Only if approved) -->
            @if($business->status === 'approved')
                @if($business->membership_status === 'active')
                    <form method="POST" action="{{ route('admin.businesses.deactivate', $business->id) }}" class="inline">
                        @csrf
                        <button type="submit" class="px-2.5 py-1 bg-amber-500 hover:bg-amber-600 text-white font-extrabold text-xs rounded-lg shadow-2xs transition-colors flex items-center gap-1" title="Mark Inactive">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                            </svg>
                            <span>{{ __('messages.mark_inactive') }}</span>
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('admin.businesses.activate', $business->id) }}" class="inline">
                        @csrf
                        <button type="submit" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-lg shadow-2xs transition-colors flex items-center gap-1" title="Mark Active">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>{{ __('messages.mark_active') }}</span>
                        </button>
                    </form>
                @endif
            @endif

            <!-- Back Button -->
            <a href="{{ route('admin.businesses.index') }}" 
               class="px-2.5 py-1 border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs rounded-lg transition-colors bg-white shadow-2xs flex items-center gap-1">
                <span>&larr;</span>
                <span>{{ __('messages.back') }}</span>
            </a>
        </div>
    </div>

    <!-- Contact & Overview Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-3.5 text-xs py-2">
        <div>
            <h5 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-0.5">{{ __('messages.status') }}</h5>
            <span class="inline-block mt-0.5 px-2 py-0.5 text-[10px] font-extrabold rounded-md uppercase {{ $business->status === 'approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' : ($business->status === 'rejected' ? 'bg-rose-50 text-rose-700 border border-rose-200/60' : 'bg-amber-50 text-amber-700 border border-amber-200/60') }}">
                {{ $business->status }}
            </span>
        </div>

        <div>
            <h5 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-0.5">{{ __('messages.membership_status') }}</h5>
            <span class="inline-block mt-0.5 px-2 py-0.5 text-[10px] font-extrabold rounded-md uppercase {{ $business->membership_status === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' : 'bg-slate-50 text-slate-700 border border-slate-200/60' }}">
                @if($business->membership_status === 'active')
                    {{ __('messages.active') }}
                @else
                    {{ __('messages.inactive') }}
                @endif
            </span>
        </div>

        <div>
            <h5 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-0.5">{{ __('messages.member_id_prefix') }}</h5>
            <p class="font-bold text-slate-900 text-xs mt-0.5">{{ $business->member_id ?? 'N/A' }}</p>
        </div>

        <div>
            <h5 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-0.5">{{ __('messages.phone') }}</h5>
            <p class="font-bold text-slate-900 text-xs mt-0.5">{{ $business->phone }}</p>
        </div>

        <div>
            <h5 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-0.5">{{ __('messages.whatsapp') }}</h5>
            <p class="font-bold text-slate-900 text-xs mt-0.5">{{ $business->whatsapp ?? 'N/A' }}</p>
        </div>

        <div>
            <h5 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-0.5">{{ __('messages.email') }}</h5>
            <p class="font-bold text-slate-900 text-xs mt-0.5 truncate" title="{{ $business->email }}">{{ $business->email ?? 'N/A' }}</p>
        </div>

        <div>
            <h5 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-0.5">{{ __('messages.area') }}</h5>
            <p class="font-bold text-slate-900 text-xs mt-0.5">{{ $business->area?->name ?? 'N/A' }}</p>
        </div>

        <div>
            <h5 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-0.5">{{ __('messages.category') }}</h5>
            <p class="font-bold text-slate-900 text-xs mt-0.5">{{ $business->category?->name ?? 'N/A' }}</p>
        </div>

        <div>
            <h5 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-0.5">{{ __('messages.website') }}</h5>
            <p class="font-bold text-primary-600 text-xs mt-0.5 truncate">
                @if($business->website)
                    <a href="{{ $business->website }}" target="_blank" class="hover:underline">{{ $business->website }}</a>
                @else
                    <span class="text-slate-400">N/A</span>
                @endif
            </p>
        </div>

        <div>
            <h5 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-0.5">{{ __('messages.registered_at') }}</h5>
            <p class="font-bold text-slate-900 text-xs mt-0.5">{{ $business->created_at ? $business->created_at->format('d-M-Y') : 'N/A' }}</p>
        </div>

        <div>
            <h5 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-0.5">{{ __('messages.approved_at') }}</h5>
            <p class="font-bold text-slate-900 text-xs mt-0.5">{{ $business->approved_at ? $business->approved_at->format('d-M-Y') : 'N/A' }}</p>
        </div>
    </div>

    <!-- Description & Address & Payment Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 pt-2.5 border-t border-slate-100 text-xs">
        <div>
            <span class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider block">{{ __('messages.description') }}</span>
            <p class="text-slate-700 leading-snug font-medium mt-0.5">{{ $business->description ?? 'No description provided.' }}</p>
        </div>

        <div>
            <span class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider block">{{ __('messages.address') }}</span>
            <p class="text-slate-700 leading-snug font-medium mt-0.5">{{ $business->address ?? 'No address provided.' }}</p>
        </div>

        <div>
            <span class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider block">{{ __('messages.payment_screenshot') }}</span>
            @if($business->payment_screenshot_path)
                <a href="{{ asset('storage/' . $business->payment_screenshot_path) }}" target="_blank" class="mt-1 inline-block border border-slate-200 rounded-lg overflow-hidden max-w-[150px] shadow-sm hover:shadow transition-all bg-slate-50 group relative">
                    <img src="{{ asset('storage/' . $business->payment_screenshot_path) }}" class="w-full h-auto object-cover max-h-[120px]" alt="Payment Screenshot">
                    <div class="absolute inset-0 bg-slate-900/35 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-[9px] text-white font-extrabold">
                        View Receipt
                    </div>
                </a>
            @else
                <p class="text-rose-500 font-bold mt-0.5">{{ __('messages.no_receipt_uploaded') }}</p>
            @endif
        </div>
    </div>

    <!-- Social Links & Product Showcase -->
    <div class="pt-2.5 border-t border-slate-100 space-y-2">
        <div class="flex items-center justify-between flex-wrap gap-2">
            <span class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider block">{{ __('messages.product_showcase_social') }}</span>
            
            @if($business->facebook || $business->instagram || $business->youtube || $business->linkedin)
                <div class="flex items-center space-x-1.5">
                    @if($business->facebook)
                        <a href="{{ $business->facebook }}" target="_blank" class="w-6 h-6 rounded-md bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors flex items-center justify-center" title="Facebook">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c4.56-.93 8-4.96 8-9.75z"/></svg>
                        </a>
                    @endif
                    @if($business->instagram)
                        <a href="{{ $business->instagram }}" target="_blank" class="w-6 h-6 rounded-md bg-pink-50 text-pink-600 hover:bg-pink-100 transition-colors flex items-center justify-center" title="Instagram">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.051.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                        </a>
                    @endif
                    @if($business->youtube)
                        <a href="{{ $business->youtube }}" target="_blank" class="w-6 h-6 rounded-md bg-rose-50 text-rose-600 hover:bg-rose-100 transition-colors flex items-center justify-center" title="YouTube">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.163a3.003 3.003 0 00-2.11-2.11C19.518 3.545 12 3.545 12 3.545s-7.518 0-9.388.508a3.003 3.003 0 00-2.11 2.11C0 8.033 0 12 0 12s0 3.967.502 5.837a3.003 3.003 0 002.11 2.11c1.87.508 9.388.508 9.388.508s7.518 0 9.388-.508a3.003 3.003 0 002.11-2.11C24 15.967 24 12 24 12s0-3.967-.502-5.837zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                        </a>
                    @endif
                    @if($business->linkedin)
                        <a href="{{ $business->linkedin }}" target="_blank" class="w-6 h-6 rounded-md bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-colors flex items-center justify-center" title="LinkedIn">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.779-1.75-1.75s.784-1.75 1.75-1.75 1.75.779 1.75 1.75-.784 1.75-1.75 1.75zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                        </a>
                    @endif
                </div>
            @endif
        </div>

        @if(empty($business->gallery_images) || count($business->gallery_images) === 0)
            <div class="py-2.5 text-center text-slate-400 text-[11px] bg-slate-50 border border-dashed border-slate-200 rounded-md">
                {{ __('messages.no_gallery_photos') }}
            </div>
        @else
            <div class="flex items-center gap-2 flex-wrap">
                @foreach($business->gallery_images as $img)
                    <a href="{{ asset('storage/' . $img) }}" target="_blank" 
                       class="w-14 h-14 rounded-lg overflow-hidden border border-slate-200 hover:shadow-md transition-all shrink-0 bg-slate-50 group relative block">
                        <img src="{{ asset('storage/' . $img) }}" class="w-full h-full object-cover" alt="Product image">
                        <div class="absolute inset-0 bg-slate-900/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-[9px] text-white font-extrabold">
                            {{ __('messages.view') }}
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection