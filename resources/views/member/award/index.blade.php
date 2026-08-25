@extends('layouts.member')
 
@section('page_title', __('messages.student_award_applications'))
 
@section('content')
<div class="space-y-2">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 bg-white p-3 rounded-xl border border-slate-100 shadow-sm">
        <p class="text-xs text-slate-500">Apply for community awards to celebrate educational achievements (marks, honors).</p>
        
        <div class="flex items-center gap-2 shrink-0">
            <!-- Search bar -->
            <form method="GET" action="{{ route('member.awards.index') }}" class="flex items-center gap-2">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('messages.search_claims') }}" 
                           class="text-xs font-semibold pl-3 pr-8 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 w-40 sm:w-48 transition-colors">
                    @if(request()->filled('search'))
                        <a href="{{ route('member.awards.index') }}" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-rose-500 font-extrabold text-sm" title="Clear search">
                            &times;
                        </a>
                    @endif
                </div>
                <button type="submit" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 font-bold text-xs text-slate-700 rounded-xl transition-colors shrink-0">
                    {{ __('messages.search') }}
                </button>
            </form>

            <a href="{{ route('member.awards.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white font-bold text-xs rounded-xl shadow-sm transition-colors whitespace-nowrap">
                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                <span>{{ __('messages.new_award_claim') }}</span>
            </a>
        </div>
    </div>

    <!-- Submissions table -->
    <div class="bg-white border border-slate-100 rounded-xl overflow-hidden shadow-sm">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-[10px] font-extrabold uppercase text-slate-400 tracking-wider border-b border-slate-100">
                    <th class="py-2.5 px-4">{{ __('messages.student_name') }}</th>
                    <th class="py-2.5 px-4">{{ __('messages.standard') }}</th>
                    <th class="py-2.5 px-4">{{ __('messages.achievement_details') }}</th>
                    <th class="py-2.5 px-4">{{ __('messages.award_requested') }}</th>
                    <th class="py-2.5 px-4">{{ __('messages.date_submitted') }}</th>
                    <th class="py-2.5 px-4">{{ __('messages.status') }}</th>
                    <th class="py-2.5 px-4">{{ __('messages.admin_remarks') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-xs font-semibold text-slate-700">
                @forelse($applications as $app)
                    <tr class="hover:bg-slate-50/50">
                        <td class="py-2.5 px-4 text-slate-900 font-bold">{{ $app->student_name }}</td>
                        <td class="py-2.5 px-4">{{ $app->standard }}</td>
                        <td class="py-2.5 px-4">{{ $app->achievement }}</td>
                        <td class="py-2.5 px-4">{{ $app->award_name }}</td>
                        <td class="py-2.5 px-4 text-slate-400 font-medium">{{ $app->created_at->format('d-M-Y') }}</td>
                        <td class="py-2.5 px-4">
                            @if($app->status == 'approved')
                                <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-lg font-bold text-[10px] uppercase">{{ __('messages.approved') }}</span>
                            @elseif($app->status == 'rejected')
                                <span class="px-2.5 py-1 bg-rose-50 text-rose-700 border border-rose-100 rounded-lg font-bold text-[10px] uppercase">{{ __('messages.rejected') }}</span>
                            @else
                                <span class="px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-100 rounded-lg font-bold text-[10px] uppercase">{{ __('messages.pending') }}</span>
                            @endif
                        </td>
                        <td class="py-2.5 px-4 text-slate-400 font-medium italic">{{ $app->admin_notes ?? 'No comments' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-slate-400">
                            {{ __('messages.no_awards_found') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($applications->hasPages())
        <div class="mt-4">
            {{ $applications->links() }}
        </div>
    @endif
</div>
@endsection
