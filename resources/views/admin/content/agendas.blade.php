@extends('layouts.admin')

@section('page_title', __('messages.core_agendas'))

@section('content')
    <div x-data="{
                    showAddModal: @json($errors->any()),
                    showEditModal: false,
                    editAgenda: {},
                    openEdit(agenda) {
                        this.editAgenda = agenda;
                        this.showEditModal = true;
                    }
                }">
        <!-- Header Actions & Search bar -->
        <div
            class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-3 rounded-xl border border-slate-100 shadow-sm mb-4">
            <form method="GET" action="{{ route('admin.content.agendas') }}"
                class="flex items-center gap-2 flex-grow max-w-md w-full">
                <div class="relative flex-grow">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="{{ __('messages.search_agendas') }}"
                        class="text-xs font-semibold pl-3 pr-8 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 w-full transition-colors">
                    @if(request()->filled('search'))
                        <a href="{{ route('admin.content.agendas') }}"
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
                <a href="{{ route('admin.content.agendas.export', request()->all()) }}"
                    class="inline-flex items-center justify-center px-3.5 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-xs rounded-xl border border-emerald-200/60 shadow-xs transition-all whitespace-nowrap">
                    📊 <span>{{ __('messages.export_excel') }}</span>
                </a>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white border border-slate-100 rounded-xl overflow-hidden shadow-sm">
            <table class="w-full text-left border-collapse table-fixed">
                <thead>
                    <tr
                        class="bg-slate-50 text-[10px] font-extrabold uppercase text-slate-400 tracking-wider border-b border-slate-100">
                        <th class="py-2.5 px-3 w-16">{{ __('messages.icon') }}</th>
                        <th class="py-2.5 px-3 w-40">{{ __('messages.title') }}</th>
                        <th class="py-2.5 px-3">{{ __('messages.desc_english_label') }}</th>
                        <th class="py-2.5 px-3">{{ __('messages.desc_gujarati_label') }}</th>
                        <th class="py-2.5 px-3 w-20 text-center">{{ __('messages.order') }}</th>
                        <th class="py-2.5 px-3 w-24 text-right">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-semibold text-slate-700">
                    @forelse($agendas as $agenda)
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-2.5 px-3 text-slate-900 font-bold">
                                <span class="text-xl">
                                    @if($agenda->icon == 'users') 👥
                                    @elseif($agenda->icon == 'academic-cap') 🎓
                                    @elseif($agenda->icon == 'briefcase') 💼
                                    @else 📌 @endif
                                </span>
                            </td>
                            <td class="py-2.5 px-3 text-slate-900 font-bold">
                                <div>{{ $agenda->title }}</div>
                                @if(!empty($agenda->title_gu))
                                    <div class="text-[11px] font-semibold text-slate-500 font-gujarati mt-0.5">
                                        {{ $agenda->title_gu }}</div>
                                @endif
                            </td>
                            <td class="py-2.5 px-3 text-slate-700 font-semibold text-xs leading-relaxed"
                                title="{{ $agenda->description }}">
                                {{ $agenda->description }}
                            </td>
                            <td class="py-2.5 px-3 text-slate-600 font-medium font-gujarati text-xs leading-relaxed"
                                title="{{ $agenda->description_gu }}">
                                {{ $agenda->description_gu ?: '-' }}
                            </td>
                            <td class="py-2.5 px-3 text-center">
                                <span class="text-[10px] font-bold text-slate-400">{{ $agenda->display_order }}</span>
                            </td>
                            <td class="py-2.5 px-3 text-right">
                                <div class="flex justify-end items-center space-x-2">
                                    {{-- Edit --}}
                                    <button type="button" @click="openEdit({
                                                                        id: {{ $agenda->id }},
                                                                        title: {{ json_encode($agenda->title) }},
                                                                        title_gu: {{ json_encode($agenda->title_gu) }},
                                                                        description: {{ json_encode($agenda->description) }},
                                                                        description_gu: {{ json_encode($agenda->description_gu) }},
                                                                        icon: {{ json_encode($agenda->icon) }},
                                                                        display_order: {{ $agenda->display_order }},
                                                                        update_url: '{{ route('admin.content.agendas.update', $agenda->id) }}'
                                                                    })"
                                        class="flex items-center justify-center w-7 h-7 rounded-lg bg-primary-50 text-primary-600 hover:bg-primary-100 transition-colors cursor-pointer"
                                        title="{{ __('messages.edit') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    {{-- Delete --}}
                                    <button type="button"
                                        @click="$dispatch('confirm-delete', { action: '{{ route('admin.content.agendas.destroy', $agenda->id) }}', message: '{{ __('messages.delete_confirm_agenda') }}' })"
                                        class="flex items-center justify-center w-7 h-7 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition-colors cursor-pointer"
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
                            <td colspan="6" class="py-12 text-center text-slate-400">{{ __('messages.no_agendas_yet') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($agendas->hasPages())
            <div class="mt-4">
                {{ $agendas->links() }}
            </div>
        @endif

        <!-- ============ EDIT MODAL ============ -->
        <div x-show="showEditModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-transition
            x-cloak>
            <div @click.away="showEditModal = false"
                class="bg-white rounded-2xl p-5 border border-slate-100 shadow-2xl max-w-2xl w-full space-y-4 relative">
                <button @click="showEditModal = false"
                    class="absolute top-3.5 right-3.5 w-7 h-7 flex items-center justify-center rounded-full bg-slate-50 hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition-colors cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <h3 class="text-xs font-black text-slate-900 pr-6 uppercase tracking-wider">
                    {{ __('messages.edit_agenda_modal_title') }}</h3>

                <!-- Display Agenda Info Header -->
                <div class="p-3 bg-slate-50 border border-slate-200/60 rounded-xl space-y-0.5">
                    <span class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider block">{{ __('messages.core_agendas') }}</span>
                    <h4 class="text-xs font-black text-slate-900 truncate" x-text="editAgenda.title + (editAgenda.title_gu ? ' / ' + editAgenda.title_gu : '')"></h4>
                </div>

                <form method="POST" :action="editAgenda.update_url" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Hidden fields to maintain agenda properties -->
                    <input type="hidden" name="title" :value="editAgenda.title">
                    <input type="hidden" name="title_gu" :value="editAgenda.title_gu">
                    <input type="hidden" name="icon" :value="editAgenda.icon">
                    <input type="hidden" name="display_order" :value="editAgenda.display_order">

                    @if($errors->any())
                        <div class="p-3 bg-rose-50 border border-rose-100 text-rose-700 text-[10px] font-semibold rounded-xl">
                            <ul class="list-disc pl-4 space-y-0.5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Side-by-Side Description Textareas Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- English Description -->
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-extrabold text-slate-700 flex items-center justify-between">
                                <span>{{ __('messages.desc_english_label') }} <span class="text-rose-500">*</span></span>
                            </label>
                            <textarea name="description" rows="4" required x-model="editAgenda.description"
                                placeholder="{{ __('messages.enter_desc_en') }}"
                                class="w-full text-xs font-semibold px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-2 focus:ring-primary-100 focus:outline-none transition-all leading-relaxed"></textarea>
                        </div>

                        <!-- Gujarati Description -->
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-extrabold text-slate-700 flex items-center justify-between">
                                <span>{{ __('messages.desc_gujarati_label') }}</span>
                            </label>
                            <textarea name="description_gu" rows="4" x-model="editAgenda.description_gu"
                                placeholder="{{ __('messages.enter_desc_gu') }}"
                                class="w-full text-xs font-semibold font-gujarati px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 focus:ring-2 focus:ring-primary-100 focus:outline-none transition-all leading-relaxed"></textarea>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                        <button type="button" @click="showEditModal = false"
                            class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs rounded-xl transition-colors cursor-pointer">{{ __('messages.cancel') }}</button>
                        <button type="submit"
                            class="px-5 py-2 bg-primary-500 hover:bg-primary-600 text-white font-extrabold text-xs rounded-xl shadow-xs transition-colors cursor-pointer">{{ __('messages.save_changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
@endsection