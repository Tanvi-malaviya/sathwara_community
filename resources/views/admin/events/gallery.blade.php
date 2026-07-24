@extends('layouts.admin')

@section('page_title', 'Event Gallery Management')

@section('content')
<div class="space-y-2" x-data="{ showUploadModal: {{ $errors->any() ? 'true' : 'false' }} }">
    <div class="bg-white p-3 rounded-xl border border-slate-100 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h3 class="text-sm font-extrabold text-slate-900">{{ $event->title }}</h3>
            <p class="text-[10px] text-slate-400 font-bold mt-1 uppercase tracking-wider">Plan photos: {{ $photos->count() }} images uploaded</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.events.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900">&larr; Back to Events</a>
            <button @click="showUploadModal = true" class="px-3 py-1.5 bg-primary-500 hover:bg-primary-600 text-white font-bold text-[10px] rounded-lg transition-colors flex items-center gap-1 shadow-sm">
                <span>+ Add Image</span>
            </button>
        </div>
    </div>

    <!-- Photo list -->
    <div class="bg-white p-6 rounded-xl border border-slate-100 shadow-sm">
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
            @forelse($photos as $photo)
                <div class="bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-sm flex flex-col justify-between group">
                    <div class="aspect-video w-full overflow-hidden bg-slate-50 relative">
                        <img class="w-full h-full object-cover" 
                             src="{{ str_starts_with($photo->image_path, 'http') ? $photo->image_path : asset('storage/' . $photo->image_path) }}" 
                             alt="Gallery Image">
                    </div>
                    <div class="p-4">
                        <button type="button" @click="$dispatch('confirm-delete', { action: '{{ route('admin.gallery.destroy', $photo->id) }}', message: 'Are you sure you want to delete this photo from the event gallery?' })" class="w-full py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold text-[10px] rounded-lg transition-colors">
                            Delete Image
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 text-slate-400">
                    No photos uploaded for this event.
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($photos->hasPages())
            <div class="mt-6 border-t border-slate-100 pt-4">
                {{ $photos->links() }}
            </div>
        @endif
    </div>

    <!-- Add Image Modal -->
    <div x-show="showUploadModal" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/40 backdrop-blur-sm"
         x-transition
         x-cloak>
        <div class="bg-white rounded-xl max-w-md w-full p-4 border border-slate-100 shadow-xl space-y-3.5 max-h-[90vh] overflow-y-auto animate-in fade-in zoom-in duration-200"
             @click.away="showUploadModal = false">
            
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                <h3 class="text-xs font-extrabold text-slate-950">Add Event Image</h3>
                <button type="button" @click="showUploadModal = false" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Modal Body -->
            @if ($errors->any())
                <div class="p-3 bg-rose-50 text-rose-800 text-[11px] font-semibold rounded-xl">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.gallery.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" name="event_id" value="{{ $event->id }}">

                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-slate-400 uppercase">Select Image / ZIP (Max 50MB)</label>
                    <input type="file" name="images[]" multiple required accept=".jpg,.jpeg,.png,.webp,.gif,.zip"
                        @change="$el.name = ($el.files.length === 1 && $el.files[0].name.toLowerCase().endsWith('.zip')) ? 'image' : 'images[]'"
                        class="text-[10px] block w-full text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-[10px] file:font-semibold file:bg-primary-50 file:text-primary-700">
                </div>



                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="showUploadModal = false" class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs rounded-xl transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-primary-500 hover:bg-primary-600 text-white font-bold text-xs rounded-xl shadow-sm transition-colors">
                        Upload to Gallery
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
