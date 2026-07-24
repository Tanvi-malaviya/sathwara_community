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
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-3 rounded-xl border border-slate-100 shadow-sm mb-4">
        <form method="GET" action="{{ route('admin.content.agendas') }}" class="flex items-center gap-2 flex-grow max-w-md w-full">
            <div class="relative flex-grow">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('messages.search_agendas') }}" 
                       class="text-xs font-semibold pl-3 pr-8 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 w-full transition-colors">
                @if(request()->filled('search'))
                    <a href="{{ route('admin.content.agendas') }}" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-rose-500 font-extrabold text-sm" title="Clear search">
                        &times;
                    </a>
                @endif
            </div>
            <button type="submit" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 font-bold text-xs text-slate-700 rounded-xl transition-colors shrink-0">
                {{ __('messages.search') }}
            </button>
        </form>

        <button @click="showAddModal = true" class="inline-flex items-center justify-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white font-bold text-xs rounded-xl shadow-md transition-all hover:scale-[1.02] active:scale-95 shrink-0 whitespace-nowrap">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            {{ __('messages.add_agenda') }}
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white border border-slate-100 rounded-xl overflow-hidden shadow-sm">
        <table class="w-full text-left border-collapse table-fixed">
            <thead>
                <tr class="bg-slate-50 text-[10px] font-extrabold uppercase text-slate-400 tracking-wider border-b border-slate-100">
                    <th class="py-2 px-3 w-16">{{ __('messages.icon') }}</th>
                    <th class="py-2 px-3 w-48">{{ __('messages.title') }}</th>
                    <th class="py-2 px-3">{{ __('messages.description') }}</th>
                    <th class="py-2 px-3 w-20 text-center">{{ __('messages.order') }}</th>
                    <th class="py-2 px-3 w-28 text-right">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-xs font-semibold text-slate-700">
                @forelse($agendas as $agenda)
                    <tr class="hover:bg-slate-50/50">
                        <td class="py-2 px-3 text-slate-900 font-bold">
                            <span class="text-xl">
                                @if($agenda->icon == 'users') 👥
                                @elseif($agenda->icon == 'academic-cap') 🎓
                                @elseif($agenda->icon == 'briefcase') 💼
                                @else 📌 @endif
                            </span>
                        </td>
                        <td class="py-2 px-3 text-slate-900 font-bold">{{ $agenda->title }}</td>
                        <td class="py-2 px-3 text-slate-500" style="max-width:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                            {{ $agenda->description }}
                        </td>
                        <td class="py-2 px-3 text-center">
                            <span class="text-[10px] font-bold text-slate-400">{{ $agenda->display_order }}</span>
                        </td>
                        <td class="py-2 px-3 text-right">
                            <div class="flex justify-end items-center space-x-2">
                                {{-- Edit --}}
                                <button type="button"
                                    @click="openEdit({
                                        id: {{ $agenda->id }},
                                        title: {{ json_encode($agenda->title) }},
                                        description: {{ json_encode($agenda->description) }},
                                        icon: {{ json_encode($agenda->icon) }},
                                        display_order: {{ $agenda->display_order }},
                                        update_url: '{{ route('admin.content.agendas.update', $agenda->id) }}'
                                    })"
                                    class="flex items-center justify-center w-7 h-7 rounded-lg bg-primary-50 text-primary-600 hover:bg-primary-100 transition-colors"
                                    title="{{ __('messages.edit') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                {{-- Delete --}}
                                <button type="button"
                                    @click="$dispatch('confirm-delete', { action: '{{ route('admin.content.agendas.destroy', $agenda->id) }}', message: '{{ __('messages.delete_confirm_agenda') }}' })"
                                    class="flex items-center justify-center w-7 h-7 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition-colors"
                                    title="{{ __('messages.delete') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-slate-400">{{ __('messages.no_agendas_yet') }}</td>
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

    <!-- ============ ADD MODAL ============ -->
    <div x-show="showAddModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-transition x-cloak>
        <div @click.away="showAddModal = false" class="bg-white rounded-2xl p-4 border border-slate-100 shadow-2xl max-w-sm w-full space-y-3 relative max-h-[90vh] overflow-y-auto">
            <button @click="showAddModal = false" class="absolute top-3 right-3 w-6 h-6 flex items-center justify-center rounded-full bg-slate-50 hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            <h3 class="text-xs font-bold text-slate-900 pr-6">{{ __('messages.add_agenda_modal_title') }}</h3>
            <form method="POST" action="{{ route('admin.content.agendas.store') }}" class="space-y-3">
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
                <div class="space-y-0.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">{{ __('messages.title') }}</label>
                    <input type="text" name="title" value="{{ old('title') }}" required placeholder="e.g. Higher Education Support" class="w-full text-xs px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg">
                </div>
                <div class="space-y-0.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">{{ __('messages.icon') }}</label>
                    <select name="icon" required class="w-full text-xs px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg">
                        <option value="users" {{ old('icon') == 'users' ? 'selected' : '' }}>👥 Social / Community</option>
                        <option value="academic-cap" {{ old('icon') == 'academic-cap' ? 'selected' : '' }}>🎓 Education / Students</option>
                        <option value="briefcase" {{ old('icon') == 'briefcase' ? 'selected' : '' }}>💼 Business Directory</option>
                        <option value="pushpin" {{ old('icon') == 'pushpin' ? 'selected' : '' }}>📌 Announcements</option>
                    </select>
                </div>
                <div class="space-y-0.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">{{ __('messages.display_order') }}</label>
                    <input type="number" name="display_order" value="{{ old('display_order', 0) }}" required class="w-full text-xs px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg">
                </div>
                <div class="space-y-0.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">{{ __('messages.description') }}</label>
                    <textarea name="description" rows="3" required placeholder="Explain the focus..." class="w-full text-xs px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg">{{ old('description') }}</textarea>
                </div>
                <div class="pt-2 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" @click="showAddModal = false" class="px-3 py-1.5 border border-slate-200 hover:bg-slate-50 text-slate-600 font-bold text-xs rounded-lg transition-colors">{{ __('messages.cancel') }}</button>
                    <button type="submit" class="px-4 py-1.5 bg-primary-500 hover:bg-primary-600 text-white font-bold text-xs rounded-lg shadow-sm">{{ __('messages.add_agenda_submit') }}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============ EDIT MODAL ============ -->
    <div x-show="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-transition x-cloak>
        <div @click.away="showEditModal = false" class="bg-white rounded-2xl p-4 border border-slate-100 shadow-2xl max-w-sm w-full space-y-3 relative max-h-[90vh] overflow-y-auto">
            <button @click="showEditModal = false" class="absolute top-3 right-3 w-6 h-6 flex items-center justify-center rounded-full bg-slate-50 hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            <h3 class="text-xs font-bold text-slate-900 pr-6">{{ __('messages.edit_agenda_modal_title') }}</h3>
            <form method="POST" :action="editAgenda.update_url" class="space-y-3">
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
                <div class="space-y-0.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">{{ __('messages.title') }}</label>
                    <input type="text" name="title" :value="editAgenda.title" required placeholder="e.g. Higher Education Support" class="w-full text-xs px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg">
                </div>
                <div class="space-y-0.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">{{ __('messages.icon') }}</label>
                    <select name="icon" required class="w-full text-xs px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg">
                        <option value="users" :selected="editAgenda.icon === 'users'">👥 Social / Community</option>
                        <option value="academic-cap" :selected="editAgenda.icon === 'academic-cap'">🎓 Education / Students</option>
                        <option value="briefcase" :selected="editAgenda.icon === 'briefcase'">💼 Business Directory</option>
                        <option value="pushpin" :selected="editAgenda.icon === 'pushpin'">📌 Announcements</option>
                    </select>
                </div>
                <div class="space-y-0.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">{{ __('messages.display_order') }}</label>
                    <input type="number" name="display_order" :value="editAgenda.display_order" required class="w-full text-xs px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg">
                </div>
                <div class="space-y-0.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">{{ __('messages.description') }}</label>
                    <textarea name="description" rows="3" required x-text="editAgenda.description" class="w-full text-xs px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg"></textarea>
                </div>
                <div class="pt-2 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" @click="showEditModal = false" class="px-3 py-1.5 border border-slate-200 hover:bg-slate-50 text-slate-600 font-bold text-xs rounded-lg transition-colors">{{ __('messages.cancel') }}</button>
                    <button type="submit" class="px-4 py-1.5 bg-primary-500 hover:bg-primary-600 text-white font-bold text-xs rounded-lg shadow-sm">{{ __('messages.save_changes') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
