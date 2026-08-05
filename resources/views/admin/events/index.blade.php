@extends('layouts.admin')

@section('page_title', __('messages.events_gatherings'))

@section('content')
<div class="space-y-3" x-data="{ showViewModal: false, selectedEvent: {} }">
    <!-- Header Action Bar -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 bg-white p-3.5 rounded-xl border border-slate-100 shadow-sm">
        {{-- <div>
            <p class="text-xs text-slate-500 font-medium">Manage community events, view registered candidates, and create new programs.</p>
        </div> --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 w-full">
            <form method="GET" action="{{ route('admin.events.index') }}" class="flex items-center gap-2 flex-1 min-w-0">
                <div class="relative flex-1 min-w-0">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('messages.search_events') }}" 
                           class="text-xs font-semibold pl-3 pr-8 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 w-full transition-colors">
                    @if(request()->filled('search'))
                        <a href="{{ route('admin.events.index') }}" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-rose-500 font-extrabold text-sm" title="{{ __('messages.clear_search') }}">
                            &times;
                        </a>
                    @endif
                </div>
                <button type="submit" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 font-bold text-xs text-slate-700 rounded-xl transition-colors shrink-0">
                    {{ __('messages.search') }}
                </button>
            </form>

            <div class="flex flex-wrap items-center gap-3 justify-end">
                <a href="{{ route('admin.events.export', request()->all()) }}" 
                   class="px-3.5 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-xs rounded-xl border border-emerald-200/60 shadow-xs transition-colors shrink-0 whitespace-nowrap">
                    📊 <span>{{ __('messages.export_excel') }}</span>
                </a>

                @php
                    $user = auth()->user();
                    $canCreateAnyEvent = $user->hasRole('Administrator') || 
                                         $user->hasPermissionTo('events_manage') || 
                                         $user->permissions->pluck('name')->contains(fn($p) => str_starts_with($p, 'event_create_') || str_starts_with($p, 'event_manage_'));
                @endphp

                @if($canCreateAnyEvent)
                    <a href="{{ route('admin.events.create') }}" 
                       class="px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white font-extrabold text-xs rounded-xl shadow-xs transition-all flex items-center gap-1 shrink-0 whitespace-nowrap">
                        <span>{{ __('messages.plan_new_event') }}</span>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Events List Table -->
    <div class="bg-white border border-slate-100 rounded-xl overflow-x-auto no-scrollbar shadow-sm">
        <table class="w-full text-left border-collapse min-w-full">
            <thead>
                <tr class="bg-slate-50 text-[10px] font-extrabold uppercase text-slate-400 tracking-wider border-b border-slate-100">
                    <th class="py-2.5 px-2">{{ __('messages.event_name') }}</th>
                    <th class="py-2.5 px-2">{{ __('messages.event_type') }}</th>
                    <th class="py-2.5 px-2">{{ __('messages.venue') }}</th>
                    <th class="py-2.5 px-2">{{ __('messages.date') }}</th>
                    <th class="py-2.5 px-2">{{ __('messages.participants') }}</th>
                    <th class="py-2.5 px-2">{{ __('messages.status') }}</th>
                    <th class="py-2.5 px-2 text-right">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-xs font-semibold text-slate-700">
                @forelse($events as $e)
                    @php
                        $userPerms = $user->permissions->pluck('name');
                        $canEditThisEvent = $user->hasRole('Administrator') || 
                                            $userPerms->contains('events_manage') || 
                                            $userPerms->contains('events_edit') || 
                                            $userPerms->contains('event_manage_' . $e->id) || 
                                            $userPerms->contains('event_edit_' . $e->id);

                        $canDeleteThisEvent = $user->hasRole('Administrator') || 
                                              $userPerms->contains('events_manage') || 
                                              $userPerms->contains('events_delete');
                    @endphp
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="py-2 px-2">
                            <div class="flex items-center gap-2">
                                <img class="w-7 h-7 rounded-lg object-cover bg-slate-100 border border-slate-200/60 shrink-0" 
                                     src="{{ str_starts_with($e->banner_path, 'http') ? $e->banner_path : asset('storage/' . $e->banner_path) }}" 
                                     alt="Banner">
                                 <div class="min-w-0">
                                      <span class="text-slate-900 font-bold text-xs truncate max-w-[140px] xl:max-w-[200px] block" title="{{ $e->title }}">{{ $e->title }}</span>
                                 </div>
                            </div>
                        </td>
                        <td class="py-2 px-2 whitespace-nowrap">
                            @if($e->event_type === 'yuva_melo')
                                <span class="px-2 py-0.5 text-[10px] font-extrabold bg-purple-50 text-purple-700 rounded-md border border-purple-200/80 inline-flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-purple-600"></span> {{ __('messages.yuva_melo') }}
                                </span>
                            @elseif($e->event_type === 'inam_vitaran')
                                <span class="px-2 py-0.5 text-[10px] font-extrabold bg-amber-50 text-amber-700 rounded-md border border-amber-200/80 inline-flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-600"></span> {{ __('messages.inam_vitaran') }}
                                </span>
                            @else
                                <span class="px-2 py-0.5 text-[10px] font-extrabold bg-slate-100 text-slate-600 rounded-md border border-slate-200 inline-flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> {{ __('messages.general') }}
                                </span>
                            @endif
                        </td>
                        <td class="py-2 px-2 text-slate-600 font-medium max-w-[110px] xl:max-w-[160px] truncate text-[11px]" title="{{ $e->venue }}">
                            {{ $e->venue }}
                        </td>
                        <td class="py-2 px-2 text-[11px] text-slate-700 whitespace-nowrap font-medium">
                            {{ date('d-M-Y', strtotime($e->date)) }}
                        </td>
                        <td class="py-2 px-2 whitespace-nowrap text-[11px]">
                            @if(($e->event_type ?? 'normal') !== 'normal' && ($e->has_registration_form || $e->registration_option))
                                <a href="{{ route('admin.events.registrations', $e->id) }}" class="text-primary-600 font-bold hover:underline">
                                    {{ $e->registrations_count }} {{ __('messages.registered') }}
                                </a>
                            @else
                                <span class="text-slate-400 font-medium">{{ __('messages.open_entry') }}</span>
                            @endif
                        </td>
                        <td class="py-2 px-2 whitespace-nowrap">
                            @if($e->status == 'published' && $e->published_date && $e->published_date->toDateString() > now()->toDateString())
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-amber-50 text-amber-700 border border-amber-200/80" title="Will automatically publish on {{ date('d-M-Y', strtotime($e->published_date)) }}">
                                    Scheduled ({{ date('d-M-Y', strtotime($e->published_date)) }})
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                                    {{ $e->status == 'published' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' : ($e->status == 'cancelled' ? 'bg-rose-50 text-rose-700 border border-rose-200/60' : 'bg-slate-100 text-slate-600 border border-slate-200') }}">
                                    {{ __('messages.' . strtolower($e->status)) != 'messages.' . strtolower($e->status) ? __('messages.' . strtolower($e->status)) : ucfirst($e->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="py-2 px-2.5 text-right whitespace-nowrap">
                            <div class="flex justify-end items-center space-x-1">
                                <a href="{{ route('admin.events.show', $e->id) }}" 
                                   class="w-6.5 h-6.5 rounded-lg bg-sky-50 text-sky-600 hover:bg-sky-100 transition-colors flex items-center justify-center" 
                                   title="{{ __('messages.view_details') }}">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.573 16.49 16.638 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.events.gallery', $e->id) }}" 
                                   class="w-6.5 h-6.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors flex items-center justify-center" 
                                   title="{{ __('messages.gallery') }}">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </a>
                                @if($canEditThisEvent)
                                    <a href="{{ route('admin.events.edit', $e->id) }}" 
                                       class="w-6.5 h-6.5 rounded-lg bg-primary-50 text-primary-600 hover:bg-primary-100 transition-colors flex items-center justify-center" 
                                       title="{{ __('messages.edit') }}">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                @endif
                                @if($canDeleteThisEvent)
                                    <button type="button" 
                                            @click="$dispatch('confirm-delete', { action: '{{ route('admin.events.destroy', $e->id) }}', message: '{{ __('messages.delete_confirm_event', ['name' => $e->title]) }}' })" 
                                            class="w-6.5 h-6.5 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition-colors flex items-center justify-center" 
                                            title="{{ __('messages.delete') }}">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-slate-400 font-medium">{{ __('messages.no_events_scheduled') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($events->hasPages())
        <div class="mt-3">
            {{ $events->links() }}
        </div>
    @endif
</div>
@endsection
