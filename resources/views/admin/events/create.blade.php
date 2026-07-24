@extends('layouts.admin')

@section('page_title', __('messages.plan_community_event'))

@section('content')
    <div class="max-w-6xl bg-white border border-slate-100 rounded-xl pt-2 pb-6 px-6 shadow-sm">

        <!-- Errors -->
        @if ($errors->any())
            <div class="mb-4 p-3 bg-rose-50 border border-rose-100 text-rose-800 rounded-xl">
                <ul class="list-disc pl-4 text-xs font-semibold space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data" 
              class="space-y-3" 
              x-data="{ eventType: '{{ old('event_type', 'normal') }}', bannerPreview: null }">
            @csrf

            <input type="hidden" name="max_participants" value="0">

            <!-- Title, Event Type & Dynamic Register Form Checkbox -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                <!-- Event Title -->
                <div class="md:col-span-6 space-y-0.5">
                    <label class="text-[10px] font-bold text-slate-500 uppercase">{{ __('messages.event_title') }}</label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                        class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                </div>

                <!-- Event Type -->
                <div :class="eventType !== 'normal' ? 'md:col-span-3' : 'md:col-span-6'" class="space-y-0.5 transition-all duration-200">
                    <label class="text-[10px] font-bold text-slate-500 uppercase">{{ __('messages.event_type') }}</label>
                    <select name="event_type" x-model="eventType" required
                        class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                        <option value="normal" {{ old('event_type') == 'normal' ? 'selected' : '' }}>1. {{ __('messages.normal_event') }}</option>
                        <option value="inam_vitaran" {{ old('event_type') == 'inam_vitaran' ? 'selected' : '' }}>2. {{ __('messages.inam_vitaran_event') }}</option>
                        <option value="yuva_melo" {{ old('event_type') == 'yuva_melo' ? 'selected' : '' }}>3. {{ __('messages.yuva_melo_event') }}</option>
                    </select>
                </div>

                <!-- Registration Checkbox -->
                <div x-show="eventType !== 'normal'" x-cloak class="md:col-span-3 space-y-0.5">
                    <label class="text-[10px] font-bold text-slate-500 uppercase block">{{ __('messages.register_form_label') }}</label>
                    <label class="flex items-center gap-2 px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg cursor-pointer hover:bg-slate-100/80 transition-colors h-[34px]">
                        <input type="hidden" name="has_registration_form" value="0">
                        <input type="checkbox" name="has_registration_form" value="1" 
                            :disabled="eventType === 'normal'"
                            {{ old('has_registration_form', '1') == '1' ? 'checked' : '' }}
                            class="w-4 h-4 text-primary-600 bg-white border-slate-300 rounded focus:ring-primary-500 cursor-pointer shrink-0">
                        <span class="text-[11px] font-bold text-slate-700 select-none truncate">{{ __('messages.enable_registration_form') }}</span>
                    </label>
                </div>
            </div>

            <!-- Date, Time, and Banner Image -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 items-center">
                <div class="space-y-0.5">
                    <label class="text-[10px] font-bold text-slate-500 uppercase">{{ __('messages.date') }}</label>
                    <input type="date" name="date" value="{{ old('date') }}"
                           @click="$event.target.showPicker?.()"
                           @focus="$event.target.showPicker?.()"
                           required
                           class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                </div>

                <div class="space-y-0.5">
                    <label class="text-[10px] font-bold text-slate-500 uppercase">{{ __('messages.time') ?? 'તારીખ / સમય' }}</label>
                    <input type="time" name="time" value="{{ old('time') }}"
                           @click="$event.target.showPicker?.()"
                           @focus="$event.target.showPicker?.()"
                           required
                           class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                </div>

                <div class="space-y-0.5">
                    <label class="text-[10px] font-bold text-slate-500 uppercase">{{ __('messages.banner_image') }}</label>
                    <div class="flex items-center gap-2">
                        <input type="file" name="banner" required
                            @change="if ($event.target.files.length) { bannerPreview = URL.createObjectURL($event.target.files[0]) }"
                            class="text-[10px] block w-full text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded file:border-0 file:text-[10px] file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 cursor-pointer">
                        <template x-if="bannerPreview">
                            <div class="relative w-14 h-8 rounded border border-slate-200 bg-slate-900 shrink-0 overflow-hidden shadow-xs" title="Banner Preview">
                                <img :src="bannerPreview" class="w-full h-full object-cover">
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div class="space-y-0.5">
                <label class="text-[10px] font-bold text-slate-500 uppercase">{{ __('messages.venue_hall_address') }}</label>
                <input type="text" name="venue" value="{{ old('venue') }}" required
                    class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="space-y-0.5">
                    <label class="text-[10px] font-bold text-slate-500 uppercase">{{ __('messages.event_pass_fee') }}</label>
                    <input type="number" name="pass_fee" step="1" min="0" value="{{ old('pass_fee', '0') }}" placeholder="0 for Free Event"
                        class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                </div>

                <div class="space-y-0.5">
                    <label class="text-[10px] font-bold text-slate-500 uppercase">{{ __('messages.status') }}</label>
                    <select name="status" required
                        class="w-full text-xs font-semibold px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:border-primary-500">
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft (Private)</option>
                        <option value="published" {{ old('status', 'published') == 'published' ? 'selected' : '' }}>Published (Public)</option>
                        <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
            </div>

            <div class="space-y-1">
                <label class="text-[10px] font-bold text-slate-500 uppercase">{{ __('messages.event_description') }}</label>
                <input type="hidden" name="description" id="description_input" value="{{ old('description') }}">
                <div class="rounded-lg overflow-hidden border border-slate-200 shadow-xs">
                    <div id="quill_editor" class="bg-slate-50 min-h-[140px] text-xs font-medium">
                        {!! old('description') !!}
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    var editorEl = document.querySelector('#quill_editor');
                    var descInput = document.querySelector('#description_input');
                    if (editorEl && descInput && typeof Quill !== 'undefined') {
                        var quill = new Quill('#quill_editor', {
                            theme: 'snow',
                            placeholder: 'Write event description, guidelines, and rules with rich formatting...',
                            modules: {
                                toolbar: [
                                    [{ 'header': [1, 2, 3, false] }],
                                    ['bold', 'italic', 'underline', 'strike'],
                                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                                    [{ 'color': [] }, { 'background': [] }],
                                    ['clean']
                                ]
                            }
                        });

                        // Real-time sync on every keystroke
                        quill.on('text-change', function() {
                            var html = quill.root.innerHTML;
                            descInput.value = (html === '<p><br></p>') ? '' : html;
                        });

                        // Sync on form submission
                        var form = editorEl.closest('form');
                        if (form) {
                            form.addEventListener('submit', function() {
                                var html = quill.root.innerHTML;
                                descInput.value = (html === '<p><br></p>') ? '' : html;
                            });
                        }
                    }
                });
            </script>

            <div class="pt-3 border-t border-slate-100 flex justify-end items-center gap-2">
                <a href="{{ route('admin.events.index') }}"
                    class="px-3 py-1.5 border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs rounded-lg transition-colors">{{ __('messages.cancel') }}</a>
                <button type="submit"
                    class="px-4 py-1.5 bg-primary-500 hover:bg-primary-600 text-white font-bold text-xs rounded-lg shadow-sm transition-colors">
                    {{ __('messages.save_and_publish') }}
                </button>
            </div>
        </form>
    </div>
@endsection