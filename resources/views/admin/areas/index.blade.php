@extends('layouts.admin')

@section('page_title', __('messages.area_management'))

@section('content')
    @php
        $user = auth()->user();
        $userPerms = $user->permissions->pluck('name');
        $canAddArea = $user->hasRole('Administrator') || $userPerms->contains('areas_manage') || $userPerms->contains('areas_add');
        $canEditArea = $user->hasRole('Administrator') || $userPerms->contains('areas_manage') || $userPerms->contains('areas_edit');
        $canDeleteArea = $user->hasRole('Administrator') || $userPerms->contains('areas_manage') || $userPerms->contains('areas_delete');
    @endphp
    <div x-data="{ 
        addOpen: false, 
        editOpen: false, 
        importOpen: false,
        editName: '',
        editPincode: '',
        editUrl: ''
    }" class="space-y-4">

        <!-- Toolbar & Actions -->
        <div
            class="flex flex-col sm:flex-row items-center justify-between gap-3 bg-white p-3 rounded-xl border border-slate-100 shadow-xs">
            <!-- Search Form -->
            <form method="GET" action="{{ route('admin.areas.index') }}" class="flex items-center gap-2 w-full sm:w-auto">
                <div class="relative w-full sm:w-64">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="{{ __('messages.search_area_placeholder') }}"
                        class="h-9 w-full text-xs font-semibold pl-3 pr-8 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 transition-colors">
                    @if(request()->filled('search'))
                        <a href="{{ route('admin.areas.index') }}"
                            class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-rose-500 font-extrabold text-sm"
                            title="Clear search">
                            &times;
                        </a>
                    @endif
                </div>
                <button type="submit"
                    class="h-9 px-3.5 bg-slate-100 hover:bg-slate-200 font-bold text-xs text-slate-700 rounded-xl transition-colors shrink-0">
                    {{ __('messages.search') }}
                </button>
            </form>

            <div class="flex flex-wrap items-center gap-2 justify-end w-full sm:w-auto">
                <a href="{{ route('admin.areas.export', request()->all()) }}"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-xs rounded-xl border border-emerald-200/60 shadow-xs transition-colors shrink-0">
                    <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>{{ __('messages.export_excel') }}</span>
                </a>

                <!-- Import Area Button -->
                @if($canAddArea)
                    <button type="button" @click="importOpen = true"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-sky-50 hover:bg-sky-100 text-sky-700 font-bold text-xs rounded-xl border border-sky-200/60 shadow-xs transition-colors shrink-0 cursor-pointer">
                        <svg class="w-3.5 h-3.5 text-sky-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        <span>{{ __('messages.import_excel') }}</span>
                    </button>
                @endif

                <!-- Add Area Button -->
                @if($canAddArea)
                    <button type="button" @click="addOpen = true"
                        class="inline-flex items-center px-3.5 py-2 bg-primary-500 hover:bg-primary-600 text-white font-bold text-xs rounded-xl shadow-xs transition-transform hover:-translate-y-0.5 shrink-0">
                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>{{ __('messages.add_new_area') }}</span>
                    </button>
                @endif
            </div>
        </div>

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="p-4 bg-rose-50 border border-rose-100 rounded-xl text-xs text-rose-800 font-semibold space-y-1">
                <p class="font-bold">Please correct the following errors:</p>
                <ul class="list-disc pl-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Area List Table -->
        <div class="bg-white border border-slate-100 rounded-xl overflow-hidden shadow-sm">
            <div class="px-4 py-2.5 bg-slate-50/80 border-b border-slate-100 flex items-center justify-between">
                <span class="text-xs font-bold text-slate-700">{{ __('messages.area_listings') }}</span>
                <span class="text-[11px] font-bold text-slate-400">{{ __('messages.total_areas') }}:
                    {{ $totalAreas }}</span>
            </div>

            <table class="w-full text-left border-collapse">
                <thead>
                    <tr
                        class="bg-slate-50/70 text-xs font-black uppercase text-slate-700 tracking-wider border-b border-slate-200">
                        <th class="py-2.5 px-4">{{ __('messages.area_name') }}</th>
                        <th class="py-2.5 px-4">{{ __('messages.pincode') }}</th>
                        <th class="py-2.5 px-4">{{ __('messages.registered_members') }}</th>
                        <th class="py-2.5 px-4">{{ __('messages.created_date') }}</th>
                        <th class="py-2.5 px-4 text-right">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-semibold text-slate-700">
                    @forelse($areas as $area)
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-2.5 px-4 text-slate-900 font-bold">{{ $area->name }}</td>
                            <td class="py-2.5 px-4">
                                @if($area->pincode)
                                    <span
                                        class="px-2 py-0.5 text-[10px] font-extrabold text-slate-700 bg-slate-100 rounded-md border border-slate-200">{{ $area->pincode }}</span>
                                @else
                                    <span class="text-slate-300 font-normal">N/A</span>
                                @endif
                            </td>
                            <td class="py-2.5 px-4 font-bold">{{ $area->member_profiles_count }}
                                {{ __('messages.members_count') }}</td>
                            <td class="py-2.5 px-4 text-slate-400 font-medium">{{ $area->created_at->format('M d, Y') }}</td>
                            <td class="py-2.5 px-4 text-right">
                                <div class="flex justify-end items-center space-x-2">
                                    @if($canEditArea)
                                        <button type="button"
                                            @click="editName = '{{ addslashes($area->name) }}'; editPincode = '{{ addslashes($area->pincode ?? '') }}'; editUrl = '{{ route('admin.areas.update', $area->id) }}'; editOpen = true"
                                            class="flex items-center justify-center w-7 h-7 rounded-lg bg-primary-50 text-primary-600 hover:bg-primary-100 transition-colors shadow-2xs border border-primary-100"
                                            title="{{ __('messages.edit') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                    @endif
                                    @if($canDeleteArea)
                                        <button type="button"
                                            @click="$dispatch('confirm-delete', { action: '{{ route('admin.areas.destroy', $area->id) }}', message: '{{ __('messages.delete_confirm_area', ['name' => addslashes($area->name)]) }}' })"
                                            class="flex items-center justify-center w-7 h-7 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition-colors shadow-2xs border border-rose-100"
                                            title="{{ __('messages.delete') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400">
                                {{ __('messages.no_areas_declared') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div>
            {{ $areas->links() }}
        </div>

        <!-- Add Area Modal -->
        <template x-teleport="body">
            <div x-show="addOpen"
                class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-950/40 backdrop-blur-sm"
                x-transition x-cloak>
                <div class="bg-white rounded-xl max-w-md w-full p-6 border border-slate-100 shadow-xl space-y-4"
                    @click.away="addOpen = false">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h3 class="text-sm font-extrabold text-slate-950">{{ __('messages.add_new_area') }}</h3>
                        <button type="button" @click="addOpen = false" class="text-slate-400 hover:text-slate-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('admin.areas.store') }}" class="space-y-4">
                        @csrf
                        <div class="space-y-1">
                            <label
                                class="text-[10px] font-bold text-slate-400 uppercase">{{ __('messages.area_name') }}</label>
                            <input type="text" name="name" required placeholder="e.g. Maninagar, Nikol, Ghatlodia..."
                                class="w-full text-xs font-semibold px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 outline-none">
                        </div>
                        <div class="space-y-1">
                            <label
                                class="text-[10px] font-bold text-slate-400 uppercase">{{ __('messages.postal_pincode') }}
                                ({{ __('messages.optional') }})</label>
                            <input type="text" name="pincode" maxlength="10" placeholder="e.g. 380008"
                                class="w-full text-xs font-semibold px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 outline-none">
                        </div>
                        <div class="flex items-center justify-end space-x-2 pt-2">
                            <button type="button" @click="addOpen = false"
                                class="px-4 py-2 border border-slate-200 text-slate-700 font-bold text-xs rounded-xl hover:bg-slate-50 transition-colors">
                                {{ __('messages.cancel') }}
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white font-bold text-xs rounded-xl shadow-sm transition-colors">
                                {{ __('messages.create_area') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

        <!-- Edit Area Modal -->
        <template x-teleport="body">
            <div x-show="editOpen"
                class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-950/40 backdrop-blur-sm"
                x-transition x-cloak>
                <div class="bg-white rounded-xl max-w-md w-full p-6 border border-slate-100 shadow-xl space-y-4"
                    @click.away="editOpen = false">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h3 class="text-sm font-extrabold text-slate-950">{{ __('messages.edit_area_details') }}</h3>
                        <button type="button" @click="editOpen = false" class="text-slate-400 hover:text-slate-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form method="POST" :action="editUrl" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div class="space-y-1">
                            <label
                                class="text-[10px] font-bold text-slate-400 uppercase">{{ __('messages.area_name') }}</label>
                            <input type="text" name="name" required x-model="editName" placeholder="e.g. Maninagar"
                                class="w-full text-xs font-semibold px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 outline-none">
                        </div>
                        <div class="space-y-1">
                            <label
                                class="text-[10px] font-bold text-slate-400 uppercase">{{ __('messages.postal_pincode') }}
                                ({{ __('messages.optional') }})</label>
                            <input type="text" name="pincode" maxlength="10" x-model="editPincode" placeholder="e.g. 380008"
                                class="w-full text-xs font-semibold px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 outline-none">
                        </div>
                        <div class="flex items-center justify-end space-x-2 pt-2">
                            <button type="button" @click="editOpen = false"
                                class="px-4 py-2 border border-slate-200 text-slate-700 font-bold text-xs rounded-xl hover:bg-slate-50 transition-colors">
                                {{ __('messages.cancel') }}
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white font-bold text-xs rounded-xl shadow-sm transition-colors">
                                {{ __('messages.save_changes') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

        <!-- Import Areas Modal -->
        <template x-teleport="body">
            <div x-show="importOpen"
                class="fixed inset-0 z-[100] flex items-center justify-center p-3 sm:p-4 bg-slate-950/40 backdrop-blur-xs"
                x-transition x-cloak>
                <div class="bg-white rounded-xl max-w-sm sm:max-w-md w-full p-4 sm:p-5 border border-slate-100 shadow-xl space-y-3"
                    @click.away="importOpen = false">
                    <!-- Header -->
                    <div class="flex items-center justify-between pb-2.5 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="text-base">📥</span>
                            <h3 class="text-xs sm:text-sm font-black text-slate-900">{{ __('messages.import_areas_csv') }}</h3>
                        </div>
                        <button type="button" @click="importOpen = false" class="text-slate-400 hover:text-slate-600 transition-colors p-1 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Instructions & Sample File Download -->
                    <div class="p-2.5 bg-slate-50 border border-slate-200/70 rounded-lg space-y-1.5">
                        <p class="text-[11px] font-semibold text-slate-600 leading-snug">
                            {{ __('messages.import_area_help') }}
                        </p>
                        <div class="flex items-center justify-between pt-1 border-t border-slate-200/50 flex-wrap gap-1">
                            <span class="text-[10px] font-bold text-slate-400">Area Name, Pincode</span>
                            <a href="{{ route('admin.areas.sample_csv') }}" 
                               class="inline-flex items-center gap-1 text-[10px] font-black text-primary-600 hover:text-primary-700 bg-white border border-primary-200 px-2 py-0.5 rounded-md shadow-2xs hover:bg-primary-50 transition-colors">
                                <span>📄 {{ __('messages.download_sample_template') }}</span>
                            </a>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.areas.import') }}" enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        <div class="space-y-1">
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-wider block">
                                {{ __('messages.choose_csv_file') }} <span class="text-rose-500">*</span>
                            </label>
                            <input type="file" name="csv_file" required accept=".csv,.txt"
                                class="w-full text-xs font-semibold px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500 outline-none file:mr-2 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-[11px] file:font-bold file:bg-slate-200 file:text-slate-700 hover:file:bg-slate-300 transition-colors cursor-pointer">
                            <span class="text-[9.5px] font-medium text-slate-400 block">.csv, .txt (Max: 5MB)</span>
                        </div>

                        <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                            <button type="button" @click="importOpen = false"
                                class="px-3 py-1.5 border border-slate-200 text-slate-700 font-bold text-xs rounded-lg hover:bg-slate-50 transition-colors cursor-pointer">
                                {{ __('messages.cancel') }}
                            </button>
                            <button type="submit"
                                class="px-4 py-1.5 bg-primary-600 hover:bg-primary-700 text-white font-bold text-xs rounded-lg shadow-2xs transition-colors inline-flex items-center gap-1 cursor-pointer">
                                <span>📥 {{ __('messages.upload_and_import') }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

    </div>
@endsection