@extends('layouts.admin')

@section('page_title', __('messages.about_us_configurations') ?? 'About Us Configurations')

@section('content')
<div class="w-full" x-data="{ activeTab: 'en' }">
    @if(session('success'))
        <div class="mb-3 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 text-xs font-bold rounded-xl flex items-center gap-2">
            <span>✅</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-3 p-4 bg-rose-50 border border-rose-100 text-rose-700 text-xs font-semibold rounded-xl">
            <ul class="list-disc pl-4 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Container -->
    <div class="bg-white border border-slate-100 rounded-2xl p-4 pt-2 shadow-sm w-full">
        <form method="POST" action="{{ route('admin.settings.about.update') }}" class="space-y-6">
            @csrf

            <!-- Language Switcher Tabs -->
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 gap-2 flex-wrap">
                <div class="flex items-center gap-2 bg-slate-100 p-1 rounded-xl">
                    <button type="button" @click="activeTab = 'en'"
                        :class="activeTab === 'en' ? 'bg-white text-primary-600 shadow-xs font-extrabold' : 'text-slate-500 hover:text-slate-700 font-bold'"
                        class="px-4 py-2 rounded-lg text-xs transition-all flex items-center gap-2 cursor-pointer">
                        <span>🇬🇧</span>
                        <span>English Content</span>
                    </button>
                    <button type="button" @click="activeTab = 'gu'"
                        :class="activeTab === 'gu' ? 'bg-white text-primary-600 shadow-xs font-extrabold' : 'text-slate-500 hover:text-slate-700 font-bold'"
                        class="px-4 py-2 rounded-lg text-xs transition-all flex items-center gap-2 cursor-pointer">
                        <span>🇮🇳</span>
                        <span>ગુજરાતી કન્ટેન્ટ (Gujarati)</span>
                    </button>
                </div>
                <div class="text-[11px] text-slate-400 font-semibold">
                    <span x-show="activeTab === 'en'">Fill in English titles & details for English visitors</span>
                    <span x-show="activeTab === 'gu'">ગુજરાતી દર્શકો માટે સબ-ટાઇટલ અને વિગતો ભરો</span>
                </div>
            </div>

            <!-- TAB 1: ENGLISH CONTENT -->
            <div x-show="activeTab === 'en'" class="space-y-6" x-cloak>
                <div class="p-3 bg-blue-50/60 border border-blue-100 rounded-xl text-blue-900 text-xs font-semibold flex items-center gap-2">
                    <span>🌐</span>
                    <span>English Language Content & Sub-Titles Configuration</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Mission Section -->
                    <div class="p-4 bg-slate-50/60 border border-slate-200/80 rounded-2xl space-y-3">
                        <h4 class="text-xs font-black text-slate-800 flex items-center gap-1.5">
                            <span>🎯</span>
                            <span>Mission Section (English)</span>
                        </h4>
                        <div class="space-y-1">
                            <label class="text-[11px] font-bold text-slate-600">Mission Sub-Title (Card Heading)</label>
                            <input type="text" name="about_mission_title_en" value="{{ old('about_mission_title_en', $settings['about_mission_title_en'] ?? '') }}" placeholder="e.g. Empowering People" class="h-9 w-full text-xs font-semibold px-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-primary-500">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[11px] font-bold text-slate-600">Mission Description Text</label>
                            <textarea name="about_mission_en" rows="3.5" placeholder="Enter mission details..." class="w-full text-xs font-semibold p-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-primary-500 leading-relaxed">{{ old('about_mission_en', $settings['about_mission_en'] ?? '') }}</textarea>
                        </div>
                    </div>

                    <!-- Vision Section -->
                    <div class="p-4 bg-slate-50/60 border border-slate-200/80 rounded-2xl space-y-3">
                        <h4 class="text-xs font-black text-slate-800 flex items-center gap-1.5">
                            <span>🌟</span>
                            <span>Vision Section (English)</span>
                        </h4>
                        <div class="space-y-1">
                            <label class="text-[11px] font-bold text-slate-600">Vision Sub-Title (Card Heading)</label>
                            <input type="text" name="about_vision_title_en" value="{{ old('about_vision_title_en', $settings['about_vision_title_en'] ?? '') }}" placeholder="e.g. Future Prosperity" class="h-9 w-full text-xs font-semibold px-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-primary-500">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[11px] font-bold text-slate-600">Vision Description Text</label>
                            <textarea name="about_vision_en" rows="3.5" placeholder="Enter vision details..." class="w-full text-xs font-semibold p-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-primary-500 leading-relaxed">{{ old('about_vision_en', $settings['about_vision_en'] ?? '') }}</textarea>
                        </div>
                    </div>

                    <!-- Objectives Section -->
                    <div class="p-4 bg-slate-50/60 border border-slate-200/80 rounded-2xl space-y-3">
                        <h4 class="text-xs font-black text-slate-800 flex items-center gap-1.5">
                            <span>📌</span>
                            <span>Objectives Section (English)</span>
                        </h4>
                        <div class="space-y-1">
                            <label class="text-[11px] font-bold text-slate-600">Objectives Sub-Title (Card Heading)</label>
                            <input type="text" name="about_objectives_title_en" value="{{ old('about_objectives_title_en', $settings['about_objectives_title_en'] ?? '') }}" placeholder="e.g. Strategic Goals" class="h-9 w-full text-xs font-semibold px-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-primary-500">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[11px] font-bold text-slate-600">Objectives Description (HTML Bulletins)</label>
                            <textarea name="about_objectives_en" rows="4" placeholder="e.g. <li>Promote education</li>" class="w-full text-xs font-semibold p-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-primary-500 font-mono leading-relaxed">{{ old('about_objectives_en', $settings['about_objectives_en'] ?? '') }}</textarea>
                        </div>
                    </div>

                    <!-- History Section -->
                    <div class="p-4 bg-slate-50/60 border border-slate-200/80 rounded-2xl space-y-3">
                        <h4 class="text-xs font-black text-slate-800 flex items-center gap-1.5">
                            <span>📜</span>
                            <span>History Section (English)</span>
                        </h4>
                        <div class="space-y-1">
                            <label class="text-[11px] font-bold text-slate-600">History Sub-Title (Heading)</label>
                            <input type="text" name="about_history_title_en" value="{{ old('about_history_title_en', $settings['about_history_title_en'] ?? '') }}" placeholder="e.g. Heritage & Journey" class="h-9 w-full text-xs font-semibold px-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-primary-500">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[11px] font-bold text-slate-600">History Description Text</label>
                            <textarea name="about_history_en" rows="4" placeholder="Enter history details..." class="w-full text-xs font-semibold p-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-primary-500 leading-relaxed">{{ old('about_history_en', $settings['about_history_en'] ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 2: GUJARATI CONTENT -->
            <div x-show="activeTab === 'gu'" class="space-y-6" x-cloak>
                <div class="p-3 bg-amber-50/70 border border-amber-100 rounded-xl text-amber-900 text-xs font-semibold flex items-center gap-2">
                    <span>🇮🇳</span>
                    <span>ગુજરાતી ભાષા સબ-ટાઇટલ્સ અને વિગતો (Gujarati Sub-Titles & Content)</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Mission Section Gujarati -->
                    <div class="p-4 bg-slate-50/60 border border-slate-200/80 rounded-2xl space-y-3">
                        <h4 class="text-xs font-black text-slate-800 flex items-center gap-1.5">
                            <span>🎯</span>
                            <span>મિશન વિભાગ (ગુજરાતી)</span>
                        </h4>
                        <div class="space-y-1">
                            <label class="text-[11px] font-bold text-slate-600">મિશન સબ-ટાઇટલ (કાર્ડ હેડિંગ)</label>
                            <input type="text" name="about_mission_title_gu" value="{{ old('about_mission_title_gu', $settings['about_mission_title_gu'] ?? '') }}" placeholder="દા.ત. લોકોન સશક્ત બનાવવું" class="h-9 w-full text-xs font-semibold px-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-primary-500">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[11px] font-bold text-slate-600">મિશન વિગતવાર લખાણ</label>
                            <textarea name="about_mission_gu" rows="3.5" placeholder="ગુજરાતીમાં મિશન લખો..." class="w-full text-xs font-semibold p-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-primary-500 leading-relaxed">{{ old('about_mission_gu', $settings['about_mission_gu'] ?? '') }}</textarea>
                        </div>
                    </div>

                    <!-- Vision Section Gujarati -->
                    <div class="p-4 bg-slate-50/60 border border-slate-200/80 rounded-2xl space-y-3">
                        <h4 class="text-xs font-black text-slate-800 flex items-center gap-1.5">
                            <span>🌟</span>
                            <span>વિઝન વિભાગ (ગુજરાતી)</span>
                        </h4>
                        <div class="space-y-1">
                            <label class="text-[11px] font-bold text-slate-600">વિઝન સબ-ટાઇટલ (કાર્ડ હેડિંગ)</label>
                            <input type="text" name="about_vision_title_gu" value="{{ old('about_vision_title_gu', $settings['about_vision_title_gu'] ?? '') }}" placeholder="દા.ત. ભવિષ્યની સમૃદ્ધિ" class="h-9 w-full text-xs font-semibold px-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-primary-500">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[11px] font-bold text-slate-600">વિઝન વિગતવાર લખાણ</label>
                            <textarea name="about_vision_gu" rows="3.5" placeholder="ગુજરાતીમાં વિઝન લખો..." class="w-full text-xs font-semibold p-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-primary-500 leading-relaxed">{{ old('about_vision_gu', $settings['about_vision_gu'] ?? '') }}</textarea>
                        </div>
                    </div>

                    <!-- Objectives Section Gujarati -->
                    <div class="p-4 bg-slate-50/60 border border-slate-200/80 rounded-2xl space-y-3">
                        <h4 class="text-xs font-black text-slate-800 flex items-center gap-1.5">
                            <span>📌</span>
                            <span>ઉદ્દેશ્યો વિભાગ (ગુજરાતી)</span>
                        </h4>
                        <div class="space-y-1">
                            <label class="text-[11px] font-bold text-slate-600">ઉદ્દેશ્યો સબ-ટાઇટલ (કાર્ડ હેડિંગ)</label>
                            <input type="text" name="about_objectives_title_gu" value="{{ old('about_objectives_title_gu', $settings['about_objectives_title_gu'] ?? '') }}" placeholder="દા.ત. વ્યૂહાત્મક લક્ષ્યો" class="h-9 w-full text-xs font-semibold px-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-primary-500">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[11px] font-bold text-slate-600">ઉદ્દેશ્યો વિગત (HTML બુલેટિન)</label>
                            <textarea name="about_objectives_gu" rows="4" placeholder="દા.ત. <li>શિક્ષણને પ્રોત્સાહન આપવું</li>" class="w-full text-xs font-semibold p-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-primary-500 font-mono leading-relaxed">{{ old('about_objectives_gu', $settings['about_objectives_gu'] ?? '') }}</textarea>
                        </div>
                    </div>

                    <!-- History Section Gujarati -->
                    <div class="p-4 bg-slate-50/60 border border-slate-200/80 rounded-2xl space-y-3">
                        <h4 class="text-xs font-black text-slate-800 flex items-center gap-1.5">
                            <span>📜</span>
                            <span>ઇતિહાસ વિભાગ (ગુજરાતી)</span>
                        </h4>
                        <div class="space-y-1">
                            <label class="text-[11px] font-bold text-slate-600">ઇતિહાસ સબ-ટાઇટલ (હેડિંગ)</label>
                            <input type="text" name="about_history_title_gu" value="{{ old('about_history_title_gu', $settings['about_history_title_gu'] ?? '') }}" placeholder="દા.ત. વારસો અને યાત્રા" class="h-9 w-full text-xs font-semibold px-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-primary-500">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[11px] font-bold text-slate-600">ઇતિહાસ વિગતવાર લખાણ</label>
                            <textarea name="about_history_gu" rows="4" placeholder="ગુજરાતીમાં ઇતિહાસ લખો..." class="w-full text-xs font-semibold p-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-primary-500 leading-relaxed">{{ old('about_history_gu', $settings['about_history_gu'] ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-5 border-t border-slate-100 flex items-center justify-between gap-3 flex-wrap">
                <div class="text-xs text-slate-500 font-medium">
                    💾 Saves both English & Gujarati configurations simultaneously.
                </div>
                <button type="submit" class="inline-flex items-center justify-center px-6 py-2.5 bg-primary-500 hover:bg-primary-600 font-bold text-xs text-white uppercase tracking-wider rounded-xl shadow-xs transition-all hover:-translate-y-0.5 cursor-pointer">
                    💾 Save All About Us Configurations
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
