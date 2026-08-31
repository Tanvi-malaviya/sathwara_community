@extends('layouts.admin')

@section('page_title', 'Event Gallery Management')

@section('content')
<div class="space-y-2" 
     x-data="{ 
         showUploadModal: {{ $errors->any() ? 'true' : 'false' }},
         lightbox: false,
         lightboxIndex: 0,
         galleryImages: [
             @foreach($photos as $photo)
                 {
                     src: '{{ $photo->url }}',
                     caption: '{{ addslashes($photo->caption ?? $event->title) }}',
                     isVideo: {{ $photo->isVideo() ? 'true' : 'false' }}
                 },
             @endforeach
         ],
         nextImage() {
             if (this.galleryImages.length > 0) {
                 this.lightboxIndex = (this.lightboxIndex + 1) % this.galleryImages.length;
             }
         },
         prevImage() {
             if (this.galleryImages.length > 0) {
                 this.lightboxIndex = (this.lightboxIndex - 1 + this.galleryImages.length) % this.galleryImages.length;
             }
         }
     }"
     @keydown.window.escape="lightbox = false"
     @keydown.window.right="if(lightbox) nextImage()"
     @keydown.window.left="if(lightbox) prevImage()">

    <div class="bg-white p-3 rounded-xl border border-slate-100 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h3 class="text-sm font-extrabold text-slate-900">{{ $event->title }}</h3>
            <p class="text-[10px] text-slate-400 font-bold mt-1 uppercase tracking-wider">Event media: {{ $photos->count() }} photos/videos uploaded</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.events.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900">&larr; Back to Events</a>
            <button @click="showUploadModal = true" class="px-3 py-1.5 bg-primary-500 hover:bg-primary-600 text-white font-bold text-[10px] rounded-lg transition-colors flex items-center gap-1 shadow-sm cursor-pointer">
                <span>+ Add Photo / Video</span>
            </button>
        </div>
    </div>

    <!-- Photo & Video list -->
    <div class="bg-white p-6 rounded-xl border border-slate-100 shadow-sm">
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
            @forelse($photos as $photo)
                @php $pUrl = $photo->url; $isVideo = $photo->isVideo(); @endphp
                <div class="bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-xs hover:shadow-md transition-shadow flex flex-col justify-between group">
                    <!-- Clickable Media Thumbnail -->
                    <div class="aspect-video w-full overflow-hidden bg-slate-950 relative flex items-center justify-center cursor-pointer"
                         @click="lightboxIndex = {{ $loop->index }}; lightbox = true">
                        @if($isVideo)
                            <video class="w-full h-full object-cover pointer-events-none" preload="metadata" muted playsinline>
                                <source src="{{ $pUrl }}">
                            </video>
                            <div class="absolute inset-0 flex items-center justify-center pointer-events-none" style="z-index: 2;">
                                <div class="w-10 h-10 rounded-full bg-slate-900/85 border border-white/30 text-white flex items-center justify-center shadow-lg backdrop-blur-xs group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5 fill-current ml-0.5 text-primary-400" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </div>
                            <span class="absolute top-2 left-2 z-10 px-2 py-0.5 rounded-md bg-slate-900/80 text-white text-[9px] font-black uppercase tracking-wider backdrop-blur-xs border border-white/20 flex items-center gap-1">
                                <svg class="w-2.5 h-2.5 fill-current text-primary-400" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                Video
                            </span>
                        @else
                            <!-- Blurred Backdrop -->
                            <img src="{{ $pUrl }}" 
                                 alt="" 
                                 aria-hidden="true"
                                 class="absolute inset-0 w-full h-full pointer-events-none select-none"
                                 style="object-fit: cover; object-position: center; filter: blur(14px) brightness(0.45); transform: scale(1.12); z-index: 0;">

                            <!-- Main Full Image -->
                            <img class="relative w-full h-full object-contain transition-transform duration-300 group-hover:scale-105" 
                                 style="z-index: 1;"
                                 src="{{ $pUrl }}" 
                                 alt="Gallery Image">
                        @endif

                        <!-- Hover Overlay -->
                        <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center" style="z-index: 10;">
                            <span class="text-white text-[10px] font-bold inline-flex items-center gap-1 bg-slate-900/80 px-2.5 py-1 rounded-full backdrop-blur-xs border border-white/20 shadow-sm">
                                @if($isVideo)
                                    <svg class="w-3 h-3 text-primary-400 fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    Play Video
                                @else
                                    <svg class="w-3 h-3 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/></svg>
                                    {{ __('messages.preview') }}
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="p-3 bg-white">
                        <button type="button" @click="$dispatch('confirm-delete', { action: '{{ route('admin.events.gallery.destroy', $photo->id) }}', message: 'Are you sure you want to delete this photo/video from the event gallery?' })" class="w-full py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold text-[10px] rounded-lg transition-colors cursor-pointer">
                            Delete Media
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 text-slate-400">
                    No photos or videos uploaded for this event.
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

    <!-- Add Image/Video Modal -->
    <template x-teleport="body">
        <div x-show="showUploadModal" 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/40 backdrop-blur-sm"
             x-transition
             x-cloak>
            <div class="bg-white rounded-xl max-w-md w-full p-4 border border-slate-100 shadow-xl space-y-3.5 max-h-[90vh] overflow-y-auto animate-in fade-in zoom-in duration-200"
                 @click.away="showUploadModal = false">
                
                <!-- Modal Header -->
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <h3 class="text-xs font-extrabold text-slate-950">Add Event Photo / Video</h3>
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

                <form method="POST" action="{{ route('admin.events.gallery.upload', $event->id) }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-400 uppercase">Select Photo / Video / ZIP (Max 100MB)</label>
                        <input type="file" name="images[]" multiple required accept=".jpg,.jpeg,.png,.webp,.gif,.zip,.mp4,.mov,.webm,.ogg,image/*,video/*"
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
    </template>

    <!-- Lightbox Preview Modal -->
    <template x-teleport="body">
        <div x-show="lightbox" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             style="position: fixed; inset: 0; z-index: 999999; background-color: rgba(15, 23, 42, 0.96); backdrop-filter: blur(12px);"
             class="flex flex-col items-center justify-between p-4 sm:p-6 select-none" 
             @click="lightbox = false"
             x-cloak>
            
            <!-- Close button (Top Right) -->
            <button @click="lightbox = false" 
                    style="position: absolute; top: 1.5rem; right: 1.5rem; z-index: 1000000;"
                    class="p-2.5 rounded-full bg-slate-800/90 hover:bg-rose-600 text-white border border-white/20 hover:border-rose-500 transition-all duration-200 cursor-pointer shadow-xl hover:scale-110 active:scale-95"
                    title="Close (Esc)">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <!-- Prev / Next Navigation Buttons -->
            <button x-show="galleryImages.length > 1" 
                    @click.stop="prevImage()" 
                    style="position: absolute; left: 1.5rem; top: 50%; transform: translateY(-50%); z-index: 1000000;"
                    class="p-3 rounded-full bg-slate-800/90 hover:bg-white text-white hover:text-slate-900 border border-white/20 hover:border-white transition-all duration-200 cursor-pointer shadow-xl hover:scale-110 active:scale-95"
                    title="Previous Image (Left Arrow)">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </button>

            <button x-show="galleryImages.length > 1" 
                    @click.stop="nextImage()" 
                    style="position: absolute; right: 1.5rem; top: 50%; transform: translateY(-50%); z-index: 1000000;"
                    class="p-3 rounded-full bg-slate-800/90 hover:bg-white text-white hover:text-slate-900 border border-white/20 hover:border-white transition-all duration-200 cursor-pointer shadow-xl hover:scale-110 active:scale-95"
                    title="Next Image (Right Arrow)">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </button>

            <!-- Main Centered Preview Media -->
            <div class="flex-1 flex items-center justify-center max-w-4xl w-full my-auto px-4 py-8" @click.stop>
                <div class="relative flex items-center justify-center bg-slate-900/60 p-2 sm:p-3 rounded-2xl border border-white/10 shadow-2xl overflow-hidden max-h-[70vh] max-w-[80vw]">
                    <template x-if="galleryImages[lightboxIndex]?.isVideo">
                        <video :src="galleryImages[lightboxIndex]?.src"
                               controls
                               autoplay
                               playsinline
                               class="rounded-xl shadow-2xl transition-all duration-300"
                               style="max-height: 60vh; max-width: 70vw; width: auto; height: auto; outline: none;">
                        </video>
                    </template>
                    <template x-if="!galleryImages[lightboxIndex]?.isVideo">
                        <img :src="galleryImages[lightboxIndex]?.src" 
                             :alt="galleryImages[lightboxIndex]?.caption || 'Event Photo'"
                             class="rounded-xl shadow-lg transition-all duration-300 select-none pointer-events-auto"
                             style="max-height: 58vh; max-width: 65vw; width: auto; height: auto; object-fit: contain; display: block;">
                    </template>
                </div>
            </div>

            <!-- Bottom Image Counter -->
            <div x-show="galleryImages.length > 0"
                 style="position: absolute; bottom: 1.5rem; left: 50%; transform: translateX(-50%); z-index: 1000000;"
                 class="px-4 py-1.5 rounded-full bg-slate-900/90 backdrop-blur-md border border-white/20 text-white font-bold text-xs tracking-wider shadow-xl flex items-center gap-1.5">
                <span class="text-primary-400 font-black" x-text="lightboxIndex + 1"></span>
                <span class="text-white/40">/</span>
                <span class="text-white/80" x-text="galleryImages.length"></span>
            </div>
        </div>
    </template>
</div>
@endsection
