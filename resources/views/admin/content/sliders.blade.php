@extends('layouts.admin')

@section('page_title', __('messages.hero_sliders'))

@section('content')
    <div x-data="{
        showAddModal: @json($errors->any() && !session('editId')),
        showEditModal: false,
        editSlider: {},
        openEdit(slide) {
            this.editSlider = slide;
            this.showEditModal = true;
        }
    }">
        <!-- Header Actions -->
        <div class="flex items-center justify-end gap-2 bg-white p-3 rounded-xl border border-slate-100 shadow-sm mb-4">
            <a href="{{ route('admin.content.sliders.export', request()->all()) }}"
                class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-xs rounded-xl border border-emerald-200/60 shadow-xs transition-all whitespace-nowrap">
                <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>{{ __('messages.export_excel') }}</span>
            </a>

            <button @click="showAddModal = true"
                class="inline-flex items-center justify-center px-4 py-2 bg-primary-500 hover:bg-primary-600 font-bold text-xs text-white rounded-xl shadow-md transition-all hover:scale-[1.02] active:scale-95 whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('messages.add_slider_banner') }}
            </button>
        </div>

        <!-- Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
            @forelse($sliders as $slide)
                @php $sImg = str_starts_with($slide->image_path, 'http') ? $slide->image_path : asset('storage/' . $slide->image_path); @endphp
                <div
                    class="bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-xs flex flex-col justify-between hover:shadow-md transition-shadow">
                    <div class="aspect-video w-full overflow-hidden bg-slate-950 relative flex items-center justify-center">
                        <!-- Blurred Backdrop -->
                        <img src="{{ $sImg }}" 
                             alt="" 
                             aria-hidden="true"
                             class="absolute inset-0 w-full h-full pointer-events-none select-none"
                             style="object-fit: cover; object-position: center; filter: blur(14px) brightness(0.45); transform: scale(1.12); z-index: 0;">

                        <!-- Main Full Image -->
                        <img class="relative w-full h-full object-contain"
                             style="z-index: 1;"
                             src="{{ $sImg }}"
                             alt="Slider image">

                        <span
                            class="absolute top-2 right-2 px-2 py-0.5 text-[9px] font-bold rounded z-10 shadow-xs {{ $slide->status ? 'bg-emerald-500 text-white' : 'bg-slate-700 text-white' }}">
                            {{ $slide->status ? __('messages.active') : __('messages.inactive') }}
                        </span>
                    </div>
                    <div class="p-3.5 space-y-2.5">
                        <div class="flex items-center justify-between text-[9.5px] text-slate-400 font-bold">
                            <span>{{ __('messages.priority') }}: {{ $slide->display_order }}</span>
                            @if($slide->button_text)
                                <span class="text-primary-600 truncate max-w-[120px]">Btn: {{ $slide->button_text }}</span>
                            @endif
                        </div>
                        <div class="flex gap-2">
                            <button type="button" @click="openEdit({
                                        id: {{ $slide->id }},
                                        image_url: '{{ $sImg }}',
                                        title: {{ json_encode($slide->title) }},
                                        subtitle: {{ json_encode($slide->subtitle) }},
                                        button_text: {{ json_encode($slide->button_text) }},
                                        button_link: {{ json_encode($slide->button_link) }},
                                        status: {{ $slide->status ? 1 : 0 }},
                                        display_order: {{ $slide->display_order }},
                                        update_url: '{{ route('admin.content.sliders.update', $slide->id) }}'
                                    })"
                                class="flex-1 flex items-center justify-center gap-1 py-1.5 bg-primary-50 hover:bg-primary-100 text-primary-600 font-bold text-[10px] rounded-lg transition-colors cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                {{ __('messages.edit') }}
                            </button>
                            <button type="button"
                                @click="$dispatch('confirm-delete', { action: '{{ route('admin.content.sliders.destroy', $slide->id) }}', message: '{{ __('messages.delete_confirm_slider') }}' })"
                                class="flex-1 flex items-center justify-center gap-1 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold text-[10px] rounded-lg transition-colors cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                {{ __('messages.delete') }}
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div
                    class="col-span-full text-center py-16 text-slate-400 bg-white rounded-3xl border border-slate-100 shadow-sm space-y-3">
                    <span class="text-3xl">🖼️</span>
                    <p class="text-xs font-medium">{{ __('messages.no_sliders_yet') }}</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($sliders->hasPages())
            <div class="mt-4 bg-white border border-slate-100 rounded-2xl p-4 shadow-sm">
                {{ $sliders->links() }}
            </div>
        @endif

        <!-- ============ ADD MODAL ============ -->
        <div x-show="showAddModal"
            x-data="{ addImagePreview: null }"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-transition
            x-cloak>
            <div @click.away="showAddModal = false; addImagePreview = null"
                class="bg-white rounded-2xl p-4 border border-slate-100 shadow-2xl max-w-sm w-full space-y-3 relative max-h-[90vh] overflow-y-auto">
                <button @click="showAddModal = false; addImagePreview = null"
                    class="absolute top-3 right-3 w-6 h-6 flex items-center justify-center rounded-full bg-slate-50 hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <h3 class="text-xs font-bold text-slate-900 pr-6">{{ __('messages.add_slider_modal_title') }}</h3>
                <form method="POST" action="{{ route('admin.content.sliders.store') }}" enctype="multipart/form-data"
                    class="space-y-3">
                    @csrf
                    <div class="space-y-1.5">
                        <label
                            class="text-[10px] font-bold text-slate-400 uppercase tracking-wide flex items-center justify-between">
                            <span>{{ __('messages.banner_image_label') }}</span>
                            <span x-show="addImagePreview" class="text-emerald-600 font-bold text-[9px]">{{ __('messages.preview') }}</span>
                        </label>

                        <!-- Live Preview for Add Modal -->
                        <div x-show="addImagePreview" class="h-28 w-full rounded-xl overflow-hidden bg-slate-950 border border-slate-200/80 shadow-xs flex items-center justify-center relative">
                            <img :src="addImagePreview" alt="" aria-hidden="true" class="absolute inset-0 w-full h-full pointer-events-none select-none" style="object-fit: cover; object-position: center; filter: blur(14px) brightness(0.45); transform: scale(1.12); z-index: 0;">
                            <img :src="addImagePreview" alt="Preview" class="relative w-full h-full object-contain" style="z-index: 1;">
                        </div>

                        <input type="file" name="image" required accept="image/*"
                            @change="const file = $event.target.files[0]; if(file) { addImagePreview = URL.createObjectURL(file); }"
                            class="text-xs text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-[10px] file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 border border-slate-200 rounded-lg p-1 w-full bg-slate-50">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-0.5">
                            <label
                                class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">{{ __('messages.button_text') }}</label>
                            <input type="text" name="button_text" placeholder="View Details"
                                class="w-full text-xs px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg">
                        </div>
                        <div class="space-y-0.5">
                            <label
                                class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">{{ __('messages.button_link') }}</label>
                            <input type="text" name="button_link" placeholder="/events"
                                class="w-full text-xs px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-0.5">
                            <label
                                class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">{{ __('messages.status') }}</label>
                            <select name="status" required
                                class="w-full text-xs px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg">
                                <option value="1">{{ __('messages.active') }}</option>
                                <option value="0">{{ __('messages.inactive') }}</option>
                            </select>
                        </div>
                        <div class="space-y-0.5">
                            <label
                                class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">{{ __('messages.order') }}</label>
                            <input type="number" name="display_order" value="0" required
                                class="w-full text-xs px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg">
                        </div>
                    </div>
                    <div class="pt-2 border-t border-slate-100 flex justify-end gap-2">
                        <button type="button" @click="showAddModal = false; addImagePreview = null"
                            class="px-3 py-1.5 border border-slate-200 hover:bg-slate-50 text-slate-600 font-bold text-xs rounded-lg transition-colors">{{ __('messages.cancel') }}</button>
                        <button type="submit"
                            class="px-4 py-1.5 bg-primary-500 hover:bg-primary-600 text-white font-bold text-xs rounded-lg shadow-sm">{{ __('messages.add_slider_submit') }}</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ============ EDIT MODAL ============ -->
        <div x-show="showEditModal"
            x-data="{ newEditPreview: null }"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-transition
            x-cloak>
            <div @click.away="showEditModal = false; newEditPreview = null"
                class="bg-white rounded-2xl p-4 border border-slate-100 shadow-2xl max-w-sm w-full space-y-3 relative max-h-[90vh] overflow-y-auto">
                <button @click="showEditModal = false; newEditPreview = null"
                    class="absolute top-3 right-3 w-6 h-6 flex items-center justify-center rounded-full bg-slate-50 hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <h3 class="text-xs font-bold text-slate-900 pr-6">{{ __('messages.edit_slider_modal_title') }}</h3>
                <form method="POST" :action="editSlider.update_url" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    @method('PUT')
                    
                    <!-- Prefilled Image Preview & Live File Change Preview -->
                    <div class="space-y-1.5">
                        <label
                            class="text-[10px] font-bold text-slate-400 uppercase tracking-wide flex items-center justify-between">
                            <span>{{ __('messages.replace_image_label') }}</span>
                            <span x-show="newEditPreview" class="text-emerald-600 font-bold text-[9px]">{{ __('messages.preview') }} ({{ __('messages.new') ?? 'New' }})</span>
                            <span x-show="!newEditPreview && editSlider.image_url" class="text-primary-600 font-bold text-[9px]">{{ __('messages.current_image') }}</span>
                        </label>

                        <!-- Preview Container -->
                        <div x-show="newEditPreview || editSlider.image_url" 
                             class="h-28 w-full rounded-xl overflow-hidden bg-slate-950 border border-slate-200/80 shadow-xs flex items-center justify-center relative">
                            <!-- Blurred Backdrop -->
                            <img :src="newEditPreview || editSlider.image_url" 
                                 alt="" 
                                 aria-hidden="true" 
                                 class="absolute inset-0 w-full h-full pointer-events-none select-none" 
                                 style="object-fit: cover; object-position: center; filter: blur(14px) brightness(0.45); transform: scale(1.12); z-index: 0;">

                            <!-- Main Image -->
                            <img :src="newEditPreview || editSlider.image_url" 
                                 alt="Slider Preview" 
                                 class="relative w-full h-full object-contain" 
                                 style="z-index: 1;">
                        </div>

                        <input type="file" name="image" accept="image/*"
                            @change="const file = $event.target.files[0]; if(file) { newEditPreview = URL.createObjectURL(file); }"
                            class="text-xs text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-[10px] file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 border border-slate-200 rounded-lg p-1 w-full bg-slate-50">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-0.5">
                            <label
                                class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">{{ __('messages.button_text') }}</label>
                            <input type="text" name="button_text" :value="editSlider.button_text" placeholder="View Details"
                                class="w-full text-xs px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg">
                        </div>
                        <div class="space-y-0.5">
                            <label
                                class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">{{ __('messages.button_link') }}</label>
                            <input type="text" name="button_link" :value="editSlider.button_link" placeholder="/events"
                                class="w-full text-xs px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-0.5">
                            <label
                                class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">{{ __('messages.status') }}</label>
                            <select name="status" required
                                class="w-full text-xs px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg">
                                <option value="1" :selected="editSlider.status == 1">{{ __('messages.active') }}</option>
                                <option value="0" :selected="editSlider.status == 0">{{ __('messages.inactive') }}</option>
                            </select>
                        </div>
                        <div class="space-y-0.5">
                            <label
                                class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">{{ __('messages.order') }}</label>
                            <input type="number" name="display_order" :value="editSlider.display_order" required
                                class="w-full text-xs px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg">
                        </div>
                    </div>
                    <div class="pt-2 border-t border-slate-100 flex justify-end gap-2">
                        <button type="button" @click="showEditModal = false; newEditPreview = null"
                            class="px-3 py-1.5 border border-slate-200 hover:bg-slate-50 text-slate-600 font-bold text-xs rounded-lg transition-colors">{{ __('messages.cancel') }}</button>
                        <button type="submit"
                            class="px-4 py-1.5 bg-primary-500 hover:bg-primary-600 text-white font-bold text-xs rounded-lg shadow-sm">{{ __('messages.save_changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection