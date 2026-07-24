@extends('layouts.admin')

@section('page_title', __('messages.general_photo_gallery'))

@section('content')
    <div x-data="{ showAddModal: @json($errors->any()) }">
        <!-- Header Actions & Search bar -->
        <!-- Header Actions -->
        <div class="flex justify-end items-center gap-2 bg-white p-3 rounded-xl border border-slate-100 shadow-sm mb-4">
            <a href="{{ route('admin.gallery.export', request()->all()) }}" 
               class="inline-flex items-center justify-center px-4 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-xs rounded-xl border border-emerald-200/60 shadow-xs transition-all shrink-0 whitespace-nowrap">
                📊 <span>Export Excel</span>
            </a>

            <button @click="showAddModal = true"
                class="inline-flex items-center justify-center px-4 py-2 bg-primary-500 hover:bg-primary-600 font-bold text-xs text-white rounded-xl shadow-md transition-all hover:scale-[1.02] active:scale-95 shrink-0 whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('messages.add_photo') }}
            </button>
        </div>

        <!-- Photos Grid List -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
            @forelse($photos as $photo)
                <div
                    class="bg-white border border-slate-100 rounded-xl overflow-hidden shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow">
                    <div class="aspect-square w-full overflow-hidden bg-slate-50 relative">
                        <img class="w-full h-full object-cover"
                            src="{{ str_starts_with($photo->image_path, 'http') ? $photo->image_path : asset('storage/' . $photo->image_path) }}"
                            alt="Gallery Image">
                    </div>
                    <div class="p-3">
                        <button type="button"
                            @click="$dispatch('confirm-delete', { action: '{{ route('admin.gallery.destroy', $photo->id) }}', message: '{{ __('messages.delete_confirm_gallery_photo') }}' })"
                            class="w-full py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold text-[10px] rounded-lg transition-colors">
                            {{ __('messages.delete_image') }}
                        </button>
                    </div>
                </div>
            @empty
                <div
                    class="col-span-full text-center py-16 text-slate-400 bg-white rounded-3xl border border-slate-100 shadow-sm space-y-3">
                    <span class="text-3xl">🖼️</span>
                    <p class="text-xs font-medium">{{ __('messages.no_photos_uploaded') }}</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($photos->hasPages())
            <div class="mt-4">
                {{ $photos->links() }}
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
                <h3 class="text-xs font-bold text-slate-900 pr-6">{{ __('messages.upload_general_photo') }}</h3>
                <form method="POST" action="{{ route('admin.gallery.store') }}" enctype="multipart/form-data"
                    class="space-y-3">
                    @csrf
                    @if ($errors->any())
                        <div class="p-3 bg-rose-50 border border-rose-100 text-rose-700 text-[10px] font-semibold rounded-xl">
                            <ul class="list-disc pl-4 space-y-0.5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="space-y-0.5">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">{{ __('messages.select_image_zip') }}</label>
                        <input type="file" name="images[]" multiple required accept=".jpg,.jpeg,.png,.webp,.gif,.zip"
                            @change="$el.name = ($el.files.length === 1 && $el.files[0].name.toLowerCase().endsWith('.zip')) ? 'image' : 'images[]'"
                            class="text-[10px] block w-full text-slate-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:font-semibold file:bg-primary-50 file:text-primary-700">
                    </div>



                    <div class="pt-2 border-t border-slate-100 flex justify-end gap-2">
                        <button type="button" @click="showAddModal = false"
                            class="px-3 py-1.5 border border-slate-200 hover:bg-slate-50 text-slate-600 font-bold text-xs rounded-lg transition-colors">{{ __('messages.cancel') }}</button>
                        <button type="submit"
                            class="px-4 py-1.5 bg-primary-500 hover:bg-primary-600 text-white font-bold text-xs rounded-lg shadow-sm">{{ __('messages.upload_photo') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection