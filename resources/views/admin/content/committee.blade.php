@extends('layouts.admin')

@section('page_title', __('messages.committee_members'))

@section('content')
    <div x-data="{
        showAddModal: @json($errors->any()),
        showEditModal: false,
        editMember: {},
        openEdit(member) {
            this.editMember = member;
            this.showEditModal = true;
        }
    }">
        <!-- Header Actions & Search bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-3 rounded-xl border border-slate-100 shadow-sm mb-4">
            <form method="GET" action="{{ route('admin.content.committee') }}" class="flex items-center gap-2 flex-grow max-w-md w-full">
                <div class="relative flex-grow">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('messages.search_committee_members') }}" 
                           class="text-xs font-semibold pl-3 pr-8 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 w-full transition-colors">
                    @if(request()->filled('search'))
                        <a href="{{ route('admin.content.committee') }}" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-rose-500 font-extrabold text-sm" title="Clear search">
                            &times;
                        </a>
                    @endif
                </div>
                <button type="submit" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 font-bold text-xs text-slate-700 rounded-xl transition-colors shrink-0">
                    {{ __('messages.search') }}
                </button>
            </form>

            <button @click="showAddModal = true"
                class="inline-flex items-center justify-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white font-bold text-xs rounded-xl shadow-sm transition-all hover:scale-[1.02] active:scale-95 shrink-0 whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('messages.add_member') }}
            </button>
        </div>

        <!-- Card Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2 mb-6">
            @forelse($members as $c)
                <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all flex flex-col items-center text-center relative ">
                    <!-- Action Buttons (Hidden by default, show on hover) -->
                    <div class="absolute top-3 right-3 flex items-center space-x-1.5  transition-opacity duration-150">
                    {{-- Edit --}}
<button type="button"
    @click="openEdit({
        id: {{ $c->id }},
        name: {{ json_encode($c->name) }},
        designation: {{ json_encode($c->designation) }},
        status: {{ $c->status ? 1 : 0 }},
        display_order: {{ $c->display_order }},
        update_url: '{{ route('admin.content.committee.update', $c->id) }}'
    })"
    class="flex items-center justify-center w-7 h-7 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 hover:text-blue-700 transition-colors shadow-sm border border-blue-100"
    title="{{ __('messages.edit') }}">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
        stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
    </svg>
</button>

{{-- Delete --}}
<button type="button"
    @click="$dispatch('confirm-delete', { action: '{{ route('admin.content.committee.destroy', $c->id) }}', message: '{{ __('messages.delete_confirm_committee', ['name' => addslashes($c->name)]) }}' })"
    class="flex items-center justify-center w-7 h-7 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 transition-colors shadow-sm border border-red-100"
    title="{{ __('messages.delete') }}">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
        stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
    </svg>
</button>
                    </div>

                    <!-- Avatar -->
                    <div class="mb-4">
                        @if($c->photo_path)
                            <img class="w-20 h-20 rounded-full object-cover ring-4 ring-slate-50 shadow-sm"
                                src="{{ str_starts_with($c->photo_path, 'http') ? $c->photo_path : asset('storage/' . $c->photo_path) }}"
                                alt="{{ $c->name }}">
                        @else
                            <div class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center text-lg font-bold text-slate-400 ring-4 ring-slate-50">
                                {{ mb_substr($c->name, 0, 1) }}
                            </div>
                        @endif
                    </div>

                    <!-- Details -->
                    <h4 class="text-sm font-bold text-slate-900 mb-1 leading-snug line-clamp-1" title="{{ $c->name }}">{{ $c->name }}</h4>
                    <p class="text-xs text-slate-500 font-medium leading-normal line-clamp-1" title="{{ $c->designation }}">{{ $c->designation }}</p>

                    <!-- Always Visible Actions on Mobile -->
                    <div class="flex items-center space-x-3 mt-4 pt-4 border-t border-slate-50 w-full justify-center sm:hidden">
                        <button type="button" @click="openEdit({
                                    id: {{ $c->id }},
                                    name: {{ json_encode($c->name) }},
                                    designation: {{ json_encode($c->designation) }},
                                    status: {{ $c->status ? 1 : 0 }},
                                    display_order: {{ $c->display_order }},
                                    update_url: '{{ route('admin.content.committee.update', $c->id) }}'
                                })"
                            class="text-xs font-bold text-primary-600 hover:text-primary-700">
                            {{ __('messages.edit') }}
                        </button>
                        <span class="text-slate-200">|</span>
                        <button type="button"
                            @click="$dispatch('confirm-delete', { action: '{{ route('admin.content.committee.destroy', $c->id) }}', message: '{{ __('messages.delete_confirm_committee', ['name' => addslashes($c->name)]) }}' })"
                            class="text-xs font-bold text-rose-600 hover:text-rose-700">
                            {{ __('messages.delete') }}
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center bg-white border border-slate-100 rounded-2xl shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <p class="text-sm font-semibold text-slate-500">{{ __('messages.no_committee_yet') }}</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($members->hasPages())
            <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm">
                {{ $members->links() }}
            </div>
        @endif

        <!-- ============ ADD MODAL ============ -->
        <div x-show="showAddModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-transition
            x-cloak>
            <div @click.away="showAddModal = false"
                class="bg-white rounded-2xl p-4 border border-slate-100 shadow-2xl max-w-sm w-full space-y-3 relative max-h-[90vh] overflow-y-auto">
                <button @click="showAddModal = false"
                    class="absolute top-3 right-3 w-6 h-6 flex items-center justify-center rounded-full bg-slate-50 hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <h3 class="text-xs font-bold text-slate-900 pr-6 font-display">{{ __('messages.new_committee_member') }}</h3>
                <form method="POST" action="{{ route('admin.content.committee.store') }}" enctype="multipart/form-data"
                    class="space-y-3">
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
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">{{ __('messages.full_name') }}</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full text-xs px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-primary-400">
                    </div>
                    <div class="space-y-0.5">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">{{ __('messages.designation_role') }}</label>
                        <input type="text" name="designation" value="{{ old('designation') }}" required placeholder="e.g. Trustee, Secretary"
                            class="w-full text-xs px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-primary-400">
                    </div>
                    <div class="space-y-0.5">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">{{ __('messages.photo_optional') }}</label>
                        <input type="file" name="photo"
                            class="text-[10px] block w-full text-slate-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:font-semibold file:bg-primary-50 file:text-primary-700">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-0.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">{{ __('messages.status') }}</label>
                            <select name="status" required
                                class="w-full text-xs px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-primary-400">
                                <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>{{ __('messages.active') }}</option>
                                <option value="0" {{ old('status', 1) == 0 ? 'selected' : '' }}>{{ __('messages.inactive') }}</option>
                            </select>
                        </div>
                        <div class="space-y-0.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">{{ __('messages.display_order') }}</label>
                            <input type="number" name="display_order" value="{{ old('display_order', 0) }}" required
                                class="w-full text-xs px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-primary-400">
                        </div>
                    </div>
                    <div class="pt-2 border-t border-slate-100 flex justify-end gap-2">
                        <button type="button" @click="showAddModal = false" class="px-3 py-1.5 border border-slate-200 hover:bg-slate-50 text-slate-600 font-bold text-xs rounded-lg transition-colors">{{ __('messages.cancel') }}</button>
                        <button type="submit" class="px-4 py-1.5 bg-primary-500 hover:bg-primary-600 text-white font-bold text-xs rounded-lg shadow-sm transition-colors">{{ __('messages.add_member_submit') }}</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ============ EDIT MODAL ============ -->
        <div x-show="showEditModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-transition
            x-cloak>
            <div @click.away="showEditModal = false"
                class="bg-white rounded-2xl p-4 border border-slate-100 shadow-2xl max-w-sm w-full space-y-3 relative max-h-[90vh] overflow-y-auto">
                <button @click="showEditModal = false"
                    class="absolute top-3 right-3 w-6 h-6 flex items-center justify-center rounded-full bg-slate-50 hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <h3 class="text-xs font-bold text-slate-900 pr-6 font-display">{{ __('messages.edit_committee_member') }}</h3>
                <form method="POST" :action="editMember.update_url" enctype="multipart/form-data" class="space-y-3">
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
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">{{ __('messages.full_name') }}</label>
                        <input type="text" name="name" :value="editMember.name" required
                            class="w-full text-xs px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-primary-400">
                    </div>
                    <div class="space-y-0.5">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">{{ __('messages.designation_role') }}</label>
                        <input type="text" name="designation" :value="editMember.designation" required
                            placeholder="e.g. Trustee, Secretary"
                            class="w-full text-xs px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-primary-400">
                    </div>
                    <div class="space-y-0.5">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">{{ __('messages.replace_photo_optional') }}</label>
                        <input type="file" name="photo"
                            class="text-[10px] block w-full text-slate-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:font-semibold file:bg-primary-50 file:text-primary-700">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-0.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">{{ __('messages.status') }}</label>
                            <select name="status" required
                                class="w-full text-xs px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-primary-400">
                                <option value="1" :selected="editMember.status == 1">{{ __('messages.active') }}</option>
                                <option value="0" :selected="editMember.status == 0">{{ __('messages.inactive') }}</option>
                            </select>
                        </div>
                        <div class="space-y-0.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">{{ __('messages.display_order') }}</label>
                            <input type="number" name="display_order" :value="editMember.display_order" required
                                class="w-full text-xs px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-primary-400">
                        </div>
                    </div>
                    <div class="pt-2 border-t border-slate-100 flex justify-end gap-2">
                        <button type="button" @click="showEditModal = false" class="px-3 py-1.5 border border-slate-200 hover:bg-slate-50 text-slate-600 font-bold text-xs rounded-lg transition-colors">{{ __('messages.cancel') }}</button>
                        <button type="submit" class="px-4 py-1.5 bg-primary-500 hover:bg-primary-600 text-white font-bold text-xs rounded-lg shadow-sm transition-colors">{{ __('messages.save_changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection