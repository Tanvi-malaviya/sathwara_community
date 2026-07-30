@extends('layouts.admin')

@section('page_title', __('messages.milestone_timeline'))

@section('content')
    <div x-data="{
                showAddModal: @json($errors->any()),
                showEditModal: false,
                editMilestone: {},
                openEdit(milestone) {
                    this.editMilestone = { ...milestone };
                    this.showEditModal = true;
                }
            }">
        <!-- Header Actions & Search bar -->
        <div
            class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-3 rounded-xl border border-slate-100 shadow-sm mb-4">
            <form method="GET" action="{{ route('admin.content.timelines') }}"
                class="flex items-center gap-2 flex-grow max-w-md w-full">
                <div class="relative flex-grow">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="{{ __('messages.search_milestones') }}"
                        class="text-xs font-semibold pl-3 pr-8 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 w-full transition-colors">
                    @if(request()->filled('search'))
                        <a href="{{ route('admin.content.timelines') }}"
                            class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-rose-500 font-extrabold text-sm"
                            title="Clear search">
                            &times;
                        </a>
                    @endif
                </div>
                <button type="submit"
                    class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 font-bold text-xs text-slate-700 rounded-xl transition-colors shrink-0">
                    {{ __('messages.search') }}
                </button>
            </form>

            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('admin.content.timelines.export', request()->all()) }}"
                    class="inline-flex items-center justify-center px-3.5 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-xs rounded-xl border border-emerald-200/60 shadow-xs transition-all whitespace-nowrap">
                    📊 <span>{{ __('messages.export_excel') }}</span>
                </a>

                <button @click="showAddModal = true"
                    class="inline-flex items-center justify-center px-4 py-2 bg-primary-500 hover:bg-primary-600 font-bold text-xs text-white rounded-xl shadow-sm transition-all hover:scale-[1.02] active:scale-95 whitespace-nowrap">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    {{ __('messages.add_milestone') }}
                </button>
            </div>
        </div>

        <!-- List -->
        <div class="bg-white border border-slate-100 rounded-xl overflow-hidden shadow-sm">
            <table class="w-full text-left border-collapse table-fixed">
                <thead>
                    <tr
                        class="bg-slate-50 text-[10px] font-extrabold uppercase text-slate-400 tracking-wider border-b border-slate-100">
                        <th class="py-3 px-4" style="width: 100px;">{{ __('messages.year') }}</th>
                        <th class="py-3 px-4" style="width: 250px;">{{ __('messages.milestone_title') }}</th>
                        <th class="py-3 px-4">{{ __('messages.description') }}</th>
                        <th class="py-3 px-4 text-right" style="width: 120px;">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-semibold text-slate-700">
                    @forelse($timelines as $time)
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-3 px-4 text-slate-900 font-bold">
                                <span class="bg-primary-50 text-primary-700 px-2 py-0.5 rounded text-[9px] font-bold">
                                    {{ $time->year }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-slate-900 font-bold">{{ $time->title }}</td>
                            <td class="py-3 px-4 text-slate-500 truncate max-w-md">{{ $time->description }}</td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex justify-end items-center space-x-1.5">
                                    {{-- Edit --}}
                                    <button type="button" @click="openEdit({
                                                                    id: {{ $time->id }},
                                                                    year: {{ json_encode($time->year) }},
                                                                    title: {{ json_encode($time->title) }},
                                                                    display_order: {{ $time->display_order }},
                                                                    description: {{ json_encode($time->description) }},
                                                                    update_url: '{{ route('admin.content.timelines.update', $time->id) }}'
                                                                })"
                                        class="flex items-center justify-center w-7 h-7 rounded-lg bg-primary-50 text-primary-600 hover:bg-primary-100 transition-colors"
                                        title="{{ __('messages.edit') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    {{-- Delete --}}
                                    <button type="button"
                                        @click="$dispatch('confirm-delete', { action: '{{ route('admin.content.timelines.destroy', $time->id) }}', message: '{{ __('messages.delete_confirm_timeline', ['name' => $time->title]) }}' })"
                                        class="flex items-center justify-center w-7 h-7 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition-colors"
                                        title="{{ __('messages.delete') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-slate-400">{{ __('messages.no_milestones_yet') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($timelines->hasPages())
            <div class="mt-4">
                {{ $timelines->links() }}
            </div>
        @endif

        <!-- ============ ADD MODAL ============ -->
        <div x-show="showAddModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-transition
            x-cloak>
            <div @click.away="showAddModal = false"
                class="bg-white rounded-2xl p-6 border border-slate-100 shadow-2xl max-w-md w-full space-y-4 relative max-h-[90vh] overflow-y-auto animate-in fade-in zoom-in-95 duration-150">
                <button @click="showAddModal = false"
                    class="absolute top-4 right-4 w-6 h-6 flex items-center justify-center rounded-full bg-slate-50 hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <h3 class="text-sm font-extrabold text-slate-950 pb-2 border-b border-slate-50">
                    {{ __('messages.new_milestone') }}
                </h3>
                <form method="POST" action="{{ route('admin.content.timelines.store') }}" class="space-y-4">
                    @csrf
                    @if($errors->any())
                        <div class="p-3 bg-rose-50 border border-rose-100 text-rose-700 text-[10px] font-semibold rounded-xl">
                            <ul class="list-disc pl-4 space-y-0.5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-400 uppercase">{{ __('messages.year') }}</label>
                            <input type="text" name="year" value="{{ old('year') }}" placeholder="e.g. 2026" required
                                class="w-full text-xs font-semibold px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-primary-400">
                        </div>
                        <div class="space-y-1">
                            <label
                                class="text-[10px] font-bold text-slate-400 uppercase">{{ __('messages.display_order') }}</label>
                            <input type="number" name="display_order" value="{{ old('display_order', 0) }}" required
                                class="w-full text-xs font-semibold px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-primary-400">
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label
                            class="text-[10px] font-bold text-slate-400 uppercase">{{ __('messages.milestone_title') }}</label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                            placeholder="e.g. Community Center Inauguration"
                            class="w-full text-xs font-semibold px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-primary-400">
                    </div>
                    <div class="space-y-1">
                        <label
                            class="text-[10px] font-bold text-slate-400 uppercase">{{ __('messages.description') }}</label>
                        <textarea name="description" rows="5" required
                            placeholder="Brief description of what was achieved..."
                            class="w-full text-xs font-semibold px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-primary-400">{{ old('description') }}</textarea>
                    </div>
                    <div class="pt-2 flex justify-end gap-2 border-t border-slate-50">
                        <button type="button" @click="showAddModal = false"
                            class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-600 font-bold text-xs rounded-xl transition-colors">{{ __('messages.cancel') }}</button>
                        <button type="submit"
                            class="px-5 py-2 bg-primary-500 hover:bg-primary-600 text-white font-bold text-xs rounded-xl shadow-sm transition-colors">
                            {{ __('messages.add_milestone_submit') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ============ EDIT MODAL ============ -->
        <div x-show="showEditModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-transition
            x-cloak>
            <div @click.away="showEditModal = false"
                class="bg-white rounded-2xl p-6 border border-slate-100 shadow-2xl max-w-md w-full space-y-4 relative max-h-[90vh] overflow-y-auto animate-in fade-in zoom-in-95 duration-150">
                <button @click="showEditModal = false"
                    class="absolute top-4 right-4 w-6 h-6 flex items-center justify-center rounded-full bg-slate-50 hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <h3 class="text-sm font-extrabold text-slate-950 pb-2 border-b border-slate-50">
                    {{ __('messages.edit_milestone') }}
                </h3>
                <form method="POST" :action="editMilestone.update_url" class="space-y-4">
                    @csrf
                    @method('PUT')
                    @if($errors->any())
                        <div class="p-3 bg-rose-50 border border-rose-100 text-rose-700 text-[10px] font-semibold rounded-xl">
                            <ul class="list-disc pl-4 space-y-0.5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-400 uppercase">{{ __('messages.year') }}</label>
                            <input type="text" name="year" :value="editMilestone.year" required
                                class="w-full text-xs font-semibold px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-primary-400">
                        </div>
                        <div class="space-y-1">
                            <label
                                class="text-[10px] font-bold text-slate-400 uppercase">{{ __('messages.display_order') }}</label>
                            <input type="number" name="display_order" :value="editMilestone.display_order" required
                                class="w-full text-xs font-semibold px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-primary-400">
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label
                            class="text-[10px] font-bold text-slate-400 uppercase">{{ __('messages.milestone_title') }}</label>
                        <input type="text" name="title" :value="editMilestone.title" required
                            placeholder="e.g. Community Center Inauguration"
                            class="w-full text-xs font-semibold px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-primary-400">
                    </div>
                    <div class="space-y-1">
                        <label
                            class="text-[10px] font-bold text-slate-400 uppercase">{{ __('messages.description') }}</label>
                        <textarea name="description" rows="5" required x-model="editMilestone.description"
                            placeholder="Brief description of what was achieved..."
                            class="w-full text-xs font-semibold px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-primary-400"></textarea>
                    </div>
                    <div class="pt-2 flex justify-end gap-2 border-t border-slate-50">
                        <button type="button" @click="showEditModal = false"
                            class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-600 font-bold text-xs rounded-xl transition-colors">{{ __('messages.cancel') }}</button>
                        <button type="submit"
                            class="px-5 py-2 bg-primary-500 hover:bg-primary-600 text-white font-bold text-xs rounded-xl shadow-sm transition-colors">
                            {{ __('messages.save_changes') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection