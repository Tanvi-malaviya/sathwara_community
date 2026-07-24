@extends('layouts.admin')

@section('page_title', __('messages.member_approvals_listings'))
 
@section('content')
    <div class="space-y-4">
        <!-- Single Integrated Toolbar Line -->
        <div class="bg-white p-3 rounded-xl border border-slate-100 shadow-xs">
            <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3">
                
                <!-- Left: Search Box & Form + Filter Status Tabs -->
                <form method="GET" action="{{ route('admin.members.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 flex-1">
                    @if(request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif

                    <!-- Search Input Expanding to fill remaining space -->
                    <div class="relative flex-1 min-w-[240px]">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="{{ __('messages.search_placeholder') }}"
                            class="h-9 w-full text-xs font-semibold pl-9 pr-4 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 focus:outline-none transition-all">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>

                    <!-- Filter Status Tabs in Same Line -->
                    <div class="flex items-center p-1 rounded-xl bg-slate-100/80 border border-slate-200/60 shrink-0 overflow-x-auto">
                        <a href="{{ route('admin.members.index', array_merge(request()->except('status', 'page'))) }}" 
                           class="px-3 py-1 rounded-lg text-xs font-bold transition-all whitespace-nowrap {{ !request('status') ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                            All ({{ $allCount ?? 0 }})
                        </a>

                        <a href="{{ route('admin.members.index', array_merge(request()->except('page'), ['status' => 'pending'])) }}" 
                           class="px-3 py-1 rounded-lg text-xs font-bold transition-all whitespace-nowrap {{ request('status') === 'pending' ? 'bg-amber-500 text-white shadow-xs' : 'text-amber-700 hover:bg-amber-100/50' }}">
                            Pending ({{ $pendingCount ?? 0 }})
                        </a>

                        <a href="{{ route('admin.members.index', array_merge(request()->except('page'), ['status' => 'approved'])) }}" 
                           class="px-3 py-1 rounded-lg text-xs font-bold transition-all whitespace-nowrap {{ request('status') === 'approved' ? 'bg-emerald-600 text-white shadow-xs' : 'text-emerald-700 hover:bg-emerald-100/50' }}">
                            Approved ({{ $approvedCount ?? 0 }})
                        </a>

                        <a href="{{ route('admin.members.index', array_merge(request()->except('page'), ['status' => 'rejected'])) }}" 
                           class="px-3 py-1 rounded-lg text-xs font-bold transition-all whitespace-nowrap {{ request('status') === 'rejected' ? 'bg-rose-600 text-white shadow-xs' : 'text-rose-700 hover:bg-rose-100/50' }}">
                            Rejected ({{ $rejectedCount ?? 0 }})
                        </a>
                    </div>
                </form>

                <!-- Right: Action Buttons (Export CSV + Add Member) -->
                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('admin.members.export', request()->all()) }}"
                        class="h-9 px-3.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-xs rounded-xl transition-colors inline-flex items-center justify-center gap-1.5 shadow-xs whitespace-nowrap border border-emerald-200/60">
                        📥 <span>{{ __('messages.export_csv') }}</span>
                    </a>

                    <a href="{{ route('admin.members.create') }}"
                        class="h-9 inline-flex items-center justify-center px-4 bg-primary-500 hover:bg-primary-600 text-white font-bold text-xs rounded-xl shadow-xs transition-colors gap-1.5 whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span>{{ __('messages.add_member') }}</span>
                    </a>
                </div>

            </div>
        </div>
 
        <!-- Table Grid -->
        <div class="bg-white border border-slate-100 rounded-xl overflow-hidden shadow-sm">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr
                        class="bg-slate-50 text-[10px] font-extrabold uppercase text-slate-400 tracking-wider border-b border-slate-100">
                        <th class="py-2.5 px-4">{{ __('messages.member_id') }}</th>
                        <th class="py-2.5 px-4">{{ __('messages.name') }}</th>
                        <th class="py-2.5 px-4">{{ __('messages.phone') }}</th>
                        <th class="py-2.5 px-4">{{ __('messages.city') }}</th>
                        <th class="py-2.5 px-4">{{ __('messages.registration_date') }}</th>
                        <th class="py-2.5 px-4">{{ __('messages.status') }}</th>
                        <th class="py-2.5 px-4 text-right">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-semibold text-slate-700">
                    @forelse($members as $m)
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-2.5 px-4 text-slate-400">#{{ sprintf('%05d', $m->id) }}</td>
                            <td class="py-2.5 px-4">
                                <div class="min-w-0">
                                    <span class="text-slate-900 font-bold block">{{ $m->name }}</span>
                                    <span class="text-[10px] text-slate-400 font-medium">{{ $m->email }}</span>
                                </div>
                            </td>
                            <td class="py-2.5 px-4">{{ $m->memberProfile->phone ?? __('messages.not_set') }}</td>
                            <td class="py-2.5 px-4">{{ $m->memberProfile->city ?? __('messages.not_set') }}</td>
                            <td class="py-2.5 px-4 text-slate-400 font-medium">{{ $m->created_at->format('d-M-Y') }}</td>
                            <td class="py-2.5 px-4">
                                @if($m->status == 'approved')
                                    <span
                                        class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-lg font-bold text-[10px] uppercase">{{ __('messages.approved') }}</span>
                                @elseif($m->status == 'rejected')
                                    <span
                                        class="px-2.5 py-1 bg-rose-50 text-rose-700 border border-rose-100 rounded-lg font-bold text-[10px] uppercase">{{ __('messages.rejected') }}</span>
                                @else
                                    <span
                                        class="px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-100 rounded-lg font-bold text-[10px] uppercase">{{ __('messages.pending') }}</span>
                                @endif
                            </td>
                            <td class="py-2.5 px-4 text-right">
                                <div class="flex justify-end items-center space-x-2">
                                    <div class="flex items-center gap-2">
                                        @if($m->status == 'pending')
                                            <form method="POST" action="{{ route('admin.members.approve', $m->id) }}"
                                                class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition-colors"
                                                    title="{{ __('messages.approve') }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                        <!-- View -->
                                        <a href="{{ route('admin.members.show', $m->id) }}"
                                            class="flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors"
                                            title="{{ __('messages.view_details') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7S3.732 16.057 2.458 12z" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                        </a>
 
                                        <!-- Edit -->
                                        <a href="{{ route('admin.members.edit', $m->id) }}"
                                            class="flex items-center justify-center w-8 h-8 rounded-lg bg-primary-50 text-primary-600 hover:bg-primary-100 transition-colors"
                                            title="{{ __('messages.edit') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M16.862 3.487a2.25 2.25 0 113.182 3.182L8.25 18.463 3 20.25l1.787-5.25L16.862 3.487z" />
                                            </svg>
                                        </a>
 
                                        <!-- Delete -->
                                        <button type="button" @click="$dispatch('confirm-delete', {
                                        action: '{{ route('admin.members.destroy', $m->id) }}',
                                        message: '{{ __('messages.delete_confirm_member', ['name' => $m->name]) }}'
                                    })" class="flex items-center justify-center w-8 h-8 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition-colors"
                                             title="{{ __('messages.delete') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 7h12M9 7V5a1 1 0 011-1h4a1 1 0 011 1v2m-8 0v12a1 1 0 001 1h8a1 1 0 001-1V7M10 11v6M14 11v6" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                {{ __('messages.no_members_found') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div>
            {{ $members->links() }}
        </div>
    </div>
@endsection