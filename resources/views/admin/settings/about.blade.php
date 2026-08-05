@extends('layouts.admin')

@section('page_title', __('messages.about_us_configurations') ?? 'About Us Configurations')

@section('content')
<div class="w-full space-y-6">

    @if($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-100 text-rose-700 text-xs font-semibold rounded-xl">
            <ul class="list-disc pl-4 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- SECTION 1: ABOUT US CONTENT SECTIONS TABLE -->
    <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm space-y-4">
        <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-black text-slate-900 flex items-center gap-2 uppercase tracking-wider">
                    <span>📜</span>
                    <span>{{ __('messages.about_content_sections') }}</span>
                </h3>
                <p class="text-xs text-slate-500 font-medium mt-0.5">{{ __('messages.manage_about_sections') }}</p>
            </div>
        </div>

        <!-- Sections Table -->
        <div class="bg-white border border-slate-100 rounded-xl overflow-hidden shadow-2xs">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-[10px] font-extrabold uppercase text-slate-400 tracking-wider border-b border-slate-100">
                        <th class="py-3 px-4 w-44">{{ __('messages.section') }}</th>
                        <th class="py-3 px-4 w-44">{{ __('messages.title_en') }}</th>
                        <th class="py-3 px-4 w-44">{{ __('messages.title_gu') }}</th>
                        <th class="py-3 px-4">{{ __('messages.description_en') }}</th>
                        <th class="py-3 px-4">{{ __('messages.description_gu') }}</th>
                        <th class="py-3 px-4 text-right w-20">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-semibold text-slate-700">
                    <!-- 1. Mission -->
                    <tr class="hover:bg-slate-50/50">
                        <td class="py-3.5 px-4 font-black text-slate-900">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 text-xs border border-blue-100">
                                🎯 {{ __('messages.mission_section') }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 font-bold text-slate-900 leading-relaxed">
                            {{ $settings['about_mission_title_en'] ?? 'Empowering People' }}
                        </td>
                        <td class="py-3.5 px-4 font-bold text-slate-800 font-gujarati leading-relaxed">
                            {{ $settings['about_mission_title_gu'] ?? 'લોકોને સશક્ત બનાવવું' }}
                        </td>
                        <td class="py-3.5 px-4 text-slate-600 text-xs leading-relaxed" title="{{ $settings['about_mission_en'] ?? '' }}">
                            {{ Str::limit($settings['about_mission_en'] ?? '-', 75) }}
                        </td>
                        <td class="py-3.5 px-4 text-slate-600 font-gujarati text-xs leading-relaxed" title="{{ $settings['about_mission_gu'] ?? '' }}">
                            {{ !empty($settings['about_mission_gu']) ? Str::limit($settings['about_mission_gu'], 75) : '-' }}
                        </td>
                        <td class="py-3.5 px-4 text-right">
                            <button type="button" @click="$dispatch('open-edit-section-modal', {
                                key: 'mission',
                                name: '🎯 {{ __('messages.mission_section') }}',
                                title_en_name: 'about_mission_title_en',
                                title_gu_name: 'about_mission_title_gu',
                                desc_en_name: 'about_mission_en',
                                desc_gu_name: 'about_mission_gu',
                                title_en: {{ json_encode($settings['about_mission_title_en'] ?? '') }},
                                title_gu: {{ json_encode($settings['about_mission_title_gu'] ?? '') }},
                                desc_en: {{ json_encode($settings['about_mission_en'] ?? '') }},
                                desc_gu: {{ json_encode($settings['about_mission_gu'] ?? '') }},
                                is_html: false
                            })" class="flex items-center justify-center w-7 h-7 ml-auto rounded-lg bg-primary-50 text-primary-600 hover:bg-primary-100 transition-colors cursor-pointer" title="{{ __('messages.edit') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                        </td>
                    </tr>

                    <!-- 2. Vision -->
                    <tr class="hover:bg-slate-50/50">
                        <td class="py-3.5 px-4 font-black text-slate-900">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-amber-50 text-amber-700 text-xs border border-amber-100">
                                🌟 {{ __('messages.vision_section') }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 font-bold text-slate-900 leading-relaxed">
                            {{ $settings['about_vision_title_en'] ?? 'Future Prosperity' }}
                        </td>
                        <td class="py-3.5 px-4 font-bold text-slate-800 font-gujarati leading-relaxed">
                            {{ $settings['about_vision_title_gu'] ?? 'ભવિષ્યની સમૃદ્ધિ' }}
                        </td>
                        <td class="py-3.5 px-4 text-slate-600 text-xs leading-relaxed" title="{{ $settings['about_vision_en'] ?? '' }}">
                            {{ Str::limit($settings['about_vision_en'] ?? '-', 75) }}
                        </td>
                        <td class="py-3.5 px-4 text-slate-600 font-gujarati text-xs leading-relaxed" title="{{ $settings['about_vision_gu'] ?? '' }}">
                            {{ !empty($settings['about_vision_gu']) ? Str::limit($settings['about_vision_gu'], 75) : '-' }}
                        </td>
                        <td class="py-3.5 px-4 text-right">
                            <button type="button" @click="$dispatch('open-edit-section-modal', {
                                key: 'vision',
                                name: '🌟 {{ __('messages.vision_section') }}',
                                title_en_name: 'about_vision_title_en',
                                title_gu_name: 'about_vision_title_gu',
                                desc_en_name: 'about_vision_en',
                                desc_gu_name: 'about_vision_gu',
                                title_en: {{ json_encode($settings['about_vision_title_en'] ?? '') }},
                                title_gu: {{ json_encode($settings['about_vision_title_gu'] ?? '') }},
                                desc_en: {{ json_encode($settings['about_vision_en'] ?? '') }},
                                desc_gu: {{ json_encode($settings['about_vision_gu'] ?? '') }},
                                is_html: false
                            })" class="flex items-center justify-center w-7 h-7 ml-auto rounded-lg bg-primary-50 text-primary-600 hover:bg-primary-100 transition-colors cursor-pointer" title="{{ __('messages.edit') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                        </td>
                    </tr>

                    <!-- 3. Objectives -->
                    <tr class="hover:bg-slate-50/50">
                        <td class="py-3.5 px-4 font-black text-slate-900">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-purple-50 text-purple-700 text-xs border border-purple-100">
                                📌 {{ __('messages.objectives_section') }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 font-bold text-slate-900 leading-relaxed">
                            {{ $settings['about_objectives_title_en'] ?? 'Strategic Goals' }}
                        </td>
                        <td class="py-3.5 px-4 font-bold text-slate-800 font-gujarati leading-relaxed">
                            {{ $settings['about_objectives_title_gu'] ?? 'વ્યૂહાત્મક લક્ષ્યો' }}
                        </td>
                        <td class="py-3.5 px-4 text-slate-600 text-xs leading-relaxed font-mono" title="{{ strip_tags($settings['about_objectives_en'] ?? '') }}">
                            {{ Str::limit(strip_tags($settings['about_objectives_en'] ?? '-'), 75) }}
                        </td>
                        <td class="py-3.5 px-4 text-slate-600 font-gujarati text-xs leading-relaxed font-mono" title="{{ strip_tags($settings['about_objectives_gu'] ?? '') }}">
                            {{ !empty($settings['about_objectives_gu']) ? Str::limit(strip_tags($settings['about_objectives_gu']), 75) : '-' }}
                        </td>
                        <td class="py-3.5 px-4 text-right">
                            <button type="button" @click="$dispatch('open-edit-section-modal', {
                                key: 'objectives',
                                name: '📌 {{ __('messages.objectives_section') }}',
                                title_en_name: 'about_objectives_title_en',
                                title_gu_name: 'about_objectives_title_gu',
                                desc_en_name: 'about_objectives_en',
                                desc_gu_name: 'about_objectives_gu',
                                title_en: {{ json_encode($settings['about_objectives_title_en'] ?? '') }},
                                title_gu: {{ json_encode($settings['about_objectives_title_gu'] ?? '') }},
                                desc_en: {{ json_encode($settings['about_objectives_en'] ?? '') }},
                                desc_gu: {{ json_encode($settings['about_objectives_gu'] ?? '') }},
                                is_html: true
                            })" class="flex items-center justify-center w-7 h-7 ml-auto rounded-lg bg-primary-50 text-primary-600 hover:bg-primary-100 transition-colors cursor-pointer" title="{{ __('messages.edit') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                        </td>
                    </tr>

                    <!-- 4. History -->
                    <tr class="hover:bg-slate-50/50">
                        <td class="py-3.5 px-4 font-black text-slate-900">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-xs border border-emerald-100">
                                📜 {{ __('messages.history_section') }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 font-bold text-slate-900 leading-relaxed">
                            {{ $settings['about_history_title_en'] ?? 'Heritage & Journey' }}
                        </td>
                        <td class="py-3.5 px-4 font-bold text-slate-800 font-gujarati leading-relaxed">
                            {{ $settings['about_history_title_gu'] ?? 'વારસો અને યાત્રા' }}
                        </td>
                        <td class="py-3.5 px-4 text-slate-600 text-xs leading-relaxed" title="{{ $settings['about_history_en'] ?? '' }}">
                            {{ Str::limit($settings['about_history_en'] ?? '-', 75) }}
                        </td>
                        <td class="py-3.5 px-4 text-slate-600 font-gujarati text-xs leading-relaxed" title="{{ $settings['about_history_gu'] ?? '' }}">
                            {{ !empty($settings['about_history_gu']) ? Str::limit($settings['about_history_gu'], 75) : '-' }}
                        </td>
                        <td class="py-3.5 px-4 text-right">
                            <button type="button" @click="$dispatch('open-edit-section-modal', {
                                key: 'history',
                                name: '📜 {{ __('messages.history_section') }}',
                                title_en_name: 'about_history_title_en',
                                title_gu_name: 'about_history_title_gu',
                                desc_en_name: 'about_history_en',
                                desc_gu_name: 'about_history_gu',
                                title_en: {{ json_encode($settings['about_history_title_en'] ?? '') }},
                                title_gu: {{ json_encode($settings['about_history_title_gu'] ?? '') }},
                                desc_en: {{ json_encode($settings['about_history_en'] ?? '') }},
                                desc_gu: {{ json_encode($settings['about_history_gu'] ?? '') }},
                                is_html: false
                            })" class="flex items-center justify-center w-7 h-7 ml-auto rounded-lg bg-primary-50 text-primary-600 hover:bg-primary-100 transition-colors cursor-pointer" title="{{ __('messages.edit') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- SECTION 2: MILESTONES TIMELINE MANAGEMENT TABLE -->
    <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
            <div>
                <h3 class="text-sm font-black text-slate-900 flex items-center gap-2 uppercase tracking-wider">
                    <span>🚀</span>
                    <span>{{ __('messages.milestone_timeline') }}</span>
                </h3>
                <p class="text-xs text-slate-500 font-medium mt-0.5">{{ __('messages.milestones_subtitle') }}</p>
            </div>
            <button type="button" @click="$dispatch('open-add-milestone-modal')"
                class="inline-flex items-center justify-center px-4 py-2 bg-primary-500 hover:bg-primary-600 font-bold text-xs text-white rounded-xl shadow-sm transition-all hover:scale-[1.02] active:scale-95 whitespace-nowrap cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('messages.add_milestone') }}
            </button>
        </div>

        <!-- Milestones Table -->
        <div class="bg-white border border-slate-100 rounded-xl overflow-hidden shadow-2xs">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-[10px] font-extrabold uppercase text-slate-400 tracking-wider border-b border-slate-100">
                        <th class="py-3 px-4 w-20 text-center">{{ __('messages.year') }}</th>
                        <th class="py-3 px-4 w-44">{{ __('messages.title_en') }}</th>
                        <th class="py-3 px-4 w-44">{{ __('messages.title_gu') }}</th>
                        <th class="py-3 px-4">{{ __('messages.description_en') }}</th>
                        <th class="py-3 px-4">{{ __('messages.description_gu') }}</th>
                        <th class="py-3 px-4 text-right w-24">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-semibold text-slate-700">
                    @forelse($timelines as $time)
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-block bg-primary-50 text-primary-700 px-2 py-0.5 rounded text-[10px] font-extrabold border border-primary-100">
                                    {{ $time->year }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-900 font-bold leading-relaxed">{{ $time->title }}</td>
                            <td class="py-3.5 px-4 text-slate-800 font-bold font-gujarati leading-relaxed">{{ $time->title_gu ?? '-' }}</td>
                            <td class="py-3.5 px-4 text-slate-600 leading-relaxed text-xs" title="{{ $time->description }}">
                                {{ Str::limit($time->description, 80) }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-600 font-gujarati leading-relaxed text-xs" title="{{ $time->description_gu }}">
                                {{ $time->description_gu ? Str::limit($time->description_gu, 80) : '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex justify-end items-center space-x-1.5">
                                    {{-- Edit --}}
                                    <button type="button" @click="$dispatch('open-edit-milestone-modal', {
                                                                    id: {{ $time->id }},
                                                                    year: {{ json_encode($time->year) }},
                                                                    title: {{ json_encode($time->title) }},
                                                                    title_gu: {{ json_encode($time->title_gu) }},
                                                                    display_order: {{ $time->display_order }},
                                                                    description: {{ json_encode($time->description) }},
                                                                    description_gu: {{ json_encode($time->description_gu) }},
                                                                    update_url: '{{ route('admin.content.timelines.update', $time->id) }}'
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
                                        @click="$dispatch('confirm-delete', { action: '{{ route('admin.content.timelines.destroy', $time->id) }}', message: '{{ __('messages.delete_confirm_timeline', ['name' => addslashes($time->title)]) }}' })"
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
                            <td colspan="6" class="py-6 text-center text-slate-400">{{ __('messages.no_milestones_yet') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('modals')
    <!-- ============ EDIT ABOUT US SECTION MODAL ============ -->
    <div x-data="{ open: false, editSection: {} }"
        @open-edit-section-modal.window="editSection = $event.detail; open = true"
        x-show="open"
        class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md"
        x-transition x-cloak>
        <div @click.away="open = false"
            class="bg-white rounded-2xl p-6 border border-slate-100 shadow-2xl max-w-2xl w-full space-y-4 relative max-h-[90vh] overflow-y-auto">
            <button @click="open = false"
                class="absolute top-4 right-4 w-7 h-7 flex items-center justify-center rounded-full bg-slate-50 hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition-colors cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider pb-2 border-b border-slate-100 flex items-center gap-2">
                <span>✏️</span>
                <span x-text="'{{ __('messages.edit') }} ' + editSection.name"></span>
            </h3>
            <form method="POST" action="{{ route('admin.settings.about.update') }}" class="space-y-4">
                @csrf

                <!-- Side-by-Side Titles -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-slate-700">{{ __('messages.subtitle_en') }} <span class="text-rose-500">*</span></label>
                        <input type="text" :name="editSection.title_en_name" x-model="editSection.title_en" required
                            placeholder="Heading in English"
                            class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 focus:outline-none transition-all">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-slate-700">{{ __('messages.subtitle_gu') }}</label>
                        <input type="text" :name="editSection.title_gu_name" x-model="editSection.title_gu"
                            placeholder="સબ-ટાઇટલ ગુજરાતીમાં"
                            class="w-full text-xs font-semibold font-gujarati px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 focus:outline-none transition-all">
                    </div>
                </div>

                <!-- Side-by-Side Descriptions -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-slate-700">
                            {{ __('messages.description_en') }} <span class="text-rose-500">*</span>
                            <template x-if="editSection.is_html">
                                <span class="text-[10px] text-purple-600 font-bold ml-1">(HTML Bullet points e.g. &lt;li&gt;...&lt;/li&gt;)</span>
                            </template>
                        </label>
                        <textarea :name="editSection.desc_en_name" rows="5" required x-model="editSection.desc_en"
                            placeholder="Enter description in English..."
                            :class="editSection.is_html ? 'font-mono' : ''"
                            class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 focus:outline-none transition-all leading-relaxed"></textarea>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-slate-700">
                            {{ __('messages.description_gu') }}
                            <template x-if="editSection.is_html">
                                <span class="text-[10px] text-purple-600 font-bold ml-1">(HTML Bullet points e.g. &lt;li&gt;...&lt;/li&gt;)</span>
                            </template>
                        </label>
                        <textarea :name="editSection.desc_gu_name" rows="5" x-model="editSection.desc_gu"
                            placeholder="ગુજરાતીમાં વિગત લખો..."
                            :class="editSection.is_html ? 'font-mono' : ''"
                            class="w-full text-xs font-semibold font-gujarati px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 focus:outline-none transition-all leading-relaxed"></textarea>
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" @click="open = false"
                        class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs rounded-xl transition-colors cursor-pointer">{{ __('messages.cancel') }}</button>
                    <button type="submit"
                        class="px-5 py-2 bg-primary-500 hover:bg-primary-600 text-white font-extrabold text-xs rounded-xl shadow-xs transition-colors cursor-pointer">
                        {{ __('messages.save_changes') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============ ADD MILESTONE MODAL ============ -->
    <div x-data="{ open: false }"
        @open-add-milestone-modal.window="open = true"
        x-show="open"
        class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md"
        x-transition x-cloak>
        <div @click.away="open = false"
            class="bg-white rounded-2xl p-6 border border-slate-100 shadow-2xl max-w-2xl w-full space-y-4 relative max-h-[90vh] overflow-y-auto">
            <button @click="open = false"
                class="absolute top-4 right-4 w-7 h-7 flex items-center justify-center rounded-full bg-slate-50 hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition-colors cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider pb-2 border-b border-slate-100">
                {{ __('messages.new_milestone') }}
            </h3>
            <form method="POST" action="{{ route('admin.content.timelines.store') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-500 uppercase">{{ __('messages.year') }} <span class="text-rose-500">*</span></label>
                        <input type="text" name="year" value="{{ old('year') }}" placeholder="e.g. 2026" required
                            class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 focus:outline-none transition-all">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-500 uppercase">{{ __('messages.display_order') }}</label>
                        <input type="number" name="display_order" value="{{ old('display_order', 0) }}" required
                            class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 focus:outline-none transition-all">
                    </div>
                </div>

                <!-- Side-by-Side Titles -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-slate-700">{{ __('messages.title_en') }} <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                            placeholder="e.g. Community Center Inauguration"
                            class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 focus:outline-none transition-all">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-slate-700">{{ __('messages.title_gu') }}</label>
                        <input type="text" name="title_gu" value="{{ old('title_gu') }}"
                            placeholder="શ્રેયસિદ્ધિનું શીર્ષક ગુજરાતીમાં"
                            class="w-full text-xs font-semibold font-gujarati px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 focus:outline-none transition-all">
                    </div>
                </div>

                <!-- Side-by-Side Descriptions -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-slate-700">{{ __('messages.description_en') }} <span class="text-rose-500">*</span></label>
                        <textarea name="description" rows="4" required
                            placeholder="Brief description in English..."
                            class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 focus:outline-none transition-all leading-relaxed">{{ old('description') }}</textarea>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-slate-700">{{ __('messages.description_gu') }}</label>
                        <textarea name="description_gu" rows="4"
                            placeholder="વિગત ગુજરાતીમાં લખો..."
                            class="w-full text-xs font-semibold font-gujarati px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 focus:outline-none transition-all leading-relaxed">{{ old('description_gu') }}</textarea>
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" @click="open = false"
                        class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs rounded-xl transition-colors cursor-pointer">{{ __('messages.cancel') }}</button>
                    <button type="submit"
                        class="px-5 py-2 bg-primary-500 hover:bg-primary-600 text-white font-extrabold text-xs rounded-xl shadow-xs transition-colors cursor-pointer">
                        {{ __('messages.add_milestone_submit') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============ EDIT MILESTONE MODAL ============ -->
    <div x-data="{ open: false, editMilestone: {} }"
        @open-edit-milestone-modal.window="editMilestone = $event.detail; open = true"
        x-show="open"
        class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md"
        x-transition x-cloak>
        <div @click.away="open = false"
            class="bg-white rounded-2xl p-6 border border-slate-100 shadow-2xl max-w-2xl w-full space-y-4 relative max-h-[90vh] overflow-y-auto">
            <button @click="open = false"
                class="absolute top-4 right-4 w-7 h-7 flex items-center justify-center rounded-full bg-slate-50 hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition-colors cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider pb-2 border-b border-slate-100">
                {{ __('messages.edit_milestone') }}
            </h3>
            <form method="POST" :action="editMilestone.update_url" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-500 uppercase">{{ __('messages.year') }} <span class="text-rose-500">*</span></label>
                        <input type="text" name="year" :value="editMilestone.year" required
                            class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 focus:outline-none transition-all">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-500 uppercase">{{ __('messages.display_order') }}</label>
                        <input type="number" name="display_order" :value="editMilestone.display_order" required
                            class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 focus:outline-none transition-all">
                    </div>
                </div>

                <!-- Side-by-Side Titles in Edit Modal -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-slate-700">{{ __('messages.title_en') }} <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" :value="editMilestone.title" required
                            placeholder="e.g. Community Center Inauguration"
                            class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 focus:outline-none transition-all">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-slate-700">{{ __('messages.title_gu') }}</label>
                        <input type="text" name="title_gu" x-model="editMilestone.title_gu"
                            placeholder="શ્રેયસિદ્ધિનું શીર્ષક ગુજરાતીમાં"
                            class="w-full text-xs font-semibold font-gujarati px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 focus:outline-none transition-all">
                    </div>
                </div>

                <!-- Side-by-Side Descriptions in Edit Modal -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-slate-700">{{ __('messages.description_en') }} <span class="text-rose-500">*</span></label>
                        <textarea name="description" rows="4" required x-model="editMilestone.description"
                            placeholder="Brief description in English..."
                            class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 focus:outline-none transition-all leading-relaxed"></textarea>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-slate-700">{{ __('messages.description_gu') }}</label>
                        <textarea name="description_gu" rows="4" x-model="editMilestone.description_gu"
                            placeholder="વિગત ગુજરાતીમાં લખો..."
                            class="w-full text-xs font-semibold font-gujarati px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-primary-500 focus:outline-none transition-all leading-relaxed"></textarea>
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button" @click="open = false"
                        class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs rounded-xl transition-colors cursor-pointer">{{ __('messages.cancel') }}</button>
                    <button type="submit"
                        class="px-5 py-2 bg-primary-500 hover:bg-primary-600 text-white font-extrabold text-xs rounded-xl shadow-xs transition-colors cursor-pointer">
                        {{ __('messages.save_changes') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endpush
@endsection