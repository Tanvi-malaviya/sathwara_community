@extends('layouts.admin')

@section('page_title', __('messages.area_management'))

@section('content')
<div x-data="{ 
    addOpen: false, 
    editOpen: false, 
    editName: '',
    editPincode: '',
    editUrl: ''
}" class="space-y-4">

    <!-- Header & Actions -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-white p-4 rounded-xl border border-slate-100 shadow-sm">
        <div>
            <h3 class="text-sm font-extrabold text-slate-800">{{ __('messages.community_area_directory') }}</h3>
            <p class="text-xs text-slate-400 mt-0.5">{{ __('messages.manage_areas_desc') }}</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <!-- Search Form -->
            <form method="GET" action="{{ route('admin.areas.index') }}" class="flex items-center gap-2">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('messages.search_area_placeholder') }}" 
                           class="text-xs font-semibold pl-3 pr-8 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary-500 w-48 sm:w-64 transition-colors">
                    @if(request()->filled('search'))
                        <a href="{{ route('admin.areas.index') }}" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-rose-500 font-extrabold text-sm" title="Clear search">
                            &times;
                        </a>
                    @endif
                </div>
                <button type="submit" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 font-bold text-xs text-slate-700 rounded-xl transition-colors shrink-0">
                    {{ __('messages.search') }}
                </button>
            </form>

            <!-- Export Excel Button -->
            <a href="{{ route('admin.areas.export', request()->all()) }}" 
               class="inline-flex items-center px-3.5 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-xs rounded-xl border border-emerald-200/60 shadow-xs transition-colors shrink-0">
                📊 <span>Export Excel</span>
            </a>

            <!-- Add Area Button -->
            <button type="button" @click="addOpen = true" class="inline-flex items-center px-3.5 py-2 bg-primary-500 hover:bg-primary-600 text-white font-bold text-xs rounded-xl shadow-sm transition-colors shrink-0">
                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('messages.add_area') }}
            </button>
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
            <span class="text-[11px] font-bold text-slate-400">{{ __('messages.total_areas') }}: {{ $totalAreas }}</span>
        </div>

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/70 text-[10px] font-extrabold uppercase text-slate-400 tracking-wider border-b border-slate-100">
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
                                <span class="px-2 py-0.5 text-[10px] font-extrabold text-slate-700 bg-slate-100 rounded-md border border-slate-200">{{ $area->pincode }}</span>
                            @else
                                <span class="text-slate-300 font-normal">N/A</span>
                            @endif
                        </td>
                        <td class="py-2.5 px-4 font-bold">{{ $area->member_profiles_count }} {{ __('messages.members_count') }}</td>
                        <td class="py-2.5 px-4 text-slate-400 font-medium">{{ $area->created_at->format('M d, Y') }}</td>
                        <td class="py-2.5 px-4 text-right">
                            <div class="flex justify-end items-center space-x-2">
                                <button type="button" 
                                        @click="editName = '{{ addslashes($area->name) }}'; editPincode = '{{ addslashes($area->pincode ?? '') }}'; editUrl = '{{ route('admin.areas.update', $area->id) }}'; editOpen = true" 
                                        class="flex items-center justify-center w-7 h-7 rounded-lg bg-primary-50 text-primary-600 hover:bg-primary-100 transition-colors shadow-2xs border border-primary-100"
                                        title="{{ __('messages.edit') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button type="button" 
                                        @click="$dispatch('confirm-delete', { action: '{{ route('admin.areas.destroy', $area->id) }}', message: '{{ __('messages.delete_confirm_area', ['name' => addslashes($area->name)]) }}' })" 
                                        class="flex items-center justify-center w-7 h-7 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition-colors shadow-2xs border border-rose-100"
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
             x-transition
             x-cloak>
            <div class="bg-white rounded-xl max-w-md w-full p-6 border border-slate-100 shadow-xl space-y-4"
                 @click.away="addOpen = false">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-extrabold text-slate-950">{{ __('messages.add_new_area') }}</h3>
                    <button type="button" @click="addOpen = false" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <form method="POST" action="{{ route('admin.areas.store') }}" class="space-y-4">
                    @csrf
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-400 uppercase">{{ __('messages.area_name') }}</label>
                        <input type="text" name="name" required placeholder="e.g. Maninagar, Nikol, Ghatlodia..." 
                               class="w-full text-xs font-semibold px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-400 uppercase">{{ __('messages.postal_pincode') }} ({{ __('messages.optional') }})</label>
                        <input type="text" name="pincode" maxlength="10" placeholder="e.g. 380008" 
                               class="w-full text-xs font-semibold px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 outline-none">
                    </div>
                    <div class="flex items-center justify-end space-x-2 pt-2">
                        <button type="button" @click="addOpen = false" class="px-4 py-2 border border-slate-200 text-slate-700 font-bold text-xs rounded-xl hover:bg-slate-50 transition-colors">
                            {{ __('messages.cancel') }}
                        </button>
                        <button type="submit" class="px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white font-bold text-xs rounded-xl shadow-sm transition-colors">
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
             x-transition
             x-cloak>
            <div class="bg-white rounded-xl max-w-md w-full p-6 border border-slate-100 shadow-xl space-y-4"
                 @click.away="editOpen = false">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-extrabold text-slate-950">{{ __('messages.edit_area_details') }}</h3>
                    <button type="button" @click="editOpen = false" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <form method="POST" :action="editUrl" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-400 uppercase">{{ __('messages.area_name') }}</label>
                        <input type="text" name="name" required x-model="editName" placeholder="e.g. Maninagar" 
                               class="w-full text-xs font-semibold px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-400 uppercase">{{ __('messages.postal_pincode') }} ({{ __('messages.optional') }})</label>
                        <input type="text" name="pincode" maxlength="10" x-model="editPincode" placeholder="e.g. 380008" 
                               class="w-full text-xs font-semibold px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 outline-none">
                    </div>
                    <div class="flex items-center justify-end space-x-2 pt-2">
                        <button type="button" @click="editOpen = false" class="px-4 py-2 border border-slate-200 text-slate-700 font-bold text-xs rounded-xl hover:bg-slate-50 transition-colors">
                            {{ __('messages.cancel') }}
                        </button>
                        <button type="submit" class="px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white font-bold text-xs rounded-xl shadow-sm transition-colors">
                            {{ __('messages.save_changes') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>

</div>
@endsection
