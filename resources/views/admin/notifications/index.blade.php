@extends('layouts.admin')

@section('page_title', __('messages.notifications'))

@section('content')
@php
    // Written out literally (not interpolated) so Tailwind's class scanner keeps these in the build.
    $notifColorClasses = [
        'primary' => 'bg-primary-50 text-primary-600',
        'emerald' => 'bg-emerald-50 text-emerald-600',
        'sky' => 'bg-sky-50 text-sky-600',
        'amber' => 'bg-amber-50 text-amber-600',
        'rose' => 'bg-rose-50 text-rose-600',
    ];
@endphp
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
        <h3 class="text-sm font-black text-slate-900">{{ __('messages.notifications') }}</h3>
        @if($notifications->getCollection()->whereNull('read_at')->isNotEmpty())
            <form method="POST" action="{{ route('admin.notifications.markAllRead') }}">
                @csrf
                <button type="submit" class="text-xs font-bold text-primary-600 hover:text-primary-700">{{ __('messages.mark_all_read') }}</button>
            </form>
        @endif
    </div>

    <div class="divide-y divide-slate-50">
        @forelse($notifications as $n)
            <a href="{{ route('admin.notifications.read', $n->id) }}"
               class="flex items-start gap-3.5 px-5 py-4 hover:bg-slate-50 transition-colors {{ is_null($n->read_at) ? 'bg-primary-50/40' : '' }}">
                <span class="w-9 h-9 rounded-xl {{ $notifColorClasses[$n->data['color'] ?? 'primary'] ?? $notifColorClasses['primary'] }} flex items-center justify-center shrink-0">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </span>
                <span class="min-w-0 flex-1">
                    <span class="flex items-center gap-1.5">
                        <span class="text-sm font-extrabold text-slate-900">{{ $n->data['title'] ?? '' }}</span>
                        @if(is_null($n->read_at))
                            <span class="w-1.5 h-1.5 rounded-full bg-primary-500 shrink-0"></span>
                        @endif
                    </span>
                    <span class="block text-xs text-slate-600 leading-relaxed mt-0.5">{{ $n->data['message'] ?? '' }}</span>
                    <span class="block text-[11px] text-slate-400 font-semibold mt-1.5">{{ $n->created_at->format('d-M-Y h:i A') }} &middot; {{ $n->created_at->diffForHumans() }}</span>
                </span>
            </a>
        @empty
            <div class="px-5 py-14 text-center text-sm text-slate-400 font-medium">
                {{ __('messages.no_notifications_yet') }}
            </div>
        @endforelse
    </div>

    @if($notifications->hasPages())
        <div class="px-5 py-4 border-t border-slate-100">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection
