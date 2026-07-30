@extends('layouts.admin')

@section('page_title', __('messages.student_award_applications'))

@section('content')
<div class="space-y-2">
    <div class="bg-white p-6 rounded-xl border border-slate-100 shadow-sm">
        <p class="text-xs text-slate-500">{{ __('messages.award_review_desc') }}</p>
    </div>

    <!-- Search & Filters Toolbar -->
    <div class="bg-white p-3 rounded-xl border border-slate-100 shadow-sm">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
            <!-- Search bar -->
            <form method="GET" action="{{ route('admin.awards.index') }}" class="flex items-center gap-2 flex-grow max-w-md w-full">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <div class="relative flex-grow">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('messages.search_awards_admin') }}" 
                           class="text-xs font-semibold pl-3 pr-8 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 w-full transition-colors">
                    @if(request()->filled('search'))
                        <a href="{{ route('admin.awards.index', request()->except('search')) }}" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-rose-500 font-extrabold text-sm" title="Clear search">
                            &times;
                        </a>
                    @endif
                </div>
                <button type="submit" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 font-bold text-xs text-slate-700 rounded-xl transition-colors shrink-0">
                    {{ __('messages.search') }}
                </button>
                <a href="{{ route('admin.awards.export', request()->all()) }}" 
                   class="px-3.5 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-xs rounded-xl border border-emerald-200/60 shadow-xs transition-colors shrink-0 whitespace-nowrap">
                    📊 <span>{{ __('messages.export_csv') }}</span>
                </a>
            </form>

            <!-- Status filter selector -->
            <div class="flex items-center gap-2 shrink-0 overflow-x-auto">
                <a href="{{ route('admin.awards.index', request()->except(['status', 'page'])) }}" 
                   class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all whitespace-nowrap {{ !request('status') ? 'bg-slate-900 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    All
                </a>
                <a href="{{ route('admin.awards.index', array_merge(request()->except('page'), ['status' => 'pending'])) }}" 
                   class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all whitespace-nowrap {{ request('status') === 'pending' ? 'bg-amber-500 text-white shadow-xs' : 'bg-amber-50 text-amber-700 hover:bg-amber-100/50' }}">
                    Pending
                </a>
                <a href="{{ route('admin.awards.index', array_merge(request()->except('page'), ['status' => 'approved'])) }}" 
                   class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all whitespace-nowrap {{ request('status') === 'approved' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100/50' }}">
                    Approved
                </a>
                <a href="{{ route('admin.awards.index', array_merge(request()->except('page'), ['status' => 'rejected'])) }}" 
                   class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all whitespace-nowrap {{ request('status') === 'rejected' ? 'bg-rose-600 text-white shadow-xs' : 'bg-rose-50 text-rose-700 hover:bg-rose-100/50' }}">
                    Rejected
                </a>
            </div>
        </div>
    </div>

    <!-- Awards Applications list -->
    <div class="bg-white border border-slate-100 rounded-xl overflow-hidden shadow-sm">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-[10px] font-extrabold uppercase text-slate-400 tracking-wider border-b border-slate-100">
                    <th class="py-2.5 px-4">{{ __('messages.parent_member') }}</th>
                    <th class="py-2.5 px-4">{{ __('messages.student_details') }}</th>
                    <th class="py-2.5 px-4">{{ __('messages.achievement') }}</th>
                    <th class="py-2.5 px-4">{{ __('messages.marksheet') }}</th>
                    <th class="py-2.5 px-4">{{ __('messages.status') }}</th>
                    <th class="py-2.5 px-4 text-right">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-xs font-semibold text-slate-700">
                @forelse($applications as $app)
                    <tr class="hover:bg-slate-50/50" x-data="{ showNotes: false }">
                        <td class="py-2.5 px-4">
                            <div class="min-w-0">
                                <span class="text-slate-900 font-bold block">{{ $app->user->name }}</span>
                                <span class="text-[10px] text-slate-400 font-medium">{{ $app->user->email }}</span>
                            </div>
                        </td>
                        <td class="py-2.5 px-4">
                            <div class="min-w-0">
                                <span class="text-slate-900 font-bold block">{{ $app->student_name }}</span>
                                <span class="text-[10px] text-slate-400 font-medium">{{ $app->standard }} • {{ $app->school }}</span>
                            </div>
                        </td>
                        <td class="py-2.5 px-4">
                            <div class="min-w-0">
                                <span class="block text-slate-800">{{ $app->award_name }}</span>
                                <span class="text-[10px] text-slate-500 font-medium">{{ $app->achievement }}</span>
                            </div>
                        </td>
                        <td class="py-2.5 px-4">
                            <a href="{{ asset('storage/' . $app->certificate_path) }}" target="_blank" class="text-primary-500 font-bold hover:underline">
                                {{ __('messages.view_marksheet') }} &rarr;
                            </a>
                        </td>
                        <td class="py-2.5 px-4">
                            @if($app->status == 'approved')
                                <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-lg font-bold text-[10px] uppercase">{{ __('messages.approved') }}</span>
                            @elseif($app->status == 'rejected')
                                <span class="px-2.5 py-1 bg-rose-50 text-rose-700 border border-rose-100 rounded-lg font-bold text-[10px] uppercase">{{ __('messages.rejected') }}</span>
                            @else
                                <span class="px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-100 rounded-lg font-bold text-[10px] uppercase">{{ __('messages.pending') }}</span>
                            @endif
                        </td>
                        <td class="py-2.5 px-4 text-right">
                            <div class="flex flex-col items-end gap-2">
                                <div class="flex justify-end items-center space-x-2">
                                    @if($app->status == 'pending')
                                        <form method="POST" action="{{ route('admin.awards.approve', $app->id) }}">
                                            @csrf
                                            <button type="submit" class="flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition-colors" title="{{ __('messages.approve') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </button>
                                        </form>
                                        <button @click="showNotes = !showNotes" type="button" class="flex items-center justify-center w-8 h-8 rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100 transition-colors" title="{{ __('messages.reject') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    @endif
                                    <button type="button" @click="$dispatch('confirm-delete', { action: '{{ route('admin.awards.destroy', $app->id) }}', message: '{{ __('messages.delete_confirm_award', ['name' => $app->student_name]) }}' })" class="flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 text-slate-400 hover:text-slate-650 hover:bg-slate-200 transition-colors" title="{{ __('messages.delete') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- Rejection Textarea -->
                                <div x-show="showNotes" class="mt-2 text-left" x-cloak>
                                    <form method="POST" action="{{ route('admin.awards.reject', $app->id) }}" class="flex gap-2">
                                        @csrf
                                        <input type="text" name="admin_notes" required placeholder="{{ __('messages.rejection_reason_placeholder') }}" class="text-[10px] font-medium px-2 py-1 border border-slate-200 rounded-lg w-40">
                                        <button type="submit" class="px-2 py-1 bg-slate-900 text-white text-[9px] font-bold rounded-lg">{{ __('messages.save') }}</button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400">
                            {{ __('messages.no_awards_found') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div>
        {{ $applications->links() }}
    </div>
</div>
@endsection
